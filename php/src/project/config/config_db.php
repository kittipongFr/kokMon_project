<?php
// ข้อมูลการเชื่อมต่อกับ MySQL
$host = "db_kokmon"; // หรือที่อยู่ IP ของเซิร์ฟเวอร์ MySQL
$database = "kok_mon";
$username = "root";
$password = "MYSQL_ROOT_PASSWORD";

// เชื่อมต่อกับ MySQL
$conn = mysqli_connect($host, $username, $password, $database);

// ตรวจสอบการเชื่อมต่อ
@date_default_timezone_set("Asia/Bangkok");
@mysqli_set_charset($conn, "utf8");
@mysqli_query($conn, "SET time_zone = '+07:00'"); // ตั้งค่า timezone เป็นไทย (+07:00)
@mysqli_query($conn, "SET NAMES utf8");

?> 