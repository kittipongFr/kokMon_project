<script type=text/javascript>







     function getReceiveMtEdit(idIn){
        let uri= "http://127.0.0.1:8080/project/api/admin/get_rmt_edit.php";
        let innerhtml = "";
        let id = {"id":idIn}
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:JSON.stringify(id),
            success:function(response){
                
          
    if(response.result == 1){
    document.querySelector(".form-popup1").style.display = "block";
 
      // ตรวจสอบทุกรายการใน receive_material_detail
      let areAllEqual = response.datalist.receive_material_detail.every(item => item.net === item.amount);
        // แสดงผลลัพธ์ในคอนโซล
        console.log("Are all equal:", areAllEqual);
        innerhtml = innerhtml + `
        <div class='form-group'>
        <div class='col-sm-12 control-label'>
           <h3>รับวัตถุดิบ:</h3>
        </div>
        </div>

        <div class='form-group'>
        <div class='col-sm-12 control-label'>
            รหัสการรับ:
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_rmt_id' readonly value='${response.datalist.receive_material.rmt_id}' id='e_rmt_id' required class='form-control' minlength='2'>
        </div>
    </div>
    <div class='form-group'>
        <div class='col-sm-12 control-label'>
            ชื่อร้าน:
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_supply_name' readonly value='${response.datalist.receive_material.supply_name}' id='e_supply_name' required class='form-control' minlength='2'>
        </div>
    </div>
    <div class='form-group'>
        <div class='col-sm-12 control-label'>
            เจ้าหน้าที่:
        </div>
        <div class='col-sm-12'>
            <input type='text' name='e_mem_id' readonly value='${response.datalist.receive_material.mem_fname} ${response.datalist.receive_material.mem_lname}' id='e_mem_id' required class='form-control' minlength='2'>
        </div>
    </div>


    <div class='form-group d-flex'>
        <div class='col-sm-12 my-2 d-flex'>
    <button class="btn btn-warning text-white"  onclick='enableEditRmt()' type='button' id="requestRMtTop">แก้ไขการรับวัตถุดิบ <i class="fas fa-wrench"></i></span></button>

    <a href='javascript:void(0)' onclick='rmtDel("${response.datalist.receive_material.rmt_id}",  ${JSON.stringify(response.datalist.receive_material_detail)})' class='btn btn-danger mx-2 text-white ${areAllEqual ? '' : 'disabled'}'>ยกเลิกการรับวัตุดิบ <i class="fas fa-eraser"></i></a>


           
<button class="btn btn-success mx-2" style="display:none;" onclick='editRmt("${response.datalist.receive_material.rmt_id}","${response.datalist.receive_material.supply_name}")' type="button" id="editRMtTop">บันทึก<span class='glyphicon glyphicon-fix'></span></button>
<button class="btn btn-danger" style="display:none;" onclick='disableEditRmt()' type="button" id="cancelRMtTop">ยกเลิก <span class='glyphicon glyphicon-fix'></span></button>

            </div>
    </div>

<hr>
    <div class='form-group'>
        <div class='col-sm-12 control-label'>
           <h3>รายการรับวัตถุดิบ:</h3>
        </div>
        </div>
        `;
        // console.log(response.datalist.receive_material_detail);

        for (let i = 0; i < response.datalist.receive_material_detail.length; i++) {
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
                <select name='e_mt_id${i + 1}' id='e_mt_id${i + 1}' disabled  class='form-control dynamic-input' required>
                    ${response.datalist.material.map(material => `
                        <option value='${material.mt_id}' ${material.mt_id === response.datalist.receive_material_detail[i].mt_id ? 'selected' : ''}>
                            ${material.mt_name} (${material.mt_unit})
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
            <input type='number' name='e_amount${i + 1}' value='${response.datalist.receive_material_detail[i].amount}' id='e_amount${i + 1}' readonly required class='form-control' minlength='2'>
                <input type='hidden' name='e_old_amount${i + 1}' value='${response.datalist.receive_material_detail[i].amount}' id='e_old_amount${i + 1}' readonly required class='form-control' minlength='2'>
                </div>
        </div>
        <div class='form-group'>
            <div class='col-sm-12 control-label' class='form-control'>
                ราคา/หน่วย :
            </div>
            <div class='col-sm-12'>
            <input type='hidden' name='e_old_price${i + 1}' value='${response.datalist.receive_material_detail[i].price}' id='e_old_price${i + 1}' readonly required class='form-control' minlength='2'>
              
                <input type='number' name='e_price${i + 1}' value='${response.datalist.receive_material_detail[i].price}' id='e_price${i + 1}' readonly required class='form-control' minlength='2'>
            </div>
        </div>
        <div class='form-group'>
            <div class='col-sm-12 control-label' class='form-control'>
                จำนวนคงเหลือ :
            </div>
            <div class='col-sm-12'>
                <input type='number' name='e_net${i + 1}' value='${response.datalist.receive_material_detail[i].net}' id='e_net${i + 1}' readonly required class='form-control' minlength='2'>
                <input type='hidden' name='e_old_net${i + 1}' value='${response.datalist.receive_material_detail[i].net}' id='e_old_net${i + 1}' readonly required class='form-control' minlength='2'>
                </div>
        </div>
       
        <div class='form-group'>
            <div class='col-sm-12 my-2 d-flex' >
            
 <button class="btn btn-warning text-white mx-2" ${response.datalist.receive_material_detail[i].net == response.datalist.receive_material_detail[i].amount ? '' :'disabled'} onclick='enableEdit(${i+1})' type='button' id="requestRMt${i + 1}">แก้ไขรายการรับ <i class="fas fa-wrench"></i></button>


 <button class="btn btn-danger" ${response.datalist.receive_material_detail[i].net == response.datalist.receive_material_detail[i].amount ? '' : 'disabled'} onclick='rmtDelList("${response.datalist.receive_material.rmt_id}", "${response.datalist.receive_material_detail[i].mt_id}", ${JSON.stringify(response.datalist.receive_material_detail)})' type='button' id="delRMt${i + 1}">ลบ <span class='fas fa-trash'></span></button>


       
            <button class="btn btn-danger" style="display:none;" onclick='disableEdit(${i+1})' type="button" id="cancelRMt${i + 1}">ยกเลิก <span class='glyphicon glyphicon-fix'></span></button>
            <button class="btn btn-success  mx-2" style="display:none;" onclick='editRmtList(${i+1})' type="button" id="editRMt${i + 1}">บันทึก<span class='glyphicon glyphicon-fix'></span></button>
           
      
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
        document.getElementById("rmtEdit").innerHTML=innerhtml;


    }
    


</script>



<script type=text/javascript>

     function getReceiveMtList(){
        let uri= "http://127.0.0.1:8080/project/api/admin/back_get_receive_mt_list.php";
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
                        <th width='20%' class='hidden-xs text-center'>วันที่รับวัตถุดิบ</th>
                            <th class='text-center' width='20%'>ID</th>
                            <th class='text-center' width='20%'>ชื่อร้านวัตถุดิบ</th>
                            <th class='text-center' width='20%' class='hidden-xs'>จำนวนรายการสินค้า</th>
                            <th class='text-center' width='20%'>แก้ไข</th>
                          </tr>
                        </thead>
                        `;

                    for(let i=0;i<response.datalist.length;i++){
                        console.log(response.datalist);
                        innerhtml = innerhtml +`<tr>
                        <td class='d-none d-md-table-cell text-center' >
                            ${response.datalist[i].date} 
                              </td>  
                          <td class='text-center'>${response.datalist[i].id} </td> 
                          
                          <td class='text-center'>
                          ${response.datalist[i].supply_name}
                          </td>
                         
                          <td class='text-center'>
                          ${response.datalist[i].detail_count}
                          </td>
                          
                          <td class='text-center'><a onclick='getReceiveMtEdit("${response.datalist[i].id}")' class='btn btn-warning text-white'><i class='fas fa-edit'></i></a>  
                          <a href='rmtDetail.php?rmt_id=${response.datalist[i].id}' class='btn btn-info text-white'><i class='fas fa-search'></i></a>
                               
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
function editAddRmtList() {
    document.getElementById('submitBtn1').addEventListener('click', function () {
        let rmt_id;
        let material = [];
        let amount = [];
        let price = [];
        rmt_id = document.getElementById("e_rmt_id").value;
        for (let i = 0; i < inputSets1.length; i++) {
            material.push(document.getElementById(inputSets1[i].dropdownId).value);
            amount.push(document.getElementById(inputSets1[i].amountId).value);
            price.push(document.getElementById(inputSets1[i].priceId).value);
        }

        

        formData = {
            "rmt_id": rmt_id,
            "material": material,
            "amount": amount,
            "price": price
        };
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "http://localhost:8080/project/api/admin/editAddRmtList.php",
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
                        window.location.replace("http://127.0.0.1:8080/project/admin/receive_mt.php");
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
function addRmt() {
    document.getElementById('submitBtn').addEventListener('click', function () {
        let mem_id, supply_name;
        let material = [];
        let amount = [];
        let price = [];

        let date = document.getElementById("date").value;
        mem_id = document.getElementById("mem_id").value;
        supply_name = document.getElementById("supply_name").value;

        for (let i = 0; i < inputSets.length; i++) {
            material.push(document.getElementById(inputSets[i].dropdownId).value);
            amount.push(document.getElementById(inputSets[i].amountId).value);
            price.push(document.getElementById(inputSets[i].priceId).value);
        }

        console.log(material + amount + price);

        formData = {
            "mem_id": mem_id,
            "supply_name": supply_name,
            "date":date,
            "material": material,
            "amount": amount,
            "price": price
        };

        $.ajax({
            type: "POST",
            url: "http://localhost:8080/project/api/admin/addRmt.php",
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
                        window.location.replace("http://127.0.0.1:8080/project/admin/receive_mt.php");
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


    


<script>
function rmtDel(idIn,receive_material_list){
   let uri= "http://127.0.0.1:8080/project/api/admin/delRmt.php";
   let idParam = idIn;
   let receiveMtList = receive_material_list;
   let areAllEqual = true;

receiveMtList.forEach(item => {
  if (item.net !== item.amount) {
    areAllEqual = false;
  }
});
console.log(areAllEqual); 



if(areAllEqual == true){
   let id = {
               "id" : idParam
           };
           Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "ต้องการยกเลิกการรับวัตถุดิบนี้ใช่หรือไม่!",
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
                       
                            setTimeout(function () {
                            window.location.replace("http://127.0.0.1:8080/project/admin/receive_mt.php");
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
    }else{
        Swal.fire({
        icon: "error",
        title: "ไม่สามารถลบได้",
        text: "เนื่องจากมีการเบิกใช้วัตถุดิบแล้ว",
     
        });

    }

    } 

    

</script>

<script>
function rmtDelList(idIn,mtIn,receive_material_list){
   let uri= "http://127.0.0.1:8080/project/api/admin/delRmtList.php";
   let idParam = idIn;
   let mtParam = mtIn;
//    console.log(idParam);

    if(receive_material_list.length > 1){
      
   let id = {
               "id" : idParam,
               "mt_id":mtParam
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
                            getReceiveMtEdit(idParam, () => {
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
        rmtDel(idParam,receive_material_list);    
} 

}


</script>





<script>

function enableEdit(i) {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น false
    document.getElementById('e_mt_id'+i).disabled = true;
    document.getElementById('e_amount'+i).readOnly = false;
    document.getElementById('e_price'+i).readOnly = false;
   

    // แสดงปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('editRMt'+i).style.display = 'inline-block';
    document.getElementById('cancelRMt'+i).style.display = 'inline-block';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('requestRMt'+i).style.display = 'none';
    document.getElementById('delRMt'+i).style.display = 'none';
    console.log(i);
}
</script>
<script>
function disableEdit(i) {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น true
    document.getElementById('e_mt_id'+i).disabled = true;
    document.getElementById('e_amount'+i).readOnly = true;
    document.getElementById('e_price'+i).readOnly = true;
   

    // ซ่อนปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('editRMt'+i).style.display = 'none';
    document.getElementById('cancelRMt'+i).style.display = 'none';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('requestRMt'+i).style.display = 'block';
    document.getElementById('delRMt'+i).style.display = 'block';
    console.log(i);
}
</script>


<script>

function enableEditRmt() {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น false

    document.getElementById('e_supply_name').readOnly = false;
   
    // แสดงปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('editRMtTop').style.display = 'block';
    document.getElementById('cancelRMtTop').style.display = 'block';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('requestRMtTop').style.display = 'none';


}
</script>
<script>
function disableEditRmt() {
    // เปลี่ยน readonly ของ input ทุกช่องในรายการนี้เป็น true
    document.getElementById('e_supply_name').readOnly = true;


    // ซ่อนปุ่ม "บันทึก${i + 1}" และ "ยกเลิก${i + 1}"
    document.getElementById('editRMtTop').style.display = 'none';
    document.getElementById('cancelRMtTop').style.display = 'none';

    // ซ่อนปุ่ม "แก้ไข${i + 1}"
    document.getElementById('requestRMtTop').style.display = 'block';


}
</script>






<script>
   function editRmtList(i){
    let rmt_id;
    let mt_id;
    let amount;
    let old_net;
    let net;
    let price;
    let old_amount;
    rmt_id = document.getElementById("e_rmt_id").value;
    mt_id = document.getElementById("e_mt_id"+i).value;
    amount = document.getElementById("e_amount"+i).value;
    old_amount = document.getElementById("e_old_amount"+i).value;
    net = document.getElementById("e_net"+i).value;
    old_net = document.getElementById("e_old_net"+i).value;
    price = document.getElementById("e_price"+i).value;
    old_price = document.getElementById("e_old_price"+i).value;

    let request_data = {
        "rmt_id":rmt_id ,
        "mt_id": mt_id,
        "amount":amount ,
        "old_amount": old_amount,
        "net": net,
        "old_net": old_net,
        "price": price,
        "old_price":old_amount
    };

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/editRmtList.php', // Update with the actual path to your PHP script
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
                            getReceiveMtEdit(rmt_id, () => {
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
   function editRmt(){
    let rmt_id;
    let supply_name;

    rmt_id = document.getElementById("e_rmt_id").value;
    supply_name = document.getElementById("e_supply_name").value;

    let request_data = {
        "rmt_id":rmt_id ,
        "supply_name": supply_name,
     
    };

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/editRmt.php', // Update with the actual path to your PHP script
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
            disableEditRmt();

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