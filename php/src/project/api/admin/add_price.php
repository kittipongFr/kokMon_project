<?php ob_start(); ?>
<?php
    #header
    @header('Content-Type: application/json');
    @header("Access-Control-Allow-Origin: *");
    @header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>
<?php
    #connection and data include  OR require
    require ("../../config/config_db.php");
?>
<?php
    #input
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $content = @file_get_contents('php://input');
        $json_data = @json_decode($content, true);
        $pro_id = trim($json_data["pro_id"]);
        $amount_conditions = trim($json_data["conditions"]);
        $price = trim($json_data["price"]);
    }else{
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();        
    }    
?>
<?php
    do {
        $price_id = "pr" . rand(1000000000, 9999999999);

        # Check if the generated cus_id already exists in the database
        $checkExistingIdQuery = "SELECT * FROM price WHERE price_id = ?";
        $stmtPriceId = $conn->prepare($checkExistingIdQuery);
        $stmtPriceId->bind_param("s", $price_id);
        $stmtPriceId->execute();
        $stmtPriceId->store_result();
    } while ($stmtPriceId->num_rows > 0);

    $strSQL = "SELECT * FROM price  where pro_id = '$inputId' A amount_conditions = '$amount_conditions'";
    $query = mysqli_query($conn, $strSQL);
    if ($query->num_rows > 0){
        echo json_encode(array("result" => 0, "message" => "มีจำนวนสั่งซื้อขั้นต่ำนี้แล้ว"));
        die();
    }


    #process
// Insert data into the manufacture table
$stmt = $conn->prepare('INSERT INTO price (price_id,pro_id, amount_conditions,price) VALUES (?, ?,?,?)');
$stmt->bind_param('ssdd',$price_id, $pro_id, $amount_conditions,$price);
$stmt->execute();

// Check for errors
if ($stmt->error) {
    echo json_encode(array("result" => 0, "message" => "Error inserting into manufacture: " . $stmt->error));
    die();
}

    

?>
<?php
    #output
    ob_end_clean();
    @mysqli_close($conn); 
     
    echo $json_response = json_encode(array("result"=>1,"message"=>"เพิ่มราคาสำเร็จ"));
 
    exit;
?>
<?php
    #log function
?>