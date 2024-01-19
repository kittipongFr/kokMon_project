<?php
ob_start();

#header
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require("../config/config_db.php");

#input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = @file_get_contents('php://input');
    $json_data = @json_decode($content, true);
    $id = trim($json_data["pro_id"]);
} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    die();
}

#process
$strSQL = "SELECT product.pro_id, product.name, product.detail, product.unit, product.amount,product.amount_reserve, product.img, price.price,price.price_id,
 price.amount_conditions 
FROM product 
JOIN price ON product.pro_id = price.pro_id
WHERE product.pro_id = '$id' ORDER BY price.amount_conditions";

$query = mysqli_query($conn, $strSQL);
$datalist = array();

while ($resultQuery = mysqli_fetch_array($query)) {
    $id = $resultQuery['pro_id'];
    $name = $resultQuery['name'];
    $detail = $resultQuery['detail'];
    $amount = $resultQuery['amount'];
    $reserve = $resultQuery['amount_reserve'];
    $unit = $resultQuery['unit'];
    $img = $resultQuery['img'];

    if (!isset($datalist["id"])) {
        $datalist["id"] = $id;
        $datalist["name"] = $name;
        $datalist["amount"] = $amount;
        $datalist["reserve"] = $reserve;
        $datalist["unit"] = $unit;
        $datalist["img"] = $img;
        $datalist["detail"] = $detail;
        $datalist["prices"] = array();
        $datalist["amount_conditions"] = array();
    }

    $datalist["prices"][] = $resultQuery['price']; 
    $datalist["amount_conditions"][] = $resultQuery['amount_conditions']; 
    $datalist["price_id"][] = $resultQuery['price_id']; 
    $datalist["priceCon"][] = array(
        $resultQuery['amount_conditions']=> $resultQuery['price']
    );
}

#output
ob_end_clean();
@mysqli_close($conn);
if ($query) {
    echo $json_response = json_encode(array("result" => 1, "message" => "พบข้อมูล", "datalist" => $datalist));
} else {
    echo $json_response = json_encode(array("result" => 0, "message" => "ไม่พบข้อมูล", "datalist" => null));
}
exit;
?>
