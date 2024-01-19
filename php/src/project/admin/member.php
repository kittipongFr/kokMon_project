<?php include('h.php');?>
<body  onload="getEnvironment()">
  


    
        <?php include('menu_l.php');?>

 
  <section class="content-header">
      <h1>
        <i class="glyphicon glyphicon-user hidden-xs"></i> <span class="hidden-xs">ข้อมูลสมาชิกในระบบ</span>
        <button class="btn btn-primary btn-sm text-white"  data-bs-toggle="modal" data-bs-target="#modal1">เพิ่มสมาชิก <i class="fas fa-plus"></i></button>   
    


  </h1>
         
      </section>


      <div class="data_table" >
        <div id="contentDetail" class="row">

 <!-- content here  -->

</div>
        </div>



<?php
               
  include('member_list.php');

  ?> 

 

<!--เพิ่ม Modal 1 -->
<div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">เพิ่มสมาชิก</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="mb-3">
      <div class="mb-3">
          <label for="textInput"  class="form-label">email</label>
          <input type="email"  id="email" class="form-control" >
        </div>
        <div class="mb-3">
          <label for="textInput"   class="form-label">รหัสผ่าน</label>
          <input type="text" minlength="8"  id="password" class="form-control" >
        </div>

          <label for="textInput"   class="form-label">ชื่อ-สกุล</label>
          <div class="d-flex">
          <input type="text" placeholder="ชื่อ"  id="fname" class="form-control" >
          <input type="text"  placeholder="สกุล"  id="lname" class="form-control" >
          </div>
        </div>
        <div class="mb-3">
          <label for="textInput"   class="form-label">เบอร์</label>
          <input type="text"  id="tel" maxlength="10" class="form-control" >
        </div>
        <div class="mb-3">
          <label for="textInput"   class="form-label">ที่อยู่</label>
          <textarea class="form-control"  id="address" rows="4"></textarea>
        </div>
        <div class="mb-3">
          <label for="textInput"   class="form-label">ตำแหน่ง</label>
         <select class="form-control" name="" id="role">
          <option value="0">ประธาน</option>
          <option value="1">เจ้าหน้าที่บันทึกข้อมูล</option>
          <option value="2">สมาชิก</option>
         </select>
        </div>
      

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">กลับ</button>
        <button type="button" onclick="addMember()" class="btn btn-success">บันทึก</button>
      </div>
    </div>
  </div>
</div>

<!--แก้ไข Modal 2 -->
<div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">แก้ไขสถานะ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="contentEdit">
    


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
        <button type="button" onclick="editMem()" class="btn btn-primary">บันทึก</button>
      </div>
    </div>
  </div>
</div>



<script type=text/javascript>
    function getEnvironment(){

      getMemList();
    }

      
    </script>
   






<?php include('footerjs.php');?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>











 
