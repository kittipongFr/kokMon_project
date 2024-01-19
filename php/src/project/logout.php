<?php
    ob_start();
    session_start();
    
    // ปิด session โดยลบทุกตัวแปร session
    session_unset();
    session_destroy();
    
    // เพิ่มโค้ด JavaScript เพื่อลบข้อมูลใน localStorage
    echo 'if (localStorage.getItem("customer_profile")) {';
    echo '<script>';
    echo 'localStorage.removeItem("customer_profile");';
    echo '</script>';
    echo '}';
    
    // ส่งผู้ใช้กลับไปยังหน้า login
    header("location: http://127.0.0.1:8080/project/login1.php");
    exit;
    ob_end_clean();
?>
