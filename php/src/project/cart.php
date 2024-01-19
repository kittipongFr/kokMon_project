<?php 
include "head.php";
include "nav.php";
?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    
    checkLogin();
});

</script>
    <!-- Cart Start -->
    <div class="container-fluid">
    <div class="row px-xl-5">
        <!-- Shop Sidebar Start -->
        <div class="col-lg-8 col-md-2">
            <h3 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">ตะกร้าสินค้า</span></h3>
        </div>
        <div class="col-lg-4 col-md-2">
            <h3 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">ตะกร้าสินค้า</span></h3>
        </div>

    </div>

    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-light table-borderless table-hover text-center mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th width="15%">เลือก</th>
                        <th width="15%">สินค้า</th>
                        <th width="15%">ขนาดสินค้า</th>
                        <th width="15%">จำนวน</th>
                        <th width="15%">ราคาสินค้า/หน่วย</th>
                        <th width="15%">ราคาสินค้าทั้งหมด</th>
                        <th width="10%">ลบ</th>
                    </tr>
                </thead>
                <tbody class="align-middle" id="content">
                    <!-- Add rows dynamically based on the cart items -->
                </tbody>
            </table>
            
        </div>
    
        <div class="col-lg-4">

            <div class="bg-light p-30 mb-5">
                <div class="border-bottom pb-2">
                    <div class="d-flex justify-content-between mb-2">
                        <h6>จำนวนรายการสั่งซื้อ</h6>
                        <h5 id="allCount">0 รายการ</h5>
                    </div>
                </div>
                <div class="pt-2 d-flex justify-content-end">
    <a id="exit" href='./index.php' class="btn btn-danger font-weight-bold w-25 my-3 py-3 mx-3 ">กลับ <i class=""></i></a>
    <button id="btnOrder" class="btn btn-success font-weight-bold w-50 my-3 py-3">สั่งซื้อสินค้า</button>
  
</div>
            </div>
   
    </div>
</div>

    <!-- Cart End -->

    


    <script>
function get_environment(){
   
$(document).ready(function() {
    // Check if jQuery is available
    if (typeof jQuery === 'undefined') {
        console.log('jQuery is not loaded.');
        return;
    }

    // Function to update data
    function updateData() {
        // Get data from local storage
        let customer_profile = localStorage.getItem("customer_profile");
        // console.log(customer_profile);
        
        // Check if customer_profile is available
        if (customer_profile) {
            customer_profile = JSON.parse(customer_profile);
            let cus_id = customer_profile.id;
            let cus = { "cus_id": cus_id }
            
            // Check if cus_id is available
            $.ajax({
                type: "POST",
                url: "http://127.0.0.1:8080/project/api/get_count_cart.php",
                data: JSON.stringify(cus),
                async: false,
                success: function(response) {
                    if (response.result === 1) {
                        document.getElementById("cart_count").innerHTML = response.count;
                    }
                },
                error: function(error) {
                    console.log("error");
                }
            });
        }
    }

    // Call the function initially
    updateData();

    // Set interval to update data every 5 seconds
    setInterval(updateData, 1000);
});

getCartDetail();
}
</script>
    <?php
// include "footer.php";


include "cart_list.php";

include "footerjs.php";
    ?>

 