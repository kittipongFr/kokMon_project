<?php include('h.php');?>
<body  onload="getEnvironment()" class="hold-transition skin-purple sidebar-mini">
  <div class="wrapper">
    <!-- Main Header -->
    <?php include('menutop.php');?>
    <!-- Left side column. contains the logo and sidebar -->
    
        <?php include('menu_l.php');?>
    <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <i class="glyphicon glyphicon-user hidden-xs"></i> <span class="hidden-xs">ข้อมูลการรับวัตถุดิบในระบบ</span>
        <a  class="btn btn-primary btn-sm"   id="openFormBtn">เพิ่มรายการรับวัตถุดิบ <i class="fa fa-plus"></i></a>   
        <!-- href="product.php?act=add" -->
        </h1>
         
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="row">
                <div class="col-sm-12">
                <div class="data_table" >
                  <div class="box-body"  id="content">
                   




                  
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</script>





<script type=text/javascript>

     function getOrderList(){
        let uri= "http://127.0.0.1:8080/project/api/admin/back_get_order.php";
        let innerhtml = "";
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:null,
            success:function(response){
                if(response.result==1){
               
                    innerhtml = innerhtml+ `<table id='example1' class='table table-striped table-light table-borderless table-hover text-center'>
                        <thead>
                        <tr class=''>
                            <th class='d-none d-md-table-cell'>วันที่</th>
                            <th >รหัส</th>
                            <th class='d-none d-md-table-cell'>จำนวนรายการ</th>
                            <th class='d-none d-md-table-cell'>ราคารวม</th>
                            <th>สถานะ</th>
                            <th>รายละเอียด</th>
                          </tr>
                        </thead>
                        `;

                    for(let i=0;i<response.datalist.length;i++){
                      let  order =  response.datalist[i];

            if (order.status === "0") {
            statusShow = `<span class='badge rounded-pill  text-black' style='background-color:#E9DC0F'>รอการอนุมัติคำสั่งซื้อ</span>`;
            } else if (order.status === "1") {
            statusShow = `<span class='badge rounded-pill ' style='background-color:green'>อนุมัติคำสั่งซื้อ</span>`;
            }   
                        console.log(response.datalist);
                        innerhtml = innerhtml +`<tr>
                          <td class='d-none d-md-table-cell'>${order.date} </td> 
                          
                          <td >
                          ${order.order_id}
                          </td>
                          <td class='d-none d-md-table-cell' >
                          ${order.count}
                              </td>  
                          <td class='d-none d-md-table-cell'>
                          ${order.total}
                          </td>
                          <td>
                          ${statusShow}
                          </td>
                          
                          <td> 
                          <a class='btn btn-sm btn-info' href='http://127.0.0.1:8080/project/admin/order_detail.php?order_id=${order.order_id}'>
                            <i class='fa fa-search'></i>
                        </a>
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
        document.getElementById("content").innerHTML=innerhtml;


    }
    
</script>




<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
</script>





<script type=text/javascript>
    function getEnvironment(){
        let member_profile = localStorage.getItem("member_profile");
        member_profile = JSON.parse(member_profile);
        console.log(member_profile.id);      
        // document.getElementById("mem_id").value =  member_profile.id;
        
        getOrderList();
    }

      
    </script>
   






<?php include('footerjs.php');?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
  </html>











 
