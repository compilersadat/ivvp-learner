<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Models\Faculty;
use App\Models\Branch;
use App\Http\Resources\StudyMaterial;
use App\Http\Resources\StudyMaterialFolderResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\StudentSubscriptionResource;
use App\Models\Package;
use App\Models\StudentPackage;
use App\Models\Content;
use App\Models\TestSeries;
use App\Http\Resources\ContentResource;
use App\Http\Resources\SlidersResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\TestSeriesResource;
use App\Models\Exam;
use App\Models\StudentResult;
use App\Models\Student;
use App\Models\Institute;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\AppUpdate;
use App\Models\StudyMaterialFolder;
class DataApiController extends ResponseController
{
    public function homeData(Request $request){
        $student=Student::where('id',$request->user()->id)->first();
        $slider=SlidersResource::collection(Slider::get());
        $data['sliders']=$slider;

        //check premium user.
        $student_pro=StudentPackage::where('student_id',$request->user()->id)->get();
        
        if($student_pro->count()){
                    $month_range = array();
                    foreach($student_pro as $st_package){
                        if($st_package->status==2){
                            $package = Package::where('id',$st_package->package_id)->first();
                            $month_range= (array)$month_range+(array)$this->calculateRangeOfMonths($package->month,$st_package->number_of_months);
                        }
                    }
                    $prime_content=Content::with('fileUpload')->where('branch',$student->branch)->where('year',$student->year)->whereIn('month',$month_range)->orderBy('order_by','ASC')->get();
                    $current_month_videos=Content::with('fileUpload')->where('branch',$student->branch)->where('year',$student->year)->where('month',$month_range[0])->where('type','video_lecture')->get();
                    $data['prime_content']=ContentResource::collection($prime_content);
                    $data['current_month_videos']=ContentResource::collection($current_month_videos);
                    $data['paid_plans']=StudentSubscriptionResource::collection($student_pro);
                    $data['is_prime']=true;
                    $data['study_materials']=[];
                    $data['free_content']=[];
                    $data['subscriptions']=PackageResource::collection(Package::whereNotIn('month',$month_range)->where('active',1)->get());
                    $data['month']=$month_range[0];
                    $data['common_study_materials']=$this->getCommonStudyMaterials($student);
        }else{
            /// Free one month
            $prime_content=Content::with('fileUpload')->where('branch',$student->branch)->where('year',$student->year)->where('month',9)->orderBy('order_by','ASC')->get();
            $current_month_videos=Content::with('fileUpload')->where('branch',$student->branch)->where('year',$student->year)->where('month',9)->where('type','video_lecture')->get();
            $data['prime_content']=ContentResource::collection($prime_content);
            $data['current_month_videos']=ContentResource::collection($current_month_videos);
            $data['paid_plans']=[];
           
            $free_content=Content::with('fileUpload')->where('branch',$student->branch)->where('year',$student->year)->where(function($query){
                $query->where('type','free_pdf')->orWhere('type','free_video');
            })->get();
             $data['study_materials']=StudyMaterial::collection(Faculty::all());
             $data['free_content']=ContentResource::collection($free_content);  
             /// Free one month 
             $data['is_prime']=true;
             $data['subscriptions']=PackageResource::collection(Package::whereNotIn('month',[9])->where('active',1)->get());
             $data['month']=9;
        }

        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);
    }

    public function instituteHomeData(Request $request)
    {
        if (! $request->user() instanceof Institute) {
            return $this->sendError('Only institutes can access this resource.', 403);
        }

        $branches = Cache::remember('institute_home_branches_v4', now()->addMinutes(10), function () {
            return $this->buildInstituteHomeBranches();
        });

        $success['message'] = "Here is data";
        $success['data'] = [
            'branches' => $branches,
        ];

        return $this->sendResponse($success);
    }

    protected function buildInstituteHomeBranches(): array
    {
        $contents = Content::query()
            ->select([
                'id',
                'title',
                'description',
                'type',
                'file_url',
                'thumbnail',
                'month',
                'branch',
                'year',
                'order_by',
            ])
            ->with(['fileUpload:id,url'])
            ->whereNotNull('branch')
            ->whereNotNull('year')
            ->orderBy('branch')
            ->orderBy('year')
            ->orderBy('order_by')
            ->get();

        if ($contents->isEmpty()) {
            return [];
        }

        $branchIds = $contents->pluck('branch')->filter()->unique()->all();
        $branchNames = Branch::whereIn('branch_id', $branchIds)->pluck('name', 'branch_id');
        $thumbnailBase = (string) env('S3_STORAGE_BASE_URL');
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];

        return $contents
            ->groupBy('branch')
            ->map(function ($branchContents, $branchId) use ($branchNames, $thumbnailBase, $months) {
                $years = $branchContents->groupBy('year')->sortKeys();

                return [
                    'branch_id' => $branchId,
                    'branch_name' => $branchNames[$branchId] ?? null,
                    'years' => $years->map(function ($yearContents, $year) use ($thumbnailBase, $months) {
                        $monthsGroup = $yearContents->groupBy('month')->sortKeys();

                        return [
                            'year' => $year,
                            'months' => $monthsGroup->map(function ($monthContents, $month) use ($thumbnailBase, $months) {
                                $safeMonth = (int) $month ?: 0;
                                $monthIndex = $safeMonth > 0 ? $safeMonth - 1 : 0;
                                $label = $month
                                    ? ($months[$monthIndex] ?? $month)
                                    : 'Unscheduled';

                                $payload = $monthContents->map(function ($content) use ($thumbnailBase, $months) {
                                    return $this->transformInstituteContent($content, $thumbnailBase, $months);
                                })->values()->all();

                                return [
                                    'month' => $month,
                                    'label' => $label,
                                    'contents' => $payload,
                                ];
                            })->values()->all(),
                        ];
                    })->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    protected function transformInstituteContent(Content $content, string $thumbnailBase, array $months): array
    {
        $monthIndex = max(0, ((int) ($content->month ?? 1)) - 1);
        $thumbnail = $content->thumbnail
            ? $thumbnailBase . ltrim($content->thumbnail, '/')
            : null;
        $fileUpload = $content->fileUpload;
        $streamUrl = optional($fileUpload)->url;
        $extension = $this->extractExtension($streamUrl);

        return [
            'id' => $content->id,
            'title' => $content->title,
            'description' => $content->description,
            'type' => $content->type,
            'type_label' => $this->prettifyType($content->type),
            'month_label' => $months[$monthIndex] ?? $content->month,
            'stream_url' => $streamUrl,
            'thumbnail_url' => $thumbnail,
            'file_extension' => $extension,
            'media_category' => $this->inferMediaCategory($content->type, $extension),
        ];
    }

    protected function extractExtension(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $normalized = parse_url($path, PHP_URL_PATH) ?: $path;
        $extension = pathinfo($normalized, PATHINFO_EXTENSION);

        return $extension ? strtolower($extension) : null;
    }

    protected function inferMediaCategory(?string $type, ?string $extension): string
    {
        $typeString = strtolower((string) $type);
        $ext = strtolower((string) $extension);

        if (Str::contains($typeString, ['video', 'lecture']) || in_array($ext, ['mp4', 'mov', 'm4v', 'webm', 'mkv'])) {
            return 'video';
        }

        if (Str::contains($typeString, ['pdf']) || $ext === 'pdf') {
            return 'pdf';
        }

        return 'file';
    }

    protected function prettifyType(?string $type): string
    {
        if (! $type) {
            return 'Resource';
        }

        return Str::of($type)->replace('_', ' ')->title();
    }

    public function primeContent(Request $request){
        $student=Student::where('id',$request->user()->id)->first();
        $student_pro=StudentPackage::where('student_id',$request->user()->id)->get();
        $month_range = array();
        foreach($student_pro as $st_package){
            if($st_package->status==2){
                $package = Package::where('id',$st_package->package_id)->first();
                $month_range= (array)$month_range+(array)$this->calculateRangeOfMonths($package->month,$st_package->number_of_months);
            }
        }
        $prime_content=Content::with('fileUpload')->where('branch',$student->branch)->where('year',$student->year)->whereIn('month',$month_range)->get();
        $data['prime_content']=ContentResource::collection($prime_content);
        $data['common_study_materials']=$this->getCommonStudyMaterials($student);
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);

    }

    public function fetchExams(Request $request){
        $student=Student::where('id',$request->user()->id)->first();
        $student_pro=StudentPackage::where('student_id',$request->user()->id)->get();
        if($student_pro){
            $month_range = array();
                    foreach($student_pro as $st_package){
                        if($st_package->status==2){
                            $package = Package::where('id',$st_package->package_id)->first();
                            $month_range = (array)$month_range + (array)$this->calculateRangeOfMonths($package->month,$st_package->number_of_months);
                        }
                    }
                   // $exclude_exams=StudentResult::where('student_id',$request->user()->id)->where('status','completed')->pluck('exam_id');
                    $data['exams']=ExamResource::collection(Exam::where('branch',$student->branch)->where('year',$student->year)->whereIn('month',$month_range)->get());
                    $success['message'] = "Here is data";
                    $success['data']=$data;
                    return $this->sendResponse($success);
        }
       
        $success['message'] = "Here is data";
                    $data['exams']=[];
                    $success['data']=$data;
                    return $this->sendResponse($success);
    }

    public function calculateRangeOfMonths($start,$no_months){
        $range=array(9);
        $i=1;
        $next_month=$start;
        while($i<=$no_months){
            if($next_month==7 || $next_month==8){
                $no_months=$no_months+1;
            }
            if($next_month!=7 || $next_month!=8){

                if($next_month>12){
                    $p=0-(12-$next_month);
                    
                    array_push($range,$p);
                }else{
                    array_push($range,$next_month);
                }
                
            }
            $i++;
            $next_month=$next_month+1;
        }
        return $range;
    }

    protected function getCommonStudyMaterials(Student $student)
    {
        $folders = StudyMaterialFolder::with(['documents' => function ($query) {
                $query->active();
            }])
            ->active()
            ->where('branch_id', $student->branch)
            ->where('year', $student->year)
            ->orderBy('name')
            ->get();

        return StudyMaterialFolderResource::collection($folders);
    }

    public function appUpdate(Request $request){
        $appUpdate=AppUpdate::where('device',$request->device)->first();
        $data['is_update_required']=$appUpdate->version_number!=$request->version;
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);
    }

    // Test series apis
    public function testSeriesHomData (Request $request){
        $series =TestSeriesResource::collection(TestSeries::where('is_live',1)->get());
        $data['serieses'] = $series;
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);
    }

}
