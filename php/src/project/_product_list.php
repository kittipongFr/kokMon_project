



<script type=text/javascript>
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
     function getProductList(){
        let uri= "http://127.0.0.1:8080/project/api/get_product_list.php";
        let innerhtml = "";
        $.ajax({
            type:"POST",
            url:uri,
            async:false,
            data:null,
            success:function(response){
                if(response.result==1){
                    let qtyNet = 0;
                    // console.log(response.datalist);
                    for (let productId in response.datalist) {
                        let product = response.datalist[productId];
                        qtyNet = product.amount-product.reserve;

                    let inputImg = product.img;
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

                                <style>
            .img-container {
                width: auto; /* กำหนดขนาดที่ต้องการ เช่น 300px */
                height: 200px; /* กำหนดขนาดที่ต้องการ เช่น 300px */
                overflow: hidden; /* ตัดขอบออกเมื่อเกินขนาดที่กำหนด */
                display: flex;
                justify-content: flex-start;
                align-items: flex-start;
            }

            .img-container img {
                width: 100%; /* ทำให้รูปเต็มขนาดของ container */
                height: auto; /* รักษาสัดส่วนของรูป */
                display: block; /* ปรับลำดับการแสดงผลให้เป็น block element */
            }
        </style>
                        <div class='col-lg-3 col-md-4 col-sm-6 pb-1'>
                    <div class='product-item bg-light mb-4 d-flex flex-column h-100  p-3' style="border-radius: 1rem; box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.2);">
                    <div class='product-img position-relative overflow-hidden'>
                    <div class="img-container mx-auto">
                    <img class='img-fluid w-100' src='./assets/images/product/${imgArray[0]}' alt=''>
                </div>
                    
                
                                <div class='product-action'>
                                
                                    <a class='btn btn-outline-dark ' href='detail.php?ID=${product.id}'>ดูรายละเอียด <i class='fa fa-search'></i></a>
                                </div>
                            </div>
                            <div class='text-center py-4'>
                                <a class='h3 text-decoration-none text-truncate' href='detail.php?ID=${product.id}'>${product.name}</a>
                                <p>จำนวนคงเหลือ : ${numberWithCommas(qtyNet)}</p>
                                <div class='d-flex align-items-center justify-content-center mt-2'>
                                    <h5>
                                    <span class='text-danger'>
                                    ${Object.values(product.prices).length > 1
                            ? `${Math.min(...Object.values(product.prices))} - ${Math.max(...Object.values(product.prices))}`
                            : Object.values(product.prices)[0]}</span>
                            บาท/${product.unit}
                                    
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
        document.getElementById("content").innerHTML="<div class='row' style='padding: 10px;'>"+innerhtml+"</div>";


    }
   

</script>