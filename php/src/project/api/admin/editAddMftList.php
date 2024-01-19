<?php
ob_start();

#headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require_once("../../config/config_db.php");

#input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $mft_id = isset($data['mft_id']) ? $data['mft_id'] : '';
    $products = isset($data['pro_id']) ? $data['pro_id'] : [];
    $amounts = isset($data['amount']) ? $data['amount'] : [];


    // Validate input data
    if (!$mft_id || empty($products) || empty($amounts)) {
        http_response_code(412); // Precondition Failed
        echo json_encode(["result" => 0, "message" => "Invalid input data"]);
        exit(); // ใส่ exit() เพื่อหยุดการทำงาน
    }
} else {
    ob_end_clean();
    http_response_code(412); // Precondition Failed
    echo json_encode(["result" => 0, "message" => "Invalid input data"]);
    exit(); // ใส่ exit() เพื่อหยุดการทำงาน
}



$productsSeen = array();
for ($i = 0; $i < count($products); $i++) {
    $product = $products[$i];

    if (isset($productsSeen[$product])) {
        echo json_encode(array("result" => 0, "message" => "มีรายการวัตถุดิบซ้ำ กรุณาแก้ไขข้อมูล: " . $product));
        die();
    } else {
        $productsSeen[$product] = true;
    }

     // Check for duplicate entries
     $checkDuplicateQuery = "SELECT * FROM manufacture_detail WHERE pro_id = ? AND manufacture_id = ?";
     $stmtCheckDuplicate = $conn->prepare($checkDuplicateQuery);
     $stmtCheckDuplicate->bind_param('ss', $product, $mft_id);
 
     if (!$stmtCheckDuplicate->execute()) {
         // http_response_code(500); // Internal Server Error
         echo json_encode(["result" => 0, "message" => "Error checking for duplicate entries: " . $stmtCheckDuplicate->error]);
         exit();
     }
 
     $resultCheckDuplicate = $stmtCheckDuplicate->get_result();
 
     if ($resultCheckDuplicate->num_rows > 0) {
         // Duplicate entry found
         $stmtCheckDuplicate->close(); // Close the statement
         // http_response_code(400); // Bad Request
         echo json_encode(["result" => 0, "message" => "มีรายการวัตถุดิบซ้ำกรุณาแก้ไขข้อมูลในรายการ = $product"]);
         exit();
     }

}


// Insert data into the manufacture_detail table using a loop
$stmt = $conn->prepare('INSERT INTO manufacture_detail (manufacture_id, pro_id, amount) VALUES (?, ?, ?)');
$stmt->bind_param('ssd', $mft_id, $product, $amount);

for ($i = 0; $i < count($products); $i++) {
    $product = $products[$i];
    $amount = $amounts[$i];


    if (!$stmt->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error inserting into manufacture_detail: " . $stmt->error]);
        exit();
    }

    // Update the amount in the Producttable
    $updateProductQuery = "UPDATE product SET amount = amount + ? WHERE pro_id = ?";
    $stmtUpdateProduct= $conn->prepare($updateProductQuery);
    $stmtUpdateProduct->bind_param('ds', $amount, $product);

    if (!$stmtUpdateProduct->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error updating amount in Product table: " . $stmtUpdateProduct->error]);
        exit();
    }

    $stmtUpdateProduct->close();
}

$stmt->close();
$conn->close();

// Output
echo json_encode(["result" => 1, "message" => "บันทึกเรียบร้อย"]);
exit(); // ใส่ exit() เพื่อหยุดการทำงาน
?>
