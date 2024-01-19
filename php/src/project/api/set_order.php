<?php
ob_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>

<?php
# connection and data include OR require
require("../config/config_db.php");
?>

<?php
# input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = file_get_contents('php://input');
    $json_data = json_decode($content, true);
    $cus_id = trim($json_data["cus_id"]);
    $pay_type = trim($json_data["pay_type"]);
    $shipping_type = trim($json_data["shipping_type"]);
    $shipping_cost = trim($json_data["shipping_cost"]);
    $address_id = trim($json_data["address_id"]);
    $pro_idList = $json_data["pro_id"];
    $price_idList = $json_data["price_id"];
    $amountList = $json_data["amount"];
    $status = 0;
    $order_id = "";
} else {
    ob_end_clean();
    header("HTTP/1.0 412 Precondition Failed");
    die(json_encode(array("result" => 0, "message" => "Invalid request")));
}
?>

<?php

# Fetch prices
$priceList = [];

foreach ($price_idList as $id) {
    $strSQLPrice = "SELECT * FROM price WHERE price_id = '$id' "; 

    $queryPrice = mysqli_query($conn, $strSQLPrice);

    if ($queryPrice) {
        while ($resultQueryPrice = mysqli_fetch_assoc($queryPrice)) {
            $priceList[] = $resultQueryPrice["price"];
        }
    } else {
        // Log the error and return an appropriate response
        die(json_encode(array("result" => 0, "message" => "เกิดข้อผิดผลาด")));
    }
}
?>

<?php
# Generate a unique order_id
$selectQuery = "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1";
$result = $conn->query($selectQuery);
if ($result->num_rows > 0) {
    // Fetch the manufacture_id
    $row = $result->fetch_assoc();
    $order_id = $row['order_id'];

    // Extract the date part
    $dateSplit = substr($order_id, 2, 6);

    // Extract the numeric part
    $numericPart = (int)substr($order_id, 8);

    // Format the current date with only the last two digits of the year
    $currentDate = date("ymd");

    if ($dateSplit == $currentDate) {
        $new_id = $numericPart + 1;
        $formatted_id = "od" . $currentDate . sprintf("%05d", $new_id);
    } else {
        $formatted_id = "od" . $currentDate . "00001";
    }
} else {
    $currentDate = date("ymd");
    $formatted_id = "od" . $currentDate . "00001";
    
}

$order_id = $formatted_id;
?>

<?php
# Insert into order table
$strSQLOrderInsert = "INSERT INTO `orders` (order_id, cus_id, status, pay_type, shipping_type,shipping_cost,address_id) VALUES (?, ?, ?, ?, ?,?,?)";
$stmtOrderInsert = mysqli_prepare($conn, $strSQLOrderInsert);

// Bind parameters to the prepared statement
mysqli_stmt_bind_param($stmtOrderInsert, "ssiiids", $order_id, $cus_id, $status, $pay_type, $shipping_type,$shipping_cost,$address_id);

// Execute the prepared statement
$queryOrderInsert = mysqli_stmt_execute($stmtOrderInsert);

// Check if the query was successful
if (!$queryOrderInsert) {
    // Log the error and return an appropriate response
    $error_message = mysqli_stmt_error($stmtOrderInsert);
    die(json_encode(array("result" => 0, "message" => "เกิดข้อผิดผลาด: $error_message")));
}

// Close the prepared statement
mysqli_stmt_close($stmtOrderInsert);
?>

<?php
# Insert into order_detail table
if (!empty($priceList)) {
    foreach ($priceList as $index => $price) {
        if (
            isset($pro_idList[$index]) &&
            isset($price_idList[$index]) &&
            isset($amountList[$index])
        ) {
            $pro_id = $pro_idList[$index];
            $price_id = $price_idList[$index];
            $amount = $amountList[$index];

            $strSQL_o_detail = "INSERT INTO orders_detail (order_id, pro_id, price_id, amount, price) VALUES (?, ?, ?, ?, ?)";
            $stmt_o_detailInsert = mysqli_prepare($conn, $strSQL_o_detail);

            if ($stmt_o_detailInsert) {
                mysqli_stmt_bind_param($stmt_o_detailInsert, "sssid", $order_id, $pro_id, $price_id, $amount, $price);

                $query_o_detailInsert = mysqli_stmt_execute($stmt_o_detailInsert);

                if (!$query_o_detailInsert) {
                    // Log the error and return an appropriate response
                    $error_message = mysqli_stmt_error($stmt_o_detailInsert);
                    die(json_encode(array("result" => 0, "message" => "เกิดข้อผิดผลาด: $error_message")));
                }

                // Close the prepared statement
                mysqli_stmt_close($stmt_o_detailInsert);
            } else {
                // Log the error and return an appropriate response
                die(json_encode(array("result" => 0, "message" => "เกิดข้อผิดผลาด")));
            }
        }
    }
} else {
    // Log the error and return an appropriate response
    die(json_encode(array("result" => 0, "message" => "ไม่มีรายการสินค้า")));
}


foreach ($pro_idList as $index => $product) {
    $strReservePro = "UPDATE product SET amount_reserve = amount_reserve + ? WHERE pro_id = ?";
    
    // Prepare the statement
    $stmtReservePro = mysqli_prepare($conn, $strReservePro);

    // Check if the statement is prepared successfully
    if ($stmtReservePro) {
        // Bind parameters
        mysqli_stmt_bind_param($stmtReservePro, "is", $amountList[$index], $product);
        
        // Execute the prepared statement
        $queryResult = mysqli_stmt_execute($stmtReservePro);

        // Check if the query was successful
        if ($queryResult) {
            // Close the statement
            mysqli_stmt_close($stmtReservePro);
        } else {
            // Handle the error and return a JSON response or log the error
            $error_message = mysqli_stmt_error($stmtReservePro);
            $json_response = json_encode(array("result" => 0, "message" => $error_message));
            echo $json_response;

            // Close the statement
            mysqli_stmt_close($stmtReservePro);

            // Stop the loop or take appropriate action
            die();
        }
    } else {
        // Handle the case when the statement couldn't be prepared
        // You might want to log the error or return an appropriate response
    }
}


foreach ($pro_idList as $index => $product) {
    // เพิ่ม SELECT ก่อนที่จะทำการ DELETE
    $selectCartQuery = "SELECT * FROM cart WHERE pro_id = '$product' AND cus_id = '$cus_id'";
    $selectCartResult = mysqli_query($conn, $selectCartQuery);

    // ตรวจสอบว่ามีข้อมูลในตาราง cart หรือไม่
    if ($selectCartResult->num_rows > 0) {
        // ถ้ามีข้อมูล ทำการ DELETE
        $deleteCartQuery = "DELETE FROM cart WHERE pro_id = '$product' AND cus_id = '$cus_id'";
        mysqli_query($conn, $deleteCartQuery);
    }
}

// amount
// : 
// "2.0"
// cus_id
// : 
// "cus8507716306"
// price_id
// : 
// "pr005"
// pro_id
// : 
// "pro5942670647"

$json_response = json_encode(array("result" => 1, "order_id"=> $order_id , "message" => "สั่งซื้อสินค้าเรียบร้อย"));
echo $json_response;
?>

<?php
# output
mysqli_close($conn);
?>

<?php
# log function
?>
