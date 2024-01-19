<?php 
include "head.php";
include "nav.php";
?>
<script>
if (localStorage.getItem("dataOrderList")) {
    console.log("มีสินค้า");
} else {
        alert("ไม่มีรายการสินค้า");       
    window.location.href = "http://127.0.0.1:8080/project/index.php";
}
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    
    checkLogin();
});

</script>

    <!-- Cart Start -->
    <div class="container-fluid" >
    <div class='row px-xl-5'>
            <!-- Shop Sidebar Start -->
            <div class='col-lg-8 col-md-2'>
                <h3 class='section-title position-relative text-uppercase mb-3'><span class='bg-secondary pr-3'>ตะกร้าสินค้า</span></h3>
            </div>
            <div class="col-lg-4 col-md-2">
            <h3 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">ข้อมูลการสั่งซื้อ</span></h3>
        </div>

        </div>
        <div class='row px-xl-5'>
            <div class='col-lg-8 table-responsive mb-5'>
                <table class='table table-light table-borderless table-hover text-center mb-0'>
                    <thead class='thead-dark'>
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
                    <tbody class='align-middle' id="content">



        </tbody>
                </table>
            </div>
            <div class="col-lg-4">

    <div class="bg-light p-30 mb-5">
        
           
            <table class='table table-light table-borderless border'>
            <tbody class='align-middle'>
            <tr>
            <td> <h5>จำนวนสินค้า</h5></td>
            <td><h5 id="allAmount" class="text-danger text-end">0 ก.ก</h5></td>
            <td>กิโลกรัม</td>
            </tr>

            <tr>
            <td> <h5>ราคารวม</h5></td>
            <td><h5 id="allTotal" class="text-danger text-end">0 บาท</h5></td>
            <td>บาท</td>
            </tr>

            <tr>
            <td> <h5>ค่าส่ง</h5></td>
            <td><h5 id="allShipping" class="text-danger text-end">0 บาท</h5></td>
            <td>บาท</td>
            </tr>

            <tr>
            <td> <h5>ราคาสุทธิ</h5></td>
            <td><h5 id="allNet" class="text-danger text-end">0 บาท</h5></td>
            <td>บาท</td>
            </tr>


            </tbody>
            </table>
             
      

     
        <div class="row border-bottom pb-2 mb-2 ">
        <div class="col-6 border-right">
    <h5>ประเภทการชำระ</h5>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="paymentMethod" id="transfer" value="0" checked onchange="handlePaymentMethodChange()">
        <label class="form-check-label" id="labelTransfer" for="transfer">
            โอนชำระ
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="1">
        <label class="form-check-label" for="cod">
            เก็บเงินปลายทาง/ชำระเมื่อรับสินค้า
        </label>
    </div>
</div>

<div class="col-6">
    <h5>ประเภทการจัดส่ง</h5>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="shippingMethod" id="shippingAdd" onclick="handlePaymentMethodChange1()" value="0" checked>
        <label class="form-check-label" for="shippingAdd">
            จัดส่งตามที่อยู่
        </label>
    </div>
    
    <div class="form-check">
        <input class="form-check-input" type="radio" name="shippingMethod" id="pickUpStore"  onchange="handlePaymentMethodChange()" value="1">
        <label class="form-check-label" for="pickUpStore">
            รับหน้าร้าน
        </label>
    </div>
</div>




            
      
</div>


        </div>

        <label for="cus_fname"><b>เลือกที่อยู่</b></label>
<button class="btn btn-info mx-2" id="openFormBtn">เพิ่มที่อยู่ <i class="fas fa-plus"></i></button>
        <select class="form-control mb-2 mt-2" id="addressSelect" aria-label="Default select example">
 
</select>

        <form name="sentMessage" id="contactForm" novalidate="novalidate">
    <input type="hidden" class="form-control" id="cus_id" />
    
    <div class="control-group">
        <label for="cus_fname">ชื่อ-สกุล</label>
        <div class="input-group">
            <input type="text" class="form-control" readonly  id="cus_name" placeholder="ชื่อ"
                required="required" data-validation-required-message="โปรดเลือกที่อยู่ของคุณ" />
        </div>
    </div>
    
    <div class="control-group">
        <label for="cus_tel">เบอร์โทร</label>
        <input type="text" class="form-control" id="cus_tel" placeholder="เบอร์โทร"
            required="required" readonly data-validation-required-message="โปรดเลือกที่อยู่ของคุณ" />
        <p class="help-block text-danger"></p>
    </div>
    
    <div class="control-group">
        <label for="address">ที่อยู่</label>
        <textarea class="form-control" readonly rows="6" id="cus_address" placeholder="ที่อยู่"
            required="required" data-validation-required-message="โปรดเลือกที่อยู่ของคุณ"></textarea>
        <p class="help-block text-danger"></p>
    </div>
    <input type="hidden" class="form-control" value="<?php echo $row["shipping_rate"]  ?>" id="shippingRate">
    <button id="btnOrder" onclick="setOrder()" class="btn btn-block btn-success font-weight-bold my-3 py-3">สั่งชื้อสินค้า</button>
    <button id="btnCancelOrder" class="btn btn-block btn-danger font-weight-bold my-3 py-3">ยกเลิก</button>
</form>

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

include "popupAddress.php";
include "oder_list.php";

include "footerjs.php";
    ?>

