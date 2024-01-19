
<script type=text/javascript>
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
     function getProductList(){
        let uri= "http://127.0.0.1:8080/project/api/admin/back_get_product_list.php";
        let innerhtml = "";
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:null,
            success:function(response){
                if(response.result==1){
                    // console.log(response.datalist);
                    let amount = "";
                    let amount_reserve = "";
                    
                    

                    
                    innerhtml = innerhtml+ `<table id='example1' class='table table-bordered table-striped'>
                        <thead>
                        <tr class=''>
                            <th width='10%' class='text-center'>ID</th>
                            <th width='10%' class='hidden-xs text-center'>รูป</th>
                            <th width='10%' class='hidden-xs text-center'>ชื่อสินค้า</th>
                            <th width='15%' class='text-center'>จำนวนคงเหลือ</th>
                            <th width='15%'  class='text-center'>จำนวนการจอง</th>
                            <th width='10%' class='hidden-xs text-center'>หน่วยการนับ</th>  
                            <th width='20%'  class='text-center'></th>
                          </tr>
                        </thead>
                        `;

                    for(let i=0;i<response.datalist.length;i++){
                        let imgArray = [];
                let inputImg = response.datalist[i].img;
                // ตรวจสอบว่ามี "," หรือไม่
                if (inputImg.includes(',')) {
                    // มี ","
                    imgArray = inputImg.split(',');
                } else {
                    // ไม่มี ","
                    imgArray.push(inputImg);
                }

                        amount = numberWithCommas(response.datalist[i].amount);
                        amount_reserve = numberWithCommas(response.datalist[i].amount_reserve);
                        console.log(response.datalist);
                        innerhtml = innerhtml +`<tr>
                          <td>${response.datalist[i].id} </td> 
                          <td class='hidden-xs text-center'><img src='../assets/images/product/${imgArray[0]}' width='70%'></td>
                          <td class='hidden-xs text-center'>
                          ${response.datalist[i].name}
                          </td>
                          <td  class='text-center'> 
                          ${amount}
                          </td>
                          <td  class='text-center'>
                          ${amount_reserve}
                          </td  class='text-center'> 
                            <td class='hidden-xs' align='center'>
                            ${response.datalist[i].unit} 
                              </td> 
                          <td  class='text-center'>
       
                          <button  class='btn btn-secondary text-white ' onclick='getPriceList("${response.datalist[i].id}")' data-bs-toggle='modal' data-bs-target='#exampleModal'>ราคา<i class="fas fa-dollar-sign"></i></button>
                          <a href='product.php?act=edit&ID=${response.datalist[i].id}'  class='btn btn-warning text-white'><i class='fas fa-edit'></i></a>   
                                <button  onclick='productDel("${response.datalist[i].id}")'class='btn btn-danger text-white '><i class='fas fa-trash '></i></button>
 
                                </td> 
                          </tr>`;

                    }
                    innerhtml += "</table>";

                }else{
                    console.log(response.message);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
        document.getElementById("contentDetail").innerHTML=innerhtml;


    }
    
</script>


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
            console.error(error);
        }
    });
});
   }

</script>

<script>
   function editProduct(){
   document.getElementById("submitBtn1").addEventListener("click", function() {
    // Create a FormData object to easily handle the form data, including files
    var formData = new FormData(document.getElementById("productEditForm"));

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/editPro.php', // Update with the actual path to your PHP script
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
            }else if(response.result==2){
                  Swal.fire({
                  title: response.message,
                  icon: 'error',
                  confirmButtonText: 'Close'
              });
              
            }else if(response.result==3){
              Swal.fire({
                  title: response.message,
                  icon: 'error',
                  confirmButtonText: 'Close'
              });

            }else if(response.result==4){
              Swal.fire({
                  title: response.message,
                  icon: 'error',
                  confirmButtonText: 'Close'
              });

            }
            
            else{
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
});
   }

</script>


<script type=text/javascript>

     function getProductEdit(){
        let uri= "http://127.0.0.1:8080/project/api/admin/get_product_edit.php";
        let innerhtml = "";
            // ดึงค่า query string จาก URL
            const queryString = window.location.search;
    
    // สร้าง URLSearchParams object เพื่อจัดการ query string
    const urlParams = new URLSearchParams(queryString);
    
    // ดึงค่า parameter ที่ชื่อ act และ id
    const actParam = urlParams.get('act');
    const idParam = urlParams.get('ID');

    // ตรวจสอบว่ามีค่า act เท่ากับ 'edit' และมีค่า id
    if (actParam === 'edit' && idParam !== null) {
        // เก็บค่า id ในตัวแปร id
        let id = {
                    "id" : idParam
                };
        document.querySelector(".form-popup1").style.display = "block";

        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:JSON.stringify(id),
            success:function(response){
                if(response.result==1){
                    console.log(response.datalist[0].name);
                    document.getElementById("e_id").value = response.datalist[0].id;
                    document.getElementById("e_name").value =  response.datalist[0].name;
                    document.getElementById("e_detail").value  =  response.datalist[0].detail;
                    document.getElementById("e_amount").value =  response.datalist[0].amount;
                    document.getElementById("e_amount_reserve").value =  response.datalist[0].amount_reserve;
                    document.getElementById("e_unit").value =  response.datalist[0].unit;
                    document.getElementById("blah").src = '../assets/images/product/'+response.datalist[0].img;

                    console.log(response.message);

                }else{
                    console.log(response.message);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
        document.getElementById("contentDetail").innerHTML=innerhtml;
    } else {
        console.log("Invalid parameters");
        
    }

    }
    
</script>




<script>
function productDel(idIn){
   let uri= "http://127.0.0.1:8080/project/api/admin/delPro.php";
   let idParam = idIn;


   let id = {
               "id" : idParam
           };
           Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "ต้องการลบรายการนี้ใช่หรือไม่!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                // User confirmed, proceed with the deletion
                $.ajax({
                    type: "POST",
                    url: uri,
                    async: false,
                    data: JSON.stringify(id),
                    success: function (response) {
                        if (response.result == 1) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            console.log(response.message);

                            setTimeout(function () {
                            window.location.replace("http://127.0.0.1:8080/project/admin/product.php");
                        }, 1000);
                        } else {
                            Swal.fire({
                                title: response.message,
                                icon: 'error',
                                confirmButtonText: 'Close'
                            });
                            console.log(response.message);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                        Swal.fire({
                        title: response.message,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                    }
                });
                document.getElementById("contentDetail").innerHTML = innerhtml;
            } else {
                // User canceled, handle accordingly
                console.log("Deletion canceled");
            }
        });
    } 

</script>






<script>
     function getPriceList(id){
        let uri= "http://127.0.0.1:8080/project/api/admin/get_price_list.php";
        let innerhtml = "";
        let pro_id = {
            "id":id
        }
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:JSON.stringify(pro_id),
            success:function(response){
                if(response.result==1){
                   
                    
    for(let i=0;i<response.datalist.length;i++){
        innerhtml += `
                    <div class='row'>
          <label for=''   class='form-label'><h5>รายการที่ :${i+1}</h5></label>
          <div class='col'>
          <input type='hidden' value='${response.datalist[i].pro_id}'  id='e_pro_price_id${i+1}' class='form-control ' > 
          <input type='hidden' value='${response.datalist[i].price_id}'  id='e_price_id${i+1}' class='form-control ' > 
          <label for='conditions'   class='form-label'>จำนวนสั่งซื้อขั้นต่ำ :</label>
            <input type='text' value='${response.datalist[i].amount_conditions}'  readonly  id='e_conditions${i+1}' class='form-control ' > 
            </div>
            <div class='col'>
            <label for='price'   class='form-label'>ราคา :</label>
          <input type='text' readonly  value='${response.datalist[i].price}'  id='e_price${i+1}' class='form-control  ' > 
            </div>
    </div>
        <div class='d-flex '>
          <button type='button' id='editPrice${i+1}' onclick='enableEdit(${i+1})'  class='btn btn-warning mt-2 mx-2'>แก้ไข <i class='fas fa-wrench'></i></button>
        <button type='button' id='delPrice${i+1}'  class='btn btn-danger mt-2 mx-2' onclick='delPrice(${i+1})'>ลบ<i class='fas fa-trash'></i></button>

        <button type='button' id='saveEditPrice${i+1}' onclick='editPrice(${i+1})'  style='display:none' class='btn btn-warning mt-2 mx-2'>บันทึก<i class='fas fa-save'></i></button>
        <button type='button' id='cancelPrice${i+1}'  style='display:none' class='btn btn-danger mt-2 mx-2' onclick='disableEdit(${i+1})'>ยกเลิก<i class="fas fa-times-circle"></i></button>
        </div>
        <hr>

                    `;   

    }
                
                }else{
                    console.log(response.message);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
        document.getElementById("add_pro_id").value=id;
        document.getElementById("contentPriceList").innerHTML=innerhtml;


    }







</script>



<script>

function enableEdit(i) {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น false
    document.getElementById('e_conditions'+i).readOnly = false;
    document.getElementById('e_price'+i).readOnly = false;
   

    // แสดงปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('saveEditPrice'+i).style.display = 'inline-block';
    document.getElementById('cancelPrice'+i).style.display = 'inline-block';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('editPrice'+i).style.display = 'none';
    document.getElementById('delPrice'+i).style.display = 'none';
    console.log(i);
}
</script>
<script>
function disableEdit(i) {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น false
    document.getElementById('e_conditions'+i).readOnly = true;
    document.getElementById('e_price'+i).readOnly = true;
   

    // แสดงปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('saveEditPrice'+i).style.display = 'none';
    document.getElementById('cancelPrice'+i).style.display = 'none';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('editPrice'+i).style.display = 'block';
    document.getElementById('delPrice'+i).style.display = 'block';
    console.log(i);
}
</script>


<script>
   function addPrice(){

    let pro_id = document.getElementById("add_pro_id").value;
    let conditions = document.getElementById("add_conditions").value;
    let price = document.getElementById("add_price").value;


    let request_data = {
        "pro_id":pro_id ,
        "conditions":conditions ,
        "price": price
    };

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/add_price.php', // Update with the actual path to your PHP script
        type: 'POST',
        data: JSON.stringify(request_data),
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
    
                        getPriceList(pro_id);
                    $('#exampleModal').modal('show');
                    document.getElementById("add_conditions").value = "";
                    document.getElementById("add_price").value = "";

            }
            
            else{
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
   function editPrice(i){

    let price_id = document.getElementById("e_price_id"+i).value;
    let conditions = document.getElementById("e_conditions"+i).value;
    let price = document.getElementById("e_price"+i).value;


    let request_data = {
        "price_id":price_id ,
        "conditions":conditions ,
        "price": price
    };

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/edit_price.php', // Update with the actual path to your PHP script
        type: 'POST',
        data: JSON.stringify(request_data),
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
    
            disableEdit(i);

            }
            
            else{
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
   function delPrice(i){
    let price_id = document.getElementById("e_price_id"+i).value;
    let pro_id = document.getElementById("e_pro_price_id"+i).value;
    let request_data = {
        "price_id":price_id 
     
    };
    Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "ต้องการลบรายการรับนี้ใช่หรือไม่!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/delete_price.php', // Update with the actual path to your PHP script
        type: 'POST',
        data: JSON.stringify(request_data),
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
                        getPriceList(pro_id);
                    $('#exampleModal').modal('show');

            }
            
            else{
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
} else {
                // User canceled, handle accordingly
                console.log("Deletion canceled");
            }
});

   }

</script>