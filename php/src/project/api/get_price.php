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
    // Query the database
    $strSql = "SELECT *
            FROM price
            WHERE price.pro_id = '$pro_id'
            ORDER BY price.amount_conditions ASC";
    
    $query = mysqli_query($conn, $strSql);
    $datalist = array();

    while ($resultQuery = mysqli_fetch_array($query)) {
        if (!isset($datalist["price_id"])) {
            $datalist["price_id"] = array();
            $datalist["prices"] = array();
            $datalist["amount_conditions"] = array();
        }
    
        $datalist["prices"][] = $resultQuery['price']; 
        $datalist["amount_conditions"][] = $resultQuery['amount_conditions']; 
        $datalist["price_id"][] = $resultQuery['price_id']; 
    }

    if ($query) {
        // Return the result as JSON
        echo json_encode(array("result" => 1, "datalist" => $datalist, "message" => "Success"));
    } else {
        echo json_encode(array("result" => 0, "datalist" => null, "message" => "Error querying the database"));
    }
} else {
    echo json_encode(array("result" => 0, "count" => null, "message" => "Invalid request method"));
}
?>
