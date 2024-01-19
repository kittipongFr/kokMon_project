<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

require("../../config/config_db.php");





// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $unit = $_POST['unit'];
    $detail = $_POST['detail'];
    $pro_id = "";
    $amount_reserve = 0;
    do {
        $pro_id = "pro" . rand(1000000000, 9999999999);

        # Check if the generated cus_id already exists in the database
        $checkExistingIdQuery = "SELECT * FROM product WHERE pro_id = ?";
        $stmtProId = $conn->prepare($checkExistingIdQuery);
        $stmtProId->bind_param("s", $pro_id);
        $stmtProId->execute();
        $stmtProId->store_result();
    } while ($stmtProId->num_rows > 0);

    // ตรวจสอบว่ามีข้อมูลที่ต้องการหรือไม่
    if (isset($name) && isset($amount) && isset($unit) && isset($detail) && isset($_FILES['img'])) {
        // ดึงข้อมูลจากฟอร์ม
        $imgArray = array();
        // ตรวจสอบและดึงข้อมูลไฟล์รูปภาพ
        $imgArray = $_FILES['img'];

        // echo json_encode($imgArray);

        
// ตรวจสอบว่ามีไฟล์รูปภาพถูกอัปโหลดหรือไม่
if (is_array($imgArray['tmp_name']) && $imgArray['error'][0] === 0) {
    // จัดเก็บไฟล์รูปภาพที่อัปโหลด
    $uploadedImages = [];
    $i = 1;
    foreach ($imgArray['tmp_name'] as $key => $tmpName) {
        $extension = pathinfo($imgArray['name'][$key], PATHINFO_EXTENSION);
        $uploadDir = '../../assets/images/product/';
        $uploadFile = $pro_id . '_' . $i . '.' . $extension;
        $i += 1;

        // ย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ปลายทาง
        move_uploaded_file($tmpName, $uploadDir.$uploadFile);

        // จัดเก็บชื่อไฟล์ที่อัปโหลดไว้เพื่อบันทึกลงในฐานข้อมูล
        $uploadedImages[] = $uploadFile;
    }
} else {
    // ถ้าไม่มีไฟล์รูปภาพถูกอัปโหลด
    $uploadedImages = [];
    // $response = array('result' => 0, 'message' => 'No image files uploaded.');
    // // header('Content-Type: application/json');
    // echo json_encode($response);
    // die();
}


    

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูล
        $sql = "INSERT INTO product (pro_id,name, amount,amount_reserve, unit, detail, img)
                VALUES ('$pro_id','$name', $amount,$amount_reserve, '$unit', '$detail', '" . implode(",", $uploadedImages) . "')";

        // ทำการเพิ่มข้อมูล
        if ($conn->query($sql) === TRUE) {
            $response = array('result' => 1, 'message' => 'เพิ่มข้อมูลสินค้าสำเร็จ', 'img' => $uploadedImages);
        } else {
            $response = array('result' => 0, 'message' => 'มีข้อผิดพลาดในการเพิ่มข้อมูล: ' . $conn->error);
        }

        // ปิดการเชื่อมต่อ
        $conn->close();

        // ส่งข้อมูลกลับเป็น JSON
        // header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // ถ้าข้อมูลไม่ครบถ้วน
        $response = array('result' => 0, 'd' => $name." ".$amount." ".$unit." ".$detail,'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน');
        // header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // ถ้าไม่ได้รับการเรียกใช้งานผ่านเมธอด POST
    $response = array('result' => 0, 'message' => 'Method not allowed');
    // header('Content-Type: application/json');
    echo json_encode($response);
}
?>
