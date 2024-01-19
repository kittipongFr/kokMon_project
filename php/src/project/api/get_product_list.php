<?php ob_start(); ?>
<?php
    #header
    @header('Content-Type: application/json');
    @header("Access-Control-Allow-Origin: *");
    @header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
?>
<?php
    #connection and data include  OR require
    require ("../config/config_db.php");
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
    $strSQL = "SELECT product.pro_id, product.name,product.amount_reserve, product.detail,product.unit,  product.amount, product.img, price.price, price.amount_conditions 
    FROM product 
    JOIN price ON product.pro_id = price.pro_id";
    
    $query = mysqli_query($conn, $strSQL);
    $datalist = array();
    
    while ($resultQuery = mysqli_fetch_array($query)) {
        $id = $resultQuery['pro_id'];
        $name = $resultQuery['name'];
        $detail = $resultQuery['detail'];
        $amount = $resultQuery['amount'];
        $reserve = $resultQuery['amount_reserve'];
        $unit = $resultQuery['unit'];
        $img = $resultQuery['img'];
    
        if (!isset($datalist[$id])) {
            $datalist[$id] = array(
                "id" => $id,
                "name" => $name,
                "prices" => array(),
                "amount" => $amount,
                "reserve"=>$reserve,
                "unit" => $unit,
                "img" => $img,
                "detail" => $detail,
                "amount_conditions" => array()
            );
        }
    
        $datalist[$id]["prices"][] = $resultQuery['price']; // เพิ่มราคาเข้าไปในอาร์เรย์ที่เก็บราคาของ pro_id นั้น
        $datalist[$id]["amount_conditions"][] = $resultQuery['amount_conditions']; // เพิ่มเงื่อนไขเข้าไปในอาร์เรย์ที่เก็บเงื่อนไขของ pro_id นั้น
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