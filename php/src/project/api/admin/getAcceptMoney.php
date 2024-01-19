<?php
ob_start();

#header
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require("../../config/config_db.php");

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
$strSQL = "SELECT accept_money_id,accept_money_total,date  FROM accept_money WHERE order_id = '$id'  ORDER BY accept_money_id DESC";

$query = mysqli_query($conn, $strSQL);
$datalist = array();
if ($query->num_rows > 0) {
    while ($resultQuery = mysqli_fetch_array($query)) {
        $accept_money_id = $resultQuery['accept_money_id'];
        $accept_money_total = $resultQuery['accept_money_total'];

        $date = $resultQuery['date'];


        if (!isset($datalist["sum_total"])) {
           

            $datalist["accept_money_id"] = array();
            $datalist["accept_money_total"] = array();
            $datalist["date"] = array();
        }

        $datalist["accept_money_id"][] = $accept_money_id; 
        $datalist["accept_money_total"][] = $accept_money_total; 
        $datalist["date"][] = $date;
        $datalist["sum_total"]  += $resultQuery['accept_money_total'];
    }
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
