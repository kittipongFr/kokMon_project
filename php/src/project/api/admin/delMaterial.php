<?php ob_start(); ?>

<?php
    #header
    @header('Content-Type: application/json');
    @header("Access-Control-Allow-Origin: *");
    @header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>

<?php
    #connection and data include OR require
    require("../../config/config_db.php");
?>

<?php
    #input
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = @file_get_contents('php://input');
        $json_data = @json_decode($content, true);
        $inputId = trim($json_data["id"]);
    } else {
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();
    }
?>

<?php

$referencingTables = ["receive_material_detail","material_used_detail"];
$recordsExist = false;

foreach ($referencingTables as $referencingTable) {
    $referencingQuery = "SELECT * FROM $referencingTable WHERE material_id = '" . mysqli_real_escape_string($conn, $inputId) . "';";
    $referencingResult = mysqli_query($conn, $referencingQuery);

    if (mysqli_num_rows($referencingResult) > 0) {
        // Records exist in at least one referencing table
        $recordsExist = true;
        echo $json_response = json_encode(array("result" => 0, "message" => "ไม่สามารถลบได้เนื่อจากมีข้อมูลในตาราง : ".$referencingTable));
      
        die();
    }
}


    #process
    $strSQL = "SELECT img FROM material WHERE material_id = '" . mysqli_real_escape_string($conn, $inputId) . "'";
    $result = mysqli_query($conn, $strSQL);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $imgFileName = $row['img'];

        $strSQL = "DELETE FROM material WHERE material_id = '" . mysqli_real_escape_string($conn, $inputId) . "'";
        $query = mysqli_query($conn, $strSQL);

        // Additional code to delete the associated image file
        if ($query) {
            $imageFilePath = "../../assets/images/material/". $imgFileName; // Adjust the path accordingly
            if (file_exists($imageFilePath)) {
                unlink($imageFilePath); // Delete the image file
            }
        }
    }

?>

<?php
    #output
    ob_end_clean();
    @mysqli_close($conn);
    if ($query) {
        echo $json_response = json_encode(array("result" => 1, "message" => "ลบสำเร็จ"));
    } else {
        echo $json_response = json_encode(array("result" => 0, "message" => "เกิดข้อผิดพลาด"));
    }
    exit;
?>

<?php
    #log function
?>
