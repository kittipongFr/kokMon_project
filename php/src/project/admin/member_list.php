
<script type=text/javascript>
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
     function getMemList(){
        let uri= "http://127.0.0.1:8080/project/api/admin/back_get_member_list.php";
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
                    let status ;
                    

                    
                    innerhtml = innerhtml+ `<table id='example1' class='table table-bordered table-striped'>
                        <thead>
                        <tr class=''>
                            <th  class='text-center'>ID</th>
                            <th  class='hidden-xs text-center'>รูป</th>
                            <th  class='hidden-xs text-center'>ชื่อสินค้า</th>
                            <th  class='text-center'>จำนวนคงเหลือ</th>
                            <th   class='text-center'>จำนวนการจอง</th>
                        
                            <th   class='text-center'></th>
                          </tr>
                        </thead>
                        `;

                    for(let i=0;i<response.datalist.length;i++){
       
                        if(response.datalist[i].role === "0"){
                        status = "ประธาน";
                    }else if(response.datalist[i].role === "1"){
                        status = "เจ้าหน้าที่บันทึกข้อมูล";
                    }else{
                        status = "สมาชิก";
                    }
                        innerhtml = innerhtml +`<tr>
                          <td>${response.datalist[i].id} </td> 
                          <td class='hidden-xs text-center'> ${response.datalist[i].fname}   ${response.datalist[i].lname}</td>
                          <td class='hidden-xs text-center'>
                          ${response.datalist[i].tel}
                          </td>
                          <td  class='text-center'> 
                          ${response.datalist[i].email}
                          </td>
                          <td  class='text-center'>
                          ${status}
                          </td  > 
                        
                          <td  class='text-center'>
       
                          <button  class='btn btn-warning text-white ' onclick='getMemEdit("${response.datalist[i].id}")' data-bs-toggle="modal" data-bs-target="#modal2"><i class='fas fa-edit'></i></button>
                                <button  onclick='delMem("${response.datalist[i].id}")'class='btn btn-danger text-white '><i class='fas fa-trash '></i></button>
 
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
     function getMemEdit(id){
        let uri= "http://127.0.0.1:8080/project/api/admin/get_member_edit.php";
        let innerhtml = "";
        let mem_id = {
            "id":id
        }
        console.log(id);
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:JSON.stringify(mem_id),
            success:function(response){
                if(response.result==1){
                   
                    
  
        innerhtml += `
        <p>ชื่อ : ${response.datalist.name}</p>
      <div class="mb-3">
          <label for="textInput"   class="form-label">ตำแหน่ง</label>
          <input type="hidden"  id="e_mem_id" value="${id}" class="form-control" >
          <select class="form-control" name="" id="e_role">
  <option value="0" ${response.datalist.role === '0' ? 'selected' : ''}>ประธาน</option>
  <option value="1" ${response.datalist.role === '1' ? 'selected' : ''}>เจ้าหน้าที่บันทึกข้อมูล</option>
  <option value="2" ${response.datalist.role === '2' ? 'selected' : ''}>สมาชิก</option>
</select>


        </div>

                    `;   

    
                
                }else{
                    console.log(response.message);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
   
        document.getElementById("contentEdit").innerHTML=innerhtml;


    }







</script>






<script>
   function addMember(){

    let fname = document.getElementById("fname").value;
    let lname = document.getElementById("lname").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let tel = document.getElementById("tel").value;
    let address = document.getElementById("address").value;
    let role = document.getElementById("role").value;


    let request_data = {
        "fname":fname ,
        "lname":lname ,
        "email":email ,
        "password":password ,
        "tel":tel ,
        "address":address ,
        "role": role
    };

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/add_member.php', // Update with the actual path to your PHP script
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
    
                        getEnvironment();
                    $('#modal1').modal('hide');
          

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
   function editMem(){
    let mem_id = document.getElementById("e_mem_id").value;
    let role = document.getElementById("e_role").value;

    let request_data = {
        "id":mem_id ,
        "role":role 
    };

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/edit_member.php', // Update with the actual path to your PHP script
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
                        getEnvironment();
                    $('#modal2').modal('hide');
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
   function delMem(id){

    let request_data = {
        "id":id 
     
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
        url: 'http://localhost:8080/project/api/admin/del_member.php', // Update with the actual path to your PHP script
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
                        getEnvironment();
                 

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