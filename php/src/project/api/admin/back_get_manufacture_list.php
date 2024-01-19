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
    #process
    $strSQL = "SELECT mft.manufacture_id, mft.date, m.mem_id, m.fname, m.lname, COUNT(mdt.manufacture_id) AS manufacture_count
    FROM manufacture AS mft
    JOIN member AS m ON mft.mem_id = m.mem_id
    LEFT JOIN manufacture_detail AS mdt ON mft.manufacture_id = mdt.manufacture_id
    GROUP BY mft.manufacture_id
    ORDER BY mft.date ASC";

$query = mysqli_query($conn, $strSQL);
$datalist = array();

while ($resultQuery = mysqli_fetch_array($query)) {
$manufacture_id = $resultQuery['manufacture_id'];
$date = $resultQuery['date'];
$mem_id = $resultQuery['mem_id'];
$fname = $resultQuery['fname'];
$lname = $resultQuery['lname'];
$manufacture_count = $resultQuery['manufacture_count'];

$datalist[] = array(
 "id" => $manufacture_id,
 "date" => $date,
 "mem_id" => $mem_id,
 "mem_name" => $fname . " " . $lname,
 "manufacture_count" => $manufacture_count
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