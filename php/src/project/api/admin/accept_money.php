<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

require("../../config/config_db.php");

// ตรวจสอบว่ามีข้อมูลที่ถูกส่งมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีไฟล์รูปที่ส่งมาหรือไม่
    $data = json_decode(file_get_contents("php://input"), true);

    $pay = isset($data['accept_money']) ? $data['accept_money'] : 0;
    $accept_total = isset($data['accept_money_total']) ? $data['accept_money_total'] : 0;
    $order_id = isset($data['id']) ? $data['id'] : '';
    $am_id = "";
    $sum_accept_money = 0 ;
    $selectQueryAccept = "SELECT sum(accept_money_total) AS sum_accept_money FROM accept_money WHERE order_id = '$order_id' ";
    $resultAccept = $conn->query($selectQueryAccept);

    if ($resultAccept->num_rows > 0) {
        $rowAccept = $resultAccept->fetch_assoc();
        $sum_accept_money = $rowAccept['sum_accept_money'];
    }


    $selectQuery = "SELECT accept_money_id FROM accept_money ORDER BY accept_money_id DESC LIMIT 1";
    $result = $conn->query($selectQuery);

    if ($result->num_rows > 0) {
        // Fetch the manufacture_id
        $row = $result->fetch_assoc();
        $accept_money_id = $row['accept_money_id'];

        // Extract the date part
        $dateSplit = substr($accept_money_id, 2, 6);

        // Extract the numeric part
        $numericPart = (int)substr($accept_money_id, 8);

        // Format the current date with only the last two digits of the year
        $currentDate = date("ymd");

        if ($dateSplit == $currentDate) {
            $new_id = $numericPart + 1;
            $formatted_id = "am" . $currentDate . sprintf("%05d", $new_id);
        } else {
            $formatted_id = "am" . $currentDate . "00001";
        }

        
    } else {
        $currentDate = date("ymd");
        $formatted_id = "am" . $currentDate . "00001";
        
    }

    $am_id = $formatted_id;



if($accept_total > ($sum_accept_money+$pay)){


        $stmt = $conn->prepare('INSERT INTO accept_money (accept_money_id, order_id, accept_money_total) VALUES (?, ?, ?)');
        $stmt->bind_param('ssd', $am_id, $order_id, $pay);
        $stmt->execute();
        
        // Check for errors
        if ($stmt->error) {
            echo json_encode(array("result" => 0, "message" => "บันทึกการรับเงินไม่สำเร็จ : " . $stmt->error));
            die();
        }

            $strUpdateCommu = "UPDATE community_enterprise  SET aml_fund = aml_fund+$pay WHERE id = 1";
            $queryCommu = mysqli_query($conn, $strUpdateCommu);
          
            if ($queryCommu) {
                echo json_encode(array("result" => 1, "message" => "บันทึกการรับเงินสำเร็จ ยอดคงเหลือ : ".$accept_total - ($sum_accept_money+$pay)." บาท"));
            }else {
                echo json_encode(array("result" => 0, "message" => "บันทึกการรับเงินไม่สำเร็จ","error" => mysqli_error($conn)));
                die();
            }


        }else{
    
            $stmt = $conn->prepare('INSERT INTO accept_money (accept_money_id, order_id, accept_money_total) VALUES (?, ?, ?)');
            $stmt->bind_param('ssd', $am_id, $order_id, $pay);
            $stmt->execute();
            
            // Check for errors
            if ($stmt->error) {
                echo json_encode(array("result" => 0, "message" => "บันทึกการรับเงินไม่สำเร็จ : " . $stmt->error));
                die();
            }
    
                $strUpdateCommu = "UPDATE community_enterprise  SET aml_fund = aml_fund+$pay WHERE id = 1";
                $queryCommu = mysqli_query($conn, $strUpdateCommu);
                if (!$queryCommu) {
                    echo json_encode(array("result" => 0, "message" => "บันทึกการรับเงินไม่สำเร็จ","error" => mysqli_error($conn)));
                    die();
                }
                $strUpdate = "UPDATE orders SET status = '10' WHERE order_id = '$order_id'";
                $query = mysqli_query($conn, $strUpdate);
                if ($query) {
                    echo json_encode(array("result" => 1, "message" => "บันทึกการรับเงินสำเร็จ ยอดชำระครบแล้ว"));
                }else {
                    echo json_encode(array("result" => 0, "message" => "บันทึกการรับเงินไม่สำเร็จ","error" => mysqli_error($conn)));
                    die();
                }

        }    

            
      // ปิดการเชื่อมต่อกับฐานข้อมูล
      mysqli_close($conn);

    } else {
        echo json_encode(array("result" => 0, "message" => "ไม่มีmethod post"));
    }

?>
