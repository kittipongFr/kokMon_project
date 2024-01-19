<?php
ob_start();

#header
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require("../../config/config_db.php");

#input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $mft_id = isset($data['id']) ? $data['id'] : '';
    $pro_id = isset($data['pro_id']) ? $data['pro_id'] : '';

    // Input validation
    if (empty($mft_id) || empty($pro_id)) {
        http_response_code(412); // Precondition Failed
        echo json_encode(["result" => 0, "message" => "Invalid input data"]);
        exit();
    }

    // Update Product amount
  
 
    $updateProductQuery = "
        UPDATE product
        SET amount = IF((amount - (
            SELECT SUM(amount)
            FROM manufacture_detail
            WHERE manufacture_detail.pro_id = product.pro_id
            AND manufacture_detail.manufacture_id = ?
        )) = 0, 0.00, (amount - (
            SELECT SUM(amount)
            FROM manufacture_detail
            WHERE manufacture_detail.pro_id = product.pro_id
            AND manufacture_detail.manufacture_id = ?
        )))
        WHERE pro_id = ?;
    ";
    
    // เพิ่ม $mt_id ใน bind_param สองครั้ง
    $stmtUpdateProduct = $conn->prepare($updateProductQuery);
    $stmtUpdateProduct->bind_param('sss', $mft_id, $mft_id, $pro_id);
    $stmtUpdateProduct->execute();


    
    // Check for errors
    if ($stmtUpdateProduct->error) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error updating Product amount: " . $stmtUpdateProduct->error]);
        exit();
    }

    $stmtUpdateProduct->close();

    // Delete data from manufacture_detail based on manufacture_id and material_id
    $deleteDetailQuery = "DELETE FROM manufacture_detail WHERE manufacture_id = ? AND pro_id = ?";
    $stmtDeleteDetail = $conn->prepare($deleteDetailQuery);
    $stmtDeleteDetail->bind_param('ss', $mft_id, $pro_id);
    $stmtDeleteDetail->execute();

    // Check for errors
    if ($stmtDeleteDetail->error) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error deleting from manufacture_detail: " . $stmtDeleteDetail->error]);
        exit();
    }

    $stmtDeleteDetail->close();
    $conn->close();

    // Output
    echo json_encode(["result" => 1, "message" => "ลบข้อมูลสำเร็จ"]);
    exit();
} else {
    ob_end_clean();
    http_response_code(412); // Precondition Failed
    echo json_encode(["result" => 0, "message" => "Invalid input data"]);
    exit();
}
?>
