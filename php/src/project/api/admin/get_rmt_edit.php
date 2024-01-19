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
        $strSQL = "SELECT rmt.receive_material_id, rmt.supply_name, rmt.mem_id,
                        m.fname, m.lname 
                    FROM receive_material AS rmt
                    JOIN member AS m ON rmt.mem_id = m.mem_id
                    WHERE rmt.receive_material_id = ?" ;

        $stmt = mysqli_prepare($conn, $strSQL);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id);

            mysqli_stmt_execute($stmt);

            $query = mysqli_stmt_get_result($stmt);

            $datalist = array('receive_material' => array(), 'receive_material_detail' => array());

            if ($resultQuery = mysqli_fetch_array($query)) {
                # Fetch data from receive_material
                $rmt_id = $resultQuery['receive_material_id'];
                $supply_name = $resultQuery['supply_name'];
                $mem_id = $resultQuery['mem_id'];
                $mem_fname = $resultQuery['fname'];
                $mem_lname = $resultQuery['lname'];

                $datalist['receive_material'] = array(
                    "rmt_id" => $rmt_id,
                    "supply_name" => $supply_name,
                    "mem_id" => $mem_id,
                    "mem_fname" => $mem_fname,
                    "mem_lname" => $mem_lname
                );
            }

            mysqli_stmt_close($stmt);

            if (!empty($datalist['receive_material'])) {
                # Fetch data from receive_material_detail
                $strSQLDetail = "SELECT rmd.material_id, rmd.amount, rmd.net, rmd.price,
                                        m.name AS material_name, m.unit AS material_unit
                                    FROM receive_material_detail AS rmd
                                    JOIN material AS m ON rmd.material_id = m.material_id
                                    WHERE rmd.receive_material_id = ? ";

                $stmtDetail = mysqli_prepare($conn, $strSQLDetail);

                if ($stmtDetail) {
                    mysqli_stmt_bind_param($stmtDetail, "s", $id);

                    mysqli_stmt_execute($stmtDetail);

                    $queryDetail = mysqli_stmt_get_result($stmtDetail);

                    while ($resultQueryDetail = mysqli_fetch_array($queryDetail)) {
                        $mt_id = $resultQueryDetail['material_id'];
                        $mt_name = $resultQueryDetail['material_name'];
                        $mt_unit = $resultQueryDetail['material_unit'];
                        $amount = $resultQueryDetail['amount'];
                        $net = $resultQueryDetail['net'];
                        $price = $resultQueryDetail['price'];

                        $datalist['receive_material_detail'][] = array(
                            "mt_id" => $mt_id,
                            "mt_name" => $mt_name,
                            "mt_unit" => $mt_unit,
                            "amount" => $amount,
                            "net" => $net,
                            "price" => $price
                        );
                    }

                    mysqli_stmt_close($stmtDetail);

                    # Fetch additional data from material table
                    $strSQLMaterial = "SELECT material_id, name, unit FROM material";
                    $queryMaterial = mysqli_query($conn, $strSQLMaterial);

                    while ($resultQueryMaterial = mysqli_fetch_array($queryMaterial)) {
                        $mt_id = $resultQueryMaterial['material_id'];
                        $mt_name = $resultQueryMaterial['name'];
                        $mt_unit = $resultQueryMaterial['unit'];

                        $datalist['material'][] = array(
                            "mt_id" => $mt_id,
                            "mt_name" => $mt_name,
                            "mt_unit" => $mt_unit
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
