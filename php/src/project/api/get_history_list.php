<?php
ob_start();

#header
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require("../config/config_db.php");

#input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = file_get_contents('php://input');
    $json_data = json_decode($content, true);
    $cus_id = trim($json_data["cus_id"]);
} else {
    ob_end_clean();
    header("HTTP/1.0 412 Precondition Failed");
    die();
}

#process
$strSQL = "SELECT orders.date as date, orders.order_id, orders.status, 
            COUNT(orders_detail.pro_id) as countPro, 
            SUM((orders_detail.price * orders_detail.amount) + orders.shipping_cost) as total
            FROM orders
            JOIN orders_detail ON orders.order_id = orders_detail.order_id
            WHERE orders.cus_id = '$cus_id'
            GROUP BY orders.date, orders.order_id
            ORDER BY orders.date DESC";

$query = mysqli_query($conn, $strSQL);
$datalist = array();

while ($resultQuery = mysqli_fetch_array($query)) {
    $date = $resultQuery['date'];
    $order_id = $resultQuery['order_id'];
    $status = $resultQuery['status'];
    $total = $resultQuery['total'];
    $count = $resultQuery['countPro'];

    $datalist[] = array(
        "date" => $date,
        "order_id" => $order_id,
        "status" => $status,
        "count" => $count,
        "total" => $total
    );
}

#output
ob_end_clean();
mysqli_close($conn);
if ($datalist) {
    echo $json_response = json_encode(array("result" => 1, "message" => "พบข้อมูล", "datalist" => $datalist));
} else {
    echo $json_response = json_encode(array("result" => 0, "message" => "ไม่พบข้อมูล", "datalist" => null));
}
exit;
?>
<?php
#log function
?>
