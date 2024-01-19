<?php include('h.php');?>


<body  onload="getCommunity()" >

    <!-- Main Header -->
    <?php include('menutop.php');?>
    <!-- Left side column. contains the logo and sidebar -->
    
        <?php include('menu_l.php');?>
    
      <section class="content-header">
      <h1   id="oidH"></h1>
       
         
      </section>

      <div class="data_table" >
        <div id="contentDetail" >

 <!-- content here  -->


 
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
    function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
function getCommunity() {
    let uri = "http://127.0.0.1:8080/project/api/admin/get_community_enterprise.php";
    let innerhtml = "";


    $.ajax({
        type: "POST",
        url: uri,
        async: false,
        data: null,
        success: function (response) {
            if (response.result == 1) {
              console.log(response.datalist);
  
                innerhtml += `
               
                <div class='d-flex'>
    <div class="card text-white bg-success mb-3 mx-2" style="max-width: 18rem;">
  <div class="card-header">เงินกองทุนทั้งหมด</div>
  <div class="card-body">
    <h5 class="card-title text-center text-white"><span>${response.datalist.aml_fund}</span> บาท</h5>
   
  </div>
</div>
                                          
<div class="card text-white bg-warning mb-3 mx-2" style="max-width: 18rem;">
  <div class="card-header">เงินกองทุนคงเหลือ</div>
  <div class="card-body">
    <h5 class="card-title  text-center text-white"><span>${response.datalist.ccl_fund}</span> บาท</h5>
    
  </div>
</div>
         



</div> 

       <div class='row'>    
       <div class='col-lg-8'> 
                              
                            <div class='card mb-4 '>
                           
                                <div class='card-body'>
                                <form  id="editForm"   enctype="multipart/form-data" >    
                                <div class="card  mb-3 mx-2">
                                  
  <div class="card-header">โลโก้</div>
  <div class="card-body">


  <div class="d-flex">

  <img id="blah" src="../assets/images/${response.datalist.img}" alt="" class="img-rounded w-25" style="margin-top: 10px;">
</div>
<input type="file" class="form-control w-50" id='img_file' name="img_file"  accept="image/*" onchange="readURL(this);" disabled>

  </div>
</div>
              
                        <div class='mt-2'>
          <label for='conditions'   class='form-label'>ชื่อวิสาหกิจ :</label>
            <input type='text' readonly  value='${response.datalist.name}' name="name"   id='name' class='form-control ' > 
            </div>
          
       
            <div class="mb-2">
  <label for="address" class="form-label">ที่อยู่ :</label>
  <textarea class="form-control" readonly id="address" name="address" rows="3">${response.datalist.address}</textarea>

</div>


<div class='mt-2'>
          <label for='conditions'   class='form-label'>อัตราค่าส่ง/กิโลกรัม :</label>
            <input type='number'  readonly value='${response.datalist.shipping_rate}' name="shipping_rate"   id='shipping_rate' class='form-control ' > 
            </div>
           
         
  <div class='mt-2'>
          <label for='conditions'   class='form-label'>เลขบัญชี :</label>
            <input type='text' readonly value='${response.datalist.bank_num}' name="bank_num"  id='bank_num' class='form-control '> 
            </div>
                                    <div class='mt-2'>
          <div class="mb-2">
  <label for="bank_name" class="form-label">รายเอียดบัญชี :</label>
  <textarea class="form-control" readonly id="bank_name" name="bank_name" rows="3">${response.datalist.bank_name}</textarea>
</div>

<div class='d-flex'>
<a class='btn btn-warning text-white mt-4 mx-2 ' onclick='enableEditRmt()' id='editBtn'>แก้ไข</a>


<a class='btn btn-danger text-white mt-4 mx-2' id='cancelBtn'  onclick='disableEditRmt()' style='display:none'>ยกเลิก</a>
<a class='btn btn-success text-white mt-4 mx-2' id='saveBtn'  onclick='editCommunity()' style='display:none'>บันทึก</a>

</div>                  

                                    </div>
                             
                                    </form> 
                                    </div>
                                  
                                    
                                    <div class='col-lg-4'>
 
                                    </div>


                                    </div>


                                    </div>  
                     
                                   
  
   
                    `;
document.getElementById("contentDetail").innerHTML = innerhtml;


            } else {
                console.log("ffff");
            }

        },
        error: function (error) {
            console.log(error);
        }
    });
    
  
}

</script>



<script>
   function editCommunity() {
    let formData = new FormData(document.getElementById("editForm"));
    var formDataObject = {};
formData.forEach(function(value, key){
    formDataObject[key] = value;
});
console.log(formDataObject);
    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/edit_community.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Handle the response from the server
            console.log(response);
            if (response.result == 1) {
                Swal.fire({
                    title: "เรียบร้อย",
                    text: response.message,
                    icon: "success"
                });
                getCommunity();
         
            } else {
                Swal.fire({
                    title: response.message,
                    icon: 'error',
                    confirmButtonText: 'Close'
                });
            }
        },
        error: function(error) {
            // Handle errors
            console.error(error);
        }
    });
}


</script>



<script>

function enableEditRmt() {

    document.getElementById('name').readOnly = false;
    document.getElementById('address').readOnly = false;
    document.getElementById('shipping_rate').readOnly = false;
    document.getElementById('bank_num').readOnly = false;
    document.getElementById('bank_name').readOnly = false;
    document.getElementById('img_file').disabled = false;
  
    document.getElementById('saveBtn').style.display = 'block';
    document.getElementById('cancelBtn').style.display = 'block';

  
    document.getElementById('editBtn').style.display = 'none';


}
</script>
<script>
function disableEditRmt() {
  
  document.getElementById('name').readOnly = true;
    document.getElementById('address').readOnly = true;
    document.getElementById('shipping_rate').readOnly = true;
    document.getElementById('bank_num').readOnly = true;
    document.getElementById('bank_name').readOnly = true;
    document.getElementById('img_file').disabled = true;
   

    document.getElementById('saveBtn').style.display = 'none';
    document.getElementById('cancelBtn').style.display = 'none';


    document.getElementById('editBtn').style.display = 'block';


}
</script>

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


    <?php

// include "order_detail_list.php";

include('footerjsDetail.php');?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>

