
<script type=text/javascript>

     function getManufactureList(){
        let uri= "http://127.0.0.1:8080/project/api/admin/back_get_manufacture_list.php";
        let innerhtml = "";
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:null,
            success:function(response){
                if(response.result==1){
                    // console.log(response.datalist);
                    
                    innerhtml = innerhtml+ `<table id='example1' class='table table-bordered table-striped' style='text-align: center;'>
                        <thead >
                        <tr class='' >
                        <th width='25%' style='text-align: center;'>วันที่ผลิต</th>
                        <th width='25%'  style='text-align: center;' >ID</th>
                            <th width='25% ' style='text-align: center;' >จำนวนรายการวัตถุดิบ</th>
                            <th width='25%' style='text-align: center;'>แก้ไข</th>
                          </tr>
                        </thead>
                        `;

                    for(let i=0;i<response.datalist.length;i++){
                        console.log(response.datalist);
                        innerhtml = innerhtml +`<tr>
                        <td >
                            ${response.datalist[i].date} 
                              </td> 
                          <td>${response.datalist[i].id} </td> 
                          <td>
                          ${response.datalist[i].manufacture_count}  
                          </td>
                        
                           
                          <td><a onclick='getMftEdit("${response.datalist[i].id}")'  class='btn btn-warning text-white'><i class='fas fa-edit'></i></a>   
                          <a href='mftDetail.php?mft_id=${response.datalist[i].id}' class='btn btn-info text-white'><i class='fas fa-search'></i></a>
                               
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
function addMft() {
    document.getElementById('submitBtn').addEventListener('click', function () {
        let mem_id;
        let pro_id = [];
        let amount = [];
        mem_id = document.getElementById("mem_id").value;
        for (let i = 0; i < inputSets.length; i++) {
            pro_id.push(document.getElementById(inputSets[i].dropdownId).value);
            amount.push(document.getElementById(inputSets[i].amountId).value);
        }


        formData = {
            "mem_id": mem_id,
            "amount": amount,
            "pro_id": pro_id
        };

        $.ajax({
            type: "POST",
            url: "http://localhost:8080/project/api/admin/addMft.php",
            processData: false,
            contentType: false,
            data: JSON.stringify(formData),
            success: function (response) {
                console.log("success...");
                console.log(response);
                console.log(response.result);
                console.log(response.message);

                if (response.result === 1) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                    });

                    setTimeout(function () {
                        window.location.replace("http://127.0.0.1:8080/project/admin/manufacture.php");
                    }, 2000);
                } else {
                    if (response.result === 3) {
                        Swal.fire({
                            title: response.message,
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
                    } else {
                        Swal.fire({
                            title: response.message,
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
                    }
                }
            },
            error: function (error) {
                console.log(formData);

                Swal.fire({
                    title: "Error occurred",
                    icon: 'error',
                    confirmButtonText: 'Close'
                });
            }
        });
    });
}

</script>



<script type=text/javascript>

     function getMftEdit(idIn){
        let uri= "http://127.0.0.1:8080/project/api/admin/get_mft_edit.php";
        let innerhtml = "";
        let id = {"id":idIn}
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:JSON.stringify(id),
            success:function(response){
            mft_length = response.datalist.manufacture_detail.length;
                console.log(mft_length);
    if(response.result == 1){
    document.querySelector(".form-popup1").style.display = "block";

        innerhtml = innerhtml + `
        <div class='form-group'>
        <div class='col-sm-12 control-label'>
           <h3>การผลิต:</h3>
        </div>
        </div>

        <div class='form-group'>
        <div class='col-sm-12 control-label'>
            รหัสการผลิต:
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_mft_id' readonly value='${response.datalist.manufacture.mft_id}' id='e_mft_id' required class='form-control' minlength='2'>
        </div>
    </div>
    <div class='form-group'>
        <div class='col-sm-12 control-label'>
            วันที่ :
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_date' readonly value='${response.datalist.manufacture.date}' id='e_date' required class='form-control' minlength='2'>
        </div>
    </div>
    <div class='form-group'>
        <div class='col-sm-12 control-label'>
            เจ้าหน้าที่:
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_mem_id' readonly value='${response.datalist.manufacture.mem_fname} ${response.datalist.manufacture.mem_lname}' id='e_mem_id' required class='form-control' minlength='2'>
            <a onclick='mftDel("${response.datalist.manufacture.mft_id}")' class='btn btn-danger text-white mt-2'>ยกเลิกการผลิต <i class='fas fa-trash '></i></a>
        </div>
    </div>

<hr>

    <div class='form-group'>
        <div class='col-sm-12 control-label'>
           <h3>รายการผลิต:</h3>
        </div>
        </div>
        `;

        for (let i = 0; i < response.datalist.manufacture_detail.length; i++) {
    innerhtml = innerhtml + `
        <div class='form-group'>
            <div class='col-sm-12 control-label'>
                <h5>รายการที่ : ${i + 1}</h5>
            </div>
        </div>
        <div class='form-group'>
            <div class='col-sm-12 control-label'>
                ชื่อวัตถุดิบ :
            </div>
            <div class='col-sm-12'>
                <select name='e_pro_id${i + 1}' id='e_pro_id${i + 1}' disabled  class='form-control dynamic-input' required>
                    ${response.datalist.product.map(product => `
                        <option value='${product.pro_id}' ${product.pro_id === response.datalist.manufacture_detail[i].pro_id ? 'selected' : ''}>
                            ${product.name} (${product.unit})
                        </option>
                    `).join('')}
                </select>
            </div>
        </div>
        <div class='form-group'>
            <div class='col-sm-12 control-label' class='form-control'>
                จำนวนรับ :
            </div>
            <div class='col-sm-12'>
                <input type='number' name='e_amount${i + 1}' value='${response.datalist.manufacture_detail[i].amount}' id='e_amount${i + 1}' readonly required class='form-control' minlength='2'>
                <input type='hidden' name='e_old_amount${i + 1}' value='${response.datalist.manufacture_detail[i].amount}' id='e_old_amount${i + 1}' readonly required class='form-control' minlength='2'>
               
                </div>
        </div>

        <div class='form-group '>
            <div class='col-sm-12 d-flex my-2' >
            <button class="btn btn-warning text-white mx-2"  onclick='enableEdit(${i+1})' type='button' id="requestMft${i + 1}">แก้ไข <i class="fas fa-wrench"></i></button>
            <button class="btn btn-danger" onclick='mftDelList("${response.datalist.manufacture.mft_id}", "${response.datalist.manufacture_detail[i].pro_id}", "${mft_length}")' type='button' id="delMft${i + 1}">ลบ<i class="fas fa-eraser"></i></button>

            <button class="btn btn-success mx-2" style="display:none;" onclick='editMftList(${i+1})' type="button" id="editMft${i + 1}">บันทึก<span class='glyphicon glyphicon-fix'></span></button>
            <button class="btn btn-danger" style="display:none;" onclick='disableEdit(${i+1})' type="button" id="cancelMft${i + 1}">ยกเลิก <span class='glyphicon glyphicon-fix'></span></button>
      
            </div>
            </div>
    `;

    
}

        console.log(response.message);
                }else{
                    console.log(response.message);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
        document.getElementById("mftEdit").innerHTML=innerhtml;


    }
    


</script>





<script>

function enableEdit(i) {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น false
    // document.getElementById('e_pro_id'+i).disabled = false;
    document.getElementById('e_amount'+i).readOnly = false;


    // แสดงปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('editMft'+i).style.display = 'inline-block';
    document.getElementById('cancelMft'+i).style.display = 'inline-block';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('requestMft'+i).style.display = 'none';
    document.getElementById('delMft'+i).style.display = 'none';
    console.log(i);
}
</script>
<script>
function disableEdit(i) {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น true
    // document.getElementById('e_pro_id'+i).disabled = true;
    document.getElementById('e_amount'+i).readOnly = true;


    // ซ่อนปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('editMft'+i).style.display = 'none';
    document.getElementById('cancelMft'+i).style.display = 'none';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('requestMft'+i).style.display = 'block';
    document.getElementById('delMft'+i).style.display = 'block';
    console.log(i);
}
</script>




<script>
   function editMftList(i){
    let mft_id;
    let pro_id;
    let amount;
    let old_amount;
    mft_id = document.getElementById("e_mft_id").value;
    pro_id = document.getElementById("e_pro_id"+i).value;
    amount = document.getElementById("e_amount"+i).value;
    old_amount = document.getElementById("e_old_amount"+i).value;

    let request_data = {
        "mft_id":mft_id ,
        "pro_id": pro_id,
        "amount":amount ,
        "old_amount": old_amount
   
    };

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/editMftList.php', // Update with the actual path to your PHP script
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
                        }).then((result) => {
                            getMftEdit(mft_id, () => {
            document.querySelector(".form-popup1").style.display = "block";
        });
                        });
    
            disableEdit(i);
            // setTimeout(function () {
            //     window.location.replace("http://127.0.0.1:8080/project/admin/product.php");
            // }, 2000);
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
function editAddMftList() {
    document.getElementById('submitBtn1').addEventListener('click', function () {
        let mft_id;
        let product = [];
        let amount = [];
        mft_id = document.getElementById("e_mft_id").value;
        for (let i = 0; i < inputSets1.length; i++) {
            product.push(document.getElementById(inputSets1[i].dropdownId).value);
            amount.push(document.getElementById(inputSets1[i].amountId).value);
          
        }
        formData = {
            "mft_id": mft_id,
            "pro_id": product,
            "amount": amount
        };
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "http://localhost:8080/project/api/admin/editAddMftList.php",
            processData: false,
            contentType: false,
            data: JSON.stringify(formData),
            success: function (response) {
                console.log("success...");
                console.log(response);
                console.log(response.result);
                console.log(response.message);

                if (response.result === 1) {
    Swal.fire({
        title: "เรียบร้อย",
        text: response.message,
        icon: "success"
    }).then((result) => {
        setTimeout(function () {
                        window.location.replace("http://127.0.0.1:8080/project/admin/manufacture.php");
                    }, 1000);
    });
} else {
                        Swal.fire({
                            title: response.message,
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
                    }
                
            },
            error: function (error) {
                console.log(formData);
                console.log(error);

                Swal.fire({
                    title: "Error occurred",
                    icon: 'error',
                    confirmButtonText: 'Close'
                });
            }
        });
    });
}

</script>




<script>
function mftDel(idIn){
   let uri= "http://127.0.0.1:8080/project/api/admin/delMft.php";
   let idParam = idIn;
   console.log(idParam);


   let id = {
               "id" : idParam
           };
           Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "ต้องการยกเลิกการผลิตใช่หรือไม่!",
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
                            window.location.replace("http://127.0.0.1:8080/project/admin/manufacture.php");
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
function mftDelList(idIn,proIn,mft_length){
   let uri= "http://127.0.0.1:8080/project/api/admin/delMftList.php";
   let idParam = idIn;
   let proParam = proIn;
   let t = mft_length;

   if(mft_length> 1){
   let id = {
               "id" : idParam,
               "pro_id":proParam
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
                            title: "เรียบร้อย",
                            text: response.message,
                            icon: "success"
                        }).then((result) => {
                            getMftEdit(idParam, () => {
            document.querySelector(".form-popup1").style.display = "block";
        });
                        });
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
            } else {
                // User canceled, handle accordingly
                console.log("Deletion canceled");
            }
        });

    }else{
        mftDel(idParam);
    }



    } 

</script>

