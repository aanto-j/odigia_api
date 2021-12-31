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


$user_id = $db->escapeString($_POST['user_id']);
$sql_query = "SELECT * FROM `groups` WHERE `user_id` = '" . $user_id . "'";
$db->sql($sql_query);
$result = $db->getResult();
if ($db->numRows($result) > 0) {
    $response["success"]   = true;
    $response["message"] = "Group Retrieved successfully";
    foreach ($result as $row) {
        $temp['user_id'] = $row['id'];
        $temp['group_name'] = $row['group_name'];
        $temp['group_description'] = $row['group_description'];
        $temp['group_image'] = DOMAIN_URL . 'upload/group_image/' . "" . $row['group_image'];
        $temp['type'] = $row['type'];
        $temp1[] = $temp;
    }
    $response['data'] = $temp1;
}
else{
    $response["success"]   = false;
    $response["message"] = "Group Not Found";
}
print_r(json_encode($response));
return false;
 


?>