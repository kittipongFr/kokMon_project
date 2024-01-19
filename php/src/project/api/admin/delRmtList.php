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
    $rmt_id = isset($data['id']) ? $data['id'] : '';
    $mt_id = isset($data['mt_id']) ? $data['mt_id'] : '';
    $sum_cost = 0;

    // Input validation
    if (empty($rmt_id) || empty($mt_id)) {
        http_response_code(412); // Precondition Failed
        echo json_encode(["result" => 0, "message" => "Invalid input data"]);
        exit();
    }

    $strSQLDetail = "SELECT material_id, amount, net, price
    FROM receive_material_detail 
    WHERE receive_material_id = '$rmt_id' AND material_id = '$mt_id'";
$stmtDetail = mysqli_query($conn, $strSQLDetail);
$detailData = mysqli_fetch_assoc($stmtDetail);
$sum_cost = $detailData["amount"]*$detailData["price"];
if ($detailData["amount"]!=$detailData["net"]) {
echo json_encode(["result" => 0, "message" => "ไม่สามารถลบรายการนี้ได้"]);
die();
}



    // Update material amount
    $strSQLDetail = "SELECT material_id, amount, net, price
    FROM receive_material_detail 
    WHERE receive_material_id = ? AND material_id = ?";

$stmtDetail = mysqli_prepare($conn, $strSQLDetail);

if (!$stmtDetail) {
http_response_code(500); // Internal Server Error
echo json_encode(["result" => 0, "message" => "Error preparing statement: " . mysqli_error($conn)]);
exit();
}

mysqli_stmt_bind_param($stmtDetail, "ss", $rmt_id, $mt_id);
mysqli_stmt_execute($stmtDetail);

if (mysqli_stmt_error($stmtDetail)) {
http_response_code(500); // Internal Server Error
echo json_encode(["result" => 0, "message" => "Error executing statement: " . mysqli_stmt_error($stmtDetail)]);
exit();
}

mysqli_stmt_store_result($stmtDetail);

if (mysqli_stmt_num_rows($stmtDetail) == 0) {
http_response_code(404); // Not Found
echo json_encode(["result" => 0, "message" => "Record not found"]);
exit();
}

mysqli_stmt_bind_result($stmtDetail, $material_id, $amount, $net, $price);
mysqli_stmt_fetch($stmtDetail);

// The variable $amount now contains the value you need

mysqli_stmt_close($stmtDetail);

// echo json_encode(["result" => $amount, "message" => "Material details retrieved successfully"]);




    $updateMaterialQuery = "
        UPDATE material
        SET amount = IF((amount - (
            SELECT SUM(net)
            FROM receive_material_detail
            WHERE receive_material_detail.material_id = material.material_id
            AND receive_material_detail.receive_material_id = ?
        )) = 0, 0.00, (amount - (
            SELECT SUM(net)
            FROM receive_material_detail
            WHERE receive_material_detail.material_id = material.material_id
            AND receive_material_detail.receive_material_id = ?
        )))
        WHERE material_id = ?;
    ";
    
    // เพิ่ม $mt_id ใน bind_param สองครั้ง
    $stmtUpdateMaterial = $conn->prepare($updateMaterialQuery);
    $stmtUpdateMaterial->bind_param('sss', $rmt_id, $rmt_id, $mt_id);
    $stmtUpdateMaterial->execute();


    
    // Check for errors
    if ($stmtUpdateMaterial->error) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error updating material amount: " . $stmtUpdateMaterial->error]);
        exit();
    }

    $stmtUpdateMaterial->close();

    // Delete data from receive_material_detail based on receive_material_id and material_id
    $deleteDetailQuery = "DELETE FROM receive_material_detail WHERE receive_material_id = ? AND material_id = ?";
    $stmtDeleteDetail = $conn->prepare($deleteDetailQuery);
    $stmtDeleteDetail->bind_param('ss', $rmt_id, $mt_id);
    $stmtDeleteDetail->execute();

    // Check for errors
    if ($stmtDeleteDetail->error) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["result" => 0, "message" => "Error deleting from receive_material_detail: " . $stmtDeleteDetail->error]);
        exit();
    }


    $updateCommuQuery = "UPDATE community_enterprise SET aml_fund = aml_fund + ? WHERE id = ?";
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
