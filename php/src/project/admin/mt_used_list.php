<script type=text/javascript>


     function getMtUsedList(){
        let uri= "http://127.0.0.1:8080/project/api/admin/back_get_mt_used.php";
        let innerhtml = "";
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:null,
            success:function(response){
                if(response.result==1){
                    // console.log(response.datalist);
                    
                    innerhtml = innerhtml+ `<table id='example1' class='table table-bordered table-striped'>
                        <thead>
                        <tr class=''>
                            <th class='text-center' width='25%'>วันที่รับวัตถุดิบ</th>
                            <th class='text-center' width='25%' class='hidden-xs'>รหัสบันทึกวัตถุดิบ</th>
                            <th class='text-center' width='25%' class='hidden-xs'>จำนวนรายการสินค้า</th>  
                            <th class='text-center' width='25%'>แก้ไข</th>
                          </tr>
                        </thead>
                        `;

                    for(let i=0;i<response.datalist.length;i++){
                        console.log(response.datalist);
                        innerhtml = innerhtml +`<tr>
                        <td class='hidden-xs text-center' >
                            ${response.datalist[i].date} 
                              </td> 
                          <td class='text-center'>${response.datalist[i].id} </td> 
                          
                       
                          <td class='hidden-xs text-center'>
                          ${response.datalist[i].detail_count}
                          </td>
                           
                          <td class='text-center'><a onclick='getMtuEdit("${response.datalist[i].id}")'  class='btn btn-warning text-white'><i class='fas fa-edit'></i></a>    
                          <a href='mtuDetail.php?mtu_id=${response.datalist[i].id}' class='btn btn-info text-white'><i class='fas fa-search'></i></a>
                              
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
function addMtu() {
    document.getElementById('submitBtn').addEventListener('click', function () {
        let mem_id;
        let mt_id = [];
        let amount = [];
        mem_id = document.getElementById("mem_id").value;
        for (let i = 0; i < inputSets.length; i++) {
            mt_id.push(document.getElementById(inputSets[i].dropdownId).value);
            amount.push(document.getElementById(inputSets[i].amountId).value);
        }
        console.log(mt_id+amount);
        formData = {
            "mem_id": mem_id,
            "amount": amount,
            "mt_id": mt_id
        };
        $.ajax({
            type: "POST",
            url: "http://localhost:8080/project/api/admin/addMtu.php",
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
                        window.location.replace("http://127.0.0.1:8080/project/admin/mt_used.php");
                    }, 2000);
                } else if (response.result === 3){
                   
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: response.message,
                        showConfirmButton: true,
                       
                    }).then((result) => {
                        if (result.isConfirmed) {
                            mtuDelAmountOver(response.id);
                          
                        }
                    });
                                        

               }else {
                   
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: response.message,
                
                        showConfirmButton: true,
                       
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.replace("http://127.0.0.1:8080/project/admin/mt_used.php");
                        }
                    });
                }
            },
            error: function (error) {
                console.log(formData);

                Swal.fire({
                    title: "error",
                    icon: 'error',
                    confirmButtonText: 'Close'
                });
            }
        });
    });
}

</script>





<script type=text/javascript>

     function getMtuEdit(idIn){
        let uri= "http://127.0.0.1:8080/project/api/admin/get_mtu_edit.php";
        let innerhtml = "";
        let id = {"id":idIn}
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:JSON.stringify(id),
            success:function(response){
                console.log(response.datalist);
                console.log(response.message);
    if(response.result == 1){
    document.querySelector(".form-popup1").style.display = "block";

        innerhtml = innerhtml + `
        <div class='form-group'>
        <div class='col-sm-12 control-label'>
           <h3>บันทึกวัตถุดิบใช้ไป:</h3>
        </div>
        </div>

        <div class='form-group'>
        <div class='col-sm-12 control-label'>
            รหัสการบันทึก:
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_mtu_id' readonly value='${response.datalist.material_used.mtu_id}' id='e_mtu_id' required class='form-control' minlength='2'>
        </div>
    </div>
    <div class='form-group'>
        <div class='col-sm-12 control-label'>
            วันที่ :
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_date' readonly value='${response.datalist.material_used.date}' id='e_date' required class='form-control' minlength='2'>
        </div>
    </div>
    <div class='form-group'>
        <div class='col-sm-12 control-label'>
            เจ้าหน้าที่:
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_mem_id' readonly value='${response.datalist.material_used.mem_fname} ${response.datalist.material_used.mem_lname}' id='e_mem_id' required class='form-control' minlength='2'>
        </div>
    </div>

    <a  onclick='mtuDel("${response.datalist.material_used.mtu_id}")' class='btn btn-danger text-white mt-2'>ลบ <i class='fas fa-trash '></i></a>

<hr>

    <div class='form-group'>
        <div class='col-sm-12 control-label'>
           <h3>รายการผลิต:</h3>
        </div>
        </div>
        `;

        for (let i = 0; i < response.datalist.material_used_detail.length; i++) {
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
                <select name='e_material_id${i + 1}' id='e_material_id${i + 1}' disabled  class='form-control dynamic-input' required>
                    ${response.datalist.material.map(material => `
                        <option value='${material.material_id}' ${material.material_id === response.datalist.material_used_detail[i].material_id ? 'selected' : ''}>
                            ${material.name} (${material.unit}) คงเหลือ : ${material.amount}
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
                <input type='number' name='e_amount${i + 1}' value='${response.datalist.material_used_detail[i].amount}' id='e_amount${i + 1}' readonly required class='form-control' minlength='2'>
                <input type='hidden' name='e_old_amount${i + 1}' value='${response.datalist.material_used_detail[i].amount}' id='e_old_amount${i + 1}' readonly required class='form-control' minlength='2'>
               
                </div>
        </div>
        <div class='form-group'>
            <div class='col-sm-12 control-label' class='form-control'>
                ต้นทุน :
            </div>
            <div class='col-sm-12'>
                <input type='number' name='e_cost${i + 1}' value='${response.datalist.material_used_detail[i].cost}' id='e_cost${i + 1}' readonly required class='form-control' minlength='2'>
              
               
                </div>
        </div>

        <div class='form-group'>
            <div class='d-flex my-2' >
            <button class="btn btn-warning text-white mx-2"  onclick='enableEdit(${i+1})' type='button' id="requestMtu${i + 1}">แก้ไข <i class='fas fa-wrench'></i></button>
            <a class="btn btn-danger text-white" onclick='mtuDelList("${response.datalist.material_used.mtu_id}", "${response.datalist.material_used_detail[i].material_id}","${response.datalist.material_used_detail.length}")' type='button' id="delMtu${i + 1}">ลบ <i class='fas fa-trash'></i></a>

            <button class="btn btn-success mx-2" style="display:none;" onclick='editMtuList(${i+1})' type="button" id="editMtu${i + 1}">บันทึก<span class='glyphicon glyphicon-fix'></span></button>
            <button class="btn btn-danger" style="display:none;" onclick='disableEdit(${i+1})' type="button" id="cancelMtu${i + 1}">ยกเลิก <span class='glyphicon glyphicon-fix'></span></button>
      
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
        document.getElementById("mtuEdit").innerHTML=innerhtml;


    }
    


</script>



<script>
function enableEdit(i) {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น false
    // document.getElementById('e_material_id'+i).disabled = false;
    document.getElementById('e_amount'+i).readOnly = false;


    // แสดงปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('editMtu'+i).style.display = 'inline-block';
    document.getElementById('cancelMtu'+i).style.display = 'inline-block';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('requestMtu'+i).style.display = 'none';
    document.getElementById('delMtu'+i).style.display = 'none';
    console.log(i);
}
</script>
<script>
function disableEdit(i) {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น true
    document.getElementById('e_material_id'+i).disabled = true;
    document.getElementById('e_amount'+i).readOnly = true;


    // ซ่อนปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('editMtu'+i).style.display = 'none';
    document.getElementById('cancelMtu'+i).style.display = 'none';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('requestMtu'+i).style.display = 'block';
    document.getElementById('delMtu'+i).style.display = 'block';
    console.log(i);
}
</script>




<script>
function editAddMtuList() {
    document.getElementById('submitBtn1').addEventListener('click', function () {
        let mtu_id;
        let mt = [];
        let amount = [];
        mtu_id = document.getElementById("e_mtu_id").value;
        for (let i = 0; i < inputSets1.length; i++) {
            mt.push(document.getElementById(inputSets1[i].dropdownId).value);
            amount.push(document.getElementById(inputSets1[i].amountId).value);
          
        }
        formData = {
            "mtu_id": mtu_id,
            "mt_id": mt,
            "amount": amount
        };
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "http://localhost:8080/project/api/admin/editAddMtuList.php",
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
                        window.location.replace("http://127.0.0.1:8080/project/admin/mt_used.php");
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
   function editMtuList(i){
    let mtu_id;
    let mt_id;
    let amount;
    let old_net;
    
    mtu_id = document.getElementById("e_mtu_id").value;
    mt_id = document.getElementById("e_material_id"+i).value;
    amount = document.getElementById("e_amount"+i).value;
    old_amount = document.getElementById("e_old_amount"+i).value;

    let request_data = {
        "mtu_id":mtu_id ,
        "mt_id": mt_id,
        "amount":amount ,
        "old_amount": old_amount
    };

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/editMtuList.php', // Update with the actual path to your PHP script
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
                            getMtuEdit(mtu_id, () => {
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
function mtuDelList(idIn,mtIn,dataLength){
   let uri= "http://127.0.0.1:8080/project/api/admin/delMtuList.php";
   let idParam = idIn;
   let mtParam = mtIn;
   console.log(idParam);

   if(dataLength > 1){

   let id = {
               "id" : idParam,
               "mt_id":mtParam
           };
           console.log(id);
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
                            getMtuEdit(idParam, () => {
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
        mtuDel(idParam);
    } 

}

</script>












<script>
function mtuDel(idIn){
   let uri= "http://127.0.0.1:8080/project/api/admin/delMtu.php";
   let idParam = idIn;
   console.log(idParam);
   let id = {
               "id" : idParam,
           };
           console.log(id);
           Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "ต้องการยกเลิกการบันทึกวัตถุดิบนี้ใช่หรือไม่!",
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
                            window.location.replace("http://127.0.0.1:8080/project/admin/mt_used.php");
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
    } 

</script>




<script>
function mtuDelAmountOver(idIn){
   let uri= "http://127.0.0.1:8080/project/api/admin/delMtu.php";
   let idParam = idIn;
   console.log(idParam);
   let id = {
               "id" : idParam,
           };
          
                // User confirmed, proceed with the deletion
                $.ajax({
                    type: "POST",
                    url: uri,
                    async: false,
                    data: JSON.stringify(id),
                    success: function (response) {
                        if (response.result == 1) {
                            window.location.replace("http://127.0.0.1:8080/project/admin/mt_used.php");
                        } else {
                            
                            console.log(response.message);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                   
                    }
                });
            
        
    } 

</script>