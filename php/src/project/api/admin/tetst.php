<?php
 require("../../config/config_db.php");
$inputId="pro5942670647";
$referencingTables = ["manufacture_detail","cart", "orders_detail", "price"];
$recordsExist = false;

foreach ($referencingTables as $referencingTable) {
    $referencingQuery = "SELECT * FROM $referencingTable WHERE pro_id = '" . mysqli_real_escape_string($conn, $inputId) . "';";
    $referencingResult = mysqli_query($conn, $referencingQuery);

    if (mysqli_num_rows($referencingResult) > 0) {
        // Records exist in at least one referencing table
        $recordsExist = true;
        echo $json_response = json_encode(array("result" => 0, "message" => "ไม่สามารถลบมีข้อมูลในตาราง : ".$referencingTable));
      
        die();
    }
}

?>