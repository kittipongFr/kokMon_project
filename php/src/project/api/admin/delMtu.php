<?php
ob_start();

# Header
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

# Connection and data include or require
require("../../config/config_db.php");





# Input validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $mtu_id = isset($data['id']) ? $data['id'] : '';

    // Input validation
    if (empty($mtu_id)) {
        http_response_code(412); // Precondition Failed
        echo json_encode(["result" => 0, "message" => "Invalid input data"]);
        exit();
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Select material_ids from receive_material_detail for a specific receive_material_id
        $selectMaterialIdsQuery = "SELECT DISTINCT material_id FROM material_used_detail WHERE material_used_id = ?";
        $stmtSelectMaterialIds = $conn->prepare($selectMaterialIdsQuery);
        $stmtSelectMaterialIds->bind_param('s', $mtu_id);
        $stmtSelectMaterialIds->execute();
        $resultMaterial = $stmtSelectMaterialIds->get_result();

        echo json_encode(["result" => $resultMaterial]);
                // Check for errors
        if ($stmtSelectMaterialIds->error) {
            throw new Exception("Error updating stmtSelectMaterialIds: " . $stmtSelectMaterialIds->error);
        }
        $materialIds = array();

        // Fetch material_ids and store them in an array
        while ($row = $resultMaterial->fetch_assoc()) {
            $materialIds[] = $row["material_id"];
            
        }
        echo json_encode(["result" => $materialIds]);

        $stmtSelectMaterialIds->close();

        // Update the material table for each material_id
        $updateMaterialQuery = "UPDATE material SET amount = amount + (
            SELECT SUM(amount) FROM material_used_detail WHERE material_used_detail.material_id = material.material_id AND material_used_detail.material_used_id = ?
        ) WHERE material.material_id = ?";
        
        $stmtUpdateMaterial = $conn->prepare($updateMaterialQuery);
        
        // Bind parameters outside the loop
        $stmtUpdateMaterial->bind_param('ss', $mtu_id, $materialId);
        
        foreach ($materialIds as $materialId) {
            // Update parameters inside the loop
            $stmtUpdateMaterial->execute();
        }
        
        // Check for errors
        if ($stmtUpdateMaterial->error) {
            throw new Exception("Error updating material amount: " . $stmtUpdateMaterial->error);
        }
        
        $stmtUpdateMaterial->close();

   // Fetch amount from material_used_detail
   $stmtFetchAmount = "SELECT material_id, amount FROM material_used_detail WHERE material_used_detail.material_used_id = ?";
   $stmtAmount = $conn->prepare($stmtFetchAmount);
   $stmtAmount->bind_param('s', $mtu_id);
   $stmtAmount->execute();
   $resultFetch = $stmtAmount->get_result();
   $amount = 0.00;
   $mt_id = "0";
   while ($rowA = $resultFetch->fetch_assoc()) {
       $amount = $rowA['amount'];
       $mt_id = $rowA['material_id'];
       
       echo json_encode(["result" => $mt_id, "message" => $amount]);

       // Fetch eligible records from receive_material_detail
       $fetchQuery = "SELECT rmd.receive_material_id, rmd.price, rmd.net, rm.date, rmd.amount
           FROM receive_material_detail AS rmd
           JOIN receive_material AS rm ON rmd.receive_material_id = rm.receive_material_id
           WHERE rmd.material_id = ? AND rmd.net != rmd.amount
           ORDER BY rm.date DESC";

       $stmtFetchInner  = $conn->prepare($fetchQuery);
       $stmtFetchInner ->bind_param('s', $mt_id);
       $stmtFetchInner ->execute();
       $resultInnerFetch = $stmtFetchInner ->get_result();

       while ($row = $resultInnerFetch->fetch_assoc()) {
           // Calculate cost based on price and amount
           $update_amount = $amount;
           $new_net = $row['net'] + $update_amount;

           if ($new_net >= $row['amount']) {
               $update_amount = $row['amount'] - $row['net'];
               $new_net = $row['amount'];
           }

           // Update receive_material_detail
           $updateQuery = "UPDATE receive_material_detail SET net = ? WHERE receive_material_id = ?";
           $stmtUpdate = $conn->prepare($updateQuery);
           $stmtUpdate->bind_param('ds', $new_net, $row['receive_material_id']);
           $stmtUpdate->execute();

           // Update the remaining amount to reduce
           $amount -= $update_amount;

           // Exit the loop if the amount becomes 0
           if ($amount <= 0) {
               break;
           }
       }
       
    }

        // Delete data from material_used_detail based on material_used_id
        $deleteDetailQuery = "DELETE FROM material_used_detail WHERE material_used_id = ?";
        $stmtDeleteDetail = $conn->prepare($deleteDetailQuery);
        $stmtDeleteDetail->bind_param('s', $mtu_id);
        $stmtDeleteDetail->execute();

        // Check for errors
        if ($stmtDeleteDetail->error) {
            throw new Exception("Error deleting from material_used_detail: " . $stmtDeleteDetail->error);
        }

        // Delete data from material_used based on material_used_id
        $deleteMtuQuery = "DELETE FROM material_used WHERE material_used_id = ?";
        $stmtDeleteMtu = $conn->prepare($deleteMtuQuery);
        $stmtDeleteMtu->bind_param('s', $mtu_id);
        $stmtDeleteMtu->execute();

        // Check for errors
        if ($stmtDeleteMtu->error) {
            throw new Exception("Error deleting from material_used: " . $stmtDeleteMtu->error);
        }

        $conn->commit(); // Commit the transaction

        ob_end_clean();
        echo json_encode(["result" => 1, "message" => "Success"]);
    } catch (Exception $e) {
        ob_end_clean();
        http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => $e->getMessage()]);
        $conn->rollback(); // Rollback changes
    } finally {
        // Close statements
        $stmtAmount->close();
        // $stmtFetch->close();
        $stmtDeleteDetail->close();
        $stmtDeleteMtu->close();
        $conn->close();
    }
} else {
    ob_end_clean();
    http_response_code(412); // Precondition Failed
    echo json_encode(["result" => 0, "message" => "Invalid input data"]);
}
?>
