<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

require("../config/config_db.php");

// ตรวจสอบว่ามีข้อมูลที่ถูกส่งมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีไฟล์รูปที่ส่งมาหรือไม่
    if (isset($_FILES['slip'])) {
        // ตรวจสอบว่ามีข้อมูลที่ต้องการหรือไม่
        if (
            isset($_POST["transfer_id"]) &&
            isset($_POST["transfer_total"])
            // isset($_POST["selectPayment"]) 
          
        ) {
            // ตรวจสอบประเภทของไฟล์รูป
            $allowedFileTypes = array('image/png', 'image/jpeg', 'image/jpg');
            $uploadedFileType = $_FILES['slip']['type'];

            if (!in_array($uploadedFileType, $allowedFileTypes)) {
                echo json_encode(array("result" => 0, "message" => "ไฟล์ที่ใช้ได้: .png, .jpg"));
                exit; // Stop execution if file type is not allowed
            }

            // Escape และกำหนดค่า
            $order_id = mysqli_real_escape_string($conn, $_POST["transfer_id"]);
            $pay_total = mysqli_real_escape_string($conn, $_POST["transfer_total"]);
            // $pay_type = mysqli_real_escape_string($conn, $_POST["pay_type"]);
            $pay_id = "";


    $selectQuery = "SELECT pay_id FROM payment ORDER BY pay_id DESC LIMIT 1";
    $result = $conn->query($selectQuery);
    
    if ($result->num_rows > 0) {
        // Fetch the manufacture_id
        $row = $result->fetch_assoc();
        $pay_id = $row['pay_id'];
    
        // Extract the date part
        $dateSplit = substr($pay_id, 2, 6);
    
        // Extract the numeric part
        $numericPart = (int)substr($pay_id, 8);
    
        // Format the current date with only the last two digits of the year
        $currentDate = date("ymd");
    
        if ($dateSplit == $currentDate) {
            $new_id = $numericPart + 1;
            $formatted_id = "py" . $currentDate . sprintf("%05d", $new_id);
        } else {
            $formatted_id = "py" . $currentDate . "00001";
        }
    
        
    } else {
        $currentDate = date("ymd");
        $formatted_id = "py" . $currentDate . "00001";
        
    }
    
    $pay_id = $formatted_id;



            // Extract file extension from the original filename
            $originalFileName = $_FILES['slip']['name'];
            $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

            // Generate a new name with the same file extension
            $newname = $pay_id . "." . $fileExtension;

            // บันทึกไฟล์รูป
            $path = "../assets/images/slip/";
            $path_copy = $path . $newname;
            move_uploaded_file($_FILES['slip']['tmp_name'], $path_copy);


            


            // สร้างคำสั่ง SQL
            $sql = "INSERT INTO payment
                    (pay_id, order_id, pay_total, slip_img)
                    VALUES ('$pay_id', '$order_id', '$pay_total', '$newname')";

            // ทำการ query
            $result = mysqli_query($conn, $sql);


            $strUpdate = "UPDATE orders SET status = '2' WHERE order_id = '$order_id'";
            $query = mysqli_query($conn, $strUpdate);

            if (!$query) {
                echo json_encode(array("result" => 0, "message" => "แจ้งการชำระสำเร็จไม่สำเร็จ", "error" => mysqli_error($conn)));
            }
            // ตรวจสอบว่า query สำเร็จหรือไม่
            if ($result) {
                echo json_encode(array("result" => 1, "message" => "แจ้งการชำระสำเร็จ"));
            } else {
                echo json_encode(array("result" => 0, "message" => "แจ้งการชำระสำเร็จไม่สำเร็จ","error" => mysqli_error($conn)));
            }

            // ปิดการเชื่อมต่อกับฐานข้อมูล
            mysqli_close($conn);
        } else {
            echo json_encode(array("result" => 0, "message" => "กรอกข้อมูลไม่ครบ"));
        }
    } else {
        echo json_encode(array("result" => 0, "message" => "ไม่พบFile รูปภาพ"));
    }
} else {
    echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด"));
}
?>
