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
    $pro_id = trim($json_data["pro_id"]);
    $cus_id = trim($json_data["cus_id"]);
    $price_id = trim($json_data["price_id"]);
    $amount = trim($json_data["amount"]);
    // Query the database
    $strSql = "UPDATE cart SET amount = '$amount' ,price_id = '$price_id'
    WHERE cus_id = '$cus_id' AND pro_id = '$pro_id'";
    
    $query = mysqli_query($conn, $strSql);
 

    if ($query) {
        // Return the result as JSON
        echo json_encode(array("result" => 1,  "message" => "Success"));
    } else {
        echo json_encode(array("result" => 0,  "message" => "Error querying the database"));
    }
} else {
    echo json_encode(array("result" => 0, "count" => null, "message" => "Invalid request method"));
}
?>
