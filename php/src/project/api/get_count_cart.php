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
    $strSql = "SELECT count(pro_id) AS countPro FROM `cart` WHERE cus_id = '$cus_id'";
    $query = mysqli_query($conn, $strSql);
    
    if ($query) {
        $resultQuery = mysqli_fetch_assoc($query);
        $countPro = intval($resultQuery['countPro']);

        // Return the result as JSON
        echo json_encode(array("result" => 1, "count" => $countPro, "message" => "Success"));
    } else {
        echo json_encode(array("result" => 0, "count" => null, "message" => "Error querying the database"));
    }
} else {
    echo json_encode(array("result" => 0, "count" => null, "message" => "Invalid request method"));
}
?>
