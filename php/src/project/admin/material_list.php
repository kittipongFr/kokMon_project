
<script type=text/javascript>
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
     function getMaterialList(){
        let uri= "http://127.0.0.1:8080/project/api/admin/back_get_material_list.php";
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
                            <th  class='text-center'>ID</th>
                            <th  class='hidden-xs text-center'>รูป</th>
                            <th  class='hidden-xs text-center'>ชื่อวัตถุดิบ</th>
                            <th  class='text-center'>จำนวนคงเหลือ</th>
                            <th  class='hidden-xs text-center'>หน่วยการนับ</th>  
                            <th   class='text-center'></th>
                          </tr>
                        </thead>
                        `;

                    for(let i=0;i<response.datalist.length;i++){
                        amount = numberWithCommas(response.datalist[i].amount);
                
                        console.log(response.datalist);
                        innerhtml = innerhtml +`<tr>
                          <td>${response.datalist[i].id} </td> 
                          <td class='hidden-xs text-center'>
                          <img src='../assets/images/material/${response.datalist[i].img}' width='100px'>
                          </td>
                          <td class='hidden-xs text-center'>
                          ${response.datalist[i].name}
                          </td>
                          <td  class='text-center'> 
                          ${amount}
                          </td>
                        
                            <td class='hidden-xs' align='center'>
                            ${response.datalist[i].unit} 
                              </td> 
                          <td  class='text-center'><button onclick='getMaterialEdit("${response.datalist[i].id}")'  class='btn btn-warning text-white'><i class='fas fa-edit'></i></button>   
                                <button  onclick='materialDel("${response.datalist[i].id}")'class='btn btn-danger text-white '><i class='fas fa-trash '></i></button>
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
   function addMaterial(){
   document.getElementById("submitBtn").addEventListener("click", function() {
    // Create a FormData object to easily handle the form data, including files
    var formData = new FormData(document.getElementById("productAddForm"));

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/addMaterial.php', // Update with the actual path to your PHP script
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
                window.location.replace("http://127.0.0.1:8080/project/admin/material.php");
            }, 2000);
     

            }else{
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
   function editMaterial(){
   document.getElementById("submitBtn1").addEventListener("click", function() {
    // Create a FormData object to easily handle the form data, including files
    var formData = new FormData(document.getElementById("productEditForm"));

    // Make an Ajax request
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/editMaterial.php', // Update with the actual path to your PHP script
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
                window.location.replace("http://127.0.0.1:8080/project/admin/material.php");
            }, 2000);
            
        }else{
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

function getMaterialEdit(idIn){
        let uri= "http://127.0.0.1:8080/project/api/admin/get_material_edit.php";
      

    let idParam =  idIn;
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
                    document.getElementById("e_unit").value =  response.datalist[0].unit;
                    document.getElementById("blah").src = '../assets/images/material/'+response.datalist[0].img;

                    console.log(response.message);

                }else{
                    console.log(response.message);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
   
    } 


    
    
</script>




<script>
function materialDel(idIn){
   let uri= "http://127.0.0.1:8080/project/api/admin/delMaterial.php";
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
                            window.location.replace("http://127.0.0.1:8080/project/admin/material.php");
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