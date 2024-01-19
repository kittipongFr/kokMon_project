<?php
ob_start();

#header
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require("../config/config_db.php");

#input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = @file_get_contents('php://input');
    $json_data = @json_decode($content, true);
    $id = trim($json_data["id"]);
} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    die();
}

#process
$strSQL = "SELECT * FROM payment WHERE order_id = '$id' ORDER BY pay_id DESC";

$query = mysqli_query($conn, $strSQL);
$datalist = array();

while ($resultQuery = mysqli_fetch_array($query)) {
    $pay_id = $resultQuery['pay_id'];
    $pay_total = $resultQuery['pay_total'];
    $slip_img = $resultQuery['slip_img'];
    $date = $resultQuery['date'];


    if (!isset($datalist["pay_total"])) {
        $datalist["pay_total"] = $pay_total;

        $datalist["pay_id"] = array();
        $datalist["slip_img"] = array();
        $datalist["date"] = array();
    }

    $datalist["pay_id"][] = $resultQuery['pay_id']; 
    $datalist["slip_img"][] = $resultQuery['slip_img']; 
    $datalist["date"][] = $resultQuery['date'];
}

#output
ob_end_clean();
@mysqli_close($conn);
if ($query) {
    echo $json_response = json_encode(array("result" => 1, "message" => "พบข้อมูล", "datalist" => $datalist));
} else {
    echo $json_response = json_encode(array("result" => 0, "message" => "ไม่พบข้อมูล", "datalist" => null));
}
exit;
?>
