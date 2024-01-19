<style>
.form-popup1 {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      border: 3px solid #f1f1f1;
      z-index: 9;
      padding: 20px;
      background-color: #f1f1f1;
      max-width: 500px;
      width: 100%;
      max-height: 80vh; 
      overflow-y: auto;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.5);
      
    }
  </style>




<!-- edit -->
<form  id="productEditForm" class="form-popup1"  >

  <div class="form-group">
  <div class="col-sm-12 control-label">
    ID :
  </div>
  <div class="col-sm-12">
    <input type="text" name="e_id" id="e_id" readonly required  class="form-control "  title="ID" minlength="2">
  </div>
</div>
<div class="form-group">
  <div class="col-sm-12 control-label">
    ชื่อสินค้า :
  </div>
  <div class="col-sm-12">
    <input type="text" name="e_name" id="e_name" required class="form-control"  title="ชื่อสินค้า" minlength="2">
  </div>
</div>
<!-- <div class="form-group">
  <div class="col-sm-12 control-label" class="form-control" >
    จำนวนคงเหลือ :
  </div>
  <div class="col-sm-12"> -->
    <input type="hidden" name="e_amount" id="e_amount" required class="form-control"  minlength="2">
  <!-- </div>
</div> -->
<!-- <div class="form-group">
  <div class="col-sm-12 control-label" class="form-control" >
    จำนวนการจอง :
  </div>
  <div class="col-sm-12"> -->
    <input type="hidden" name="e_amount_reserve" id="e_amount_reserve" required class="form-control"  minlength="2">
  <!-- </div>
</div> -->
<div class="form-group">
  <div class="col-sm-12 control-label">
    หน่วยนับ :
  </div>
  <div class="col-sm-12">

    <input type="text" name="e_unit" id="e_unit" required class="form-control"  minlength="2">

  </div>
</div>

  <div class="form-group">
  <div class="col-sm-12 control-label">
    รายละเอียดสินค้า :
  </div>
  <div class="col-sm-12">
      <textarea name="e_detail" id="e_detail" class="form-control" cols="30" rows="10"></textarea>
  </div>
</div>
<div class="form-group">
  <div class="col-sm-12 control-label">
    รูปภาพ :
  </div>
  <div class="col-sm-12">
    <input type="file" name="e_img" id="e_img" required class="form-control" accept="image/*" onchange="readURL(this);"/>

    <img id="blah" src="" alt="" width="250" class="img-rounded" style="margin-top: 10px;">

    <input type="hidden" name="o_img" id="o_img"  class="form-control"/>
 
  </div>
</div>
<div class="form-group">
  <div class="col-sm-12">
  </div>
  <div class="col-sm-12">
  <button  class="btn btn-success" type="button" id="submitBtn1">บันทึก</button>
    
    <a href="product.php" class="btn btn-danger">ยกเลิก</a>
  </div>
</div>
</form>



