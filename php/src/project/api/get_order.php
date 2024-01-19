<?php ob_start(); ?>
<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

// Include your database connection code here
require("../config/config_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = @file_get_contents('php://input');
    $json_data = @json_decode($content, true);

    if (!$json_data || !isset($json_data["cus_id"], $json_data["pro_id"], $json_data["price_id"], $json_data["amount"])) {
        echo json_encode(array("result" => 0, "message" => "Invalid JSON data"));
        exit();
    }

    $cus_id = trim($json_data["cus_id"]);
    $pro_ids = $json_data["pro_id"];
    $price_id = $json_data["price_id"];
    $amount = $json_data["amount"];

  

    // Check connection
    if (!$conn) {
        die(json_encode(array("result" => 0, "message" => "Connection failed: " . mysqli_connect_error())));
    }


    $addressList = array();
    $strSql = "SELECT address_id,name,address,tel FROM address WHERE  cus_id = '$cus_id'  ORDER BY address_id DESC";
    $query = mysqli_query($conn, $strSql);
    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $addressList[] = array(
                    "address_id"=>$row["address_id"],
                    "name"=>$row["name"],
                    "address"=>$row["address"],
                    "tel"=>$row["tel"],
            );
        }
    } else {
      
        echo "Error: " . mysqli_error($conn);
    }








    $datalist = array();
    if (!is_array($pro_ids)) {
        // If pro_id is not an array, treat it as a single value
        $pro_ids = array($pro_ids);
        $price_id = array($price_id);
        $amount = array($amount);
    }

  
    // Loop through each pro_id
    for ($i = 0; $i < count($pro_ids); $i++) {
        $current_pro_id = $pro_ids[$i];
        $current_price_id = $price_id[$i];

        $strSql = "SELECT price.*, product.*
                   FROM price
                   JOIN product ON price.pro_id = product.pro_id
                   WHERE price.price_id = '$current_price_id' AND price.pro_id = '$current_pro_id'";

        $query = mysqli_query($conn, $strSql);

        if ($query) {
            // Fetch data for the current pro_id
            $resultQuery = mysqli_fetch_array($query);

            // Check if data is fetched
            if ($resultQuery) {
                $datalist[$current_pro_id] = array(
                    "pro_id" => $resultQuery['pro_id'],
                    "pro_name" => $resultQuery['name'],
                    "pro_amount" => $amount[$i],
                    "pro_unit" => $resultQuery['unit'],
                    "pro_img" => $resultQuery['img'],
                    "pro_detail" => $resultQuery['detail'],
                    "price" => $resultQuery['price'],
                    "price_id" => $resultQuery['price_id'],
                    "amount_condition" => $resultQuery['amount_conditions']
                );
            } else {
                // Handle the case when no data is fetched for the current pro_id
                $datalist[$current_pro_id] = array(
                    "error" => "No data found for pro_id: $current_pro_id"
                );
            }
        } else {
            // Handle the case when the query fails for the current pro_id
            $datalist[$current_pro_id] = array(
                "error" => "Query failed for pro_id: $current_pro_id"
            );
        }
    }

    // Return the result as JSON
    echo json_encode(array("result" => 1, "datalist" => $datalist,"addressList"=>$addressList, "message" => "Success"));

    // Close the database connection
    mysqli_close($conn);


} else {
    echo json_encode(array("result" => 0, "message" => "Invalid request method"));
}
?>
