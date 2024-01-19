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
    let uri = "http://127.0.0.1:8080/project/api/admin/get_mtu_detail.php";
    let innerhtml = "";
    const queryString = window.location.search;

    // สร้าง URLSearchParams object เพื่อจัดการ query string
    const urlParams = new URLSearchParams(queryString);

    const idParam = urlParams.get('mtu_id');

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
                                        
                                            <h4 class='me-3'>รหัสการบันทึกวัตถุดิบใช้ไป : <span class='me-3 text-danger'>${response.datalist.material_used.mtu_id}</span></h4>
                                             <br>
                                             <span class='me-3 '>บันทึกเมื่อ : ${response.datalist.material_used.date}</span>
                                            <div class=''>
                                      
                                          <h5>เจ้าหน้าที่ : ${response.datalist.material_used.mem_fname} ${response.datalist.material_used.mem_lname}</h5>
                                           </div>
                                        </div>
                                        <div class=''>
                                         
                                        </div>
                                    </div>
                                    <table class='table table-borderless'>
                                        
                                        <thead>
                                        
                                        <td align='center' width="25%"><strong>รหัสสินค้า</strong></td>
                                        <td align='center' width="25%"><strong>ชื่อ</strong></td>
                                        <td align='center' width="25%" ><strong>จำนวน</strong></td>
                                        <td align='center' width="25%" ><strong>ต้นทุนการผลิต</strong></td>
                                        </thead>
                                        <tbody>
                                    `;
                               

                 
                    response.datalist.material_used_detail.forEach(function (mftDetail) {
                      console.log(mftDetail.mt_name);
                    innerhtml += `
                    <tr>

                   
                    <td class='text-center'>
                   ${mftDetail.material_id}
                    </td>
                    <td class='text-center'>
                    <h5 class=' mb-0'><a href='#' class='text-reset'>${mftDetail.pro_name}</a></h5>
                    <span class='small'>หน่วย: ${mftDetail.pro_unit}</span></td>

                    <td class='text-center'>${numberWithCommas(mftDetail.amount)} </td>
                    <td class='text-center'>${numberWithCommas(mftDetail.cost)}  บาท</td>
          
                    </tr>
                    
                                                
                        `;
                        $total += mftDetail.cost;
                        
                });


innerhtml += `
    </tbody>
    <tfoot>
       
       
       <tr></tr>
       <tr class='fw-bold'>
         
           <td colspan='2'></td>
           <td style='font-size:18pt' class='text-center'> รวมต้นทุนการผลิตทั้งสิ้น </td>
           <td style='font-size:18pt' class='text-center'><span class='text-danger'>${numberWithCommas($total)}</span>&nbsp&nbsp&nbspบาท</td>
           <td></td>
       </tr>
   </tfoot>
</table>
<a class='btn btn-danger' href='mt_used.php'> กลับ <i class="fas fa-sign-out-alt"></i> </a>
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

