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
    $rmt_id = isset($data['rmt_id']) ? $data['rmt_id'] : '';
    $mt_id = isset($data['mt_id']) ? $data['mt_id'] : '';
    $amount = isset($data['amount']) ? $data['amount'] : '';
    $old_amount = isset($data['old_amount']) ? $data['old_amount'] : '';
    $net = isset($data['net']) ? $data['net'] : '';
    $old_net = isset($data['old_net']) ? $data['old_net'] : '';
    $old_price = isset($data['old_price']) ? $data['old_price'] : '';
    $price = isset($data['price']) ? $data['price'] : '';
    $sum_cost = $amount*$price;
    $sum_cost_old = floatval($old_amount) * floatval($old_price);

    if ($amount < $net) {
        echo json_encode(array("result" => 0, "message" => "ห้าม!ค่าคงเหลือวัตถุดิบ มากกว่า จำนวนรับวัตถุดิบ"));
        die();
    }


    if ($amount != $old_amount && $net != $old_net) {
        $sql = "UPDATE receive_material_detail SET
        amount = '$amount',
        net = '$net',
        price = '$price'
        WHERE  receive_material_id = '$rmt_id' AND material_id = '$mt_id' ";

        $result = mysqli_query($conn, $sql);

        $sql = "UPDATE material SET
        amount = (amount-'$old_net')+'$net'
        WHERE  material_id = '$mt_id' ";

        $result = mysqli_query($conn, $sql);

    } elseif ($amount == $old_amount && $net != $old_net) {
        $sql = "UPDATE receive_material_detail SET
        amount = '$amount',
        net = '$net',
        price = '$price'
        WHERE  receive_material_id = '$rmt_id' AND material_id = '$mt_id' ";

        $result = mysqli_query($conn, $sql);

        $sql = "UPDATE material SET
        amount = (amount-'$old_net')+'$net'
        WHERE  material_id = '$mt_id' ";

        $result = mysqli_query($conn, $sql);

    } elseif ($amount != $old_amount && $net == $old_net) {
        $used = $old_amount - $net;
        $newNet = $amount - $used;

        $sql = "UPDATE receive_material_detail SET
        amount = '$amount',
        net = '$newNet',
        price = '$price'
        WHERE  receive_material_id = '$rmt_id' AND material_id = '$mt_id' ";

        $result = mysqli_query($conn, $sql);

        $sql = "UPDATE material SET
        amount = (amount-'$net')+'$newNet'
        WHERE  material_id = '$mt_id' ";

        $result = mysqli_query($conn, $sql);
    }else{
        $sql = "UPDATE receive_material_detail SET
        amount = '$amount',
        net = '$net',
        price = '$price'
        WHERE  receive_material_id = '$rmt_id' AND material_id = '$mt_id' ";

        $result = mysqli_query($conn, $sql);
    }
}


$updateCommuQuery = "UPDATE community_enterprise SET aml_fund = (aml_fund + ?)-? WHERE id = ?";
$stmtUpdateCommu = $conn->prepare($updateCommuQuery);
$updatedAmlFund = $sum_cost;
$updatedAmlFundOld = $sum_cost_old;
$communityId = 1;
$stmtUpdateCommu->bind_param('ddi', $updatedAmlFundOld ,$updatedAmlFund, $communityId);
$stmtUpdateCommu->execute();
if ($stmtUpdateCommu->error) {
    echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด : " . $stmtUpdateCommu->error));
    die();
}



$stmtUpdateCommu->close();

// Insert data into the receive_material table
$conn->close();

// Check for errors
if ($result) {
    echo json_encode(array("result" => 1, "message" => "แก้ไขเรียบร้อย"));
} else {
    echo json_encode(array("result" => 0, "message" => "แก้ไขไม่สำเร็จ "));
}
// Output
?>
