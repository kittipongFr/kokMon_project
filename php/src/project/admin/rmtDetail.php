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


function getRmtDetail() {
    let uri = "http://127.0.0.1:8080/project/api/admin/get_rmt_detail.php";
    let innerhtml = "";
    const queryString = window.location.search;

    // สร้าง URLSearchParams object เพื่อจัดการ query string
    const urlParams = new URLSearchParams(queryString);

    const idParam = urlParams.get('rmt_id');

    let dataPrice = [];
    let dataId = {
        "id": idParam
    }


    let statusShow = ``;
    let btnCancel = ``;
    let btnRequest = ``;
    let pay_type = "";
    $total = 0;
    $.ajax({
        type: "POST",
        url: uri,
        async: false,
        data: JSON.stringify(dataId),
        success: function (response) {
          console.log(response.datalist);
            if (response.result == 1) {
                let amount = "";
                let price = "";
                let net = "";
       
                
                innerhtml += `
                
                  
                        <div class='col-lg-12'>
                            <div class='card mb-4'>
                                <div class='card-body'>
                                    <div class='mb-3 d-flex justify-content-between'>
                                        <div>
                                        
                                            <h4 class='me-3'>รหัสการรับวัตถุดิบ : <span class='me-3 text-danger'>${response.datalist.receive_material.rmt_id}</span></h4>
                                             <br>
                                             <span class='me-3 '>บันทึกเมื่อ : ${response.datalist.receive_material.date}</span>
                                            <div class=''>
                                            <h5>ชื่อร้าน : ${response.datalist.receive_material.supply_name}</h5> 
                                          <h5>เจ้าหน้าที่ : ${response.datalist.receive_material.mem_fname} ${response.datalist.receive_material.mem_lname}</h5>
                                           </div>
                                        </div>
                                        <div class=''>
                                         
                                        </div>
                                    </div>
                                    <table class='table table-borderless'>
                                        
                                        <thead>
                                        <td align='center' width="15%"></td>
                                        <td align='center' width="25%"><strong>ราคา/หน่วย</strong></td>
                                        <td align='center' width="25%"><strong>จำนวนรับ</strong></td>
                                        <td align='center' width="25%" ><strong>เป็นเงิน</strong></td>
                                        <td align='center' width="25%" ><strong>จำนวนคงเหลือ</strong></td>
                                       
                                        </thead>
                                        <tbody>
                                    `;
                               

                 
                    response.datalist.receive_material_detail.forEach(function (rmtDetail) {
                    //   console.log(rmtDetail.mt_name);
                      amount = numberWithCommas(rmtDetail.amount);
                      price = numberWithCommas(rmtDetail.price);
                      net  = numberWithCommas(rmtDetail.net);
                    innerhtml += `
                    <tr>
                    <td class='text-center'>
                  
                   
                    <h5 class=' mb-0'><a href='#' class='text-reset'>${rmtDetail.mt_name}</a></h5>
                    <span class='small'>หน่วย: ${rmtDetail.mt_unit}</span>
                    
                    </td>
                    <td class='text-center'>${price}</td>
                    <td class='text-center'>${amount}</td>
                    <td class='text-center'>${numberWithCommas(rmtDetail.amount*rmtDetail.price)} &nbsp&nbsp&nbspบาท</td>
                    <td class='text-center'>${net}</td>
                   
                    </tr>
                                                
                        `;
                        $total += rmtDetail.amount*rmtDetail.price;
                });


innerhtml += `
    </tbody>
    <tfoot>
       
       
        <tr></tr>
        <tr class='fw-bold'>
            <td ></td>
            <td colspan='2'></td>
            <td style='font-size:18pt' class='text-center'> รวมเป็นเงินทั้งสิ้น </td>
            <td style='font-size:18pt' class='text-center'><span class='text-danger'>${numberWithCommas($total)}</span>&nbsp&nbsp&nbspบาท</td>
            <td></td>
        </tr>
    </tfoot>
</table>
<a class='btn btn-danger' href='receive_mt.php'> กลับ <i class="fas fa-sign-out-alt"></i> </a>
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
   
   getRmtDetail();
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

