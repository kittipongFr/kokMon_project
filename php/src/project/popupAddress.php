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
<form  id="productAddForm" class="form-popup"  >
<div class="form-group">
  <div class="col-sm-12 control-label">
    ชื่อผู้รับ :
  </div>
  <div class="col-sm-12">
    <input type="text" name="nameN" id="nameN" required class="form-control"  title="ชื่อผุ้รับ" minlength="2">
  </div>
</div>


  
<div class="form-group">
  <div class="col-sm-12 control-label">
    เบอร์โทร :
  </div>
  <div class="col-sm-12">

  <input type="text" name="telN" id="telN"  required class="form-control" minlength="10"  minlength="10">

  </div>
</div>

  <div class="form-group">
  <div class="col-sm-12 control-label">
    ที่อยู่ :
  </div>
  <div class="col-sm-12">
      <textarea name="addressN" id="addressN" class="form-control" cols="30" rows="10"></textarea>
  </div>
</div>



  <div class="col-sm-12">
  <button  class="btn btn-success" onclick="addAddress()"  type="button" id="submitBtn">บันทึก</button>
    
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




<script>
        function addAddress(){
            let name = document.getElementById("nameN").value;
            let tel = document.getElementById("telN").value;
            let address = document.getElementById("addressN").value;
            let customer_profile = localStorage.getItem("customer_profile");
            customer_profile = JSON.parse(customer_profile);
            let cus_id = customer_profile.id;
        
            let request_data = {
                    "cus_id":cus_id,
                    "name":name,
                    "tel":tel,
                    "address":address
            }
            console.log(request_data);
            
            let uri="http://localhost:8080/project/api/addAddress.php";

            $.ajax({
                type:"POST",
                url:uri,
                data:JSON.stringify(request_data),
                async:false,
                success:function(response){
                console.log(response.result);
                    if(response.result === 1){
                        Swal.fire({
                    position: "center",
                    icon: "success",
                    title: response.message,
                    showConfirmButton: true,
                  
                    })
                    .then(() => {
                   getCartDetail();
                    });
            }
                
                },error:function(error){
                    Swal.fire({
                        title: response.message,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });        
        }

    </script>