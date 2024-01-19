

<script type="text/javascript">



let selectedAmount = [];
let selectedPro_id = [];
let selectedPrice_id = [];
let selectedProNet = [];
let selectedCount= 0;
function getCartDetail() {
    let innerhtml = "";
    let customer_profile = localStorage.getItem("customer_profile");
    customer_profile = JSON.parse(customer_profile);
    let idParam = customer_profile.id;


    let dataId = {
        "cus_id": idParam
    };
    let uri = "http://127.0.0.1:8080/project/api/get_cart.php";
    $.ajax({
        type: "POST",
        url: uri,
        async: false,
        data: JSON.stringify(dataId),
        success: function (response) {
            if(response.datalist.length === 0){

innerhtml += `

    <tr>
        <td class='align-middle'>ไม่มีสินค้าในตะกร้า</td>
    </tr>
    `;
}

            if (response.result == 1) {
    let allAmount = 0.00;
    let allTotal = 0.00;
    console.log(response.datalist.length);
    for (let productId in response.datalist) {

    let product = response.datalist[productId];

    let result = product.price * product.pro_amount;
    allTotal += result.toFixed(2);
    
    allAmount += parseFloat(product.pro_amount);
    
    var val = Math.round(Number(result) * 100) / 100;
    var parts = val.toString().split(".");
    var total = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");

    let i = productId;
  
    let inputImg = product.pro_img;
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
        <tr id='row${i}'>
            <td class='align-middle'>
            <input type='checkbox' class='form-check-input' id='selectCheckbox${productId}' onchange='selectProduct("${productId}")'>

                <label class='form-check-label' for='selectCheckbox${productId}'></label>
            </td>
  <td  class='align-middle'><a onclick="window.location.href='http://127.0.0.1:8080/project/detail.php?ID=${productId}'">
    <img src='./assets/images/product/${imgArray[0]}' alt='' style='width: 50px;'>
    ${product.pro_name} 
</a></td>
            <td class='align-middle'>${product.pro_unit}</td>
            <td class='align-middle'>
                <div class='input-group quantity mx-auto' style='width: 150px;'>
                    <div class='input-group-btn'>
                        <button class='btn btn-sm btn-primary btn-minus' id='btnDel${productId}'  onclick='changeQuantity(-1,"${productId}");updatePrice("${productId}")'>
                            <i class='fa fa-minus'></i>
                        </button>
                    </div>
    <input type='number' id='amount${productId}' onchange='updatePrice("${productId}")'  class='form-control form-control-sm bg-secondary border-0 text-center'  value='${product.pro_amount}'>
                    <div class='input-group-btn'>
                        <button class='btn btn-sm btn-primary btn-plus' id='btnPlus${productId}' onclick='changeQuantity(1,"${productId}");updatePrice("${productId}")'>
                            <i class='fa fa-plus'></i>
                        </button>
                    </div>
                </div>
      
            </td>
            <td class='align-middle'>
            <input type='hidden' id='pro_net${productId}' readonly class='form-control form-control-sm bg-secondary border-0 text-center'  value='${product.pro_net}'>
            
                <input type='number' id='price${productId}' readonly class='form-control form-control-sm bg-secondary border-0 text-center'  value='${product.price}'>
                <input type='hidden' id='price_id${productId}' class='form-control form-control-sm bg-secondary border-0 text-center'  value='${product.price_id}'>
            </td>
            <td class='align-middle' id='total${productId}'>
                ${total}
            </td>
            <td class='align-middle'><button class='btn btn-sm btn-danger' onclick='delCart("${productId}")'><i class='fa fa-times'></i></button></td>
        </tr>
    `;
   

                }
    


                document.getElementById("allAmount").innerHTML = allAmount.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById("allTotal").innerHTML = allTotal.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
   
            
            
            }else {
                console.log(response.message);
                innerhtml += `
                    <tr>
                        <td class='align-middle'>ไม่มีสินค้าในตะกร้า</td>
                    </tr>
                    `;
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
    document.getElementById("content").innerHTML = innerhtml;
}



function selectProduct(productId) {
    let checkbox = document.getElementById(`selectCheckbox${productId}`);
    let productAmount = document.getElementById(`amount${productId}`).value;
    let productNet = document.getElementById(`pro_net${productId}`).value;
    let productPrice_id = document.getElementById(`price_id${productId}`).value;
    let productPro_id = productId;

    if (checkbox.checked) {
        // เพิ่มราคาเข้าใน selectedPrices ถ้า checkbox ถูกเลือก
        selectedAmount.push(productAmount);
        selectedPrice_id.push(productPrice_id);
        selectedProNet.push(productNet);
        selectedPro_id.push(productPro_id);
        selectedCount++;  // เพิ่มจำนวนที่ถูกเลือก
    } else {
        // ลบราคาออกจาก selectedPrice_id ถ้า checkbox ถูกยกเลิก
        let index = selectedPrice_id.indexOf(productPrice_id);
    if (index !== -1) {
            selectedAmount.splice(index, 1);
            selectedPrice_id.splice(index, 1);
            selectedProNet.splice(index, 1);
            selectedPro_id.splice(index, 1);
            selectedCount--;  // ลดจำนวนที่ถูกเลือก
        }
    }

    // แสดงผลลัพธ์ที่ console
    console.log("Selected Amount:", selectedAmount);
    console.log("Selected Pro ID:", selectedPro_id);
    console.log("Selected Net ID:", selectedProNet);
    console.log("Selected Price ID:", selectedPrice_id);
    console.log("Selected Count:", selectedCount);

    // แสดงจำนวนรายการที่ถูกเลือกที่ <div class="d-flex justify-content-between mb-2">
    document.getElementById("allCount").innerHTML = `${selectedCount} รายการ`;
}


document.getElementById("btnOrder").addEventListener("click", function () {
    // Check if selectedPro_id is not empty
    checkAmount = true;
for (let i = 0; i < selectedAmount.length; ++i) {
if((selectedProNet[i])<selectedAmount[i]){
        checkAmount = false;
  
        break;
    }
    }
if(checkAmount) {  

    if (selectedPro_id.length > 0) {
        // Create dataOrderList
        let dataOrderList = {
            "pro_id": selectedPro_id,
            "price_id": selectedPrice_id,
            "amount": selectedAmount
        };

        // Store dataOrderList in localStorage
        localStorage.setItem("dataOrderList", JSON.stringify(dataOrderList));

        // Redirect to "order.php"
        window.location.href = "./order.php";
    } else {
       
         Swal.fire({
            title: 'ข้อมูลผิดพลาด',
            text: 'กรุณาเลือกสินค้าที่จะชำระเงิน!',
            icon: 'error',
       
           
        });
    }
}else{
    Swal.fire({
            title: 'ผิดพลาด',
            text: 'จำนวนสินค้าไม่เพียงพอ',
            icon: 'error',
        });
} 



});



function changeQuantity(change, pro_id) {
    const quantityInput = document.getElementById('amount' + pro_id);
    let currentQuantity = parseFloat(quantityInput.value);
    currentQuantity += change;
    // Ensure the quantity is not less than 0.1
    currentQuantity = Math.max(currentQuantity, 0.1);
    quantityInput.value = currentQuantity.toFixed(1.0);
}

// Function to update price based on quantity
function updatePrice(pro_id) {
    let dataId = {
        "pro_id": pro_id
    };
    let uri = "http://127.0.0.1:8080/project/api/get_price.php";
    $.ajax({
        type: "POST",
        url: uri,
        data: JSON.stringify(dataId),
        success: function (response) {
            // console.log(response); // Debugging: Check the response
    
            if (response.result == 1) {
                const quantityInput = document.getElementById('amount' + pro_id);

                const priceInput = document.getElementById('price' + pro_id);
                const price_idInput = document.getElementById('price_id' + pro_id);
                const total = document.getElementById('total' + pro_id);
                
                const quantity = parseFloat(quantityInput.value);

                // Find the maximum condition index that is less than or equal to the quantity
                const maxIndex = response.datalist.amount_conditions.reduce((maxIndex, condition, index) => {
                    if (parseFloat(condition) <= quantity) {
                        return index > maxIndex ? index : maxIndex;
                    }
                    return maxIndex;
                }, -1);

                // Set the price based on the found index
                if (maxIndex !== -1) {
                    priceInput.value = response.datalist.prices[maxIndex];

                    price_idInput.value = response.datalist.price_id[maxIndex];
               

                    // Calculate the result from multiplication and format it using toLocaleString
                    let result = parseFloat(response.datalist.prices[maxIndex] * quantity).toFixed(2);

                    var val = Math.round(Number(result) * 100) / 100;

                    var parts = val.toString().split(".");

                    var num = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");

                    // Assign the formatted result to the input element
                    total.innerHTML = num;
                    amount = quantityInput.value;
                    price_id = price_idInput.value;


                    updatePriceData(pro_id,amount,price_id);
                   
                }
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}
</script>



<script>
    function updatePriceData(pro_id,amount,price_id) {
       
let dataIn= {
        "pro_id": pro_id,
        "amount":amount,
        "price_id":price_id,
            "cus_id":cus_id
    };
    let uri = "http://127.0.0.1:8080/project/api/update_price_cart.php";
    $.ajax({
        type: "POST",
        url: uri,
        data: JSON.stringify(dataIn),
        success: function (response) {
            console.log("update success");
            getCartDetail();
        },
        error: function (error) {
            console.log(error);
            console.log("update fails");
        }
    });
}
</script>




<script>
function delCart(pro_id){
   let uri= "http://127.0.0.1:8080/project/api/delCart.php";
   let customer_profile = localStorage.getItem("customer_profile");
    customer_profile = JSON.parse(customer_profile);
    let cus_id = customer_profile.id;

   let id = {
                "cus_id": cus_id,
               "pro_id" : pro_id
           };
           console.log(id);
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
                            window.location.replace("http://127.0.0.1:8080/project/cart.php");
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
              
            } else {
                // User canceled, handle accordingly
                console.log("Deletion canceled");
            }
        });
    } 

</script>