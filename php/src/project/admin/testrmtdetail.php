<!doctype html>
<html lang="en">
  <head>
  	<title>Sidebar 07</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <title>วิสาหกิจบ้านโคกมอน</title>
    <!-- Tell the browser to be responsive to screen width -->

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- <link href="../assets/bower_components/font-awesome/css/font-awesome.min.css"rel="stylesheet"> -->
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/bower_components/DataTables-1.13.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="../assets/bower_components/Ionicons/css/ionicons.min.css"> -->
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-xxx" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="../assets/bower_components/DataTables-1.13.8/css/dataTables.bootstrap.min.css"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../assets/dist/css/skins/_all-skins.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="../assets/fonts/font.css"> -->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mitr">

    <style>
        /* .boxs{
             box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
        } */
    body {
        font-family: "Mitr", serif;
    }

    .card .card-body{
    font-family: "Mitr", serif;
    }
    
    </style>

		<link rel="stylesheet" href="../css/style.css">
  </head>
  <body onload="getOrderDetail()">
		
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar" class="active">
				<h1><a href="index.html" class="logo">M.</a></h1>
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="#"><span class="fa fa-home"></span> Home</a>
          </li>
          <li>
              <a href="#"><span class="fa fa-user"></span> About</a>
          </li>
          <li>
            <a href="#"><span class="fa fa-sticky-note"></span> Blog</a>
          </li>
          <li>
            <a href="#"><span class="fa fa-cogs"></span> Services</a>
          </li>
          <li>
            <a href="#"><span class="fa fa-paper-plane"></span> Contacts</a>
          </li>
        </ul>

        <div class="footer">
        	<p>
					  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib.com</a>
					</p>
        </div>
    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="btn btn-primary">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>
            <!-- <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="nav navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Portfolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
              </ul>
            </div> -->

            <div class="d-flex" >
<span>กิตติพงษ์ พาบุดดา</span>

            </div>




          </div>
        </nav>

<!-- content เริ่ม -->

<h3 class="mx-4"><span>รายละเอียดการรับวัตถุดิบ</span></h3>
<div id="contentDetail">



</div>

                                     
                     
                    
                   


<script>
function getOrderDetail() {
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
              
                innerhtml += `
                
                  
                        <div class='col-lg-12'>
                            <div class='card mb-4'>
                                <div class='card-body'>
                                    <div class='mb-3 d-flex justify-content-between'>
                                        <div>
                                        
                                            <h4 class='me-3'>รหัสการรับวัตถุดิบ : <span class='me-3 text-danger'>${response.datalist.receive_material.rmt_id}</span></h4>
                                             <br>
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
                                        <td align='center' width="25%"><strong>จำนวนรับ</strong></td>
                                        <td align='center' width="25%"><strong>จำนวนคงเหลือ</strong></td>
                                        <td align='center' width="25%" ><strong>ราคา/หน่วย</strong></td>
                                        <td align='center' width="25%" ><strong>เป็นเงิน</strong></td>
                                        </thead>
                                        <tbody>
                                    `;
                               

                 
                    response.datalist.receive_material_detail.forEach(function (rmtDetail) {
                      console.log(rmtDetail.mt_name);
                    innerhtml += `
                    <tr>
                    <td class='text-center'>
                  
                   
                    <h5 class=' mb-0'><a href='#' class='text-reset'>${rmtDetail.mt_name}</a></h5>
                    <span class='small'>หน่วย: ${rmtDetail.mt_unit}</span>
                    
                    </td>
                    <td class='text-center'>${rmtDetail.amount}</td>
                    <td class='text-center'>${rmtDetail.price}</td>
                    <td class='text-center'>${rmtDetail.net}</td>
                    <td class='text-center'>${rmtDetail.amount*rmtDetail.price} &nbsp&nbsp&nbspบาท</td>
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
            <td style='font-size:18pt' class='text-center'><span class='text-danger'>${$total}</span>&nbsp&nbsp&nbspบาท</td>
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






        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../assets/bower_components/DataTables-1.13.8/js/datatables.min.js"></script>

<script src="../assets/bower_components/DataTables-1.13.8/js/pdfmake.min.js"></script>
<script src="../assets/bower_components/DataTables-1.13.8/js/vfs_fonts.js"></script>



<!-- <script src="../assets/bower_components/DataTables-1.13.8/js/dataTables.bootstrap.min.js"></script> -->
<script src="../assets/dist/js/adminlte.min.js"></script>
<script src="../assets/dist/js/demo.js"></script>




<style>
.data_table{
  padding: 5px;
}

.data_table .btn{
 
  margin: 5px 3px 5px 3px;
}

</style>

<style type="text/css">
    	body{
    background:#eee;
}
.card {
    box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: 1rem;
}
.text-reset {
    --bs-text-opacity: 1;
    color: inherit!important;
}
a {
    color: #5465ff;
    text-decoration: none;
}
    </style>







</style>
    <script src="../js/popper.js"></script>

    <script src="../js/main.js"></script>
  </body>
</html>