<?php
/*
functions
---------------------------------------------
0. xss_clean($data)
1. get_product_by_id($id=null)
2. get_product_by_variant_id($arr)
3. convert_to_parent($measurement,$measurement_unit_id)
4. rows_count($table,$field = '*',$where = '')
5. get_configurations()
6. get_balance($id)
7. get_bonus($id)
8. get_wallet_balance($id)
9. update_wallet_balance($balance,$id)
10. add_wallet_transaction($order_id="",$id,$type,$amount,$message,$status = 1)
11. update_order_item_status($order_item_ids,$order_id,$status)
12. validate_promo_code($user_id,$promo_code,$total)
13. get_settings($variable,$is_json = false)
14. send_order_update_notification($uid,$title,$message,$type)
15. send_notification_to_delivery_boy($uid,$title,$message,$type,$order_id)
16. get_promo_details($promo_code)
17. store_return_request($user_id,$order_id,$order_item_id)
18. get_role($id)
19. get_permissions($id)
20. add_delivery_boy_commission($id,$type,$amount,$message,$status = "SUCCESS")
21. store_delivery_boy_notification($delivery_boy_id,$order_id,$title,$message,$type)
22. is_item_available_in_cart($user_id,$product_variant_id)
23. time_slot_config()
24. is_address_exists($id="",$user_id="")
25. is_user_or_dboy_exists($type,$type_id)
26. get_user_or_delivery_boy_balance($type,$type_id)
27. store_withdrawal_request($type, $type_id, $amount, $message)
28. debit_balance($type, $type_id, $new_balance)
29. is_records_exists($type, $type_id,$offset,$limit)
30. get_product_id_by_variant_id($product_variant_id)
31. update_delivery_boy_wallet_balance($balance, $id)
32. low_stock_count($low_stock_limit)
33. sold_out_count()
34. is_product_available($product_id)
35. is_product_added_as_favorite($user_id, $product_id)
36. validate_email($email)
37. update_forgot_password_code($email,$code)
38. validate_code($code)
39. get_user($code)
40. update_password($code,$password_hash)
41. is_return_request_exists($user_id, $order_item_id)
42. get_last_inserted_id($table)
43. is_product_cancellable($order_item_id)
44. is_default_address_exists($user_id)
44. get_data($fields=[], $where,$table)
45. update_order_status($id,$status,$delivery_boy_id=0)
46. verify_paystack_transaction($reference, $email, $amount)
47. get_variant_id_by_product_id($product_id)
48. get_order_item_by_order_id($id)
49. add_wallet_balance($order_id, $user_id, $amount, $type,$message)
50. send_notification_to_admin($id, $title, $message, $type, $order_id)
51. add_seller_wallet_transaction($order_id = "",$order_item_id, $seller_id, $type, $amount, $message = 'Used against Order Placement', $status = 1)
52. replaceArrayKeys($array)
53. validate_image($file, $is_image = true)
54. validate_other_images($tmp_name, $type)
55. is_order_item_cancelled($order_item_id)
56. is_order_item_returned($active_status, $postStatus)
57. cancel_order_item($id, $order_item_id)
58. get_user_address($address_id)
59. send_notification_to_seller($sid, $title, $message, $type, $id)
60. check_for_return_request($product_id = 0, $order_id = 0)
61. delete_product($product_id)
62. get_seller_permission($seller_id, $permission)
63. get_seller_balance($seller_id)
64. delete_order($order_id)
65. delete_order_item($order_item_id)
66. select_top_sellers()
67. select_top_categories()
68. function set_timezone($config)
69. delete_other_images($pid, $i, $seller_id = "0")
70. delete_variant($v_id)
71. get_seller_address($seller_id)
72. add_transaction($order_id = "", $id = "", $type = '', $amount, $message = '', $date = '', $status = 1)



*/
include_once('crud.php');

class custom_functions
{
    protected $db;
    function __construct()
    {
        $this->db = new Database();
        $this->db->connect();
    }


    public function validate_image($file, $is_image = true)
    {
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $file['tmp_name']);
        } else if (function_exists('mime_content_type')) {
            $type = mime_content_type($file['tmp_name']);
        } else {
            $type = $file['type'];
        }
        $type = strtolower($type);
        if ($is_image == false) {
            if (in_array($type, array('text/plain', 'application/csv', 'application/vnd.ms-excel', 'text/csv'))) {
                return true;
            } else {
                return false;
            }
        } else {
            if (in_array($type, array('image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'application/octet-stream'))) {
                return true;
            } else {
                return false;
            }
        }
    }
    
}
// $this->db->disconnect();