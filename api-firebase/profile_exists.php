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

if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile Number Name is Empty";
    print_r(json_encode($response));
    return false;
}


$mobile = $db->escapeString($_POST['mobile']);
$sql_query = "SELECT * FROM `users` WHERE `mobile` = '" . $mobile . "'";
$db->sql($sql_query);
$result = $db->getResult();

if ($db->numRows($result) > 0) {
    $user_name_exist = $result[0]['user_name'];

    if (empty($user_name_exist)){
        
        $response["success"]   = false;
        $response["message"] = "Please Update profile";
        print_r(json_encode($response));
        return false;

    }
    else{
        $sql_query = "SELECT * FROM `users` WHERE `user_name` = '" . $user_name_exist . "'";
        $db->sql($sql_query);
        $result = $db->getResult();
        foreach ($result as $row) {
            $response['success']     = true;
            $response['user_id'] = $row['id'];
            $response['first_name'] = $row['first_name'];
            $response['last_name'] = $row['last_name'];
            $response['profile'] = DOMAIN_URL . 'upload/profile/' . "" . $row['profile'];
            $response['mobile'] = $row['mobile'];
            $response['user_name'] = $row['user_name'];
            $response['description'] = $row['description'];
            $response['city'] = $row['city'];
            $response['instagram'] = $row['instagram'];
            $response['twitter'] = $row['twitter'];
            $response['facebook'] = $row['facebook'];
            $response['linkedin'] = $row['linkedin'];
            $response['youtube'] = $row['youtube'];
            
        }
        $response["success"]   = true;
        $response["message"] = "Welcome";
        print_r(json_encode($response));
        return false;

    }
}else{
    $response["success"]   = false;
    $response["message"] = "Please Update profile";
    print_r(json_encode($response));
    return false;
}

 


?>