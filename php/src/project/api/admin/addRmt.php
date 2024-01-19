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
    $data = json_decode(file_get_contents("php://input"), true);
    $mem_id = isset($data['mem_id']) ? $data['mem_id'] : '';
    $supply_name = isset($data['supply_name']) ? $data['supply_name'] : '';
    $materials = isset($data['material']) ? $data['material'] : array();
    $amounts = isset($data['amount']) ? $data['amount'] : array();
    $prices = isset($data['price']) ? $data['price'] : array();
    $date = isset($data['date']) ? $data['date'] : '';
    $rmt_id = "";

    $sum_cost = 0;
    

} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}

if(empty($mem_id)||empty($supply_name)||empty($materials)||empty($amounts)||empty($prices)||empty($date)){
    echo json_encode(array("result" => 0, "message" => "ข้อมูลไม่ครบ"));
        die();
}

// Generate a unique rmt_id
$selectQuery = "SELECT receive_material_id FROM receive_material ORDER BY receive_material_id DESC LIMIT 1";
$result = $conn->query($selectQuery);

if ($result->num_rows > 0) {
    // Fetch the manufacture_id
    $row = $result->fetch_assoc();
    $receive_material_id = $row['receive_material_id'];

    // Extract the date part
    $dateSplit = substr($receive_material_id, 2, 6);

    // Extract the numeric part
    $numericPart = (int)substr($receive_material_id, 8);

    // Format the current date with only the last two digits of the year
    $currentDate = date("ymd");

    if ($dateSplit == $currentDate) {
        $new_id = $numericPart + 1;
        $formatted_id = "rm" . $currentDate . sprintf("%05d", $new_id);
    } else {
        $formatted_id = "rm" . $currentDate . "00001";
    }

    
} else {
    $currentDate = date("ymd");
    $formatted_id = "rm" . $currentDate . "00001";
    
}

$rmt_id = $formatted_id;

// Insert data into the receive_material table


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

}

$stmt = $conn->prepare('INSERT INTO receive_material (receive_material_id, mem_id, supply_name,date) VALUES (?, ?, ?,?)');
$stmt->bind_param('ssss', $rmt_id, $mem_id, $supply_name,$date);
$stmt->execute();

// Check for errors
if ($stmt->error) {
    echo json_encode(array("result" => 0, "message" => "Error inserting into receive_material: " . $stmt->error));
    die();
}

$stmt->close();



$stmt = $conn->prepare('INSERT INTO receive_material_detail (receive_material_id, material_id, amount, net, price) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('ssddd', $rmt_id, $material, $amount, $amount, $price);




for ($i = 0; $i < count($materials); $i++) {
    $material = $materials[$i];
    $amount = $amounts[$i];
    $price = $prices[$i];
    $sum_cost += $amounts[$i] * $prices[$i];
    $stmt->execute();

    // Check for errors
    if ($stmt->error) {
        echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด : " . $stmt->error));
        die();
    }
 

    // Update the amount in the material table
    $updateMaterialQuery = "UPDATE material SET amount = amount + ? WHERE material_id = ?";
    $stmtUpdateMaterial = $conn->prepare($updateMaterialQuery);
    $stmtUpdateMaterial->bind_param('ds', $amount, $material);
    $stmtUpdateMaterial->execute();

    // Check for errors
    if ($stmtUpdateMaterial->error) {
        echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด : " . $stmtUpdateMaterial->error));
        die();
    }

    $stmtUpdateMaterial->close();
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
$stmt->close();
$conn->close();

// Output
echo json_encode(array("result" => 1, "message" => "บันทึกเรียบร้อย"));

?>
