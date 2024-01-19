<?php

use PSpell\Dictionary;

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
    $amount_list = isset($data['amount_list']) ? $data['amount_list'] : '';
    $pro_id_list = isset($data['pro_id_list']) ? $data['pro_id_list'] : '';

    // เชื่อมต่อกับฐานข้อมูล
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

if($status === "6"){


    for ($i = 0; $i < count($pro_id_list); $i++) {
        $strUpdatePro = "UPDATE product SET amount = amount-'$amount_list[$i]' , amount_reserve = amount_reserve+'$amount_list[$i]'  WHERE pro_id = '$pro_id_list[$i]'";
        $queryPro = mysqli_query($conn, $strUpdatePro);
        if (!$queryPro) {
            echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด", "error" => mysqli_error($conn)));
            die();
            }
    }
    

        // สร้างคำสั่ง SQL เพื่ออัปเดต status ในตาราง orders
        $sql = "UPDATE orders SET status = ? WHERE order_id = ?";

        // สร้าง prepared statement
        $stmt = $conn->prepare($sql);

        // ผูกพารามิเตอร์
        $stmt->bind_param("ss", $status, $order_id);

        // ประมวลผลคำสั่ง SQL
        if ($stmt->execute()) {
            echo json_encode(array("result" => 1, "message" => "สำเร็จ"));
        } else {
            echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด: " . $stmt->error));
            die();
        }



}else{
    // สร้างคำสั่ง SQL เพื่ออัปเดต status ในตาราง orders
    $sql = "UPDATE orders SET status = ? WHERE order_id = ?";

    // สร้าง prepared statement
    $stmt = $conn->prepare($sql);

    // ผูกพารามิเตอร์
    $stmt->bind_param("ss", $status, $order_id);

    // ประมวลผลคำสั่ง SQL
    if ($stmt->execute()) {
        echo json_encode(array("result" => 1, "message" => "สำเร็จ"));
    } else {
        echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด: " . $stmt->error));
        die();
    }
    // ปิด prepared statement
    $stmt->close();
}


    // ปิดการเชื่อมต่อกับฐานข้อมูล
    $conn->close();


} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid request method", "datalist" => null));
}
?>
