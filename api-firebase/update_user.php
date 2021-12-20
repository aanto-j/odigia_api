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
if (empty($_POST['id'])) {
    $response['success'] = false;
    $response['message'] = "ID is Empty";
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


$id = $db->escapeString($_POST['id']);
$first_name = $db->escapeString($_POST['first_name']);
$last_name = $db->escapeString($_POST['last_name']);
$description = (isset($_POST['description']) && !empty($_POST['description'])) ? trim($db->escapeString($_POST['description'])) : "";
$city = (isset($_POST['city']) && !empty($_POST['city'])) ? trim($db->escapeString($_POST['city'])) : "";
$instagram = (isset($_POST['instagram']) && !empty($_POST['instagram'])) ? trim($db->escapeString($_POST['instagram'])) : "";
$twitter = (isset($_POST['twitter']) && !empty($_POST['twitter'])) ? trim($db->escapeString($_POST['twitter'])) : "";
$facebook = (isset($_POST['facebook']) && !empty($_POST['facebook'])) ? trim($db->escapeString($_POST['facebook'])) : "";
$linkedin = (isset($_POST['linkedin']) && !empty($_POST['linkedin'])) ? trim($db->escapeString($_POST['linkedin'])) : "";
$youtube = (isset($_POST['youtube']) && !empty($_POST['youtube'])) ? trim($db->escapeString($_POST['youtube'])) : "";
$update_profile = (isset($_POST['update_profile']) && !empty($_POST['update_profile'])) ? trim($db->escapeString($_POST['update_profile'])) : "";

$sql = 'select * from users where id =' . $id;
$db->sql($sql);
$res = $db->getResult();

if (!empty($res)) {
    if(isset($update_profile) && $update_profile == 1){
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
            $data = array(
                'profile' => $filename
            );
            if ($db->update('users', $data, 'id=' . $id)) {
                $response["success"]   = true;
                $response["message"] = "User updated successfully";
                print_r(json_encode($response));
        
            }
            else {
                $response["success"]   = false;
                $response["message"] = "User updated failed";
                print_r(json_encode($response));
        
            }
        
        
        }
        else {
            $response["success"]   = false;
            $response["message"] = "User updated failed";
            print_r(json_encode($response));
    
        }

    }
    else {
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'description' => $description,
            'city' => $city,
            'instagram' => $instagram,
            'twitter' => $twitter,
            'facebook' => $facebook,
            'linkedin' => $linkedin,
            'youtube' => $youtube
        );
        if ($db->update('users', $data, 'id=' . $id)) {
            $response["success"]   = true;
            $response["message"] = "User updated successfully";
            print_r(json_encode($response));
    
        }
        else {
            $response["success"]   = false;
            $response["message"] = "User updated failed";
            print_r(json_encode($response));
    
        }
    
    }
    
}





?>