<?php include('h.php');?>
<body  onload="getEnvironment()" >
  <div class="wrapper">
    <!-- Main Header -->
    <?php include('menutop.php');?>
    <!-- Left side column. contains the logo and sidebar -->
    
        <?php include('menu_l.php');?>
  
      <section class="content-header">
      <h1>
        <i class="glyphicon glyphicon-user hidden-xs"></i> <span class="hidden-xs">ข้อมูลบันทึกวัตถุดิบใช้ไปในระบบ</span>
        <a  class="btn btn-primary btn-sm text-white"   id="openFormBtn">บันทึกวัตถุดิบใช้ในการผลิต <i class="fa fa-plus"></i></a>   
        <!-- href="product.php?act=add" -->
        </h1>
      </section>
      <div class="data_table" >
        <div id="contentDetail" class="row">

 <!-- content here  -->

</div>
        </div>
       
<?php
include('receive_mt_list.php');
?>   

<?php
    include('mt_used_list.php');
    ?>  
<?php
// include '../config/config_db.php';
// $query = "SELECT * FROM material ORDER BY material_id asc";
// $result = mysqli_query($conn, $query);
?>
<?php
  include 'popupAddMtu.php'; 
  include 'popupEditMtu.php';
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
        let member_profile = localStorage.getItem("member_profile");
        member_profile = JSON.parse(member_profile);
        console.log(member_profile.id);      
        document.getElementById("mem_id").value =  member_profile.id;
        // document.getElementById("customer_profile_id").value =  customer_profile.id;
        // document.getElementById("customer_profile_email").value =  customer_profile.name;
        addMtu();
        editAddMtuList();
        getMtUsedList();
    }

      
    </script>
   






<?php include('footerjs.php');?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>











 
