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
if (empty($_POST['group_id'])) {
    $response['success'] = false;
    $response['message'] = "Group ID is Empty";
    print_r(json_encode($response));
    return false;
}


$user_id = $db->escapeString($_POST['user_id']);
$group_id = $db->escapeString($_POST['group_id']);
$type = (isset($_POST['type']) && !empty($_POST['type'])) ? trim($db->escapeString($_POST['type'])) : "";
$sql = 'select * from groups where id = $group_id and user_id = $user_id';
$db->sql($sql);
$res = $db->getResult();

if (!empty($res)) {
    $sql = "UPDATE groups SET `type`= '$type'  WHERE `id`=" . $group_id;
    $db->sql($sql);
    $response["success"]   = true;
    $response["message"] = "Group updated successfully";
    print_r(json_encode($response));
    
}

?>