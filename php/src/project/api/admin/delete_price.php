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
        $price_id = trim($json_data["price_id"]);
       
    }else{
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();        
    }    
?>
<?php


    $referencingQuery = "SELECT * FROM orders_detail WHERE price_id = '" . mysqli_real_escape_string($conn, $price_id) . "';";
    $referencingResult = mysqli_query($conn, $referencingQuery);

    if (mysqli_num_rows($referencingResult) > 0) {
        // Records exist in at least one referencing table
        echo $json_response = json_encode(array("result" => 0, "message" => "ไม่สามารถลบมีข้อมูลในตาราง : orders_detail"));
      
        die();
    }



    #process
    $strSQL = "DELETE FROM price WHERE price_id = '$price_id' ";
    
    $query = mysqli_query($conn, $strSQL);

    

?>
<?php
    #output
    ob_end_clean();
    @mysqli_close($conn); 
    if($query){       
        echo $json_response = json_encode(array("result"=>1,"message"=>"ลบสำเร็จ"));
    }else{     
        echo $json_response = json_encode(array("result"=>0,"message"=>"เกิดข้อผิดพลาด"));
    }
    exit;
?>
<?php
    #log function
?>