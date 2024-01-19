<?php include "head.php"; ?>
<?php include "nav.php"; ?>

<style>
    /* CSS for Desktop */
    @media only screen and (min-width: 768px) {
        .hide-on-mobile {
            display: table-cell !important;
        }
    }

    /* CSS for Mobile */
    @media only screen and (max-width: 767px) {
        .hide-on-mobile {
            display: none !important;
        }
    }
</style>


<!-- check login in head.php -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    
    checkLogin();
});

</script>



<!-- Cart Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <!-- Shop Sidebar Start -->
        <div class="col-lg-12 col-md-2">
            <h3 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">ประวัติการสั่งซื้อ</span></h3>
        </div>
    </div>
    <div class="row px-xl-5">
        <div class="col-lg-12 table-responsive mb-5">
            <table class="table table-light table-borderless table-hover text-center mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th class="d-none d-md-table-cell">วันที่</th>
                        <th>รหัส</th>
                        <th class="d-none d-md-table-cell">จำนวน</th>
                        <th>ราคารวม</th>
                        <th>สถานะ</th>
                        <th>รายละเอียด</th>
                    </tr>
                </thead>
                <tbody class="align-middle" id="content">
                    <!-- ข้อมูลจะถูกเพิ่มที่นี่โดยใช้ JavaScript -->
                </tbody>
            </table>
            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script type="text/javascript">

    // เพิ่มตัวแปร global สำหรับเก็บข้อมูลทั้งหมด
    let allOrders = [];
    function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
    function getOrderHistoryList() {
        let uri = "http://127.0.0.1:8080/project/api/get_history_list.php";
        let customer_profile = localStorage.getItem("customer_profile");
        customer_profile = JSON.parse(customer_profile);
        let cus_id = customer_profile.id;
        let statusShow = ``;

        let dataId = {
            "cus_id": cus_id
        };

        $.ajax({
            type: "POST",
            url: uri,
            async: false,
            data: JSON.stringify(dataId),
            success: function(response) {
                if (response.result == 1) {
                    // ให้ allOrders เก็บข้อมูลทั้งหมด
                  
                    allOrders = response.datalist;
                    // เรียกฟังก์ชันเพื่อแสดงข้อมูลหน้าแรก
                    showPage(1);
                    // เรียกฟังก์ชันเพื่อสร้าง Pagination
                    buildPagination();
                    
                } else {
                    document.getElementById("content").innerHTML = "<h4 class='text-danger'>ไม่มีคำสั่งซื้อ</h4>";
                    console.log(response.message);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // ฟังก์ชันแสดงข้อมูลตามหน้าที่กำหนด
    function showPage(pageNumber) {
        let itemsPerPage = 5;
        let startIndex = (pageNumber - 1) * itemsPerPage;
        let endIndex = startIndex + itemsPerPage;
        let pageData = allOrders.slice(startIndex, endIndex);

        let innerhtml = "";
        for (let order of pageData) {
            if (order.status === "0") {
            statusShow = `<span class='badge rounded-pill  text-black' style='background-color:yellow'>รอการอนุมัติคำสั่งซื้อ</span>`;
            } else if (order.status === "1") {
            statusShow = `<span class='badge rounded-pill ' style='background-color:green'>อนุมัติคำสั่งซื้อแล้ว</span>`;
            } 
            else if (order.status === "2") {
            statusShow = `<span class='badge rounded-pill text-black' style='background-color:yellow'>รอยืนยันการชำระ</span>`;
            }else if (order.status === "3") {
            statusShow = `<span class='badge rounded-pill text-black' style='background-color:yellow'>รอการจัดส่งสินค้า</span>`;
            }else if (order.status === "4") {
            statusShow = `<span class='badge rounded-pill text-black' style='background-color:yellow'>จัดส่งแล้ว รอลูกค้ายืนยัน</span>`;
             
            }else if (order.status === "5") {
            statusShow = `<span class='badge rounded-pill text-black' style='background-color:yellow'>รอลูกค้ามารับที่ร้าน</span>`;
             
            }else if (order.status === "6") {
            statusShow = `<span class='badge rounded-pill text-black' style='background-color:yellow'>รอการบันทึกการรับเงิน</span>`;
            }         
            //ส่วนที่เหลือ
            else if (order.status === "7") {
            statusShow = `<span class='badge rounded-pill ' style='background-color:red'>ยกเลิกคำสั่งซื้อแล้ว</span>`;
            }
            else if (order.status === "8") {
            statusShow = `<span class='badge rounded-pill ' style='background-color:red'>ปฏิเสธคำซื้อแล้ว</span>`;
            }
            else if (order.status === "9") {
            statusShow = `<span class='badge rounded-pill ' style='background-color:red'>รอการชำระให้สมบูรณ์</span>`;
            }else if (order.status === "10") {
            statusShow = `<span class='badge rounded-pill ' style='background-color:green'>คำสั่งซื้อเสร็จสิ้น</span>`;
            }

            else{
            statusShow = `<span class='badge rounded-pill ' style='background-color:red'>สถานะส่วนที่เหลือ</span>`;
            }
          


            innerhtml += `
                <tr>
                    <td class='align-middle d-none d-md-table-cell'>${order.date}</td>
                    <td class='align-middle'>${order.order_id}</td>
                    <td class='align-middle d-none d-md-table-cell'>${order.count}</td>
                    <td class='align-middle'>${numberWithCommas(order.total)}</td>
                    <td class='align-middle' id='status'>${statusShow}</td>
                    <td class='align-middle'>
                        <a class='btn btn-sm btn-info' href='http://127.0.0.1:8080/project/order_detail.php?order_id=${order.order_id}'>
                            <i class='fa fa-search'></i>
                        </a>
                    </td>
                </tr>`;
        }

        document.getElementById("content").innerHTML = innerhtml;
    }

    // ฟังก์ชันสร้าง Pagination
    function buildPagination() {
        let itemsPerPage = 5;
        let totalPages = Math.ceil(allOrders.length / itemsPerPage);
        let pagination = $("#pagination");

        for (let i = 1; i <= totalPages; i++) {
            let li = $("<li class='page-item'><a class='page-link' href='#'>" + i + "</a></li>");
            li.click(function() {
                showPage(i);
            });
            pagination.append(li);
        }
    }

    // เรียกฟังก์ชันเพื่อแสดงข้อมูลและสร้าง Pagination
    getOrderHistoryList();

</script>

<?php
// include "footer.php";
include "order_detail_list.php";
include "footerjs.php";
?>
