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
    $mtu_id = isset($data['id']) ? $data['id'] : '';
    $mt_id = isset($data['mt_id']) ? $data['mt_id'] : '';
    

    // Input validation
    if (empty($mtu_id) || empty($mt_id)) {
        http_response_code(412); // Precondition Failed
        echo json_encode(["result" => 0, "message" => "Invalid input data"]);
        exit();
    }

//edit 
try {
    $updateMaterialQuery = "
    UPDATE material
    SET amount = amount + (
        SELECT amount
        FROM material_used_detail
        WHERE material_used_detail.material_id = material.material_id
        AND material_used_detail.material_used_id = ?
    )
    WHERE material_id = ?;
";

    // เพิ่ม $mt_id ใน bind_param สองครั้ง
    $stmtUpdateMaterial = $conn->prepare($updateMaterialQuery);
    $stmtUpdateMaterial->bind_param('ss', $mtu_id,  $mt_id);
    $stmtUpdateMaterial->execute();

    // Check for errors
    if ($stmtUpdateMaterial->error) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error updating material amount: " . $stmtUpdateMaterial->error]);
        exit();
    }
    $stmtUpdateMaterial->close();




    $stmtFetchAmount = "SELECT amount
    FROM material_used_detail
    WHERE material_used_detail.material_id = '$mt_id'
    AND material_used_detail.material_used_id = '$mtu_id' ";
    $stmtAmount = mysqli_query($conn, $stmtFetchAmount);
    $detailData = mysqli_fetch_assoc($stmtAmount);

    $amount = $detailData["amount"];


    // Fetch eligible records from receive_material_detail
    $fetchQuery = "SELECT rmd.receive_material_id, rmd.price, rmd.net, rm.date ,rmd.amount
        FROM receive_material_detail AS rmd
        JOIN receive_material AS rm ON rmd.receive_material_id = rm.receive_material_id
        WHERE rmd.material_id =  ? AND  rmd.net != rmd.amount
        ORDER BY rm.date DESC";

    $stmtFetch = $conn->prepare($fetchQuery);
    $stmtFetch->bind_param('s', $mt_id);
    $stmtFetch->execute();
    $resultFetch = $stmtFetch->get_result();

    while ($row = $resultFetch->fetch_assoc()) {
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
        $stmtUpdate->bind_param('ds',  $new_net, $row['receive_material_id']);
        $stmtUpdate->execute();

        // Update the remaining amount to reduce
        $amount -= $update_amount;

        // Exit the loop if the amount becomes 0
        if ($amount <= 0) {
            break;
        }
    }

        // Delete data from material_used_detail based on material_used_id and material_id
        $deleteDetailQuery = "DELETE FROM material_used_detail WHERE material_used_id = ? AND material_id = ?";
        $stmtDeleteDetail = $conn->prepare($deleteDetailQuery);
        $stmtDeleteDetail->bind_param('ss', $mtu_id, $mt_id);
        $stmtDeleteDetail->execute();
    
        // Check for errors
        if ($stmtDeleteDetail->error) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["result" => 0, "message" => "Error deleting from material_used_detail: " . $stmtDeleteDetail->error]);
            exit();
        }
    
       


    $conn->commit(); // Commit the transaction

    ob_end_clean();
    echo json_encode(array("result" => 1, "message" => "Success"));
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500); // Internal Server Error
    echo json_encode(array("result" => 0, "message" => $e->getMessage()));
    $conn->rollback(); // Rollback changes
    die();
} finally {
    // Close statements

    $conn->close();
}

} else {
    ob_end_clean();
    http_response_code(412); // Precondition Failed
    echo json_encode(["result" => 0, "message" => "Invalid input data"]);
    exit();
}
?>
