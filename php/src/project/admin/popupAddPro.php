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
<form  id="productAddForm" class="form-popup"  enctype="multipart/form-data" >
<div class="form-group">
  <div class="col-sm-12 control-label">
    ชื่อสินค้า :
  </div>
  <div class="col-sm-12">
    <input type="text" name="name" id="name" required class="form-control"  title="ชื่อสินค้า" minlength="2">
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
    รายละเอียดสินค้า :
  </div>
  <div class="col-sm-12">
      <textarea name="detail" id="detail" class="form-control" cols="30" rows="10"></textarea>
  </div>
</div>
<div class="form-group">
    <div class="col-sm-12 control-label">
      รูปภาพ :
    </div>
    <div class="col-sm-12">
      <!-- Use the 'multiple' attribute to allow multiple file selection -->
      <input type="file" name="img[]" id="img" required class="form-control" accept="image/*" multiple onchange="readURLs(this);" />
      <!-- Note: 'img[]' in the 'name' attribute signifies an array of files -->
      <!-- Display the selected images (you can customize the appearance as needed) -->
      <div id="imagePreview"></div>
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





<!-- Script to display and delete selected images -->
<script>
  function readURLs(input) {
    var imagePreview = document.getElementById('imagePreview');
    imagePreview.innerHTML = ''; // Clear previous previews

    if (input.files && input.files.length > 0) {
      for (var i = 0; i < input.files.length; i++) {
        var reader = new FileReader();

        reader.onload = function (e) {
          var imgElement = document.createElement('div');
          imgElement.className = 'image-container';
          imgElement.innerHTML =
            '<img src="' + e.target.result + '" width="100" class="img-rounded" />' +
            '<button type="button" class="btn btn-danger btn-remove" onclick="removeImage(this)">ลบ</button>';
          imagePreview.appendChild(imgElement);
          // updateInputName();
        };

        reader.readAsDataURL(input.files[i]);
      }
    }
  }

  function removeImage(btn) {
    var imageContainer = btn.parentNode;
    imageContainer.parentNode.removeChild(imageContainer);
    // updateInputName();
  }


//   function updateInputName() {
//   var fileInput = document.getElementById('img');
//   var imageContainers = document.querySelectorAll('.image-container');
//   var newName = 'img[]';

//   imageContainers.forEach(function (_, index) {
//     newName += '[' + index + ']';
//   });

//   fileInput.setAttribute('name', newName);
// }

</script>

<style>
  .image-container {
    margin-bottom: 10px;
  }

  .btn-remove {
    margin-top: 5px;
  }
</style>








<script>
   function addProduct(){
   document.getElementById("submitBtn").addEventListener("click", function() {
    // Create a FormData object to easily handle the form data, including files
    var formData = new FormData(document.getElementById("productAddForm"));

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/addPro.php', // Update with the actual path to your PHP script
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Handle the response from the server
            console.log(response);
            if(response.result==1){
              Swal.fire({
              title: "เรียบร้อย",
              text: response.message,
              icon: "success"
            });
            setTimeout(function () {
                window.location.replace("http://127.0.0.1:8080/project/admin/product.php");
            }, 2000);
     

            }else{
                console.log(response.img)
              Swal.fire({
                  title: response.message,
                  icon: 'error',
                  confirmButtonText: 'Close'
              });

            }

        },
        error: function(error) {
            // Handle errors
     
            console.log(error);
        }
    });
});
   }

</script>
