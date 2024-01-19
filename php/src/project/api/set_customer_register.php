<?php ob_start(); ?>
<?php
    #header
    @header('Content-Type: application/json');
    @header("Access-Control-Allow-Origin: *");
    @header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>   
<?php
    #connection and data include OR require
    require ("../config/config_db.php");
?>
<?php
    #input
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = @file_get_contents('php://input');
        $json_data = @json_decode($content, true);
        $inputEmail = trim($json_data["email"]);
        $inputPassword = trim($json_data["password"]);
        $inputFname = trim($json_data["fname"]);
        $inputLname = trim($json_data["lname"]);
        $inputTel = trim($json_data["tel"]);
        $inputAddress = trim($json_data["address"]);
        $cus_id = "";

    } else {
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();
    }
?>

<?php

if (empty($inputEmail) || empty($inputPassword) || empty($inputFname) || empty($inputLname) || empty($inputTel) || empty($inputAddress)) {
    ob_end_clean();
    @header("HTTP/1.0 400 Bad Request");
    die(json_encode(array("message" => "Missing required fields")));
}

# Check if the email already exists
$checkEmailQuery = "SELECT * FROM customer WHERE email = ?";
$stmtEmail = $conn->prepare($checkEmailQuery);
$stmtEmail->bind_param("s", $inputEmail);
$stmtEmail->execute();
$stmtEmail->store_result();

do {
    $cus_id = "cus" . rand(1000000000, 9999999999);

    # Check if the generated cus_id already exists in the database
    $checkExistingIdQuery = "SELECT * FROM customer WHERE cus_id = ?";
    $stmtCusId = $conn->prepare($checkExistingIdQuery);
    $stmtCusId->bind_param("s", $cus_id);
    $stmtCusId->execute();
    $stmtCusId->store_result();
} while ($stmtCusId->num_rows > 0);

// Check if the email already exists
if ($stmtEmail->num_rows > 0) {
    ob_end_clean();
    echo $json_response = json_encode(array("result"=>3,"message" => "E-Mail นี้เคยลงทะเบียนแล้ว"));
    exit;
}else{

        # Hash the password
        $options = ['cost' => 10,];
        $hashedPassword = password_hash($inputPassword, PASSWORD_BCRYPT, $options);

        # Insert user data into the database
        $query = "INSERT INTO customer (cus_id, email, password, fname, lname, telephone, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($query);

        if ($stmtInsert === false) {
            die('Error preparing statement: ' . $conn->error);
        }

        $stmtInsert->bind_param("sssssss", $cus_id, $inputEmail, $hashedPassword, $inputFname, $inputLname, $inputTel, $inputAddress);
        $result = $stmtInsert->execute();

        if ($result === false) {
            die('Error executing statement: ' . $stmtInsert->error);
        }

        $stmtInsert->close();


        # Close the statement for email check
        $stmtEmail->close();
        # Close the statement for cus_id check
        $stmtCusId->close();

    #output
    ob_end_clean();
    @mysqli_close($conn);
    if ($stmtInsert) {
        echo $json_response = json_encode(array("result" => 1, "message" => "สมัครสมาชิกเรียบร้อย"));
    } else {
        echo $json_response = json_encode(array("result" => 0, "message" => "สมัครสมาชิกผิดพลาด"));
    }


}

?>

<?php
    #log function
?>
