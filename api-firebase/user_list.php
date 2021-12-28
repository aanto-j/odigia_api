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

$sql_query = "SELECT * FROM `users`";
$db->sql($sql_query);
$result = $db->getResult();
if ($db->numRows($result) > 0) {
    $response["success"]   = true;
    $response["message"] = "Users Retrieved successfully";
    foreach ($result as $row) {
        $temp['id'] = $row['id'];
        $temp['first_name'] = $row['first_name'];
        $temp['last_name'] = $row['last_name'];
        $temp['user_name'] = $row['user_name'];
        $temp['mobile'] = $row['mobile'];
        $temp['profile'] = DOMAIN_URL . 'upload/profile/' . "" . $row['profile'];
        $temp['description'] = $row['description'];
        $temp['city'] = $row['city'];
        $temp['instagram'] = $row['instagram'];
        $temp['twitter'] = $row['twitter'];
        $temp['facebook'] = $row['facebook'];
        $temp['linkedin'] = $row['linkedin'];
        $temp['youtube'] = $row['youtube'];
        $temp1[] = $temp;
    }
    $response['data'] = $result;
}
else{
    $response["success"]   = false;
    $response["message"] = "Users Not Found";
}
print_r(json_encode($response));
return false;
 


?>