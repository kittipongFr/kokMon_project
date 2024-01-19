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
// Include your database connection code here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = @file_get_contents('php://input');
    $json_data = @json_decode($content, true);
    $cus_id = trim($json_data["cus_id"]);
    // Query the database
    $strSql = "SELECT cart.*,cart.amount AS cart_amount, price.*, product.*, customer.*,(product.amount-product.amount_reserve) AS pro_net
            FROM cart
            INNER JOIN price ON cart.price_id = price.price_id
            INNER JOIN product ON cart.pro_id = product.pro_id
            INNER JOIN customer ON cart.cus_id = customer.cus_id
            WHERE cart.cus_id = '$cus_id'";
    
    $query = mysqli_query($conn, $strSql);
    $datalist = array();
    $customer = array();

   


    while ($resultQuery = mysqli_fetch_array($query)) {
        $id = $resultQuery['pro_id'];
        $name = $resultQuery['name'];
        $detail = $resultQuery['detail'];
        $amount = $resultQuery['cart_amount'];
        $unit = $resultQuery['unit'];
        $img = $resultQuery['img'];
        $price = $resultQuery['price'];
        $price_id = $resultQuery['price_id'];
        $amount_condition = $resultQuery['amount_conditions'];
        $pro_net = $resultQuery['pro_net'];
    

        $cus_name = $resultQuery['fname']." ".$resultQuery['lname'];
        $cus_id = $resultQuery['cus_id'];
        $customer = array(
            "name"=> $cus_name,
            "id"=>$cus_id
        );
        if (!isset($datalist[$id])) {
            $datalist[$id] = array(
                "pro_id" => $id,
                "pro_name" => $name,
                "pro_amount" => $amount,
                "pro_unit" => $unit,
                "pro_img" => $img,
                "pro_detail" => $detail,
                "price" => $price,
                "price_id" => $price_id,
                "amount_condition" => $amount_condition,
                "pro_net"=>$pro_net
            );
        }

    }
    if ($query) {


        // Return the result as JSON
        echo json_encode(array("result" => 1, "datalist" => $datalist,"customer"=>$customer, "message" => "Success"));
    } else {
        echo json_encode(array("result" => 0, "datalist" => null, "message" => "Error querying the database"));
    }
} else {
    echo json_encode(array("result" => 0, "count" => null, "message" => "Invalid request method"));
}
?>
