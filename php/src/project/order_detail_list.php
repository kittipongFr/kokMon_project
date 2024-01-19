<script>
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}


function getOrderDetail() {
    let uri = "http://127.0.0.1:8080/project/api/get_order_detail.php";
    let innerhtml = "";
    const queryString = window.location.search;

    // สร้าง URLSearchParams object เพื่อจัดการ query string
    const urlParams = new URLSearchParams(queryString);

    const idParam = urlParams.get('order_id');
    console.log(idParam);
    let dataPrice = [];
    let dataId = {
        "order_id": idParam
    }
    let statusShow = ``;
    let btnCancel = ``;
    let btnRequest = ``;
    let pay_type = "";
    let btnShowPay = ``;
    $.ajax({
        type: "POST",
        url: uri,
        async: false,
        data: JSON.stringify(dataId),
        success: function (response) {
            if (response.result == 1) {
                

                if(response.reject_detail){
                cancel_detail = `<span>วันที่ปฏิเสธ : ${response.reject_detail[0].date}</span><br>
                <span>สาเหตุที่ปฏิเสธ : ${response.reject_detail[0].detail}</span>`;
            }

                if(response.cancel_detail){
                cancel_detail = `<span>วันที่ยกเลิก : ${response.cancel_detail[0].date}</span><br>
                <span>สาเหตุที่ยกเลิก : ${response.cancel_detail[0].detail}</span>`;
            }
            

                switch (response.data[0].status + '&' + response.data[0].pay_type+'&' + response.data[0].shipping_type) {
            case "0&0&0":
                console.log("รอการยืนยันสั่งซื้อ/โอนชำระ/จัดส่งตามที่อยู่");
                statusShow = `<span class='badge rounded-pill bg-warning text-black' style='font-size:13pt'>รอการอนุมัติคำสั่งซื้อ</span>`;
                btnRequest = `<button type='button' class='btn btn-danger float-right' data-bs-toggle='modal' data-bs-target='#modalCancel'>
  ยกเลิกคำสั่งซื้อ
</button>`;
                btnCancel = ``;
                break;
            case "1&0&0":
             
                statusShow = `<span class='badge rounded-pill bg-success' style='font-size:13pt'>อนุมัติคำสั่งซื้อ</span>`;
                btnRequest = `<button class='btn btn-info text-white float-right' data-bs-toggle='modal' data-bs-target='#modalTransfer' >โอนชำระ <i class="fas fa-credit-card"></i></button>`;
                btnCancel = ``;
                break;
            case "2&0&0":
                statusShow = `<span class='badge rounded-pill bg-warning' style='font-size:13pt'>รอการยืนยันการชำระ</span>`;
                btnRequest = `<button class='btn btn-info text-white float-right' data-bs-toggle='modal' data-bs-target='#modalTransfer' >โอนชำระ <i class="fas fa-credit-card"></i></button>`;
                btnCancel = ``;
                btnShowPay=`<button class='btn btn-warning text-white float-right' onclick='getPayment("${response.data[0].order_id}")' data-bs-toggle='modal' data-bs-target='#modalPayment' >ประวัติการแจ้งโอนชำระ <i class="fas fa-credit-card"></i></button>`;
                break;
            case "3&0&0":
                statusShow = `<span class='badge rounded-pill bg-warning text-black' style='font-size:13pt'>รอการจัดส่งสินค้า</span>`;
                btnRequest = ``;
                btnCancel = ``;
                btnShowPay=`<button class='btn btn-warning text-black float-right' onclick='getPayment("${response.data[0].order_id}")' data-bs-toggle='modal' data-bs-target='#modalPayment' >ประวัติการแจ้งโอนชำระ <i class="fas fa-credit-card"></i></button>`;
                
                break;
            case "4&0&0":
                statusShow = `<span class='badge rounded-pill bg-warning' style='font-size:13pt'>จัดส่งแล้ว รอลูกค้ายืนยัน</span>`;
                btnRequest = `<button class='btn btn-success text-white float-right' onclick='confirmReceive("${response.data[0].order_id}","10")' >ยืนยันการรับสินค้า</button>`;
                btnCancel = ``;
                btnShowPay=`<button class='btn btn-warning text-black float-right' onclick='getPayment("${response.data[0].order_id}")' data-bs-toggle='modal' data-bs-target='#modalPayment' >ประวัติการแจ้งโอนชำระ <i class="fas fa-credit-card"></i></button>`;
                
                break;
            case "6&0&0":
                console.log("การสั่งซื้อเสร็จสิ้น/โอนชำระ/จัดส่งตามที่อยู่ลูกค้า");
                break;
            case "7&0&0":
                statusShow = `<span class='badge rounded-pill bg-danger' style='font-size:13pt'>ยกเลิกคำสั่งซื้อแล้ว</span>`;
                btnRequest = ``;
                btnCancel = ``;
                break;
            case "8&0&0":
                statusShow = `<span class='badge rounded-pill bg-danger' style='font-size:13pt'>ปฏิเสธคำสั่งซื้อแล้ว</span>`;
                btnRequest = ``;
                btnCancel = ``;
                break;
            case "9&0&0":
                statusShow = `<span class='badge rounded-pill bg-danger' style='font-size:13pt'>รอการชำระให้สมบูรณ์</span>`;
                btnRequest = `<button class='btn btn-info text-white float-right' data-bs-toggle='modal' data-bs-target='#modalTransfer' >แจ้งชำระอีกครั้ง <i class="fas fa-credit-card"></i></button>`;
                btnCancel = ``;
                btnShowPay=`<button class='btn btn-warning text-white float-right' onclick='getPayment("${response.data[0].order_id}")' data-bs-toggle='modal' data-bs-target='#modalPayment' >ประวัติการแจ้งโอนชำระ <i class="fas fa-credit-card"></i></button>`;
                break;
            case "10&0&0":
                statusShow = `<span class='badge rounded-pill bg-success' style='font-size:13pt'>คำสั่งซื้อเสร็จสมบูรณ์</span>`;
                btnRequest = ``;
                btnCancel = ``;
                btnShowPay=`<button class='btn btn-warning text-white float-right' onclick='getPayment("${response.data[0].order_id}")' data-bs-toggle='modal' data-bs-target='#modalPayment' >ประวัติการแจ้งโอนชำระ <i class="fas fa-credit-card"></i></button>`;
                break;
            case "0&1&0":
                statusShow = `<span class='badge rounded-pill bg-warning text-black' style='font-size:13pt'>รอการอนุมัติคำสั่งซื้อ</span>`;
                btnRequest = `<button type='button' class='btn btn-danger float-right' data-bs-toggle='modal' data-bs-target='#modalCancel'>
                                ยกเลิกคำสั่งซื้อ
                                </button>`;
                btnCancel = ``;
                break;
            case "1&1&0":
                statusShow = `<span class='badge rounded-pill bg-warning text-black' style='font-size:13pt'>รอจัดส่งสินค้า</span>`;
                btnRequest = ``;
                btnCancel = ``;
                break;
            case "2&1&0":
                console.log("4&1&0: กระทำตามที่คุณต้องการ");
                break;
            case "3&1&0":
                statusShow = `<span class='badge rounded-pill bg-warning text-black' style='font-size:13pt'>รอการจัดส่งสินค้า</span>`;
                btnRequest = ``;
                btnCancel = ``;
                btnShowPay=``;
                
                break;
            case "4&1&0":
                statusShow = `<span class='badge rounded-pill bg-warning text-black' style='font-size:13pt'>จัดส่งแล้ว รอลูกค้ายืนยัน <i class="fas fa-truck-moving"></i></span>`;
                btnRequest = `<button class='btn btn-success text-white float-right' onclick='confirmReceive("${response.data[0].order_id}","6")' >ยืนยันการรับสินค้า</button>`;
                btnCancel = ``;
                btnShowPay=``;
                break;
            case "6&1&0":
                statusShow = `<span class='badge rounded-pill bg-warning text-black' style='font-size:13pt'>รอการการบันทึกรับเงิน</span>`;
                btnRequest = ``;
                btnCancel = ``;
                btnShowPay=``;
                break;
            case "7&1&0":
                statusShow = `<span class='badge rounded-pill bg-danger' style='font-size:13pt'>ยกเลิกคำสั่งซื้อแล้ว</span>`;
                btnRequest = ``;
                btnCancel = ``;
                break;
            case "8&1&0":
                statusShow = `<span class='badge rounded-pill bg-danger' style='font-size:13pt'>ปฏิเสธคำสั่งซื้อแล้ว</span>`;
                btnRequest = ``;
                btnCancel = ``;
                break;
            case "10&1&0":
                statusShow = `<span class='badge rounded-pill bg-success' style='font-size:13pt'>คำสั่งซื้อเสร็จสมบูรณ์</span>`;
                btnRequest = `<button type='button' class='btn btn-success float-right' onclick='getAcceptMoney("${response.data[0].order_id}","${response.data[0].status}")' data-bs-toggle='modal' data-bs-target='#modalAcceptMoney'>
                       ประวัติการรับเงิน
                        </button>`;
                btnCancel = ``;
                btnShowPay=``;
                break;
            default:
                console.log("ไม่มี case ที่ตรงกับค่าที่กำหนด");
        
        
            //มารับเอง
            case "0&1&1":
                statusShow = `<span class='badge rounded-pill bg-warning' style='font-size:13pt'>รอการอนุมัติคำสั่งซื้อ</span>`;
                btnRequest = `<button type='button' class='btn btn-danger float-right' data-bs-toggle='modal' data-bs-target='#modalCancel'>
  ยกเลิกคำสั่งซื้อ
</button>`;
                btnCancel = ``;
        
                break;
            case "5&1&1":
                statusShow = `<span class='badge rounded-pill bg-warning' style='font-size:13pt'>รอลูกค้ามารับที่ร้าน</span>`;
                btnRequest = ``;
                btnCancel = ``;
                break;
        
        case "7&1&1":
        statusShow = `<span class='badge rounded-pill bg-danger' style='font-size:13pt'>ยกเลิกคำสั่งซื้อแล้ว</span>`;
                btnRequest = ``;
                btnCancel = ``;
                break;
        case "8&1&1":
        statusShow = `<span class='badge rounded-pill bg-danger' style='font-size:13pt'>ปฏิเสธคำสั่งซื้อแล้ว</span>`;
                btnRequest = ``;
                btnCancel = ``;
                break;
                }
        
        switch (response.data[0].pay_type) {
            case "0":
            pay_type = `<span class='badge rounded-pill bg-success' style='font-size:13pt'>โอนจ่าย</span>`;
            break;
            case "1":
            pay_type = `<span class='badge rounded-pill bg-info' style='font-size:13pt'>ชำระปลายทาง</span>`;
            break;
        
          }
           
          
        
                        innerhtml += `
                                <div class='col-lg-8'>
                                    <div class='card mb-4'>
                                        <div class='card-body'>
                                            <div class='mb-3 d-flex justify-content-between'>
                                                <div>
                                                
                                                    <span class='me-3'>วันที่สังซื้อสินค้า : ${response.data[0].date}</span>
                                        <br>
                                                    <div class='d-flex'>
                                                    
                                                   <div class='' id='statusShow'></div>
                                                   </div>
                                                 
                                                   <div class='' id='cancel_detail'></div>
                                               
                                                </div>
                                                <div class='d-flex'>
                                                    <button class='btn  p-0 me-3 d-none d-lg-block btn-icon-text'> <span class='text'>Invoice</span><i class="fas fa-file-invoice"></i></button>
                                                    <div class='dropdown'>
                                                        <button class='btn btn-link p-0 text-muted' type='button' data-bs-toggle='dropdown'>
                                                            <i class='bi bi-three-dots-vertical'></i>
                                                        </button>
                                                        <ul class='dropdown-menu dropdown-menu-end'>
                                                            <li><a class='dropdown-item' href='#'><i class='bi bi-pencil'></i> Edit</a></li>
                                                            <li><a class='dropdown-item' href='#'><i class='bi bi-printer'></i> Print</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class='table table-borderless'>
                                                <tbody>
                                                <thead>
                                                <td></td>
                                                <td><strong>จำนวน</strong></td>
                                                <td align='right'><strong>ราคา/หน่วย</strong></td>
                                                <td align='right'><strong>เป็นเงิน</strong></td>
                                                </thead>
                                            `;
        
                        // Loop รายการ order_detail
                        response.data[0].order_detail.forEach(function (orderDetail) {
                    let inputImg = orderDetail.img;
                    let imgArray = [];
                // ตรวจสอบว่ามี "," หรือไม่
                if (inputImg.includes(',')) {
                    // มี ","
                    imgArray = inputImg.split(',');
                } else {
                    // ไม่มี ","
                    imgArray.push(inputImg);
                }
                            innerhtml += `
                            <tr>
                            <td>
                            <div class='d-flex mb-2'>
                            <div class='flex-shrink-0'>
                            <img src='assets/images/product/${imgArray[0]}' alt width='35' class='img-fluid'>
                            </div>
                            <div class='flex-lg-grow-1 ms-3'>
                            <h5 class=' mb-0'><a href='#' class='text-reset'>${orderDetail.name}</a></h5>
                            <span class='small'>หน่วย: ${orderDetail.unit}</span>
                            </div>
                            </div>
                            </td>
                            <td>${orderDetail.amount}</td>
                            <td class='text-end'>${numberWithCommas(orderDetail.price)}</td>
                            <td class='text-end'>${numberWithCommas(orderDetail.amount*orderDetail.price)} บาท</td>
                            </tr>
                                                        
                                `;
                        });
                        let total = calculateTotal(response.data[0].order_detail);
                        let allTotal = total + parseFloat(response.data[0].shipping_cost);
        
        
                        innerhtml += `
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                    <td></td>
                                                        <td colspan='2'>รวมเป็นเงิน</td>
                                                        <td class='text-end'>${numberWithCommas(total.toFixed(2))} บาท</td>
                                                      
                                                    </tr>
                                                    <tr>
                                                    <td></td>
        
                                                        <td colspan='2'>ค่าส่ง</td>
                                                        <td class='text-end'>${numberWithCommas(response.data[0].shipping_cost)} บาท</td>
                                                        
                                                    </tr>
                                                    <tr>
                                                    </tr>
                                                    <tr class='fw-bold'>
                                                    <td></td>
                                                        <td colspan='2'>รวมเป็นเงินทั้งสิ้น</td>
                                                        <td class='text-end'>${numberWithCommas(allTotal.toFixed(2))} บาท</td>
                                                        
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <a class='btn btn-danger' href='order_history.php'>  กลับ <i class="fas fa-sign-out-alt"></i> </a>
                                            <div class='d-flex  float-end'>
                                            <div id="btn_show_pay"></div>
                                            <div id='btnCancel'></div> 
                                            <div class='mx-2' id='btnRequest'></div>
                                      
                                            </div>
                                            
                                            
                                        </div>
                                    </div>
                                    </div>
        
                                    
                              
        
            <div class='col-lg-4'>
        
            <div class='card mb-2'>
                    <div class='card-body'>
                        <h5><strong>ข้อมูลลูกค้า <i class="fas fa-user-tie"></i></strong></h5>
                        <address>
                            <b>ชื่อ :</b>  ${response.data[0].name}<br>
                            <b>เบอร์ :</b> ${response.data[0].tel}<br>
                            <b>ที่อยู่ :</b> ${response.data[0].address}
                        </address>
                    </div>
                </div>
        
        
                <div class='card mb-2' >
                <div class='card-body' id='shippingBox0'>
                        <h5 ><strong>การจัดส่ง <i class="fas fa-shipping-fast"></i></strong>  <span class='badge rounded-pill bg-yellow' id='shipping'>รอดำเนินการ</span></h5>
                        <strong>ประเภทการจัดส่ง : <span class='text-danger' id='shipping_type0'></span></strong>
                        <span></span><br>
                        <div id='shippingShow'>
                        </div>

                        </div>
        
                    <div class='card-body' id='shippingBox1'>
                        <h5 ><strong >การจัดส่ง <i class="fas fa-shipping-fast"></i></strong>  <span class='badge rounded-pill bg-yellow' id='shipping'>รอดำเนินการ</span></h5>
                        <strong>ประเภทการจัดส่ง :  <span id='shipping_type1'  class='text-danger'></span></strong>
                       <br>
                        </div>
        
                        </div>
        
                       
        
        
          
                <div class='card mb-2' >
                    <div class='card-body' id='payBox'>
                        <h5><b>ประเภทการชำระ <i class="fas fa-comments-dollar"></i></b> <span class='badge bg-yellow rounded-pill'>ยังไม่จ่าย</span></h5>
                        <div class='mx-2' id='pay_type'></div>
                        
                        
                        <p><span>ยอดรวมทั้งสิ้น: </span><span class='text-danger'>${allTotal.toFixed(2)} </span> บ.</p>
                    </div>
                </div>
        
             
                </div>
        
          
           
                            `;
        document.getElementById("content").innerHTML = innerhtml;
        
        
        if (response.data[0].shipping_type === "0" ) {
            document.getElementById("shipping_type0").innerHTML = "จัดส่งตามที่อยู่ลูกค้า";
            document.getElementById("shippingBox1").style.display = "none";
        } else{
            document.getElementById("shipping_type1").innerHTML = "ลูกค้ามารับที่ร้าน";
            document.getElementById("shippingBox0").style.display = "none";
            document.getElementById("payBox").style.display = "none";
        } 
        
        if (response.data[0].pay_type === "1" && response.data[0].shipping_type === "1") {
            document.getElementById("payBox").style.display = "none";
        }
        if (response.cancel_detail|| response.reject_detail) {
        document.getElementById("cancel_detail").innerHTML = cancel_detail;
        }
        document.getElementById("oidH").innerHTML = "Order #"+response.data[0].order_id;
        document.getElementById("cancel_id").value = response.data[0].order_id;
        document.getElementById("transfer_id").value = response.data[0].order_id;
        document.getElementById("transfer_total").value = allTotal;
        document.getElementById("transfer_total_show").value = numberWithCommas(allTotal);
        
       

        if (["4", "6", "10"].includes(response.data[0].status)) {
            getShipping(response.data[0].order_id);
        }



    
    } else {
                        console.log("ffff");
                    }
        
                },
                error: function (error) {
                    console.log(error);
                }
            });
            
          
            document.getElementById("statusShow").innerHTML = statusShow;
            document.getElementById("btnRequest").innerHTML = btnRequest;
            document.getElementById("btnCancel").innerHTML = btnCancel;
            document.getElementById("pay_type").innerHTML = pay_type;
            document.getElementById("btn_show_pay").innerHTML = btnShowPay;
           
        }

function calculateTotal(orderDetail) {
    let total = 0;
    orderDetail.forEach(function (item) {
        total += parseFloat(item.price) * parseFloat(item.amount);
    });
    return parseFloat(total);
}
</script>





<script>
        function cancelOrder(){
            let id = document.getElementById("cancel_id").value;
            let detail = document.getElementById("cancel_details").value;
            let request_data = {
                    "id":id,
                    "detail":detail
            }
            console.log(request_data);
            
            let uri="http://localhost:8080/project/api/cancelOrder.php";

            $.ajax({
                type:"POST",
                url:uri,
                data:JSON.stringify(request_data),
                async:false,
                success:function(response){
                console.log(response.result);
                    if(response.result === 1){
                        Swal.fire({
                    position: "center",
                    icon: "success",
                    title: response.message,
                    showConfirmButton: true,
                }).then(function() {
                    getOrderDetail();
                    $('#modalCancel').modal('hide');
                });
            }
                
                },error:function(error){
                    Swal.fire({
                        title: response.message,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });        
        }

    </script>




<script>
   function addPayment() {
    let formData = new FormData(document.getElementById("paymentForm"));

    $.ajax({
        url: 'http://localhost:8080/project/api/addPayment.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Handle the response from the server
            console.log(response);
            if (response.result == 1) {
                Swal.fire({
                    title: "เรียบร้อย",
                    text: response.message,
                    icon: "success"
                });
                getOrderDetail();
                $('#modalTransfer').modal('hide');
            } else {
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
   function getPayment(id) {
    let innerhtml = ``;
    let order_id = {"id":id}
    $.ajax({
        url: 'http://localhost:8080/project/api/getPayment.php',
        type: 'POST',
        data: JSON.stringify(order_id),
        contentType: false,
        processData: false,
        success: function(response) {

            if (response.result == 1) {
                if (response.datalist) { 
                innerhtml += `<div class="mb-3">
          <h5>รหัสคำสั่งซื้อ ${id}</h5>
          </div>
          <hr>
          `;
           for(let i=0;i<response.datalist.pay_id.length;i++){
                innerhtml += `
                <div class="mb-3">
                <h5>รายการที่ ${i+1}</h5>
                <label for="textInput"   class="form-label">รหัสการชำระ ${response.datalist.pay_id[i]}</label>
                <label for="textInput"   class="form-label">วันที่แจ้งชำระ ${response.datalist.date[i]}</label>
              
          <img class="img img-fluid" src="./assets/images/slip/${response.datalist.slip_img[i]}" alt="">
        </div>
        <hr>
                
                `;
           }
        }else{
            innerhtml += `ไม่มีรายการรับเงิน`;
           
        }

           document.getElementById("paymentShow").innerHTML = innerhtml;

            } else {
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
   function getShipping(id){
    let request_data = {
                    "id":id,
            }
      
            let uri="http://localhost:8080/project/api/getShipping.php";

            $.ajax({
                type:"POST",
                url:uri,
                data:JSON.stringify(request_data),
                async:false,
                success:function(response){
                
                    if(response.result === 1){
                        console.log(response.datalist);
                document.getElementById("shippingShow").innerHTML = `<strong>บริษัทขนส่ง : </strong>
                        <span>${response.datalist.tracking} </span><br>
                        <strong>เลขพัสดุ : </strong>
                        <span>${response.datalist.shipping_co} </span><br>
                        <strong>วันที่จัดส่ง : </strong>
                        <span>${response.datalist.date} </span><br>`;
            }
                
                },error:function(error){
                   
                }
            });        



    }
</script>



<script>
   function confirmReceive(id,status) {

    let order_id = {"id":id,
                    "status":status}
    $.ajax({
        url: 'http://localhost:8080/project/api/confirmReceive.php',
        type: 'POST',
        data: JSON.stringify(order_id),
        contentType: false,
        processData: false,
        success: function(response) {

        if (response.result == 1) {
            Swal.fire({
                    position: "center",
                    icon: "success",
                    title: response.message,
                    showConfirmButton: true,
                    
                }).then(function() {
                    getOrderDetail();
                });   
           

            } else {
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
   function getAcceptMoney(id,status) {
    let innerhtml = ``;
    let innerhtml1 = ``; 
    let alltotal = document.getElementById("transfer_total").value;
    let order_id = {"id":id}
    $.ajax({
        url: 'http://localhost:8080/project/api/admin/getAcceptMoney.php',
        type: 'POST',
        data: JSON.stringify(order_id),
        contentType: false,
        processData: false,
        success: function(response) {

            if (response.result == 1) {
        if(response.datalist){
                innerhtml += `<div class="mb-3">
          <h5>รหัสคำสั่งซื้อ ${id}</h5>
          </div>
          <hr>
          `;
           for(let i=0;i<response.datalist.accept_money_id.length;i++){
                innerhtml += `
                <div class="mb-3">
                <h5>รายการที่ ${i+1}</h5>
                <p>รหัสการรับเงิน : ${response.datalist.accept_money_id[i]}</p>
                <p>ยอดการรับเงิน : ${response.datalist.accept_money_total[i]}</p>
                <p>วันที่รับเงิน : ${response.datalist.date[i]}</p>
              
          
        </div>
        <hr>
                
                `;
           }
        
           innerhtml1 += `
           <h5>ยอดที่ต้องชำระ : <span class='text-danger'>${alltotal}</span> บาท</h5>  
           <h5>รวมยอดการรับเงิน : <span class='text-danger'>${response.datalist.sum_total}</span> บาท</h5>  
           <h5>ยอดที่ต้องจ่ายคงเหลือ : <span class='text-danger'>${alltotal-response.datalist.sum_total}</span> บาท</h5>  

        `;
        }else{
            innerhtml += `ไม่มีรายการรับเงิน`;
            innerhtml1 += ``;
        }
           document.getElementById("acceptMoneyShow").innerHTML = innerhtml;
           document.getElementById("acceptMoneyShowTotal").innerHTML = innerhtml1;
            } else {
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