<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Model\Role;
use App\Model\Department;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Model\Location;
use Illuminate\Http\Request;
class EmployeeController extends Controller
{

    public function bulk_insert(){
        return view('admin.admin.bulk_insert');
    }

    public function sampleCsvDownload(){
      $fileName = 'sampleemployeeupload.csv';
      $headers = array(
              "Content-type"        => "text/csv",
              "Content-Disposition" => "attachment; filename=$fileName",
              "Pragma"              => "no-cache",
              "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
              "Expires"             => "0"
          );

          $columns = array('Name', 'Location', 'Building', 'Department', 'Device','Role' ,'Email','Mobile','Ip Allow','Ip Address','Status','Employee Type','From Date','Till Date','Gender','Company Id');

          $callback = function() use($columns) {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);
                  $row=[];
                  $row['Name']  = 'Pramod';
                  $row['Location']    = 5;
                  $row['Building']    = 1;
                  $row['Department']  = 4;
                  $row['Device']  = 2;
                  $row['Role']  = 6;
                  $row['Email']    = 'pramod@gmail.com';
                  $row['Mobile']    ='8931909840';
                  $row['Ip_Allow']  = 1;
                  $row['Ip_Address']  = '';
                  $row['Status']  = 1;
                  $row['Employee_Type']  = 'permanent';
                  $row['From_Date']  = '07/28/2021 00:00:00';
                  $row['Till_Date']  = '07/28/2021 00:00:00';
                  $row['gender']  = 'MALE';
                  $row['Company_Id']  = 'CID01';
                  

                  fputcsv($file, array($row['Name'],$row['Location'], $row['Building'],
                   $row['Department'], $row['Device'], $row['Role'],$row['Email'],$row['Mobile'],
                   $row['Ip_Allow'],$row['Ip_Address'],$row['Status'],$row['Employee_Type'],
                   $row['From_Date'],$row['Till_Date'],$row['gender'],$row['Company_Id']));
                   fclose($file);
          };

          return response()->stream($callback, 200, $headers);
    }

    public function employeeStore(Request $request){
      $csv = $request->file('employee_sample');
      $insert_data=[];
      $handle = fopen($csv,"r");
      $count=0;
      while (($row = fgetcsv($handle, 10000, ",")) != FALSE) //get row vales
      {
          if($count!=0){
              if($row['0']!='' || $row['1']!='' || $row['2']!='' || $row['3']!='' || $row['4']!='' || $row['5']!='' || $row['6']!='' || $row['7']!=''){
                $data['name']=$row['0'];
                $data['location_id']=$row['1'];
                $data['building_id']=$row['2'];
                $data['department_id']=$row['3'];
                $data['role_id']=$row['4'];
                $data['device_id']=$row['5'];
                $data['email']=$row['6'];
                $data['mobile']=$row['7'];
                $data['allowed_ip']=$row['8'];
                $data['ip']=$row['9'];
                $data['status_id']=$row['10'];
                $data['employee_type']=$row['11'];
                if($row['11']!="permanent"){
                  $data['from_date']=$row['12'];
                  $data['till_date']=$row['13'];
                }
                  $data['gender']=$row['14'];
                  $data['company_id']=$row['15'];
                  $data['password']=bcrypt('Admin@123');
                array_push($insert_data,$data);
              }


          }
          $count++;

    }


    Admin::insert($insert_data);
    return redirect()->route('admin.'.request()->segment(2).'.index')->with(['class'=>'success','message'=>'CSV Uploaded successfully.']);
  }
}
