<?php
ob_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>

<?php
# connection and data include OR require
require("../config/config_db.php");
?>

<?php
# input
# input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = file_get_contents('php://input');
    $json_data = json_decode($content, true);

    // ตรวจสอบว่า JSON decoding สำเร็จหรือไม่
    if ($json_data === null) {
        ob_end_clean();
        header("HTTP/1.0 400 Bad Request");
        die(json_encode(array("result" => 0, "message" => "Invalid JSON")));
    }

    // ตรวจสอบค่าที่ได้จาก JSON ว่ามีหรือไม่
    $id = isset($json_data["id"]) ? trim($json_data["id"]) : "";
    $status = isset($json_data["status"]) ? trim($json_data["status"]) : "";

    // echo $cus_id;

    // ตรวจสอบ key ที่ใช้ใน JSON
    if (empty($id) ) {
        ob_end_clean();
        header("HTTP/1.0 400 Bad Request");
        die(json_encode(array("result" => 0, "message" => "Invalid JSON keys")));
    }
} else {
    ob_end_clean();
    header("HTTP/1.0 412 Precondition Failed");
    die(json_encode(array("result" => 0, "message" => "Invalid request")));
}

?>



<?php

$strSQL= "UPDATE orders SET status = '$status' WHERE order_id = '$id'";
$query = mysqli_query($conn, $strSQL);

if (!$query) {
    // Log the error and return an appropriate response
    $error_message = mysqli_error($conn,$query);
    ob_end_clean();
    header("HTTP/1.0 500 Internal Server Error");
    die(json_encode(array("result" => 0, "message" => "เกิดข้อผิดผลาด: $error_message")));
}


?>




<?php
# output
$json_response = json_encode(array("result" => 1,  "message" => "ยืนยันการรับสินค้าเรียบร้อย"));
echo $json_response;

// Close the connection
mysqli_close($conn);
?>

<?php
# log function
?>
