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
        $inputId = trim($json_data["id"]);
    }else{
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();        
    }    
?>
<?php
    #process
    $strSQL = "SELECT * FROM community_enterprise LIMIT 1";
    
    $query = mysqli_query($conn, $strSQL);
    $datalist = array();
    
    $resultQuery = mysqli_fetch_array($query);
        $name = $resultQuery['name'];
        $address = $resultQuery['address'];
        $img = $resultQuery['img'];
        $bank_name = $resultQuery['bank_name'];
        $bank_num = $resultQuery['bank_num'];
        $aml_fund = $resultQuery['aml_fund'];
        $ccl_fund = $resultQuery['ccl_fund'];
        $shipping_rate = $resultQuery['shipping_rate'];
 
        $datalist = array(
                "name" => $name,
                "address" => $address,
                "bank_num" => $bank_num,
                "img" => $img,
                "bank_name" => $bank_name,
                "aml_fund" => $aml_fund,
                "ccl_fund" => $ccl_fund,
                "shipping_rate" => $shipping_rate
                   
        );
    

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