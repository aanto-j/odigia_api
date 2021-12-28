<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');
include('../includes/custom-functions.php');
$db = new Database();
$fn = new custom_functions();
$db->connect();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['subscriber_id'])) {
    $response['success'] = false;
    $response['message'] = "Subscriber ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['channel_id'])) {
    $response['success'] = false;
    $response['message'] = "Channel ID is Empty";
    print_r(json_encode($response));
    return false;
}
$subscriber_id = $db->escapeString($_POST['subscriber_id']);
$user_id = $db->escapeString($_POST['user_id']);
$channel_id = $db->escapeString($_POST['channel_id']);

$subs_arr = json_decode($subscriber_id, true);
$sql_query = "SELECT * FROM `channel` WHERE `id` = '" . $channel_id . "'";
$db->sql($sql_query);
$result = $db->getResult();
if ($db->numRows($result) > 0) {
    for ($i = 0; $i < count($subs_arr); $i++) {
        $sub_id = $subs_arr[$i];
        $sql = "INSERT INTO channel_subscribers(`subscriber_id`,`channel_id`)VALUES($sub_id,$channel_id)";
        $db->sql($sql);
    
    }
    
    $response["success"]   = true;
    $response["message"] = "Subscriber Added";
    print_r(json_encode($response));
    return false;
    
}
else{
    $response["success"]   = false;
    $response["message"] = "Channel Not Found";
    print_r(json_encode($response));
    return false;
}

 


?>