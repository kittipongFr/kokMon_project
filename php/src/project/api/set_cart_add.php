<?php ob_start(); ?>
<?php
#header
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>   
<?php
#connection and data include  OR require
require("../config/config_db.php");
?>
<?php
#input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = @file_get_contents('php://input');
    $json_data = @json_decode($content, true);
    $inputCusId = trim($json_data["cus_id"]);
    $inputProId = trim($json_data["pro_id"]);
    $inputAmount = trim($json_data["amount"]);
    $inputPrice = trim($json_data["price_id"]);
} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    die();
}
?>
<?php
$strSQLPrice = "SELECT price_id, amount_conditions
FROM product
JOIN price ON product.pro_id = price.pro_id
WHERE product.pro_id = '$inputProId'
ORDER BY amount_conditions ASC";

$queryPrice = mysqli_query($conn, $strSQLPrice);

if ($queryPrice) {
    $datalistPrice = array();

    while ($resultQueryPrice = mysqli_fetch_assoc($queryPrice)) {
        $datalistPrice[] = array(
            'price_id' => $resultQueryPrice['price_id'],
            'amount_conditions' => $resultQueryPrice['amount_conditions']
        );
    }

    # Find the highest amount conditions
    $highestAmountConditions = null;
    $highestPriceId = null;



    # Now $highestAmountConditions contains the highest value of amount_conditions

    # Fetch the sum of amount and $inputAmount from cart
    $strSQLCart = "SELECT amount 
        FROM cart
        WHERE pro_id = '$inputProId' AND cus_id = '$inputCusId'";
        $queryCart = mysqli_query($conn, $strSQLCart);
   
    if (mysqli_num_rows($queryCart) > 0) {
            // Rows found, handle the data
            $resultQueryCart = mysqli_fetch_assoc($queryCart);
            $sumAmount = $resultQueryCart['amount']+$inputAmount;


            foreach ($datalistPrice as $item) {
                if ($sumAmount >= $item['amount_conditions'] ) {
                    $highestAmountConditions = $item['amount_conditions'];
                    $highestPriceId = $item['price_id'];
                }
            }


        # Compare $sumAmount with $highestAmountConditions
        if ($sumAmount >= $highestAmountConditions) {
            $strSQLCartUpdate = "UPDATE cart SET amount = amount + '$inputAmount' ,price_id = '$highestPriceId'
            WHERE cus_id = '$inputCusId' AND pro_id = '$inputProId'";
            $queryCartUpdate = mysqli_query($conn, $strSQLCartUpdate);

            # Do something if $sumAmount is greater than or equal to $highestAmountConditions
            // echo "The sum of amount and \$inputAmount is greater than or equal to the highest amount_conditions.";
            if (!$queryCartUpdate) {
                # Handle the query error for cart table update
                echo $json_response = json_encode(array("result" => 0, "count" => null, "message" => "ไม่พบข้อมูล"));
                die();
            }
            
        } else {
            echo $json_response = json_encode(array("result" => 0, "count" => null, "message" => "ไม่พบข้อมูล"));
                die();
        }
    } else {
       
     
            $strSQLCartInsert = "INSERT INTO cart (cus_id, pro_id, amount, price_id) VALUES (?, ?, ?, ?)";
            $stmtCartInsert = mysqli_prepare($conn, $strSQLCartInsert);
           
            // Bind parameters to the prepared statement
            mysqli_stmt_bind_param($stmtCartInsert, "ssds", $inputCusId, $inputProId, $inputAmount, $inputPrice);

            // Execute the prepared statement
            $queryCartInsert = mysqli_stmt_execute($stmtCartInsert);

            // Check if the query was successful
            if (!$queryCartInsert) {
                // Handle the error and return a JSON response
                $error_message = mysqli_stmt_error($stmtCartInsert);
                $json_response = json_encode(array("result" => 0, "count" => null, "message" => $error_message));
                echo $json_response;
                die();
            }

            // Close the prepared statement
            mysqli_stmt_close($stmtCartInsert);
            // If successful, return a JSON response
        

                }
} else {
    # Handle the query error for price table
    echo $json_response = json_encode(array("result" => 0, "count" => null, "message" => "ไม่พบข้อมูล"));
                die();
}

$strSQL = "SELECT count(pro_id) As countPro FROM cart WHERE cus_id='".$inputCusId."'";

$query = @mysqli_query($conn, $strSQL);
$resultQuery = @mysqli_fetch_array($query);
$countPro = intval($resultQuery['countPro']);
?>
<?php
#output
@mysqli_close($conn);
if ($query) {
    echo $json_response = json_encode(array("result" => 1, "count" => $countPro, "message" => "เพิ่มใส่ตะกร้าเรียบร้อย"));
} else {
    echo $json_response = json_encode(array("result" => 0, "count" => null, "message" => "ไม่พบข้อมูล"));
    die();
}
?>
<?php
#log function
?>
