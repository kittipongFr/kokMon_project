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
    $content = @file_get_contents('php://input');
    $json_data = @json_decode($content, true);

    if (isset($json_data["id"])) {
        $id = trim($json_data["id"]);

        #process
        $strSQL = "SELECT mtu.material_used_id, mtu.date, mtu.mem_id,
                        m.fname, m.lname 
                    FROM material_used AS mtu
                    JOIN member AS m ON mtu.mem_id = m.mem_id
                    WHERE mtu.material_used_id = ?";

        $stmt = mysqli_prepare($conn, $strSQL);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id);

            mysqli_stmt_execute($stmt);

            $query = mysqli_stmt_get_result($stmt);

            $datalist = array('material_used' => array(), 'material_used_detail' => array(), 'material' => array());

            if ($resultQuery = mysqli_fetch_array($query)) {
                # Fetch data from material_used
                $mtu_id = $resultQuery['material_used_id'];
                $date = $resultQuery['date'];
                $mem_id = $resultQuery['mem_id'];
                $mem_fname = $resultQuery['fname'];
                $mem_lname = $resultQuery['lname'];

                $datalist['material_used'] = array(
                    "mtu_id" => $mtu_id,
                    "date" => $date,
                    "mem_id" => $mem_id,
                    "mem_fname" => $mem_fname,
                    "mem_lname" => $mem_lname
                );
            }

            mysqli_stmt_close($stmt);

            if (!empty($datalist['material_used'])) {
                # Fetch data from material_used_detail
                $strSQLDetail = "SELECT mtu.material_id, mtu.amount,
                                        mt.name AS mt_name, mt.unit AS mt_unit ,mtu.cost
                                    FROM material_used_detail AS mtu 
                                    JOIN material AS mt ON mtu.material_id = mt.material_id
                                    WHERE mtu.material_used_id = ? ";

                $stmtDetail = mysqli_prepare($conn, $strSQLDetail);

                if ($stmtDetail) {
                    mysqli_stmt_bind_param($stmtDetail, "s", $id);

                    mysqli_stmt_execute($stmtDetail);

                    $queryDetail = mysqli_stmt_get_result($stmtDetail);

                    while ($resultQueryDetail = mysqli_fetch_array($queryDetail)) {
                        $material_id = $resultQueryDetail['material_id'];
                        $pro_name = $resultQueryDetail['mt_name'];
                        $pro_unit = $resultQueryDetail['mt_unit'];
                        $amount = $resultQueryDetail['amount'];
                        $cost = $resultQueryDetail['cost'];



                        $datalist['material_used_detail'][] = array(
                            "material_id" => $material_id,
                            "pro_name" => $pro_name,
                            "pro_unit" => $pro_unit,
                            "amount" => $amount,
                            "cost" => $cost
                        );
                    }

                    mysqli_stmt_close($stmtDetail);

                    # Fetch additional data from Material table
                    $strSQLMaterial = "SELECT material_id, name, unit FROM material";
                    $queryMaterial = mysqli_query($conn, $strSQLMaterial);

                    while ($resultQueryMaterial = mysqli_fetch_assoc($queryMaterial)) {
                        $material_id = $resultQueryMaterial['material_id'];
                        $name = $resultQueryMaterial['name'];
                        $unit = $resultQueryMaterial['unit'];
                      
                        $datalist['material'][] = array(
                            "material_id" => $material_id,
                            "name" => $name,
                            "unit" => $unit
                           
                        );
                    }

                    #output
                    @mysqli_close($conn);
                    echo json_encode(array("result" => 1, "message" => "พบข้อมูล", "datalist" => $datalist));
                    exit;
                } else {
                    echo json_encode(array("result" => 0, "message" => "Error preparing statement (detail): " . mysqli_error($conn), "datalist" => null));
                }
            } else {
                echo json_encode(array("result" => 0, "message" => "ไม่พบข้อมูล", "datalist" => null));
            }
        } else {
            echo json_encode(array("result" => 0, "message" => "Error preparing statement: " . mysqli_error($conn), "datalist" => null));
        }
    } else {
        ob_end_clean();
        @header("HTTP/1.0 412 Precondition Failed");
        echo json_encode(array("result" => 0, "message" => "Invalid input data", "datalist" => null));
    }
} else {
    ob_end_clean();
    @header("HTTP/1.0 412 Precondition Failed");
    echo json_encode(array("result" => 0, "message" => "Invalid request method", "datalist" => null));
}
?>
