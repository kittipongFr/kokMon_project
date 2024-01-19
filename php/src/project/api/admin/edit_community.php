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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีไฟล์รูปที่ส่งมาหรือไม่
    if (isset($_FILES['img_file']) && !empty($_FILES['img_file']['name'])) {
        // ตรวจสอบว่ามีข้อมูลที่ต้องการหรือไม่
        if (
            isset($_POST["name"]) &&
            isset($_POST["address"])  &&
            isset($_POST["shipping_rate"])  &&
            isset($_POST["bank_num"])  &&
            isset($_POST["bank_name"]) 
        ) {
            // ตรวจสอบประเภทของไฟล์รูป
            $allowedFileTypes = array('image/png', 'image/jpeg');
            $uploadedFileType = $_FILES['img_file']['type'];

            if (!in_array($uploadedFileType, $allowedFileTypes)) {
                echo json_encode(array("result" => 0, "message" => "ไฟล์ที่ใช้ได้ : .png, .jpg"));
                exit; // Stop execution if file type is not allowed
            }

            // Escape และกำหนดค่า
            $name = mysqli_real_escape_string($conn, $_POST["name"]);
            $address = mysqli_real_escape_string($conn, $_POST["address"]);
            $shipping_rate = mysqli_real_escape_string($conn, $_POST["shipping_rate"]);
            $bank_num = mysqli_real_escape_string($conn, $_POST["bank_num"]);
            $bank_name = mysqli_real_escape_string($conn, $_POST["bank_name"]);

            $originalFileName = $_FILES['img_file']['name'];
           

                // Generate a new name with the same file extension
                $newname = $originalFileName;


            // บันทึกไฟล์รูป
            $path = "../../assets/images/";
            $path_copy = $path . $newname;
            move_uploaded_file($_FILES['img_file']['tmp_name'], $path_copy);

            // สร้างคำสั่ง SQL
            $strSQL = "UPDATE community_enterprise SET name = '$name' , address = '$address'
            ,bank_name = '$bank_name',bank_num = '$bank_num',shipping_rate = '$shipping_rate' , img = '$newname' WHERE id = 1 ";
            
            $query = mysqli_query($conn, $strSQL);

         

            // ตรวจสอบว่า query สำเร็จหรือไม่
            if ($query) {
                echo json_encode(array("result" => 1, "message" => "แก้ไขสินค้าสำเร็จ"));
            } else {
                echo json_encode(array("result" => 0, "message" => "แก้ไขสินค้าไม่สำเร็จ"));
            }

            // ปิดการเชื่อมต่อกับฐานข้อมูล
            mysqli_close($conn);
        } else {
            echo json_encode(array("result" => 0, "message" => "กรอกข้อมูลไม่ครบ"));
        }
    } else {
        // echo json_encode(array("result" => 3, "message" => "ไม่พบFile รูปภาพ"));
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $address = mysqli_real_escape_string($conn, $_POST["address"]);
        $shipping_rate = mysqli_real_escape_string($conn, $_POST["shipping_rate"]);
        $bank_num = mysqli_real_escape_string($conn, $_POST["bank_num"]);
        $bank_name = mysqli_real_escape_string($conn, $_POST["bank_name"]);

        $strSQL = "UPDATE community_enterprise SET name = '$name' , address = '$address'
        ,bank_name = '$bank_name',bank_num = '$bank_num',shipping_rate = '$shipping_rate'
         WHERE id = 1 ";
        
        $query = mysqli_query($conn, $strSQL);

    // ตรวจสอบว่า query สำเร็จหรือไม่
    if ($query) {
        echo json_encode(array("result" => 1, "message" => "แก้ไขสินค้าสำเร็จ"));
    } else {
        echo json_encode(array("result" => 0, "message" => "แก้ไขสินค้าไม่สำเร็จ","error" => mysqli_error($conn)));
    }

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    mysqli_close($conn);
    }
} else {
    echo json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด"));
    die();
}
?>






























