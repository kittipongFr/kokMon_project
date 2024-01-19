<?php

# header
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
ob_start();
# connection and data include OR require
require("../../config/config_db.php");

# input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = @file_get_contents('php://input');
    $json_data = @json_decode($content, true);
    $order_id = isset($json_data["order_id"]) ? trim($json_data["order_id"]) : die(json_encode(array("result" => 0, "message" => "order_id parameter is missing")));
} else {
    ob_end_clean();
    @header("HTTP/1.0 405 Method Not Allowed");
    die(json_encode(array("result" => 0, "message" => "Method Not Allowed")));
}

# query orders
$strSQLOrder = "SELECT orders.*, customer.fname, customer.lname, address.address, address.tel, address.name
                FROM orders
                JOIN customer ON orders.cus_id = customer.cus_id
                JOIN address ON orders.address_id = address.address_id
                WHERE orders.order_id = '$order_id' ";
$queryOrder = mysqli_query($conn, $strSQLOrder);
if (!$queryOrder) {
    die(json_encode(array("result" => 0, "message" => "Query error: " . mysqli_error($conn))));
}

$orderData = [];

while ($resultQueryOrder = mysqli_fetch_assoc($queryOrder)) {
    # query orders_detail
    $strSQLOrderDetail = "SELECT orders_detail.*, product.name ,product.img ,product.unit 
    FROM orders_detail 
    INNER JOIN product ON orders_detail.pro_id = product.pro_id 
    WHERE orders_detail.order_id = '$order_id'";
$queryOrderDetail = mysqli_query($conn, $strSQLOrderDetail);


    if (!$queryOrderDetail) {
        die(json_encode(array("result" => 0, "message" => "Query error: " . mysqli_error($conn))));
    }

    $orderDetailData = [];

    while ($resultQueryOrderDetail = mysqli_fetch_assoc($queryOrderDetail)) {
        $orderDetailData[] = $resultQueryOrderDetail;
    }

    $resultQueryOrder["order_detail"] = $orderDetailData;
    $orderData[] = $resultQueryOrder;
}

$cancel_details = [];
$reject_details=[];
$strCancelDetail = "SELECT * FROM cus_cancel WHERE order_id = '$order_id'";
$queryCancelDetail = mysqli_query($conn, $strCancelDetail);

$strRejectDetail = "SELECT * FROM mem_reject WHERE order_id = '$order_id'";
$queryRejectDetail = mysqli_query($conn, $strRejectDetail);


    // Check if there are rows returned from the query
    if (mysqli_num_rows($queryCancelDetail) > 0 && mysqli_num_rows($queryRejectDetail) > 0) {
        while ($row = mysqli_fetch_assoc($queryCancelDetail)) {
            $cancel_details[] = [
                "detail" => $row["detail"],
                "date" => $row["date"]
            ];
        }
        while ($row = mysqli_fetch_assoc($queryRejectDetail)) {
            $reject_details[] = [
                "detail" => $row["detail"],
                "date" => $row["date"]
            ];
        }
        # output
    echo json_encode(array("result" => 1, "data" => $orderData,"cancel_detail"=>$cancel_details,"reject_detail"=>$reject_details));
    }else if(mysqli_num_rows($queryCancelDetail) > 0){
        while ($row = mysqli_fetch_assoc($queryCancelDetail)) {
            $cancel_details[] = [
                "detail" => $row["detail"],
                "date" => $row["date"]
            ];
        }
        echo json_encode(array("result" => 1, "data" => $orderData,"cancel_detail"=>$cancel_details));


    }else if(mysqli_num_rows($queryRejectDetail) > 0){
        while ($row = mysqli_fetch_assoc($queryRejectDetail)) {
            $reject_details[] = [
                "detail" => $row["detail"],
                "date" => $row["date"]
            ];
        }
        echo json_encode(array("result" => 1, "data" => $orderData,"reject_detail"=>$reject_details));
    }else{
        echo json_encode(array("result" => 1, "data" => $orderData));
    }


    
# close connection
@mysqli_close($conn);

?>
