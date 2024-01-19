<?php 
include "head.php";
include "nav.php";
?>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    
    checkLogin();
});

</script>


<style type="text/css">
    	body{
    background:#eee;
}
.card {
    box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: 1rem;
}
.text-reset {
    --bs-text-opacity: 1;
    color: inherit!important;
}
a {
    color: #5465ff;
    text-decoration: none;
}
    </style>
<div class="container-fluid">

  <div class="container">

    <!-- <p class="text-danger"></p>  ใช้คอมเม้น -->
    <div class="d-flex justify-content-between align-items-center py-3">
      <h2 class="h5 mb-0"><a href="#" id="oidH" class="text-muted"></a> </h2>
    </div>

    <div class="row" id="content">
     





      





    </div>
  </div>
</div>





<!-- Modal ยกเลิกorder -->
<div class="modal fade" id="modalCancel" tabindex="-1" aria-labelledby="modalCancelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCancelLabel">ยกเลิกคำสั่งซื้อ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Text Input -->
        <div class="mb-3">
          <label for="textInput"   class="form-label">รหัสคำสั่งซื้อ</label>
          <input type="text" readonly id="cancel_id" class="form-control" >
        </div>
        <!-- Textarea -->
        <div class="mb-3">
          <label for="textareaInput"  class="form-label">เหตุผลการยกเลิก</label>
          <textarea class="form-control"  id="cancel_details" rows="4"></textarea>
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">กลับ</button>
        <button type="button" class="btn btn-danger" onclick="cancelOrder()">บันทึกการยกเลิก</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal โอนเงิน -->
<div class="modal fade" id="modalTransfer" tabindex="-1" aria-labelledby="modalTransferLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTransferLabel">แจ้งชำระ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Text Input -->
  <form id="paymentForm">
        <div class="mb-3">
          <label for="textInput"   class="form-label">รหัสคำสั่งซื้อ</label>
          <input type="text" name="transfer_id" readonly id="transfer_id" class="form-control" >
        </div>
        <!-- Textarea -->
        <div class="mb-3">
          <label for=""  class="form-label">ยอดที่ต้องชำระ</label>
          <input type="text" readonly id="transfer_total_show" class="form-control" >
          <input type="hidden" name="transfer_total" readonly id="transfer_total" class="form-control" >
          
        </div>

        <div class="mb-3">
          <label for=""  class="form-label">ประเภทการชำระ</label>
          <div class="form-check">
  <input class="form-check-input" type="radio" value="0" name="selectPayment" id="selectPayment1" checked onchange="handlePaymentSelection()">
  <label class="form-check-label" for="selectPayment1">
    โอนชำระ
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" value="1" name="selectPayment" id="selectPayment2"  disabled onchange="handlePaymentSelection()">
  <label class="form-check-label" for="selectPayment2">
    Qr Promptpay
  </label>
</div>

          
        </div>
        <div id='transfer' style="display: none;">
        <div class="mb-3" >
          <label for=""  class="form-label">รายละเอียดบัญชี</label><br>
        <span>เลขที่บัญชี : <?php echo $row["bank_num"] ?></span><br>
        <span>ชื่อบัญชี : <?php echo $row["bank_name"] ?></span>
          
        </div>

        <div class="mb-3">
          <label for=""  class="form-label">สลิปการชำระ</label>
          <input type="file"  name="slip" id="slip" class="form-control" accept="image/*" >
          
        </div>
        </div>
      </div>
      <div class="modal-footer">
      
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">กลับ</button>
        <button type="button" class="btn btn-success" onclick="addPayment()">บันทึกการแจ้งโอนชำระ</button>
      </div>
  </form>
    </div>
  </div>
</div>





<!-- Modal ข้อมูลแจ้งโอน -->
<div class="modal fade" id="modalPayment" tabindex="-1" aria-labelledby="modalPaymentLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPaymentLabel">ข้อมูลแจ้งโอนชำระ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='paymentShow'>
        <!-- Text Input -->

        <!-- Textarea -->
    
      </div>
      <div class="modal-footer">
        
      <!-- <button type="button" class="btn btn-danger" onclick="">ยอดชำระไม่ถูกต้อง</button></button>
        <button type="button" class="btn btn-success" onclick="">ยืนยันการชำระ</button> -->
      </div>
    </div>
  </div>
</div>





<!-- Modal การรับเงิน -->
<div class="modal fade" id="modalAcceptMoney" tabindex="-1" aria-labelledby="modalAcceptMoneyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAcceptMoneyLabel">การรับเงิน</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" >
        <div id='acceptMoneyShow'></div>
        <div id='acceptMoneyShowTotal'></div>
      </div>
      
    </div>
  </div>
</div>


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

getOrderDetail();
}



</script>



<script>
  document.addEventListener('DOMContentLoaded', function() {
  // เรียกใช้ฟังก์ชันทันทีเมื่อหน้าเว็บโหลดเสร็จ
  handlePaymentSelection();
});
// ฟังก์ชันที่ถูกเรียกเมื่อมีการเลือก radio button
function handlePaymentSelection() {
  // ตรวจสอบว่า selectPayment1 ถูก checked หรือไม่
  if (document.getElementById('selectPayment1').checked) {
    // ถ้าถูก checked ให้แสดง div id="transfer"
    document.getElementById('transfer').style.display = 'block';
  } else {
    // ถ้าไม่ถูก checked ให้ซ่อน div id="transfer"
    document.getElementById('transfer').style.display = 'none';
  }
}


</script>

    <?php
// include "footer.php";

include "order_detail_list.php";

include "footerjs.php";
    ?>

