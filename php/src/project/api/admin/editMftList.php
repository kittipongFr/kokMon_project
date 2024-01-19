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
    $mft_id = isset($data['mft_id']) ? $data['mft_id'] : '';
    $pro_id = isset($data['pro_id']) ? $data['pro_id'] : '';
    $amount = isset($data['amount']) ? $data['amount'] : '';
    $old_amount = isset($data['old_amount']) ? $data['old_amount'] : '';




    if ($amount != $old_amount ) {
        $sql = "UPDATE manufacture_detail SET
        amount = '$amount'
        WHERE  manufacture_id = '$mft_id' AND pro_id = '$pro_id' ";

        $result = mysqli_query($conn, $sql);

        $sql = "UPDATE product SET
        amount = (amount-'$old_amount')+'$amount'
        WHERE  pro_id = '$pro_id' ";

        $result = mysqli_query($conn, $sql);

    }else{
        $sql = "UPDATE manufacture_detail SET
        amount = '$amount'
        WHERE  manufacture_id = '$mft_id' AND pro_id = '$pro_id' ";

        $result = mysqli_query($conn, $sql);
    }


// Insert data into the receive_material table

$conn->close();
// Check for errors
if ($result) {
    echo json_encode(array("result" => 1, "message" => "แก้ไขเรียบร้อย"));
} else {
    echo json_encode(array("result" => 0, "message" => "แก้ไขไม่สำเร็จ "));
}
// Output
}else{
    echo json_encode(array("result" => 0, "message" => "ไม่มี Method Post"));
}
?>
