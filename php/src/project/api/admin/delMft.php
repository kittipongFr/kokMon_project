<?php
ob_start();

#header
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require("../../config/config_db.php");

#input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $mft_id = isset($data['id']) ? $data['id'] : '';

} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}

// Input validation
if (empty($mft_id)) {
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}

// Select Product_ids from manufacture_detail for a specific receive_Product_id
$selectProductIdsQuery = "
    SELECT DISTINCT pro_id
    FROM manufacture_detail
    WHERE manufacture_id = ?
";

$stmtSelectProductIds = $conn->prepare($selectProductIdsQuery);
$stmtSelectProductIds->bind_param('s', $mft_id);
$stmtSelectProductIds->execute();
$stmtSelectProductIds->bind_result($productId);

$productIds = array();

// Fetch Product_ids and store them in an array
while ($stmtSelectProductIds->fetch()) {
    $productIds[] = $productId;
}

$stmtSelectProductIds->close();

// Update the Product table for each Product_id
$updateProductQuery = "
    UPDATE product
    SET amount = amount - (
        SELECT SUM(amount)
        FROM manufacture_detail
        WHERE manufacture_detail.pro_id = product.pro_id
        AND manufacture_detail.manufacture_id = ?
    )
    WHERE product.pro_id = ? 
";

$stmtUpdateProduct = $conn->prepare($updateProductQuery);

foreach ($productIds as $productId) {
    $stmtUpdateProduct->bind_param('ss', $mft_id, $productId);
    $stmtUpdateProduct->execute();
}

$stmtUpdateProduct->close();

// Delete data from manufacture_detail
$deleteDetailQuery = "DELETE FROM manufacture_detail WHERE manufacture_id = ?";
$stmtDeleteDetail = $conn->prepare($deleteDetailQuery);
$stmtDeleteDetail->bind_param('s', $mft_id);
$stmtDeleteDetail->execute();

// Check for errors after executing the prepared statement
if ($stmtDeleteDetail->error) {
    // Handle the error (e.g., log it, send an email, etc.)
    echo json_encode(array("result" => 0, "message" => "Error deleting detail: " . $stmtDeleteDetail->error));
    die();
}

$stmtDeleteDetail->close();

// Delete data from manufacture
$deleteMftQuery = "DELETE FROM manufacture WHERE manufacture_id = ?";
$stmtDeleteMft = $conn->prepare($deleteMftQuery);
$stmtDeleteMft->bind_param('s', $mft_id);
$stmtDeleteMft->execute();

// Check for errors after executing the prepared statement
if ($stmtDeleteMft->error) {
    // Handle the error (e.g., log it, send an email, etc.)
    echo json_encode(array("result" => 0, "message" => "Error deleting manufacture: " . $stmtDeleteMft->error));
    die();
}

$stmtDeleteMft->close();
$conn->close();

// Output
echo json_encode(array("result" => 1, "message" => "ลบข้อมูลเรียบร้อย"));
?>
