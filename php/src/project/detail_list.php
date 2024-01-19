<script type=text/javascript>
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}












let pro_amount = 0;
let pro_reserve = 0;
function getProductDetail(){
        let uri= "http://127.0.0.1:8080/project/api/get_product_detail.php";
        let innerhtml = "";
        const queryString = window.location.search;
    
    // สร้าง URLSearchParams object เพื่อจัดการ query string
    const urlParams = new URLSearchParams(queryString);
    
    const idParam = urlParams.get('ID');
    let dataPrice = [];
    let  dataId = {
            "pro_id":idParam
        }
        console.log(idParam);


        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:JSON.stringify(dataId) ,
            success:function(response){
                if(response.result==1){
                    
                    let qtyNet = parseFloat(response.datalist.amount-response.datalist.reserve);
                    pro_amount = response.datalist.amount;
                    pro_reserve = response.datalist.reserve;
                    // console.log(response.datalist.name);
                    //ส่งไปอัปราคา
                    dataPrice = response.datalist;
                    let inputImg = response.datalist.img;
                    let imgArray = []
                // ตรวจสอบว่ามี "," หรือไม่
                if (inputImg.includes(',')) {
                    // มี ","
                    imgArray = inputImg.split(',');
                    console.log(imgArray);
                } else {
                    // ไม่มี ","
                    imgArray.push(inputImg);
                    console.log(imgArray);
                }

                        innerhtml += `
                        <style>
            .img-container {
                width: 500px; /* กำหนดขนาดที่ต้องการ เช่น 300px */
                height: 500px; /* กำหนดขนาดที่ต้องการ เช่น 300px */
                overflow: hidden; /* ตัดขอบออกเมื่อเกินขนาดที่กำหนด */
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .img-container img {
                width: 100%; /* ทำให้รูปเต็มขนาดของ container */
                height: auto; /* รักษาสัดส่วนของรูป */
                display: block; /* ปรับลำดับการแสดงผลให้เป็น block element */
            }
        </style>



    <div class='col-lg-4 mb-30  bg-light  mx-auto   justify-content-center' style="border-radius: 1rem; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);">
               <h3 class='text-center mt-5'>รูปภาพสินค้า</h3>
                        <div id='product-carousel ' class='carousel slide' data-ride='carousel'>
                    <div class='carousel-inner bg-light '>
                        <div class='carousel-item active '>
                        <div class="img-container mx-auto">
                        <img class='w-100  img-fluid' src='./assets/images/product/${imgArray[0]}' alt='Image'>
                        </div>
                        </div> `;

                    if(imgArray[1]){
                        for (let i = 1; i < imgArray.length; i++) {
                        innerhtml +=`<div class='carousel-item'>
                        <div class="img-container mx-auto">
                        <img class='w-100  img-fluid' src='./assets/images/product/${imgArray[i]}' alt='Image'>
                        </div>
                        </div>`;
                        }
                    }
                        innerhtml +=` </div>
                    <a class='carousel-control-prev' href='#product-carousel' data-slide='prev'>
                        <i class='fa fa-2x fa-angle-left text-dark'></i>
                    </a>
                    <a class='carousel-control-next' href='#product-carousel' data-slide='next'>
                        <i class='fa fa-2x fa-angle-right text-dark'></i>
                    </a>
                </div>
                
                </div>

            <div class='col-lg-7 h-auto mb-30  bg-light'  style="border-radius: 1rem; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);">
                <div class='h-100 bg-light p-30'>
                    <h1 class='text-info'>${response.datalist.name}</h1>
                    <div class='d-flex mb-3'>
                   
                    </div>

                    <a class='h3 text-decoration-none text-truncate' href=''>จำนวนคงเหลือ : ${numberWithCommas(qtyNet)} ${response.datalist.unit}</a>


                    <h3 class='font-weight-semi-bold mb-4 mt-4 '>   <span class='text-success'>${Object.values(response.datalist.prices).length > 1
                            ? `${Math.min(...Object.values(response.datalist.prices))} - ${Math.max(...Object.values(response.datalist.prices))}`
                            : Object.values(response.datalist.prices)[0]}</span>   
                            <span >บาท/${response.datalist.unit}</span></h3>
                            <p class='mb-4'><h6><b>ข้อมูลราคา: </b></h6></p>
                            <ul>
                            ${response.datalist.amount_conditions.map((condition, index) => `
    <li>${condition}${index < response.datalist.amount_conditions.length - 1 ? ` - ${response.datalist.amount_conditions[index + 1] - 1} กิโลกรัม` : ''} : ${response.datalist.prices[index]} บาท/${response.datalist.unit}</li>
`).slice(0, -1).join('')}
${response.datalist.amount_conditions.map((condition, index) => `
    ${index === response.datalist.amount_conditions.length - 1 ? `<li>${condition} กิโลกรัมขึ้นไป : ${response.datalist.prices[index]} บาท/${response.datalist.unit}</li>` : ''}
`).filter(Boolean).join('')}

                            </ul>
                    
                   
                            <div class='d-flex align-items-center mb-4 pt-2'>
                       
                            <div class='input-group quantity mr-3' style='width: auto;'>
                                <div class='input-group-btn' >
                                    <button class='btn btn-primary btn-minus' style='height: 38px;' id="btnPlus" onclick='changeQuantity(-1)'>
                                        <i class='fa fa-minus'></i>
                                    </button>
                                </div>
                           
                                 <input id='quantityInput' type='number'  class='form-control bg-secondary border-0 text-center' value='0.0' style='width: 150px;' '>
                               
                                <div class='input-group-btn'>
                                    <button class='btn btn-primary btn-plus' id="btnDel" style='height: 38px;' onclick='changeQuantity(1)'>
                                        <i class='fa fa-plus'></i>
                                    </button>
                                </div>
                         
                               
                            </div>
                        
                    </div>

                    <div class='d-flex mt-n4'>
               
                    
   
              




                    
                    </div>
                    <div class='d-flex mb-2 mt-2'>
                    <button onclick='addCart("${response.datalist.id}")' class='btn btn-primary px-3'>เพิ่มลงตะกร้า <i class='fa fa-shopping-cart mr-1'></i></button>
                        <button href='' onclick='addOrder("${response.datalist.id}")'  class='btn btn-success'>สั่งชื้อสินค้า <i class="fas fa-file-invoice"></i></button>
                    </div>
                    
                    <div class='d-flex '>
    <p class='mx-4'  >ราคา :</p>
    <input id='price' type='number' readonly class='form-control bg-secondary border-0 text-center'  style='width:30%;' > 
    <p class="mx-2">บาท/${response.datalist.unit}</p>
   
</div>

<div class='d-flex '>
<p class='mx-2 ' style='padding-right:5px'> ราคารวม :</p>
    <input id="total" type="text" readonly class="form-control bg-secondary border-0 text-center" style='width:30%' >
    <p class="mx-2">บาท</p>
</div>
                    <input id='price_id' type='hidden' readonly  class='form-control bg-secondary border-0 text-center' value='' style='width: 20%;' >
                        <div class='tab-pane fade show active' id='tab-pane-1'>
                            <h4 class='mt-2'>รายละเอียด</h4>
                            <p>${response.datalist.detail}</p>
                            </div>
                        <div class='tab-pane fade show active' id='tab-pane-2'>
                            <h4 class='mb-0'>ข้อมูลเพิ่มเติม</h4>
                            <p>ธูปหอมของเราเป็นสินค้าคุณภาพดีทำขึ้นมาเองเเล้วส่งออกให้ผู้จัดจำหน่ายทั่วไปที่มาติดต่อ
                            สถานที่ผลิตธูปหอมของเราเพื่อลูกค้าจะมารับสินค้าจากทางเราเองโดยไม่ต้องให้ทางเราจัดส่งให้โดยบริษัทขนส่ง บ้านโคกมอน ตำบลป่าสังข์ อำเภอจตุรพักตรพิมาน ร้อยเอ็ด 45180 </p>
                           
                        </div>
                    </div>
                    </div>
                        `;
                       
                    

                }else{
                    console.log(response.message);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
        document.getElementById("content").innerHTML="<div class='row' style='padding: 10px;'>"+innerhtml+"</div>";
 // Add event listener to quantityInput

 document.getElementById('quantityInput').addEventListener('input', function () {
    updatePrice(dataPrice);
});
document.getElementById('quantityInput').addEventListener('change', function () {
    updatePrice(dataPrice);
});
document.getElementById('btnPlus').addEventListener('click', function () {
   
   updatePrice(dataPrice);
});document.getElementById('btnDel').addEventListener('click', function () {
   
   updatePrice(dataPrice);
});

// document.getElementById('btnIncrement20').addEventListener('click', function () {
   
//     updatePrice(dataPrice);
// });

// document.getElementById('btnIncrement100').addEventListener('click', function () {
    
//     updatePrice(dataPrice);
// });

// document.getElementById('btnIncrement500').addEventListener('click', function () {
  
//     updatePrice(dataPrice);
// });
// <button class='btn btn-success btn-sm' id='btnIncrement20' onclick='changeQuantity(20);'>+20</button>
//                     <button class='btn btn-info btn-sm' id='btnIncrement100' onclick='changeQuantity(50);'>+50</button>
//                     <button class='btn text-white btn-danger btn-sm' id='btnIncrement500' onclick='changeQuantity(100);' >+100</button>


}










// Function to update price based on quantity
function updatePrice(datalist) {
    const priceInput = document.getElementById('price');
    const price_idInput = document.getElementById('price_id');
    const total = document.getElementById('total');
    const quantity = parseFloat(quantityInput.value);

    // Find the maximum condition index that is less than or equal to the quantity
    const maxIndex = datalist.amount_conditions.reduce((maxIndex, condition, index) => {
        if (parseFloat(condition) <= quantity) {
            return index > maxIndex ? index : maxIndex;
        }
        return maxIndex;
    }, -1);

    // Set the price based on the found index
    if (maxIndex !== -1) {
        priceInput.value = datalist.prices[maxIndex];
        price_idInput.value = datalist.price_id[maxIndex];

        // คำนวณผลลัพธ์จากการคูณและจัดรูปแบบด้วย toLocaleString
        let result = parseFloat(datalist.prices[maxIndex] * quantity).toFixed(2);
    

// นำผลลัพธ์ไปกำหนดค่าใน input element
total.value = result;

    
    } else {
        // If no matching condition is found, set the minimum price
        priceInput.value = Math.min(...Object.values(datalist.prices));
        price_idInput.value = Math.min(...Object.values(datalist.price_id));
    }
}





function changeQuantity(change) {
    const quantityInput = document.getElementById('quantityInput');
    let currentQuantity = parseFloat(quantityInput.value);
    currentQuantity += change;
    // Ensure the quantity is not less than 0.1
    currentQuantity = Math.max(currentQuantity, 0.1);
    quantityInput.value = currentQuantity.toFixed(1.0);
   
}

</script>

<script type=text/javascript>

     function getProductOther(){
        let uri= "http://127.0.0.1:8080/project/api/get_product_list.php";
        let innerhtml = "";
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:null,
            success:function(response){
                if(response.result==1){
                    // console.log(response.datalist);
                    for (let productId in response.datalist) {
                        let product = response.datalist[productId];

                        let inputImg = product.img;
                    let imgArray = [];
                // ตรวจสอบว่ามี "," หรือไม่
                if (inputImg.includes(',')) {
                    // มี ","
                    imgArray = inputImg.split(',');
                    console.log(imgArray);
                } else {
                    // ไม่มี ","
                    imgArray.push(inputImg);
                    console.log(imgArray);
                }
                        innerhtml += `
                        <style>
            .img-container1 {
                width: 300px; /* กำหนดขนาดที่ต้องการ เช่น 300px */
                height: 300px; /* กำหนดขนาดที่ต้องการ เช่น 300px */
                overflow: hidden; /* ตัดขอบออกเมื่อเกินขนาดที่กำหนด */
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .img-container1 img {
                width: 100%; /* ทำให้รูปเต็มขนาดของ container */
                height: auto; /* รักษาสัดส่วนของรูป */
                display: block; /* ปรับลำดับการแสดงผลให้เป็น block element */
            }
        </style>


                        <div class='col-lg-3 col-md-4 col-sm-6 pb-1'>
                    <div class='product-item bg-light mb-4 d-flex flex-column h-100' style="border-radius: 1rem; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);">
                    <div class='product-img position-relative overflow-hidden'>
                    <div class="img-container1 mx-auto">
                <img class='img-fluid w-100' src='./assets/images/product/${imgArray[0]}'  alt=''>
                </div>
                                <div class='product-action'>

                                
                                  
                <a class='btn btn-outline-dark ' href='detail.php?ID=${product.id}'>ดูรายละเอียด <i class='fa fa-search'></i></a>
                                </div>
                            </div>
                            <div class='text-center py-4'>
                                <a class='h3 text-decoration-none text-truncate' href=''>${product.name}</a>
                                <p>จำนวนคงเหลือ : ${product.amount}</p>
                                <div class='d-flex align-items-center justify-content-center mt-2'>
                                    <h5><span class='text-danger'>
                                    ${Object.values(product.amount_conditions).length > 1
                            ? `${Math.min(...Object.values(product.amount_conditions))} - ${Math.max(...Object.values(product.amount_conditions))}`
                            : Object.values(product.amount_conditions)[0]}</span>
                            <span>บาท/${product.unit}</span>

                                    </h5><h6 class='text-muted ml-2'><del></del></h6>
                                </div>
                               
                            </div>
                        </div>
                    </div>

                        `;
                    }

                }else{
                    console.log(response.message);
                }
                
            },error:function(error){
                console.log(error);
            }
        });
        document.getElementById("other").innerHTML="<div class='row' style='padding: 10px;'>"+innerhtml+"</div>";


    }
        
    </script>



    <script>
        function addCart(pro_id){
            
            checkLogin();
            let amount = document.getElementById("quantityInput").value;
            let price_id = document.getElementById("price_id").value;
            let customer_profile = localStorage.getItem("customer_profile");
            customer_profile = JSON.parse(customer_profile);
            console.log(customer_profile.id);
            let cus_id = customer_profile.id;
    if((pro_amount-pro_reserve)>amount){
           
        
            let request_data = {
                    "cus_id":cus_id,
                    "pro_id":pro_id,
                    "amount":amount,
                    "price_id":price_id
            }
            
            let uri="http://localhost:8080/project/api/set_cart_add.php";

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
                    showConfirmButton: false,
                    timer: 1000,
                    });
                    // .then(() => {
                    // // เมื่อ popup message ปิดลง ให้โหลดหน้าเว็บหรือทำงานต่อ
                    // window.location.href = "http://127.0.0.1:8080/project/detail.php?ID="+pro_id; // แก้ URL ตามที่คุณต้องการ
                    // });
            }
                
                },error:function(error){
                    Swal.fire({
                        title: response.message,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });     
        }else{
            Swal.fire({
        title: "จำนวนสินค้าไม่เพียงพอ",
        icon: 'error',
        confirmButtonText: 'Close'
                    })
        }   
        }

    </script>



<script>
  function addOrder(proId){
    checkLogin();

    
    let amount = []
    let price_id = []
    let pro_id = []
    amount.push(document.getElementById("quantityInput").value);
    price_id.push(document.getElementById("price_id").value);
    pro_id.push(proId);
    checkAmount = true;
for (let i = 0; i < amount.length; ++i) {
if((pro_amount-pro_reserve)<amount[i]){
        checkAmount = false;
        break;
    }
    }
if(checkAmount) {   
    if (price_id != "") {
        // Create dataOrderList
        let dataOrderList = {
          
            "pro_id":pro_id,
            "amount":amount,
            "price_id":price_id
        };

        // Store dataOrderList in localStorage
        localStorage.setItem("dataOrderList", JSON.stringify(dataOrderList));

        // Redirect to "order.php"
        window.location.href = "./order.php";
    } else {
       
        Swal.fire({
                        title: "ข้อมูลผิดพลาด",
                        icon: 'error',
                        confirmButtonText: 'Close'
                    })
    }
}else{
    Swal.fire({
        title: "จำนวนสินค้าไม่เพียงพอ",
        icon: 'error',
        confirmButtonText: 'Close'
                    })

}


}
</script>






