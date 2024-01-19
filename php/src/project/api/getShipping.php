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
$strSQL = "SELECT * FROM shipping WHERE order_id = '$id'";

$query = mysqli_query($conn, $strSQL);


$resultQuery = mysqli_fetch_array($query);
    $tracking = $resultQuery['tracking'];
    $shipping_co = $resultQuery['shipping_co'];
    $date = $resultQuery['date'];


        $datalist = array(
                "tracking"=>$tracking,
                "shipping_co"=> $shipping_co,
                "date"=> $date
        );



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
