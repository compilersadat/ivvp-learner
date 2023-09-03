<?php

namespace App\Http\Controllers\Admin;
use App\Models\Student;
use App\Models\StudentPackage;
use App\Models\Package;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadStudentController extends Controller
{
    public function index()
    {
        return view('admin.uploadstudents.create');
    }
    public function create()
    {
        return view('admin.uploadstudents.create');
    }

    public function download()
    {
        $filePath = public_path("IVVP_student_data.xlsx");
        $headers = ['Content-Type: application/xlsx'];
        $fileName = 'IVVP_student_data.xlsx';

        return response()->download($filePath, $fileName, $headers);
        
    }

    public function store(Request $request)
    {
        // $this->validate($request,[
        //     'file' => ['required', File::types(['xls', 'xlsx'])->smallerThan(10000)]
        // ]);

        $file = $request->file('file');
        
        $rows = Excel::toArray([], $file)[0];
        
        $columnNames = array_shift($rows);
        $packageUpdates = [];
        $existingStudents = [];
        
        foreach ($rows as $row) {
            $data = array_combine($columnNames, $row);
            
           
            $student = Student::where('email', $data['email'])->first();
            
            if (!$student) {
                $student = Student::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'faculty' => $data['faculty'],
                    'branch' => $data['branch'],
                    'collage' => $data['collage'],
                    'instructor' => $data['instructor'],
                    'district' => $data['district'],
                    'year' => $data['year'],
                    'm_toung' => $data['m_toung'],
                    'image' => $data['image'],
                    'first' => $data['first'],
                    'paid' => $data['paid'],
                    
                ]);
            }
            
            $studentPackage = StudentPackage::where('student_id', $student->id)->first();
            
            if (!$studentPackage) {
                
                $packageUpdates[] = [
                    'student' => $student,
                    'package_id' => $data['package_id'],
                    'start_date' => $data['start_date'],
                    'start_month' => $data['start_month'],
                    'status' => $data['status'],
                    'payment_status' => $data['payment_status'],
                ];
               
            } else {
                $existingStudents[] = $student . $studentPackage;
            }
        }
        


        

            // Insert new student packages
            foreach ($packageUpdates as $packageUpdate) {
                
                $package = Package::where('id', $packageUpdate['package_id'])->first();
                if($package){
                    StudentPackage::create([
                        'student_id' => $packageUpdate['student']->id,
                        'package_id' => $packageUpdate['package_id'],
                        'package_name' => $package['name'],
                        'number_of_months' => $package['number'],
                        'start_date' => $packageUpdate['start_date'],
                        'start_month' => $packageUpdate['start_month'],
                        'price' => $package['price'],
                        'status' => $packageUpdate['status'],
                        'payment_status' => $packageUpdate['payment_status'],
                    ]);
                }
                
            }

        
        

        
        
       
        
        // Use the $existingStudents variable as needed
        // For example, you can loop through the existing students:
        foreach ($existingStudents as $existingStudent) {
            // Access student details: $existingStudent->name, $existingStudent->email, etc.
        }
   
        // return back()->with('success', 'Students created successfully.');
        return response()->json(['message' => 'Data imported successfully <br> 
        List of existing students.<br>
        '.implode(" ", $existingStudents )]);
}



}


