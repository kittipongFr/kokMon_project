<?php
ob_start();

#header
@header('Content-Type: application/json');
@header("Access-Control-Allow-Origin: *");
@header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

#connection and data include OR require
require("../../config/config_db.php");

#input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $mtu_id = isset($data['mtu_id']) ? $data['mtu_id'] : '';
    $mt_id = isset($data['mt_id']) ? $data['mt_id'] : '';
    $amount = isset($data['amount']) ? $data['amount'] : '';
    $old_amount = isset($data['old_amount']) ? $data['old_amount'] : '';




    if ($amount != $old_amount ) {


        mysqli_begin_transaction($conn);
        try {

    
            // Update material
            $stmt2 = $conn->prepare("UPDATE material SET amount = (amount + ?) - ? WHERE material_id = ?");
            $stmt2->bind_param("dds", $old_amount, $amount, $mt_id);
            $stmt2->execute();
            if (!$stmt2) {
                echo json_encode(array("result" => 0, "message" => "แก้ไขไม่สำเร็จ "));
            }
            $stmt2->close();
    

            // Fetch eligible records from receive_material_detail
            $cost = 0;
            $check_old_amount = $old_amount;
            $check_amount = $amount;
            $fetchQuery = "SELECT 
            rmd.receive_material_id, 
            rmd.price, 
            rmd.net, 
            (SELECT SUM(net) FROM receive_material_detail WHERE material_id = ? AND amount != 0) as sumNet,
            rmd.amount, 
            (SELECT SUM(amount) FROM receive_material_detail WHERE material_id = ? AND amount != 0) as sumAmount, 
            rm.date 
            FROM receive_material_detail AS rmd
            JOIN receive_material AS rm ON rmd.receive_material_id = rm.receive_material_id
            WHERE rmd.material_id = ? AND rmd.amount != rmd.net 
            ORDER BY rm.date DESC";
        
            $stmtFetch = $conn->prepare($fetchQuery);
            $stmtFetch->bind_param('sss', $mt_id, $mt_id, $mt_id);
        
            if (!$stmtFetch->execute()) {
                throw new Exception("Fetch query execution failed: " . $stmtFetch->error);
            }
        
            $resultFetch = $stmtFetch->get_result();
            while ($row = $resultFetch->fetch_assoc()) {
                // Update receive_material_detail
        
                if($check_old_amount>$row["sumAmount"]-$row["sumNet"]){
                    echo json_encode(array("result" => 0, "message" => "จำนวนวัตถุดิบเกินจำนวนรับ  วัตถุดิบรับ : ".$row["sumAmount"] ));
                    die();
                }
                if($check_amount>($row["sumNet"]+$check_old_amount)){
                    echo json_encode(array("result" => 0, "message" => "จำนวนวัตถุดิบไม่เพียงพอ  วัตถุดิบคงเหลือ : ".$row["sumNet"]+$check_old_amount ));
                    die();
                }       
                $update_amount = $old_amount;
                $new_net = $row['net'] + $update_amount;

                if ($new_net >= $row['amount']) {
                    $update_amount = $row['amount'] - $row['net'];
                    $new_net = $row['amount'];
                }
        
                $updateQuery = "UPDATE receive_material_detail SET net = ? WHERE receive_material_id = ?";
                $stmtUpdate = $conn->prepare($updateQuery);
                $stmtUpdate->bind_param('ds', $new_net, $row['receive_material_id']);
        
                if (!$stmtUpdate->execute()) {
                    throw new Exception("Update query execution failed: " . $stmtUpdate->error);
                }
        
                // Update the remaining amount to reduce
                $old_amount -= $update_amount;
        
        
                // Exit the loop if the amount becomes 0
                if ($old_amount <= 0) {
                    break;
                }
            }
                    // Fetch eligible records from receive_material_detail
            $fetchQuery = "SELECT rmd.receive_material_id, rmd.price, rmd.net, rmd.amount, rm.date 
            FROM receive_material_detail AS rmd
            JOIN receive_material AS rm ON rmd.receive_material_id = rm.receive_material_id
            WHERE rmd.material_id = ? AND rmd.amount != 0
            ORDER BY rm.date ASC";

            $stmtFetch = $conn->prepare($fetchQuery);
            $stmtFetch->bind_param('s', $mt_id);

            if (!$stmtFetch->execute()) {
            throw new Exception("Fetch query execution failed: " . $stmtFetch->error);
            }

            $resultFetch = $stmtFetch->get_result();

            while ($row = $resultFetch->fetch_assoc()) {
            $update_amount = $amount;

            $totalCost = $row['price'] * min($amount, $row['net']);
            $cost += $totalCost;
            $new_net = $row['net'] - $update_amount;

            if ($new_net < 0) {
            $update_amount =  $amount - ($update_amount - $row['net']);
            $new_net = 0;
            }

            $updateQuery = "UPDATE receive_material_detail SET net = ? WHERE receive_material_id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param('ds', $new_net, $row['receive_material_id']);

            if (!$stmtUpdate->execute()) {
            throw new Exception("Update query execution failed: " . $stmtUpdate->error);
            }

            // Update the remaining amount to reduce
            $amount -= $update_amount;
            // Exit the loop if the amount becomes 0
            if ($amount <= 0) {
            break;
            }
            }

            $stmt1 = $conn->prepare("UPDATE material_used_detail SET amount = ? ,cost =? WHERE material_used_id = ? AND material_id = ?");
            $stmt1->bind_param("ddss", $check_amount,$cost, $mtu_id, $mt_id);
            $stmt1->execute();
            if (!$stmt1) {
                echo json_encode(array("result" => 0, "message" => "แก้ไขไม่สำเร็จ "));
            }
    
            $stmt1->close();

        mysqli_commit($conn);
        echo json_encode(array("result" => 1, "message" => "Successfully updated."));
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(array("result" => 0, "message" => "Error: " . $e->getMessage()));
    }finally {
        // Close prepared statements and the database connection
        if (isset($stmtFetch)) {
            $stmtFetch->close();
        }
    
        if (isset($stmtUpdate)) {
            $stmtUpdate->close();
        }
    }






    }else{
        $sql = "UPDATE material_used_detail SET
        amount = '$amount'
        WHERE  material_used_id = '$mtu_id' AND material_id = '$mt_id' ";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo json_encode(array("result" => 1, "message" => "แก้ไขเรียบร้อย"));
        } else {
            echo json_encode(array("result" => 0, "message" => "แก้ไขไม่สำเร็จ "));
        }
    }


// Insert data into the receive_material_used table

$conn->close();
// Check for errors

// Output
}else{
    echo json_encode(array("result" => 0, "message" => "ไม่มี Method Post"));
}
?>
