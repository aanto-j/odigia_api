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

if (empty($_POST['user_name'])) {
    $response['success'] = false;
    $response['message'] = "User Name is Empty";
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
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile Number Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_FILES['profile'])) {
    $response['success'] = false;
    $response['message'] = "Profile is Empty";
    print_r(json_encode($response));
    return false;
}


$user_name = $db->escapeString($_POST['user_name']);
$first_name = $db->escapeString($_POST['first_name']);
$last_name = $db->escapeString($_POST['last_name']);
$mobile = $db->escapeString($_POST['mobile']);
$sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $response['success'] = false;
    $response['message'] = "User Id already Exit";
    
    print_r(json_encode($response));

}
else{
    if (isset($_FILES['profile']) && !empty($_FILES['profile']) && $_FILES['profile']['error'] == 0 && $_FILES['profile']['size'] > 0){
        if (!is_dir('../upload/profile/')) {
            mkdir('../upload/profile/', 0777, true);
        }
        
        $profile = $db->escapeString($_FILES['profile']['name']);
        $extension = pathinfo($_FILES["profile"]["name"])['extension'];
        $result = $fn->validate_image($_FILES["profile"]);
        if (!$result) {
            $response["error"]   = true;
            $response["message"] = "Image type must jpg, jpeg, gif, or png!";
            print_r(json_encode($response));
            return false;
        }
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = '../upload/profile/' . "" . $filename;
        if (!move_uploaded_file($_FILES["profile"]["tmp_name"], $full_path)) {
            $response["error"]   = true;
            $response["message"] = "Invalid directory to load profile!";
            print_r(json_encode($response));
            return false;
        }
        $sql = "INSERT INTO users(`user_name`,`first_name`, `last_name`, `mobile`, `profile`)VALUES('$user_name','$first_name','$last_name','$mobile','$filename')";
        $db->sql($sql);
        $res = $db->getResult();

        $sql_query = "SELECT * FROM `users` WHERE `user_name` = '" . $user_name . "'";
        $db->sql($sql_query);
        $result = $db->getResult();

        if ($db->numRows($result) > 0) {
            $response["success"]   = true;
            $response["message"] = "User registered successfully";
            
            foreach ($result as $row) {
                $response['success']     = true;
                $response['user_id'] = $row['id'];
                $response['first_name'] = $row['first_name'];
                $response['last_name'] = $row['last_name'];
                $response['profile'] = DOMAIN_URL . 'upload/profile/' . "" . $row['profile'];
                $response['mobile'] = $row['mobile'];
                $response['user_name'] = $row['user_name'];
                
            }
        }


        print_r(json_encode($response));
        return false;
        

    }
    else {
        $response['success'] = false;
        $response['message'] = "Upload Profile is Empty";
        
        print_r(json_encode($response));

    }
    

}
 


?>