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
    $mem_id = isset($data['mem_id']) ? $data['mem_id'] : '';
    $products = isset($data['pro_id']) ? $data['pro_id'] : array();
    $amounts = isset($data['amount']) ? $data['amount'] : array();
    $mft_id = "";

} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid input data"));
    die();
}
$formatted_id = "";

// Generate a unique mft_id
$selectQuery = "SELECT manufacture_id FROM manufacture ORDER BY manufacture_id DESC LIMIT 1";
$result = $conn->query($selectQuery);

if ($result->num_rows > 0) {
    // Step 2: Fetch the _id
    $row = $result->fetch_assoc();
    $manufacture_id = $row['manufacture_id'];

    // Step 3: Remove the first 8 characters and convert the remaining part to a number
    $numericPart = (int)substr($manufacture_id, 8);
    $dateSplit = (int)substr($manufacture_id, 2, 6);

    // Step 4: Add 1 to the obtained number

    // Step 5: Format the current date with only the last two digits of the year
    $currentDate = date("ymd");
    if ($dateSplit == $currentDate) {
        $new_id = $numericPart + 1;
        $formatted_id = "mu" . $currentDate . sprintf("%05d", $new_id);
    } else {
        $formatted_id = "mu" . $currentDate . "00001";
    }

    echo "Generated ID: " . $formatted_id;
} else {
    $currentDate = date("ymd");
    $formatted_id = "mf" . $currentDate . "00001";
}

$mft_id = $formatted_id;

// Insert data into the manufacture table
$stmt = $conn->prepare('INSERT INTO manufacture (manufacture_id, mem_id) VALUES (?, ?)');
$stmt->bind_param('ss', $mft_id, $mem_id);
$stmt->execute();

// Check for errors
if ($stmt->error) {
    echo json_encode(array("result" => 0, "message" => "Error inserting into manufacture: " . $stmt->error));
    die();
}

$stmt->close();

// Insert data into the manufacture table using a loop
$stmt = $conn->prepare('INSERT INTO manufacture_detail (manufacture_id, pro_id, amount) VALUES (?, ?, ?)');

for ($i = 0; $i < count($products); $i++) {
    $product = $products[$i];
    $amount = $amounts[$i];

    $stmt->bind_param('ssd', $mft_id, $product, $amount);
    $stmt->execute();

    // Check for errors
    if ($stmt->error) {
        echo json_encode(array("result" => 0, "message" => "Error inserting into manufacture_detail: " . $stmt->error));
        die();
    }

    // Update the amount in the product table
    $updateProductQuery = "UPDATE product SET amount = amount + ? WHERE pro_id = ?";
    $stmtUpdateProduct = $conn->prepare($updateProductQuery);
    $stmtUpdateProduct->bind_param('ds', $amount, $product);
    $stmtUpdateProduct->execute();

    // Check for errors
    if ($stmtUpdateProduct->error) {
        echo json_encode(array("result" => 0, "message" => "Error updating amount in material table: " . $stmtUpdateProduct->error));
        die();
    }

    $stmtUpdateProduct->close();
}

$stmt->close();
$conn->close();

// Output
echo json_encode(array("result" => 1, "message" => "บันทึกเรียบร้อย"));
?>
