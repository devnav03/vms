<?php // Code within app\Helpers\Helper.php











if (!function_exists('adminRoute')) {



    function adminRoute($slug,$param=null){



        return route('admin.'.request()->segment(2).'.'.$slug,$param);



    }



}



if (!function_exists('can')) {



    function can($expression,$type='admin') {



        $expression = strpos($expression, '_')?$expression : $expression.'_'.request()->segment(2);



        return  auth($type)->user()->hasAccess($expression.'_'.request()->segment(2));



    }



}


 function get_designations($id){
	 return \DB::table('designations')
    ->join('designation_departments', 'designations.id', '=','designation_departments.designation_id')
    ->select('designations.id', 'designations.name')
    ->where('designations.status', 1)
    ->where('designation_departments.department_id', $id)
    ->get();
 }


 function get_employees_by_designation($id, $depa){
	 return App\Admin::where('designation_id', $id)->where('department_id', $depa)->where('status_id', 1)->select('id', 'name')->get();
 }

 function get_meeting_perm($room_id, $designation_id){
	 return App\Model\DesignationConference::where('room_id', $room_id)->where('designation_id', $designation_id)->select('id')->first();
 }



if (!function_exists('adminLog')) {



    function adminLog($expression,$logMessage) {



        AdminLog::write($expression,'Admin',$logMessage);   



    }



}



if (!function_exists('bucketPath')) {



    function bucketPath($name,$image='') {



        return  ('images/'.str_singular($name).'/'.$image);



    }



}



if (!function_exists('bucketUrl')) {



    function bucketUrl($image='',$path='') {



        // $image = (!strpos($image, 'images/'))?($path)?bucketPath($path,$image):$image:$image;



        // return 'https://'.preg_replace('/([^:])(\/{2,})/', '$1/','d1cjibd3ghkjda.cloudfront.net/'.$image);          



        return 'https://'.preg_replace('/([^:])(\/{2,})/', '$1/','d1cjibd3ghkjda.cloudfront.net/'.$image);          



    }



}



if (!function_exists('cdn')) {



    function cdn($image='',$path='') {



        return bucketUrl($image,$path);          



    }



}



















