<?php include('h.php');?>
<body  onload="getEnvironment()">
  
    
        <?php include('menu_l.php');?>

 
  <section class="content-header">
      <h1>
        <i class="glyphicon glyphicon-user hidden-xs"></i> <span class="hidden-xs">ข้อมูลวัตถุดิบในระบบ</span>
        <a  class="btn btn-primary btn-sm text-white"   id="openFormBtn">เพิ่มวัตถุดิบ <i class="fas fa-plus"></i></a>   
        <!-- href="product.php?act=add" -->
        </h1>
         
      </section>


      <div class="data_table" >
        <div id="contentDetail" class="row">

 <!-- content here  -->

</div>
        </div>



<?php
               
  include('material_list.php');
  include('popupAddMt.php');
  include('popupEditMt.php');

  ?> 









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





<script type=text/javascript>
    function getEnvironment(){
        
        addMaterial();
        editMaterial();
    
        getMaterialList();
    }

      
    </script>
   






<?php include('footerjs.php');?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>











 
