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
    $strSQL = "SELECT * FROM member";
    
    $query = mysqli_query($conn, $strSQL);
    $datalist = array();
    
    while ($resultQuery = mysqli_fetch_array($query)) {
        $id = $resultQuery['mem_id'];
        $fname = $resultQuery['fname'];
        $lname = $resultQuery['lname'];
        $address = $resultQuery['address'];
        $telephone = $resultQuery['telephone'];
        $email = $resultQuery['email'];
        $role = $resultQuery['role'];
   
        $datalist[] = array(
                "id" => $id,
                "fname" => $fname,
                "lname" => $lname,
                "address" => $address,
                "tel" => $telephone,
                "email" => $email,
                "role" => $role              
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