<?php include('h.php');?>
<body  onload="getEnvironment()">
  


    
        <?php include('menu_l.php');?>

 
  <section class="content-header">
      <h1>
        <i class="glyphicon glyphicon-user hidden-xs"></i> <span class="hidden-xs">ข้อมูลสินค้าในระบบ</span>
        <a  class="btn btn-primary btn-sm text-white"   id="openFormBtn">เพิ่มสินค้า <i class="fas fa-plus"></i></a>   
        <!-- href="product.php?act=add" -->
        </h1>
         
      </section>


      <div class="data_table" >
        <div id="contentDetail" class="row">

 <!-- content here  -->

</div>
        </div>



<?php
               
  include('product_list.php');
  include('popupAddPro.php');
  include('popupEditPro.php');

  ?> 


<!-- Modal จัดการราคา -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">จัดการราคา</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <input type='text'  readonly  id='add_pro_id' class='form-control ' > 
        <!-- Text Input -->
        <div class="mb-3" id="contentPriceList">



      </div>
      <div class="mb-3">
    
      <div class='row'>
      <label for=''   class='form-label'><h5>เพิ่มรายการราคา :</h5></label>
          <div class='col'>
          <label for='conditions'   class='form-label'>จำนวนสั่งซื้อขั้นต่ำ :</label>
            <input type='number'    id='add_conditions' class='form-control ' > 
            </div>
    
      <div class='col'>
          <label for='conditions'   class='form-label'>ราคา :</label>
            <input type='number'    id='add_price' class='form-control ' > 
            </div>
      </div>
      <button type="button" class="btn btn-success float-end mt-2" onclick="addPrice()">เพิ่ม <i class="fas fa-plus"></i></button>
        
    </div>
    
  </div>

</div>
  </div>





<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
    <script>
        document.getElementById("openFormBtn").addEventListener("click", function() {
      document.querySelector(".form-popup").style.display = "block";
    });

    function closeForm() {
  document.querySelector(".form-popup").style.display = "none";
  
  
}
    </script>

<script>
    
    function closeForm() {
  document.querySelector(".form-popup1").style.display = "none";
  
}
    </script>



<script type=text/javascript>
    function getEnvironment(){
        
        addProduct();
        editProduct();
        getProductEdit();
        
        getProductList();
    }

      
    </script>
   






<?php include('footerjs.php');?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>











 
