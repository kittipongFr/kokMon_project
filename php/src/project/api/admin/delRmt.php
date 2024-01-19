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
    $rmt_id = isset($data['id']) ? $data['id'] : '';

} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}

// Input validation
if (empty($rmt_id)) {
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}


$strSQLDetail = "SELECT sum(amount* price) as sum_cost
                 FROM receive_material_detail 
                 WHERE receive_material_id = '$rmt_id' ";
$stmtDetail = mysqli_query($conn, $strSQLDetail);
$detailData = mysqli_fetch_assoc($stmtDetail);
    $sum_cost = $detailData["sum_cost"];



// Select material_ids from receive_material_detail for a specific receive_material_id
$selectMaterialIdsQuery = "
    SELECT DISTINCT material_id
    FROM receive_material_detail
    WHERE receive_material_id = ?
";

$stmtSelectMaterialIds = $conn->prepare($selectMaterialIdsQuery);
$stmtSelectMaterialIds->bind_param('s', $rmt_id);
$stmtSelectMaterialIds->execute();
$stmtSelectMaterialIds->bind_result($materialId);

$materialIds = array();

// Fetch material_ids and store them in an array
while ($stmtSelectMaterialIds->fetch()) {
    $materialIds[] = $materialId;
}

$stmtSelectMaterialIds->close();

// Update the material table for each material_id
$updateMaterialQuery = "
    UPDATE material
    SET amount = amount - (
        SELECT SUM(net)
        FROM receive_material_detail
        WHERE receive_material_detail.material_id = material.material_id
        AND receive_material_detail.receive_material_id = ?
    )
    WHERE material.material_id = ? 
";

$stmtUpdateMaterial = $conn->prepare($updateMaterialQuery);

foreach ($materialIds as $materialId) {
    $stmtUpdateMaterial->bind_param('ss', $rmt_id, $materialId);
    $stmtUpdateMaterial->execute();
}

$stmtUpdateMaterial->close();


// Delete data from receive_material_detail
$deleteDetailQuery = "DELETE FROM receive_material_detail WHERE receive_material_id = ?";
$stmtDeleteDetail = $conn->prepare($deleteDetailQuery);
$stmtDeleteDetail->bind_param('s', $rmt_id);
$stmtDeleteDetail->execute();
$stmtDeleteDetail->close();



// Delete data from receive_material
$deleteRmtQuery = "DELETE FROM receive_material WHERE receive_material_id = ?";
$stmtDeleteRmt = $conn->prepare($deleteRmtQuery);
$stmtDeleteRmt->bind_param('s', $rmt_id);
$stmtDeleteRmt->execute();
$stmtDeleteRmt->close();



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

$conn->close();

// Output
echo json_encode(array("result" => 1, "message" => "ลบข้อมูลเรียบร้อย"));
?>
