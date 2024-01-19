<?php ob_start(); ?>
<?php
    #header
    @header('Content-Type: application/json');
    @header("Access-Control-Allow-Origin: *");
    @header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers'); 
?>
<?php
    #connection and data
    require("../../config/config_db.php");
    //print_r($conn);
    
?>
<?php
    #input
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $content = @file_get_contents('php://input');
        $json_data = @json_decode($content, true);
        $id = trim($json_data["mem_id"]);
    }else{
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();
    }
?>
<?php
    #process
    $strSQL = "SELECT * FROM member WHERE mem_id = '".$id."' ";
    $query = @mysqli_query($conn,$strSQL);
    $resultQuery = @mysqli_fetch_array($query);
    $datalist = array();
    echo $resultQuery;
    if($resultQuery['role'] != ""){
        $result=1;
        $message = "เข้าได้";
    }else{
        $result=0;
        $message = "คุณไม่มีสิทธิ์ใช้งานหน้านี้";
    }
?>
<?php
    #output
    ob_clean();
    @mysqli_close($conn);
    echo $json_response = json_encode(array("result" => $result, "message" => $message));
    exit;
?>
