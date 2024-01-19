<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

require("../../config/config_db.php");

// ตรวจสอบว่ามีข้อมูลที่ถูกส่งมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีไฟล์รูปที่ส่งมาหรือไม่
    if (isset($_FILES['e_img']) && !empty($_FILES['e_img']['name'])) {
        // ตรวจสอบว่ามีข้อมูลที่ต้องการหรือไม่
        if (
            isset($_POST["e_name"]) &&
            isset($_POST["e_amount"]) &&
            isset($_POST["e_unit"]) &&
            isset($_POST["e_detail"])
        ) {
            // ตรวจสอบประเภทของไฟล์รูป
            $allowedFileTypes = array('image/png', 'image/jpeg');
            $uploadedFileType = $_FILES['e_img']['type'];

            if (!in_array($uploadedFileType, $allowedFileTypes)) {
                echo json_encode(array("result" => 0, "message" => "Invalid file type. Allowed types: .png, .jpg"));
                exit; // Stop execution if file type is not allowed
            }

            // Escape และกำหนดค่า
            $name = mysqli_real_escape_string($conn, $_POST["e_name"]);
            $amount = mysqli_real_escape_string($conn, $_POST["e_amount"]);
            $amount_reserve = mysqli_real_escape_string($conn, $_POST["e_amount_reserve"]);
            $unit = mysqli_real_escape_string($conn, $_POST["e_unit"]);
            $detail = mysqli_real_escape_string($conn, $_POST["e_detail"]);
            $pro_id = mysqli_real_escape_string($conn, $_POST["e_id"]);

           

            do {
                $date1 = date("Ymd_His");
                $numrand = (mt_rand());

                // Extract file extension from the original filename
                $originalFileName = $_FILES['e_img']['name'];
                $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                // Generate a new name with the same file extension
                $newname = $numrand . $date1 . "." . $fileExtension;

                # Check if the generated cus_id already exists in the database
                $checkExistingIdQuery = "SELECT * FROM product WHERE img = ?";
                $stmtImg = $conn->prepare($checkExistingIdQuery);
                $stmtImg->bind_param("s", $newname);
                $stmtImg->execute();
                $stmtImg->store_result();
            } while ($stmtImg->num_rows > 0);

            // บันทึกไฟล์รูป
            $path = "../../assets/images/product/";
            $path_copy = $path . $newname;
            move_uploaded_file($_FILES['e_img']['tmp_name'], $path_copy);

            // สร้างคำสั่ง SQL
            $sql = "UPDATE product SET
                name = '$name',
                amount = '$amount',
                amount_reserve = '$amount_reserve',
                unit = '$unit',
                detail = '$detail',
                img = '$newname'
                WHERE pro_id = '$pro_id'";

            // ทำการ query
            $result = mysqli_query($conn, $sql);

            // ตรวจสอบว่า query สำเร็จหรือไม่
            if ($result) {
                echo json_encode(array("result" => 1, "message" => "แก้ไขสินค้าสำเร็จ"));
            } else {
                echo json_encode(array("result" => 2, "message" => "แก้ไขสินค้าไม่สำเร็จ"));
            }

            // ปิดการเชื่อมต่อกับฐานข้อมูล
            mysqli_close($conn);
        } else {
            echo json_encode(array("result" => 3, "message" => "กรอกข้อมูลไม่ครบ"));
        }
    } else {
        // echo json_encode(array("result" => 3, "message" => "ไม่พบFile รูปภาพ"));
        $name = mysqli_real_escape_string($conn, $_POST["e_name"]);
        $amount = mysqli_real_escape_string($conn, $_POST["e_amount"]);
        $amount_reserve = mysqli_real_escape_string($conn, $_POST["e_amount_reserve"]);
        $unit = mysqli_real_escape_string($conn, $_POST["e_unit"]);
        $detail = mysqli_real_escape_string($conn, $_POST["e_detail"]);
        $pro_id = mysqli_real_escape_string($conn, $_POST["e_id"]);
        $sql = "UPDATE product SET
        name = '".$name."',
        amount = '".$amount."',
        amount_reserve = '".$amount_reserve."',
        unit = '".$unit."',
        detail = '".$detail."'
        WHERE pro_id = '".$pro_id."'";

    // ทำการ query
    $result = mysqli_query($conn, $sql);

    // ตรวจสอบว่า query สำเร็จหรือไม่
    if ($result) {
        echo json_encode(array("result" => 1, "message" => "แก้ไขสินค้าสำเร็จ"));
    } else {
        echo json_encode(array("result" => 2, "message" => "แก้ไขสินค้าไม่สำเร็จ"));
    }

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    mysqli_close($conn);
    }
} else {
    echo json_encode(array("result" => 4, "message" => "เกิดข้อผิดพลาด"));
}
?>
