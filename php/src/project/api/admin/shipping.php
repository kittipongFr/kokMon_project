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
    if (!isset($data['id']) || !isset($data['shipping_co']) || !isset($data['tracking'])) {
        ob_end_clean();
        http_response_code(412); // Precondition Failed
        echo json_encode(array("result" => 0, "message" => "Invalid input data"));
        die();
    }

   
    $id = $data['id'];
    $shipping_co = $data['shipping_co'];
    $tracking = $data['tracking'];
    $pro_id_list = $data['pro_id_list'];
    $amount_list = $data['amount_list'];

 
} else {
    ob_end_clean();
    http_response_code(412); // Precondition Failed
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}


for ($i = 0; $i < count($pro_id_list); $i++) {
    $strUpdatePro = "UPDATE product SET amount = amount-'$amount_list[$i]' , amount_reserve = amount_reserve+'$amount_list[$i]'  WHERE pro_id = '$pro_id_list[$i]'";
    $queryPro = mysqli_query($conn, $strUpdatePro);
    if (!$queryPro) {
        echo json_encode(array("result" => 0, "message" => "แจ้งการจัดส่งสินค้าไม่สำเร็จ", "error" => mysqli_error($conn)));
        die();
        }
}



$strUpdate = "UPDATE orders SET status = '4' WHERE order_id = '$id'";
$query = mysqli_query($conn, $strUpdate);

if (!$query) {
echo json_encode(array("result" => 0, "message" => "แจ้งการจัดส่งสินค้าไม่สำเร็จ", "error" => mysqli_error($conn)));
die();
}

    $sql = "INSERT INTO shipping
    (order_id, tracking	, shipping_co)
    VALUES ('$id', '$tracking', '$shipping_co')";

    // ทำการ query
    $result = mysqli_query($conn, $sql);



// ตรวจสอบว่า query สำเร็จหรือไม่
if ($result) {
echo json_encode(array("result" => 1, "message" => "แจ้งการจัดส่งสินค้าสำเร็จ"));
} else {
echo json_encode(array("result" => 0, "message" => "แจ้งการจัดส่งสินค้าไม่สำเร็จ","error" => mysqli_error($conn)));
}


?>
