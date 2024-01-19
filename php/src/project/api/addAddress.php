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
    $cus_id = isset($json_data["cus_id"]) ? trim($json_data["cus_id"]) : "";
    $tel = isset($json_data["tel"]) ? trim($json_data["tel"]) : "";
    $name = isset($json_data["name"]) ? trim($json_data["name"]) : "";
    $address = isset($json_data["address"]) ? trim($json_data["address"]) : "";

    // echo $cus_id;

    // ตรวจสอบ key ที่ใช้ใน JSON
    if (empty($cus_id) || empty($tel) || empty($name) || empty($address)) {
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
# Generate a unique order_id
$selectQuery = "SELECT address_id FROM address ORDER BY address_id DESC LIMIT 1";
$result = $conn->query($selectQuery);
if ($result->num_rows > 0) {
    // Fetch the manufacture_id
    $row = $result->fetch_assoc();
    $address_id = $row['address_id'];

    // Extract the date part
    $dateSplit = substr($address_id, 2, 6);

    // Extract the numeric part
    $numericPart = (int)substr($address_id, 8);

    // Format the current date with only the last two digits of the year
    $currentDate = date("ymd");

    if ($dateSplit == $currentDate) {
        $new_id = $numericPart + 1;
        $formatted_id = "ad" . $currentDate . sprintf("%05d", $new_id);
    } else {
        $formatted_id = "ad" . $currentDate . "00001";
    }
} else {
    $currentDate = date("ymd");
    $formatted_id = "ad" . $currentDate . "00001";
    
}

$address_id = $formatted_id;


?>

<?php
# Insert into order table
$strSQLAdsInsert = "INSERT INTO `address` (address_id, cus_id, tel, name, address) VALUES (?, ?, ?, ?, ?)";
$stmtAdsInsert = mysqli_prepare($conn, $strSQLAdsInsert);

// ตรวจสอบว่าคำสั่ง SQL สำเร็จหรือไม่
if (!$stmtAdsInsert) {
    ob_end_clean();
    header("HTTP/1.0 500 Internal Server Error");
    die(json_encode(array("result" => 0, "message" => "Database error")));
}
// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmtAdsInsert, "sssss", $address_id, $cus_id, $tel, $name, $address);

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
$json_response = json_encode(array("result" => 1,  "message" => "เพิ่มที่อยู่เรียบร้อย"));
echo $json_response;

// Close the connection
mysqli_close($conn);
?>

<?php
# log function
?>
