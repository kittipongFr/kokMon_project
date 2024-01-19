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
    $supply_name = isset($data['supply_name']) ? $data['supply_name'] : '';


        $sql = "UPDATE receive_material SET
        supply_name = '$supply_name'
        WHERE  receive_material_id = '$rmt_id' ";

        $result = mysqli_query($conn, $sql);
    


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
    echo json_encode(array("result" => 0, "message" => "ไม่มีMethod Post"));
}
?>
