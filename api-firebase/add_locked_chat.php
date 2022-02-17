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

if (empty($_POST['view_price'])) {
    $response['success'] = false;
    $response['message'] = "no price!";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['message'])) {
    $response['success'] = false;
    $response['message'] = "Message cannot be empty!";
    print_r(json_encode($response));
    return false;
}

$view_price = $db->escapeString($_POST['view_price']);
$message = $db->escapeString($_POST['message']);
$public = (isset($_POST['public']) && !empty($_POST['public'])) ? trim($db->escapeString($_POST['public'])) : "0";
$expiry_period = (isset($_POST['expiry_period']) && !empty($_POST['expiry_period'])) ? trim($db->escapeString($_POST['expiry_period'])) : "0";
$visible_msg = (isset($_POST['visible_msg']) && !empty($_POST['visible_msg'])) ? trim($db->escapeString($_POST['visible_msg'])) : "";
$videoname = (isset($_POST['video']) && !empty($_POST['video'])) ? trim($db->escapeString($_POST['video'])) : "";
$filename = (isset($_POST['file']) && !empty($_POST['file'])) ? trim($db->escapeString($_POST['file'])) : "";

if(isset($_FILES['image']) && !empty($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0){
    if(!is_dir("../upload/locked/images/")){
        mkdir("../upload/locked/images/",0777,true);
    }

    $image = $db->escapeString($_FILES['image']['name']);
    $extension = pathinfo($_FILES["image"]["name"])['extension'];
    $result = $fn->validate_image($_FILES["image"]);
    if (!$result) {
        $response["error"]   = true;
        $response["message"] = "Image type must jpg, jpeg, gif, or png!";
        print_r(json_encode($response));
        return false;
    }
    $imagename = microtime(true) . '.' . strtolower($extension);
    $full_path = '../upload/locked/images/' . "" . $imagename;
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
        $response["error"]   = true;
        $response["message"] = "Invalid directory to load profile!";
        print_r(json_encode($response));
        return false;
    }
    $sql = "INSERT INTO `locked` (`view_price`, `message`, `public`, `expiry_period`, `visible_msg`,`image`,`video`,`file`) 
    VALUES ('$view_price', '$message', '$public', '$expiry_period', '$visible_msg', '$imagename', '$videoname', '$filename')";
    $db->sql($sql);
    $response["success"] = true;
    $response["message"] = "Message added successfully with image";
    print_r(json_encode($response));
}
else if(isset($_FILES['video']) && !empty($_FILES['video']) && $_FILES['video']['error'] == 0 && $_FILES['video']['size'] > 0){
    if(!is_dir("../upload/locked/video/")){
        mkdir("../upload/locked/video/",0777,true);
    }

    $image = $db->escapeString($_FILES['video']['name']);
    $extension = pathinfo($_FILES["video"]["name"])['extension'];
    $result = $fn->validate_video($_FILES["video"]);
    if (!$result) {
        $response["error"]   = true;
        $response["message"] = "Video type must be .mkv, .mov or .mp4!";
        print_r(json_encode($response));
        return false;
    }
    $imagename = microtime(true) . '.' . strtolower($extension);
    $full_path = '../upload/locked/video/' . "" . $imagename;
    if (!move_uploaded_file($_FILES["video"]["tmp_name"], $full_path)) {
        $response["error"]   = true;
        $response["message"] = "Invalid directory to load profile!";
        print_r(json_encode($response));
        return false;
    }
    $sql = "INSERT INTO `locked` (`view_price`, `message`, `public`, `expiry_period`, `visible_msg`,`image`,`video`,`file`) 
    VALUES ('$view_price', '$message', '$public', '$expiry_period', '$visible_msg', '$imagename', '$videoname', '$filename')";
    $db->sql($sql);
    $response["success"] = true;
    $response["message"] = "Message added successfully with video";
    print_r(json_encode($response));
}
// else if(isset($_FILES['image']) && !empty($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0){
//     if(!is_dir("../upload/locked/images/")){
//         mkdir("../upload/locked/images/",0777,true);
//     }

//     $image = $db->escapeString($_FILES['image']['name']);
//     $extension = pathinfo($_FILES["image"]["name"])['extension'];
//     $result = $fn->validate_image($_FILES["image"]);
//     if (!$result) {
//         $response["error"]   = true;
//         $response["message"] = "Image type must jpg, jpeg, gif, or png!";
//         print_r(json_encode($response));
//         return false;
//     }
//     $imagename = microtime(true) . '.' . strtolower($extension);
//     $full_path = '../upload/locked/images/' . "" . $imagename;
//     if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
//         $response["error"]   = true;
//         $response["message"] = "Invalid directory to load profile!";
//         print_r(json_encode($response));
//         return false;
//     }
//     $sql = "INSERT INTO `locked` (`view_price`, `message`, `public`, `expiry_period`, `visible_msg`,`image`,`video`,`file`) 
//     VALUES ('$view_price', '$message', '$public', '$expiry_period', '$visible_msg', '$imagename', '$videoname', '$filename')";
//     $db->sql($sql);
//     $response["success"] = true;
//     $response["message"] = "Message added successfully with image";
//     print_r(json_encode($response));
// }
else{
    $sql = "INSERT INTO `locked` (`view_price`, `message`, `public`, `expiry_period`, `visible_msg`,`image`,`video`,`file`) 
    VALUES ('$view_price', '$message', '$public', '$expiry_period', '$visible_msg', '', '$videoname', '$filename')";
    $db->sql($sql);
    $response["success"] = true;
    $response["message"] = "Message added successfully without image";
    print_r(json_encode($response));
}


?>