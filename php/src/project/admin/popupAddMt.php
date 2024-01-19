<style>
    /* Additional styles for the pop-up form */
    .form-popup {
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





<!-- //add -->
<form  id="productAddForm" class="form-popup"   enctype="multipart/form-data" >
<div class="form-group">
  <div class="col-sm-12 control-label">
  ชื่อวัตถุดิบ :
  </div>
  <div class="col-sm-12">
    <input type="text" name="name" id="name" required class="form-control"  title="ชื่อวัตถุดิบ" minlength="2">
  </div>
</div>

    <input type="hidden" name="amount" value="0" id="amount" required class="form-control"  minlength="2">
  
<div class="form-group">
  <div class="col-sm-12 control-label">
    หน่วยนับ :
  </div>
  <div class="col-sm-12">

  <input type="text" name="unit" id="unit"  required class="form-control"  minlength="2">

  </div>
</div>


<div class="form-group">
  <div class="col-sm-12 control-label">
    รูปภาพ :
  </div>
  <div class="col-sm-12">
    <input type="file" name="img" id="img" required class="form-control" accept="image/*" onchange="readURL(this);"/>
    <!-- <img id="blah" src="#" alt="" width="100" class="img-rounded" style="margin-top: 10px;"> -->
  </div>
</div>
<div class="form-group">
  <div class="col-sm-12">
  </div>
  <div class="col-sm-12">
  <button  class="btn btn-success"  type="button" id="submitBtn">บันทึก</button>
    
    <a href="" onclick="closeForm()" class="btn btn-danger">ยกเลิก</a>
  </div>
</div>
</form>

<script>
        document.getElementById("openFormBtn").addEventListener("click", function() {
      document.querySelector(".form-popup").style.display = "block";
    });

    function closeForm() {
  document.querySelector(".form-popup").style.display = "none";
  
  
}
    </script>