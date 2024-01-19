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
        $strSQL = "SELECT mft.manufacture_id, mft.date, mft.mem_id,
                        m.fname, m.lname 
                    FROM manufacture AS mft
                    JOIN member AS m ON mft.mem_id = m.mem_id
                    WHERE mft.manufacture_id = ?";

        $stmt = mysqli_prepare($conn, $strSQL);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id);

            mysqli_stmt_execute($stmt);

            $query = mysqli_stmt_get_result($stmt);

            $datalist = array('manufacture' => array(), 'manufacture_detail' => array(), 'product' => array());

            if ($resultQuery = mysqli_fetch_array($query)) {
                # Fetch data from manufacture
                $mft_id = $resultQuery['manufacture_id'];
                $date = $resultQuery['date'];
                $mem_id = $resultQuery['mem_id'];
                $mem_fname = $resultQuery['fname'];
                $mem_lname = $resultQuery['lname'];

                $datalist['manufacture'] = array(
                    "mft_id" => $mft_id,
                    "date" => $date,
                    "mem_id" => $mem_id,
                    "mem_fname" => $mem_fname,
                    "mem_lname" => $mem_lname
                );
            }

            mysqli_stmt_close($stmt);

            if (!empty($datalist['manufacture'])) {
                # Fetch data from manufacture_detail
                $strSQLDetail = "SELECT mft.pro_id, mft.amount,
                                        p.name AS pro_name, p.unit AS pro_unit 
                                    FROM manufacture_detail AS mft 
                                    JOIN product AS p ON mft.pro_id = p.pro_id
                                    WHERE mft.manufacture_id = ? ";

                $stmtDetail = mysqli_prepare($conn, $strSQLDetail);

                if ($stmtDetail) {
                    mysqli_stmt_bind_param($stmtDetail, "s", $id);

                    mysqli_stmt_execute($stmtDetail);

                    $queryDetail = mysqli_stmt_get_result($stmtDetail);

                    while ($resultQueryDetail = mysqli_fetch_array($queryDetail)) {
                        $pro_id = $resultQueryDetail['pro_id'];
                        $pro_name = $resultQueryDetail['pro_name'];
                        $pro_unit = $resultQueryDetail['pro_unit'];
                        $amount = $resultQueryDetail['amount'];

                        $datalist['manufacture_detail'][] = array(
                            "pro_id" => $pro_id,
                            "pro_name" => $pro_name,
                            "pro_unit" => $pro_unit,
                            "amount" => $amount
                        );
                    }

                    mysqli_stmt_close($stmtDetail);

                    # Fetch additional data from Product table
                    $strSQLProduct = "SELECT pro_id, name, unit FROM product";
                    $queryProduct = mysqli_query($conn, $strSQLProduct);

                    while ($resultQueryProduct = mysqli_fetch_assoc($queryProduct)) {
                        $pro_id = $resultQueryProduct['pro_id'];
                        $name = $resultQueryProduct['name'];
                        $unit = $resultQueryProduct['unit'];

                        $datalist['product'][] = array(
                            "pro_id" => $pro_id,
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
