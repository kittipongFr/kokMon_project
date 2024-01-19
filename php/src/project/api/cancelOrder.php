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
    $detail = isset($json_data["detail"]) ? trim($json_data["detail"]) : "";



    // ตรวจสอบ key ที่ใช้ใน JSON
    if (empty($id) || empty($detail)) {
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
$productList = array();
$strSelect = "SELECT * FROM orders_detail WHERE order_id = '$id'";
$querySelect = mysqli_query($conn,$strSelect);
 while ($row =  mysqli_fetch_assoc($querySelect)){
    $productList[] = array(
        "pro_id"=>$row["pro_id"],
        "amount"=>$row["amount"],
        );
 }

 for ($i = 0; $i < count($productList); $i++) {
    $strUpdateReserve = "UPDATE product SET amount_reserve = amount_reserve - '{$productList[$i]['amount']}'  WHERE pro_id = '{$productList[$i]['pro_id']}'";
    $queryUpdateReserve = mysqli_query($conn,$strUpdateReserve);
}


$strUpdate = "UPDATE orders SET status = '7' WHERE order_id = '$id'";
$queryUpdate = mysqli_query($conn,$strUpdate);
if (!$queryUpdate) {
    ob_end_clean();
    header("HTTP/1.0 500 Internal Server Error");
    die(json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด")));
}



# Insert into order table
$strSQLAdsInsert = "INSERT INTO `cus_cancel` (order_id, detail) VALUES (?, ?)";
$stmtAdsInsert = mysqli_prepare($conn, $strSQLAdsInsert);

// ตรวจสอบว่าคำสั่ง SQL สำเร็จหรือไม่
if (!$stmtAdsInsert) {
    ob_end_clean();
    header("HTTP/1.0 500 Internal Server Error");
    die(json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด")));
}
// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmtAdsInsert, "ss", $id, $detail);

// Execute the prepared statement
$queryAdsInsert = mysqli_stmt_execute($stmtAdsInsert);

// Check if the query was successful
if (!$queryAdsInsert) {
    // Log the error and return an appropriate response
    $error_message = mysqli_stmt_error($stmtAdsInsert);
    ob_end_clean();
    header("HTTP/1.0 500 Internal Server Error");
    die(json_encode(array("result" => 0, "message" => "เกิดข้อผิดผลาด: $error_message")));
}
// Close the prepared statement
mysqli_stmt_close($stmtAdsInsert);
?>




<?php
# output
$json_response = json_encode(array("result" => 1,  "message" => "ยกเลิกเรียบร้อย"));
echo $json_response;

// Close the connection
mysqli_close($conn);
?>

<?php
# log function
?>
