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
        //print_r($json_data["email"]);
        $inputEmail= trim($json_data["email"]);
        $inputPassword = trim($json_data["password"]);
        $session = trim($json_data["session"]);
    }else{
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        die();
    }
?>
<?php
    #process
    $strSQL = "SELECT * FROM member WHERE email = '".$inputEmail."' ";
    $query = @mysqli_query($conn,$strSQL);
    $resultQuery = @mysqli_fetch_array($query);
    $datalist = array();
    print_r($resultQuery);
    if(trim($resultQuery['email']) !="" && $inputPassword==$resultQuery['password']){
        print_r("YES");
        $result=1;
        $message = "เข้าสู่ระบบสำเร็จ";
        $datalist = array("id"=>$resultQuery['mem_id'],"fname"=>$resultQuery['fname'],"lname"=>$resultQuery['lname'],"email"=>$resultQuery['email'],"role"=>$resultQuery['role']);
        $strSQL="UPDATE member SET session='".$session."' WHERE email ='".trim($resultQuery['email'])."' ";
        $query = @mysqli_query($conn,$strSQL);
    }else{
        print_r("NO");
        $result=0;
        $message = "เข้าสู่ระบบไม่สำเร็จ";
    }
// ?>
// <?php
    #output
    ob_clean();
    @mysqli_close($conn);
    echo $json_response = json_encode(array("result" => $result, "message" => $message,"datalist"=>$datalist));
    _log_member_login($content,$json_response);
    exit;
?>
<?php
    #log function
    #ใครเรียกใช้งาน #เวลาที่เรียกใช้งาน #ส่งอะไรมา? #เราตอบอะไรกลับ?
    function _log_member_login($content,$json_response){
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = @date("Y-m-d H:i:s");
        $_log = "\n".$date."".$ip." request:".$content." response:".$json_response;
        $objFopen = @fopen("../log/_log_member_login.log","a+");
        @fwrite($objFopen,$_log);
        @fclose($objFopen);
    }
// ?>