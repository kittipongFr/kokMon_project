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
    #input
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $content = @file_get_contents('php://input');
        $json_data = @json_decode($content, true);
    }else{
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();        
    }    
?>
<?php
$strSQL = "SELECT rmt.receive_material_id, m.mem_id, m.fname, m.lname, rmt.supply_name, rmt.date, COUNT(rmd.receive_material_id) AS detail_count
FROM receive_material AS rmt
JOIN member AS m ON rmt.mem_id = m.mem_id
LEFT JOIN receive_material_detail AS rmd ON rmt.receive_material_id = rmd.receive_material_id
GROUP BY rmt.receive_material_id
ORDER BY rmt.date ASC";

$query = mysqli_query($conn, $strSQL);
$datalist = array();

while ($resultQuery = mysqli_fetch_array($query)) {
$id = $resultQuery['receive_material_id'];
$mem_id = $resultQuery['mem_id'];
$mem_name = $resultQuery['fname']." ".$resultQuery['lname'];
$supply_name = $resultQuery['supply_name'];
$date = $resultQuery['date'];
$detail_count = $resultQuery['detail_count'];

$datalist[] = array(
"id" => $id,
"mem_id" => $mem_id,
"mem_name" => $mem_name,
"supply_name" => $supply_name,
"date" => $date,
"detail_count" => $detail_count
);
}


?>
<?php
    #output
    ob_end_clean();
    @mysqli_close($conn); 
    if($query){       
        echo $json_response = json_encode(array("result"=>1,"message"=>"พบข้อมูล","datalist"=>$datalist));
    }else{     
        echo $json_response = json_encode(array("result"=>0,"message"=>"ไม่พบข้อมูล","datalist"=>null));
    }
    exit;
?>
<?php
    #log function
?>