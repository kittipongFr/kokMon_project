<?php


ob_start();

#header
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

include '../../config/config_db.php';

$query = "SELECT * FROM material ORDER BY material_id ASC";
$result = mysqli_query($conn, $query);

$materialData = array();

while ($row = mysqli_fetch_assoc($result)) {
    $materialData[] = $row;
}


echo json_encode($materialData);

?>