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
                    $prime_content=Content::where('branch',$student->branch)->where('year',$student->year)->whereIn('month',$month_range)->orderBy('order_by','ASC')->get();
                    $current_month_videos=Content::where('branch',$student->branch)->where('year',$student->year)->where('month',$month_range[0])->where('type','video_lecture')->get();
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
            $prime_content=Content::where('branch',$student->branch)->where('year',$student->year)->where('month',9)->orderBy('order_by','ASC')->get();
            $current_month_videos=Content::where('branch',$student->branch)->where('year',$student->year)->where('month',9)->where('type','video_lecture')->get();
            $data['prime_content']=ContentResource::collection($prime_content);
            $data['current_month_videos']=ContentResource::collection($current_month_videos);
            $data['paid_plans']=[];
           
            $free_content=Content::where('branch',$student->branch)->where('year',$student->year)->where(function($query){
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

        $contents = Content::whereNotNull('branch')
            ->whereNotNull('year')
            ->orderBy('branch')
            ->orderBy('year')
            ->orderBy('order_by')
            ->get()
            ->groupBy('branch');

        $branchIds = $contents->keys()
            ->filter(function ($branchId) {
                return ! is_null($branchId);
            })
            ->all();

        $branchNames = Branch::whereIn('branch_id', $branchIds)->pluck('name', 'branch_id');

        $branches = $contents->map(function ($branchContents, $branchId) use ($branchNames) {
            $years = $branchContents->groupBy('year')->sortKeys();

            return [
                'branch_id' => $branchId,
                'branch_name' => $branchNames[$branchId] ?? null,
                'years' => $years->map(function ($yearContents, $year) {
                    return [
                        'year' => $year,
                        'contents' => ContentResource::collection($yearContents),
                    ];
                })->values(),
            ];
        })->values();

        $success['message'] = "Here is data";
        $success['data'] = [
            'sliders' => SlidersResource::collection(Slider::get()),
            'branches' => $branches,
        ];

        return $this->sendResponse($success);
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
        $prime_content=Content::where('branch',$student->branch)->where('year',$student->year)->whereIn('month',$month_range)->get();
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
