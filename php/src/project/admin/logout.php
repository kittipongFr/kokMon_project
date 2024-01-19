<?php
    ob_start();
    session_start();
    
    // ปิด session โดยลบทุกตัวแปร session
    session_unset();
    session_destroy();
    echo '<script>';
    // เพิ่มโค้ด JavaScript เพื่อลบข้อมูลใน localStorage
    echo 'if (localStorage.getItem("member_profile")) {';
   
    echo 'localStorage.removeItem("member_profile");';
   
    echo '}';
    echo '</script>';
    // ส่งผู้ใช้กลับไปยังหน้า login
    header("location: http://127.0.0.1:8080/project/admin/back_login.php");
    exit;
    ob_end_clean();
?>
