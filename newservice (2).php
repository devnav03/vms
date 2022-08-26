<?php
//   include('conn.php');
include('../admin/include/db.php');
$tnameopn="acrm_service_register";
$tnameemp="acrm_employee_register";


    $id = $_GET['id'];


$showquery= "select employee_code  from  $tnameemp where id= $id";
$showdata = mysqli_query($conn,$showquery);    
$arrdata= mysqli_fetch_array($showdata);
$employee_code = $arrdata['employee_code']; 



$sql=mysqli_query($conn,"SELECT * FROM $tnameopn WHERE employee_code='$employee_code'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql)>0) {
    while($arr = mysqli_fetch_array($sql)){
        $user['complaint_no'] = $arr['complaint_no'];
        $user['priority'] = $arr['priority'];
        $user['booking_date'] = $arr['booking_date'];
        $user['emp_name'] = $arr['emp_name'];
        $user['service_type'] = $arr['service_type'];
        $user['status'] = $arr['status'];
        $data[]=$user;
    }
    $response = ['status'=> 200, 'message' => 'successfully', 'user' => $data];
}else{
    $response = ['status'=> 201, 'message' => 'No Service'];
}
header('Content-Type: application/json; charset=utf-8');
exit(json_encode($response)); 