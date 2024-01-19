<?php include('h.php');?>


<body  onload="get_environment()" >

    <!-- Main Header -->
    <?php include('menutop.php');?>
    <!-- Left side column. contains the logo and sidebar -->
    
        <?php include('menu_l.php');?>
    
      <section class="content-header">
      <h1   id="oidH"></h1>
  
      </section>
 <!-- <p class="text-danger"></p>  ใช้คอมเม้น -->
      <div class="data_table" >
        <div id="contentDetail" class="row">

 <!-- content here  -->


 
</div>
        </div>





<!-- Modal ยกเลิกorder -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ยกเลิกคำสั่งซื้อ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Text Input -->
        <div class="mb-3">
          <label for="textInput"   class="form-label">รหัสคำสั่งซื้อ</label>
          <input type="text" readonly id="reject_id" class="form-control" >
        </div>
        <!-- Textarea -->
        <div class="mb-3">
          <label for="textareaInput"  class="form-label">เหตุผลการยกเลิก</label>
          <textarea class="form-control"  id="reject_detail" rows="4"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">กลับ</button>
        <button type="button" class="btn btn-danger" onclick="rejectOrder()">บันทึกการปฏิเสธ</button>
      </div>
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
      
      </div>
      <div class="modal-body">
      <div class=''>
           <label for="textInput" style="font-size: 20pt;"  class="form-label">ยอดที่ต้องชำระ : <span class="text-danger" id="confirm_total_txt"></span> บาท</label>

          <input type="hidden" readonly id="confirm_total" class="form-control" >
          </div>
          </div>
      <div class="modal-footer" id='btn-paymentShow'>

      </div>
    </div>
  </div>
</div>







<!-- Modal จัดส่งสินค้า -->
<div class="modal fade" id="shippingModal" tabindex="-1" aria-labelledby="shippingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shippingModalLabel">จัดส่งสินค้า</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Text Input -->
        <div class="mb-3">
          <label for="textInput"   class="form-label">รหัสคำสั่งซื้อ</label>
          <input type="text" readonly id="shipping_id" class="form-control" >
        </div>
        <div class="mb-3">
          <label for="textInput"   class="form-label">ชื่อบริษัทขนส่ง</label>
          <input type="text"  id="shipping_co" class="form-control" >
        </div>
        <!-- Textarea -->
        <div class="mb-3">
          <label for="textareaInput"  class="form-label">หมายเลขพัสดุ</label>
          <input type="text"  id="tracking" class="form-control" >
        </div>
   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">กลับ</button>
        <div id='btn_submit_shipping'></div>
        
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
      
      <div class="modal-body" id="acceptForm">
        <!-- Text Input -->
        <div class="mb-3">
          <label for="textInput"   class="form-label">รหัสคำสั่งซื้อ :</label>
          <input type="text" readonly id="accept_money_id" class="form-control" >
        </div>
        <div class="mb-3">
          <label for="textInput"   class="form-label">ยอดต้องที่ได้รับคงเหลือ :</label>
          <input type="text" readonly  id="accept_money_total_txt" class="form-control" >
          <input type="hidden"  id="accept_money_balance" class="form-control" >
          <input type="hidden"  id="accept_money_total" class="form-control" >
        </div>
        <div class="mb-3">
          <label for="textInput"   class="form-label">ยอดที่ได้รับ :</label>
          <input type="number" required id="accept_money" class="form-control" >
        </div>
      
      </div>
      <div class="modal-footer" id="acceptBtn">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">กลับ</button>
        <button type="button" class="btn btn-success" onclick="acceptMoney()">บันทึกการรับเงิน</button>
      </div>
    </div>
  </div>
</div>

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




<script>
function get_environment(){
   
getOrderDetail();
}



</script>
    <?php

include "order_detail_list.php";

include('footerjsDetail.php');?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>

