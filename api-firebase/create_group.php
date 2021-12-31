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
if (empty($_POST['group_name'])) {
    $response['success'] = false;
    $response['message'] = "Group Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['group_description'])) {
    $response['success'] = false;
    $response['message'] = "Group Description is Empty";
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
$group_name = $db->escapeString($_POST['group_name']);
$group_description = $db->escapeString($_POST['group_description']);
$type = $db->escapeString($_POST['type']);
$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
if (!empty($res)) {
    if (isset($_FILES['group_image']) && !empty($_FILES['group_image']) && $_FILES['group_image']['error'] == 0 && $_FILES['group_image']['size'] > 0){
        if (!is_dir('../upload/group_image/')) {
            mkdir('../upload/group_image/', 0777, true);
        }
        
        $group_image = $db->escapeString($_FILES['group_image']['name']);
        $extension = pathinfo($_FILES["group_image"]["name"])['extension'];
        $result = $fn->validate_image($_FILES["group_image"]);
        if (!$result) {
            $response["error"]   = true;
            $response["message"] = "Image type must jpg, jpeg, gif, or png!";
            print_r(json_encode($response));
            return false;
        }
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = '../upload/group_image/' . "" . $filename;
        if (!move_uploaded_file($_FILES["group_image"]["tmp_name"], $full_path)) {
            $response["error"]   = true;
            $response["message"] = "Invalid directory to load group_image!";
            print_r(json_encode($response));
            return false;
        }
        $sql = "INSERT INTO groups(`user_id`,`group_name`, `group_description`, `group_image`, `type`)VALUES($user_id,'$group_name','$group_description','$filename','$type')";
        $db->sql($sql);
        $res = $db->getResult();
        $sql_query = "SELECT * FROM `groups` ORDER BY id DESC LIMIT 1";
        $db->sql($sql_query);
        $result = $db->getResult();
        if ($db->numRows($result) > 0) {
            $response["success"]   = true;
            $response["message"] = "Group created successfully";
            foreach ($result as $row) {
                $response['success']     = true;
                $response['group_id'] = $row['id'];
                $response['group_name'] = $row['group_name'];
                $response['group_description'] = $row['group_description'];
                $response['group_image'] = DOMAIN_URL . 'upload/group_image/' . $row['group_image'];
                $response['type'] = $row['type'];
                
                
            }
            print_r(json_encode($response));
            return false;

        }
        
        
    }
    else {
        $response['success'] = false;
        $response['message'] = "Upload group_image is Empty";
        
        print_r(json_encode($response));

    }

}



?>