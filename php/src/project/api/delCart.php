<?php ob_start(); ?>

<?php
    #header
    @header('Content-Type: application/json');
    @header("Access-Control-Allow-Origin: *");
    @header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>

<?php
    #connection and data include OR require
    require("../config/config_db.php");
?>

<?php
    #input
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = @file_get_contents('php://input');
        $json_data = @json_decode($content, true);
        $pro_id = trim($json_data["pro_id"]);
        $cus_id = trim($json_data["cus_id"]);
    } else {
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();
    }
?>

<?php

        $strSQL = "DELETE FROM cart WHERE pro_id = '$pro_id' AND cus_id = '$cus_id'  ";
        $query = mysqli_query($conn, $strSQL);

?>

<?php
    #output
    ob_end_clean();
    @mysqli_close($conn);
    if ($query) {
        echo $json_response = json_encode(array("result" => 1, "message" => "ลบสำเร็จ"));
    } else {
        echo $json_response = json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด"));
    }
    exit;
?>

<?php
    #log function
?>
