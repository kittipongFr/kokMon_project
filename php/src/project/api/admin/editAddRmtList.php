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
    $rmt_id = isset($data['rmt_id']) ? $data['rmt_id'] : '';
    $materials = isset($data['material']) ? $data['material'] : [];
    $amounts = isset($data['amount']) ? $data['amount'] : [];
    $prices = isset($data['price']) ? $data['price'] : [];
    $sum_cost = 0;

    // Validate input data
    if (!$rmt_id || empty($materials) || empty($amounts) || empty($prices)) {
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

$materialsSeen = array();
for ($i = 0; $i < count($materials); $i++) {
    $material = $materials[$i];
    $amount = $amounts[$i];
    $price = $prices[$i];

    if (isset($materialsSeen[$material])) {
        echo json_encode(array("result" => 0, "message" => "มีรายการวัตถุดิบซ้ำ กรุณาแก้ไขข้อมูล: " . $material));
        die();
    } else {
        $materialsSeen[$material] = true;
    }

    // Check for duplicate entries
    $checkDuplicateQuery = "SELECT * FROM receive_material_detail WHERE material_id = ? AND receive_material_id = ?";
    $stmtCheckDuplicate = $conn->prepare($checkDuplicateQuery);
    $stmtCheckDuplicate->bind_param('ss', $material, $rmt_id);

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
        echo json_encode(["result" => 0, "message" => "มีรายการวัตถุดิบซ้ำกรุณาแก้ไขข้อมูล = $material"]);
        exit();
    }

}
$stmtCheckDuplicate->close(); // Close the statement

// Insert data into the receive_material_detail table using a loop
$stmt = $conn->prepare('INSERT INTO receive_material_detail (receive_material_id, material_id, amount, net, price) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('ssddd', $rmt_id, $material, $amount, $amount, $price);



for ($i = 0; $i < count($materials); $i++) {
    $material = $materials[$i];
    $amount = $amounts[$i];
    $price = $prices[$i];
    $sum_cost += $amounts[$i] * $prices[$i];

    

    // Insert data into receive_material_detail
    if (!$stmt->execute()) {
        // http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error inserting into receive_material_detail: " . $stmt->error]);
        exit();
    }

    // Update the amount in the material table
    $updateMaterialQuery = "UPDATE material SET amount = amount + ? WHERE material_id = ?";
    $stmtUpdateMaterial = $conn->prepare($updateMaterialQuery);
    $stmtUpdateMaterial->bind_param('ds', $amount, $material);

    if (!$stmtUpdateMaterial->execute()) {
        // http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error updating amount in material table: " . $stmtUpdateMaterial->error]);
        exit();
    }

    $stmtUpdateMaterial->close(); // Close the statement
}
$updateCommuQuery = "UPDATE community_enterprise SET aml_fund = aml_fund - ? WHERE id = ?";
$stmtUpdateCommu = $conn->prepare($updateCommuQuery);
$updatedAmlFund = $sum_cost;
$communityId = 1;
$stmtUpdateCommu->bind_param('di', $updatedAmlFund, $communityId);
$stmtUpdateCommu->execute();
if ($stmtUpdateCommu->error) {
    echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด : " . $stmtUpdateCommu->error));
    die();
}



$stmtUpdateCommu->close();



$stmt->close(); // Close the statement

$conn->close();


// Output
echo json_encode(["result" => 1, "message" => "บันทึกเรียบร้อย"]);
exit(); // ใส่ exit() เพื่อหยุดการทำงาน
?>
