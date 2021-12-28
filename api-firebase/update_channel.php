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
if (empty($_POST['channel_id'])) {
    $response['success'] = false;
    $response['message'] = "Channel ID is Empty";
    print_r(json_encode($response));
    return false;
}


$user_id = $db->escapeString($_POST['user_id']);
$channel_id = $db->escapeString($_POST['channel_id']);
$type = (isset($_POST['type']) && !empty($_POST['type'])) ? trim($db->escapeString($_POST['type'])) : "";
$sql = 'select * from channel where id = $channel_id and user_id = $user_id';
$db->sql($sql);
$res = $db->getResult();

if (!empty($res)) {
    $sql = "UPDATE channel SET `type`= '$type'  WHERE `id`=" . $channel_id;
    $db->sql($sql);
    $response["success"]   = true;
    $response["message"] = "Channel updated successfully";
    print_r(json_encode($response));
    
}

?>