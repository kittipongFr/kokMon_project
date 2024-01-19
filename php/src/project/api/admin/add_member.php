<?php ob_start(); ?>
<?php
    #header
    @header('Content-Type: application/json');
    @header("Access-Control-Allow-Origin: *");
    @header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>   
<?php
    #connection and data include OR require
    require ("../../config/config_db.php");
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
        $role = trim($json_data["role"]);
        $session = "0";
        $mem_id = "";




    } else {
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();
    }
?>

<?php

if (empty($inputEmail) || empty($inputPassword) || empty($inputFname) || empty($inputLname) || empty($inputTel) || empty($inputAddress) ) {
    ob_end_clean();
    @header("HTTP/1.0 400 Bad Request");
    die(json_encode(array("result"=>0,"message" => "กรุณากรอกข้อมูลให้ครบ")));
}

# Check if the email already exists
$checkEmailQuery = "SELECT * FROM member WHERE email = ?";
$stmtEmail = $conn->prepare($checkEmailQuery);
$stmtEmail->bind_param("s", $inputEmail);
$stmtEmail->execute();
$stmtEmail->store_result();

do {
    $mem_id = "mem" . rand(1000000000, 9999999999);

    # Check if the generated mem_id already exists in the database
    $checkExistingIdQuery = "SELECT * FROM member WHERE mem_id = ?";
    $stmtMemId = $conn->prepare($checkExistingIdQuery);
    $stmtMemId->bind_param("s", $mem_id);
    $stmtMemId->execute();
    $stmtMemId->store_result();
} while ($stmtMemId->num_rows > 0);

// Check if the email already exists
if ($stmtEmail->num_rows > 0) {
    ob_end_clean();
    echo $json_response = json_encode(array("result"=>0,"message" => "E-Mail นี้เคยลงทะเบียนแล้ว"));
    exit;
}else{

        # Hash the password
        $options = ['cost' => 10,];
        $hashedPassword = password_hash($inputPassword, PASSWORD_BCRYPT, $options);

        # Insert user data into the database
        $query = "INSERT INTO member (mem_id, email, password, fname, lname, telephone, address,role,session) VALUES (?, ?, ?, ?, ?, ?, ? ,?,?)";
        $stmtInsert = $conn->prepare($query);

        if ($stmtInsert === false) {
            die('Error preparing statement: ' . $conn->error);
        }

        $stmtInsert->bind_param("sssssssss", $mem_id, $inputEmail, $hashedPassword, $inputFname, $inputLname, $inputTel, $inputAddress,$role,$session);
        $result = $stmtInsert->execute();

        if ($result === false) {
            ob_end_clean();
            echo $json_response = json_encode(array("result"=>0,"message" => "เกิดข้อผิดพลาด1". $conn->error));
            exit;
        }

    


        # Close the statement for email check
        $stmtEmail->close();
        # Close the statement for mem_id check
        $stmtMemId->close();

    #output
    ob_end_clean();
    
    if ($stmtInsert) {
        echo $json_response = json_encode(array("result" => 1, "message" => "เพิ่มสมาชิกเรียบร้อย"));
    } else {
        echo $json_response = json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด"));
        die();
    }
    $stmtInsert->close();
    @mysqli_close($conn);
}

?>

<?php
    #log function
?>
