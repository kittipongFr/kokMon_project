<?php
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require("../../config/config_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีข้อมูล JSON ที่ถูกส่งมาหรือไม่
    $data = json_decode(file_get_contents("php://input"), true);
    $status = isset($data['status']) ? $data['status'] : '';
    $order_id = isset($data['order_id']) ? $data['order_id'] : '';
    $cus_id = isset($data['cus_id']) ? $data['cus_id'] : '';
    

    // เชื่อมต่อกับฐานข้อมูล
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // สร้างคำสั่ง SQL เพื่ออัปเดต status ในตาราง orders
    $sql = "UPDATE orders SET status = ? WHERE order_id = ?";

    // สร้าง prepared statement
    $stmt = $conn->prepare($sql);

    // ผูกพารามิเตอร์
    $stmt->bind_param("ss", $status, $order_id);

    // ประมวลผลคำสั่ง SQL
    if ($stmt->execute()) {
        $response = array("result" => 1, "message" => "สำเร็จ");
    } else {
        $response = array("result" => 0, "message" => "เกิดข้อผิดพลาด: " . $stmt->error);
    }

    // ปิด prepared statement
    $stmt->close();

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    $conn->close();

    // ส่งคำตอบกลับเป็น JSON
    echo json_encode($response);

} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid request method", "datalist" => null));
}
?>
