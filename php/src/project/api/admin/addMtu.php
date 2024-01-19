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
    if (!isset($data['mem_id']) || !isset($data['mt_id']) || !isset($data['amount'])) {
        ob_end_clean();
        http_response_code(412); // Precondition Failed
        echo json_encode(array("result" => 0, "message" => "Invalid input data"));
        die();
    }

   
    $mem_id = $data['mem_id'];
    $material = $data['mt_id'];
    $amounts = $data['amount'];
    $mtu_id = "";
 
} else {
    ob_end_clean();
    http_response_code(412); // Precondition Failed
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}

// Generate a unique mtu_id
$selectQuery = "SELECT material_used_id FROM material_used ORDER BY material_used_id DESC LIMIT 1";
$result = $conn->query($selectQuery);

if ($result->num_rows > 0) {
    // Fetch the manufacture_id
    $row = $result->fetch_assoc();
    $material_used_id = $row['material_used_id'];

    // Extract the date part
    $dateSplit = substr($material_used_id, 2, 6);

    // Extract the numeric part
    $numericPart = (int)substr($material_used_id, 8);

    // Format the current date with only the last two digits of the year
    $currentDate = date("ymd");

    if ($dateSplit == $currentDate) {
        $new_id = $numericPart + 1;
        $formatted_id = "mu" . $currentDate . sprintf("%05d", $new_id);
    } else {
        $formatted_id = "mu" . $currentDate . "00001";
    }

    
} else {
    $currentDate = date("ymd");
    $formatted_id = "mu" . $currentDate . "00001";
    
}

$mtu_id = $formatted_id;
 //check duplicate
$materialsSeen = array();
for ($i = 0; $i < count($material); $i++) {
    $materialOne = $material[$i];
  
    if (isset($materialsSeen[$materialOne])) {
        echo json_encode(array("result" => 0, "message" => "มีรายการวัตถุดิบซ้ำ กรุณาแก้ไขข้อมูล: " . $materialOne));
        die();
    } else {
        $materialsSeen[$materialOne] = true;
    }

}


 //insert material_used
$stmtInsertMaterialUsed = $conn->prepare('INSERT INTO material_used (material_used_id, mem_id) VALUES (?, ?)');
$stmtInsertMaterialUsed->bind_param('ss', $mtu_id, $mem_id);
$resultInsertMaterialUsed = $stmtInsertMaterialUsed->execute();

// Check for errors
if (!$resultInsertMaterialUsed) {
    ob_end_clean();
    echo json_encode(array("result" => 0, "message" => "Error inserting into material_used: " . $stmtInsertMaterialUsed->error));
    die();
}

// Insert data into the material_used_detail table using a loop
$stmtInsertMaterialUsedDetail = $conn->prepare('INSERT INTO material_used_detail (material_used_id, material_id, amount, cost) VALUES (?, ?, ?, ?)');
$stmtInsertMaterialUsedDetail->bind_param('ssdd', $mtu_id, $mt, $amount, $cost);

$conn->begin_transaction(); // Start transaction

for ($i = 0; $i < count($material); $i++) {
    $mt = $material[$i];
    $amount = $amounts[$i];
    $cost = 0.00;
    $check_amount = $amounts[$i];

    // Fetch eligible records from receive_material_detail ข้อมูลการรับวัตถุดิบแบบfifo
    $fetchQuery = "SELECT rmd.receive_material_id,rmd.material_id, rmd.price, rmd.net, rm.date ,rmd.amount, 
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


        if ($check_amount > $row["sumNet"]) {
                ob_end_clean();
                echo json_encode(array("result" => 3,"id"=>$mtu_id, "message" => "จำนวนวัตถุดิบไม่เพียงพอ  วัตถุดิบคงเหลือ : ".$row["sumNet"]));
                die();
            
        }
        

        // Update receive_material_detail จำนวนวัตถุดิบในreceive_material_detail แบบfifo
        $updateQuery = "UPDATE receive_material_detail SET net = net - ? WHERE receive_material_id = ?  AND material_id = ?";
        $stmtUpdate = $conn->prepare($updateQuery);
        $stmtUpdate->bind_param('dss', min($amount, $row['net']), $row['receive_material_id'] , $row['material_id'] );
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

    // Update the amount in the mt tableจำนวนวัตถุดิบ
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
$stmtInsertMaterialUsed->close();
$conn->close();

// Output
ob_end_clean();
echo json_encode(array("result" => 1, "message" => "บันทึกเรียบร้อย"));
?>
