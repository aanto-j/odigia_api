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
if (empty($_POST['channel_name'])) {
    $response['success'] = false;
    $response['message'] = "Channel Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['channel_description'])) {
    $response['success'] = false;
    $response['message'] = "Channel Description is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['type'])) {
    $response['success'] = false;
    $response['message'] = "Type is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);
$channel_name = $db->escapeString($_POST['channel_name']);
$channel_description = $db->escapeString($_POST['channel_description']);
$type = $db->escapeString($_POST['type']);
$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
if (!empty($res)) {
    if (isset($_FILES['channel_image']) && !empty($_FILES['channel_image']) && $_FILES['channel_image']['error'] == 0 && $_FILES['channel_image']['size'] > 0){
        if (!is_dir('../upload/channel_image/')) {
            mkdir('../upload/channel_image/', 0777, true);
        }
        
        $channel_image = $db->escapeString($_FILES['channel_image']['name']);
        $extension = pathinfo($_FILES["channel_image"]["name"])['extension'];
        $result = $fn->validate_image($_FILES["channel_image"]);
        if (!$result) {
            $response["error"]   = true;
            $response["message"] = "Image type must jpg, jpeg, gif, or png!";
            print_r(json_encode($response));
            return false;
        }
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = '../upload/channel_image/' . "" . $filename;
        if (!move_uploaded_file($_FILES["channel_image"]["tmp_name"], $full_path)) {
            $response["error"]   = true;
            $response["message"] = "Invalid directory to load channel_image!";
            print_r(json_encode($response));
            return false;
        }
        $sql = "INSERT INTO channel(`user_id`,`channel_name`, `channel_description`, `channel_image`, `type`)VALUES($user_id,'$channel_name','$channel_description','$filename','$type')";
        $db->sql($sql);
        $res = $db->getResult();
        $response["success"]   = true;
        $response["message"] = "Channel created successfully";
        print_r(json_encode($response));
    }
    else {
        $response['success'] = false;
        $response['message'] = "Upload channel_image is Empty";
        
        print_r(json_encode($response));

    }

}



?>