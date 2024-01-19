<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

require("../../config/config_db.php");

// ตรวจสอบว่ามีข้อมูลที่ถูกส่งมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีไฟล์รูปที่ส่งมาหรือไม่
    if (isset($_FILES['img'])) {
        // ตรวจสอบว่ามีข้อมูลที่ต้องการหรือไม่
        if (
            isset($_POST["name"]) &&
            isset($_POST["unit"]) 
          
        ) {
            // ตรวจสอบประเภทของไฟล์รูป
            $allowedFileTypes = array('image/png', 'image/jpeg', 'image/jpg');
            $uploadedFileType = $_FILES['img']['type'];

            if (!in_array($uploadedFileType, $allowedFileTypes)) {
                echo json_encode(array("result" => 0, "message" => "ไฟล์ที่ใช้ได้: .png, .jpg"));
                exit; // Stop execution if file type is not allowed
            }

            // Escape และกำหนดค่า
            $name = mysqli_real_escape_string($conn, $_POST["name"]);
            $unit = mysqli_real_escape_string($conn, $_POST["unit"]);
            $amount = 0;
            $mt_id = "";

            do {
                $mt_id = "mt" . rand(1000000000, 9999999999);

                # Check if the generated cus_id already exists in the database
                $checkExistingIdQuery = "SELECT * FROM material WHERE material_id = ?";
                $stmtProId = $conn->prepare($checkExistingIdQuery);
                $stmtProId->bind_param("s", $mt_id);
                $stmtProId->execute();
                $stmtProId->store_result();
            } while ($stmtProId->num_rows > 0);

            do {
                $date1 = date("Ymd_His");
                $numrand = (mt_rand());

                // Extract file extension from the original filename
                $originalFileName = $_FILES['img']['name'];
                $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                // Generate a new name with the same file extension
                $newname = $numrand . $date1 . "." . $fileExtension;

                # Check if the generated cus_id already exists in the database
                $checkExistingIdQuery = "SELECT * FROM material WHERE img = ?";
                $stmtImg = $conn->prepare($checkExistingIdQuery);
                $stmtImg->bind_param("s", $newname);
                $stmtImg->execute();
                $stmtImg->store_result();
            } while ($stmtImg->num_rows > 0);

            // บันทึกไฟล์รูป
            $path = "../../assets/images/material/";
            $path_copy = $path . $newname;
            move_uploaded_file($_FILES['img']['tmp_name'], $path_copy);

            // สร้างคำสั่ง SQL
            $sql = "INSERT INTO material
                    (material_id, name, amount, unit, img)
                    VALUES ('$mt_id', '$name', '$amount', '$unit',  '$newname')";

            // ทำการ query
            $result = mysqli_query($conn, $sql);

            // ตรวจสอบว่า query สำเร็จหรือไม่
            if ($result) {
                echo json_encode(array("result" => 1, "message" => "เพิ่มวัตถุดิบสำเร็จ"));
            } else {
                echo json_encode(array("result" => 0, "message" => "เพิ่มวัตถุดิบไม่สำเร็จ","error" => mysqli_error($conn)));
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
