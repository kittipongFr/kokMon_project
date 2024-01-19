<?php
ob_start();

# Set headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

# Include necessary files
require("../../config/config_db.php");

# Validate input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate $data structure
    if ( !isset($data['mt_id']) || !isset($data['amount']) || !isset($data['mtu_id'])) {
        ob_end_clean();
        http_response_code(412); // Precondition Failed
        echo json_encode(array("result" => 0, "message" => "Invalid input data"));
        die();
    }

    $material = $data['mt_id'];
    $amounts = $data['amount'];
    $mtu_id = $data['mtu_id'];
} else {
    ob_end_clean();
    http_response_code(412); // Precondition Failed
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}


$materialsSeen = array();
for ($i = 0; $i < count($material); $i++) {
    $materialOne = $material[$i];
  
    if (isset($materialsSeen[$materialOne])) {
        echo json_encode(array("result" => 0, "message" => "มีรายการวัตถุดิบซ้ำ กรุณาแก้ไขข้อมูล: " . $materialOne));
        die();
    } else {
        $materialsSeen[$materialOne] = true;
    }


    // Check for duplicate entries
    $checkDuplicateQuery = "SELECT * FROM material_used_detail WHERE material_id = ? AND material_used_id = ?";
    $stmtCheckDuplicate = $conn->prepare($checkDuplicateQuery);
    $stmtCheckDuplicate->bind_param('ss', $materialOne, $mtu_id);

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
        echo json_encode(["result" => 0, "message" => "มีรายการวัตถุดิบซ้ำกรุณาแก้ไขข้อมูล = $materialOne"]);
        exit();
    }

}
$stmtCheckDuplicate->close(); // Close the statement


// Insert data into the material_used_detail table using a loop
$stmtInsertMaterialUsedDetail = $conn->prepare('INSERT INTO material_used_detail (material_used_id, material_id, amount, cost) VALUES (?, ?, ?, ?)');
$stmtInsertMaterialUsedDetail->bind_param('ssdd', $mtu_id, $mt, $amount, $cost);

$conn->begin_transaction(); // Start transaction

for ($i = 0; $i < count($material); $i++) {
    $mt = $material[$i];
    $amount = $amounts[$i];
    $cost = 0.00;
    $check_amount = $amounts[$i];

    // Fetch eligible records from receive_material_detail
    $fetchQuery = "SELECT rmd.receive_material_id, rmd.price, rmd.net, rm.date ,rmd.amount, 
            (SELECT SUM(net) FROM receive_material_detail WHERE material_id = ? AND amount != 0) as sumNet
               FROM receive_material_detail AS rmd
               JOIN receive_material AS rm ON rmd.receive_material_id = rm.receive_material_id
               WHERE rmd.material_id = ? AND rmd.amount > 0
               ORDER BY rm.date ASC";

    $stmtFetch = $conn->prepare($fetchQuery);
    $stmtFetch->bind_param('ss', $mt,$mt);

    $stmtFetch->execute();
    $resultFetch = $stmtFetch->get_result();

    while ($row = $resultFetch->fetch_assoc()) {
        // Calculate cost based on price and amount
        $totalCost = $row['price'] * min($amount, $row['net']);
        $cost += $totalCost;


        if($check_amount>($row["sumNet"])){
            echo json_encode(array("result" => 0, "message" => "จำนวนวัตถุดิบไม่เพียงพอ  วัตถุดิบคงเหลือ : ".$row["sumNet"]));
            die();
        }

        // Update receive_material_detail
        $updateQuery = "UPDATE receive_material_detail SET net = net - ? WHERE receive_material_id = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param('ds', min($amount, $row['net']), $row['receive_material_id']);
        $stmtUpdate->execute();

        // Update the remaining amount to reduce
        $amount -= min($amount, $row['net']);

        // Exit the loop if the amount becomes 0
        if ($amount <= 0) {
            break;
        }
    }

    // Insert data into material_used_detail with the calculated cost
    $stmtInsertMaterialUsedDetail->bind_param('ssdd', $mtu_id, $mt, $amounts[$i], $cost);
    $resultInsertMaterialUsedDetail = $stmtInsertMaterialUsedDetail->execute();

    // Check for errors
    if (!$resultInsertMaterialUsedDetail) {
        ob_end_clean();
        echo json_encode(array("result" => 0, "message" => "Error inserting into material_used_detail: " . $stmtInsertMaterialUsedDetail->error));
        $conn->rollback(); // Rollback changes
        die();
    }

    // Update the amount in the mt table
    $updateMtQuery = "UPDATE material SET amount = amount - ? WHERE material_id = ?";
    $stmtUpdateMt = $conn->prepare($updateMtQuery);
    $stmtUpdateMt->bind_param('ds', $amounts[$i], $mt);
    $stmtUpdateMt->execute();

    // Check for errors
    if ($stmtUpdateMt->error) {
        ob_end_clean();
        echo json_encode(array("result" => 0, "message" => "Error updating amount in material table: " . $stmtUpdateMt->error));
        $conn->rollback(); // Rollback changes
        die();
    }

    $stmtUpdateMt->close();
}

// Commit transaction
$conn->commit();

$stmtInsertMaterialUsedDetail->close();

$conn->close();

// Output
ob_end_clean();
echo json_encode(array("result" => 1, "message" => "บันทึกเรียบร้อย"));
?>
