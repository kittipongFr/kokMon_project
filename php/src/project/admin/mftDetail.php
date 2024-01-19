<?php include('h.php');?>
<body  onload="get_environment()">

    <!-- Main Header -->
   
    <!-- Left side column. contains the logo and sidebar -->
    
        <?php include('menu_l.php');?>
    


<!-- content เริ่ม -->

<h3 class="mx-4"><span>รายละเอียดการรับวัตถุดิบ</span></h3>
<div id="contentDetail">



</div>

                                     
                     
                    
                   


<script>
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

function getMftDetail() {
    let uri = "http://127.0.0.1:8080/project/api/admin/get_mft_detail.php";
    let innerhtml = "";
    const queryString = window.location.search;

    // สร้าง URLSearchParams object เพื่อจัดการ query string
    const urlParams = new URLSearchParams(queryString);

    const idParam = urlParams.get('mft_id');

    let dataPrice = [];
    let dataId = {
        "id": idParam
    }


    let statusShow = ``;
    let btnCancel = ``;
    let btnRequest = ``;
    let pay_type = "";
    $total = 0;
    $unit = "";
    $.ajax({
        type: "POST",
        url: uri,
        async: false,
        data: JSON.stringify(dataId),
        success: function (response) {
          console.log(response.datalist);
            if (response.result == 1) {
              
                innerhtml += `
                
                  
                        <div class='col-lg-12'>
                            <div class='card mb-4'>
                                <div class='card-body'>
                                    <div class='mb-3 d-flex justify-content-between'>
                                        <div>
                                        
                                            <h4 class='me-3'>รหัสการรับวัตถุดิบ : <span class='me-3 text-danger'>${response.datalist.manufacture.mft_id}</span></h4>
                                             <br>
                                             <span class='me-3 '>บันทึกเมื่อ : ${response.datalist.manufacture.date}</span>
                                            <div class=''>
                                      
                                          <h5>เจ้าหน้าที่ : ${response.datalist.manufacture.mem_fname} ${response.datalist.manufacture.mem_lname}</h5>
                                           </div>
                                        </div>
                                        <div class=''>
                                         
                                        </div>
                                    </div>
                                    <table class='table table-borderless'>
                                        
                                        <thead>
                                        
                                        <td align='center' width="25%"><strong>รหัสสินค้า</strong></td>
                                        <td align='center' width="25%"><strong>ชื่อ</strong></td>
                                        <td align='center' width="25%" ><strong>รูป</strong></td>
                                        <td align='center' width="25%" ><strong>จำนวนผลิต</strong></td>
                                        </thead>
                                        <tbody>
                                    `;
                               

                 
                    response.datalist.manufacture_detail.forEach(function (mftDetail) {
                      console.log(mftDetail.mt_name);
                    innerhtml += `
                    <tr>

                   
                    <td class='text-center'>
                   ${mftDetail.pro_id}
                    </td>
                    <td class='text-center'>
                    <h5 class=' mb-0'><a href='#' class='text-reset'>${mftDetail.pro_name}</a></h5>
                    <span class='small'>หน่วย: ${mftDetail.pro_unit}</span></td>

                    <td class='text-center'><img src='../assets/images/product/${mftDetail.img}' width='100px'></td>
                    <td class='text-center'>${numberWithCommas(mftDetail.amount)} ${mftDetail.pro_unit}</td>
          
                    </tr>
                                                
                        `;
                        
                });


innerhtml += `
    </tbody>

</table>
<a class='btn btn-danger' href='manufacture.php'> กลับ <i class="fas fa-sign-out-alt"></i> </a>
<div class='d-flex float-end'>
    <div id='btnCancel'></div> 
    <div class='mx-2' id='btnRequest'></div>
</div>
</div>
</div>
</div>`
;

document.getElementById("contentDetail").innerHTML = innerhtml;

            } else {
                console.log("ผิด");
            }

        },
        error: function (error) {
            console.log(error);
        }
    });
    
  

   
}


</script>



<script>

function get_environment(){
   
    getMftDetail();
   }




</script>
    <?php



include('footerjsDetail.php');?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>





</script>
    <?php


include('footerjsDetail.php');?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>

