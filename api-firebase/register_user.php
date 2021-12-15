<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');
$db = new Database();
$db->connect();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['first_name'])) {
    $response['success'] = false;
    $response['message'] = "First Name is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['last_name'])) {
    $response['success'] = false;
    $response['message'] = "Last Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['mobile_number'])) {
    $response['success'] = false;
    $response['message'] = "Mobile Number Name is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$first_name = $db->escapeString($_POST['first_name']);
$last_name = $db->escapeString($_POST['last_name']);
$mobile_number = $db->escapeString($_POST['mobile_number']);
$sql = "SELECT * FROM users WHERE user_id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $response['success'] = false;
    $response['message'] = "User Id already Exit";
    
    print_r(json_encode($response));

}
else{
    $sql = "INSERT INTO users(`user_id`,`first_name`, `last_name`, `mobile_number`)VALUES('$user_id','$first_name','$last_name','$mobile_number')";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "user register is successfully";
    print_r(json_encode($response));

}
 


?>