

<script type="text/javascript">

let selectedAmount = [];
let selectedPro_id = [];
let selectedPrice_id = [];

let shipping = 0.00;

function getCartDetail() {
    let innerhtml = "";
    let customer_profile = localStorage.getItem("customer_profile");
    customer_profile = JSON.parse(customer_profile);
    let cus_id = customer_profile.id;
    let dataOrderList = localStorage.getItem("dataOrderList");
    dataOrderList = JSON.parse(dataOrderList);
    let pro_id = dataOrderList.pro_id;
    let price_id = dataOrderList.price_id;
    let amount = dataOrderList.amount;
    let dataId = {
        "cus_id": cus_id,
        "pro_id":  pro_id,
        "price_id": price_id,
        "amount":  amount
    };
 
    let uri = "http://127.0.0.1:8080/project/api/get_order.php";
    $.ajax({
        type: "POST",
        url: uri,
        async: false,
        data: JSON.stringify(dataId),
        success: function (response) {
            if (response.result == 1) {
    let allAmount = 0;
    let allTotal = 0.00;
    let allShipping = 0.00;
    let allNet= 0.00;
    
    
    
    for (let productId in response.datalist) {
    let product = response.datalist[productId];

    let result = product.price * product.pro_amount;
    allTotal += result;
    
    allAmount += parseFloat(product.pro_amount);
    let shipping_rate = <?php echo $row["shipping_rate"]; ?>;

    let shippingMethodElements = document.getElementsByName("shippingMethod");
let selectedShippingMethod;

for (let i = 0; i < shippingMethodElements.length; i++) {
    if (shippingMethodElements[i].checked) {
        selectedShippingMethod = shippingMethodElements[i].value;
        break;
    }
}





if(selectedShippingMethod==='0'){
    allShipping += allAmount * shipping_rate;
}
 
console.log("p : "+selectedShippingMethod+" ship : "+allShipping);

    



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
            <input type='checkbox' class='form-check-input' id='selectCheckbox${productId}' onchange='selectProduct("${productId}")' checked>

                <label class='form-check-label' for='selectCheckbox${productId}'></label>
            </td>
            <td class='align-middle'><img src='./assets/images/product/${imgArray[0]}' alt='' style='width: 50px;'> ${product.pro_name}</td>
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
                <input type='number' id='price${productId}' readonly class='form-control form-control-sm bg-secondary border-0 text-center'  value='${product.price}'>
                <input type='hidden' id='price_id${productId}' class='form-control form-control-sm bg-secondary border-0 text-center'  value='${product.price_id}'>
            </td>
            <td class='align-middle' id='total${productId}'>
                ${total}
            </td>
            <td class='align-middle'><button onclick='removeItemFromLocalStorage("${productId}")' class='btn btn-sm btn-danger'><i class='fa fa-times'></i></button></td>
        </tr>
    `;
    }

    let selectAddress = "";
    selectAddress += `<option style='color: #dc3545;' value='0'>-- เลือกที่อยู่ของคุณ --</option>`;
for (let addressId in response.addressList) {
    let address = response.addressList[addressId];

    // ปรับปรุงส่วนนี้ เพื่อให้ value ของ option เป็น JSON string ของทั้ง address object
    selectAddress += `<option value='${JSON.stringify(address)}'>${address.name} ${address.address}</option>`;

}
let selectElement = document.getElementById("addressSelect");
selectElement.innerHTML = selectAddress;

// เรียกใช้งานเพื่อแสดงข้อมูลใน input fields หากมีค่าที่ถูกเลือกแล้ว



// Event listener for the change event of <select> element
addressSelect.addEventListener("change", function (event) {
    // Retrieve the selected address data
    var selectedAddress = JSON.parse(event.target.value);

    // Update input fields with selected address data
    document.getElementById("cus_id").value = cus_id;
    document.getElementById("cus_name").value = selectedAddress.name;
    document.getElementById("cus_tel").value = selectedAddress.tel;
    document.getElementById("cus_address").value = selectedAddress.address;
});

document.addEventListener("DOMContentLoaded", function () {
    let addressSelect;
    addressSelect = document.getElementById("addressSelect");

    // Trigger change event to display data in input fields for the first item
    addressSelect.dispatchEvent(new Event("change"));
console.log("fff");
    // Add event listener to prevent duplicate actions
    addressSelect.addEventListener("change", preventDuplicateAction);
});


               
                document.getElementById("allAmount").innerHTML = allAmount.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById("allTotal").innerHTML = allTotal.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById("allShipping").innerHTML = allShipping.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById("allNet").innerHTML = (allTotal+allShipping).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                shipping = allShipping;
                console.log(shipping);
            } else {
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


$(window).bind('beforeunload', function(){
    // Remove dataOrderList from localStorage
    localStorage.removeItem("dataOrderList");
});



// Add an event listener to the button with id "btnCancelOrder"
document.getElementById("btnCancelOrder").addEventListener("click", function () {
    // Remove dataOrderList from localStorage
    localStorage.removeItem("dataOrderList");

    // Redirect to "index.php"
    window.location.href = "index.php";
});




// เพิ่มโค้ดนี้เพื่อป้องกันการกระทำซ้ำเมื่อมีการเปลี่ยนแปลงใน <select> element
function preventDuplicateAction() {
    // ป้องกันการกระทำ
    addressSelect.removeEventListener("change", preventDuplicateAction);

    // แสดงข้อมูลใน input fields
    addressSelect.dispatchEvent(new Event("change"));

    // เพิ่ม event listener ที่ป้องกันการกระทำใหม่
    addressSelect.addEventListener("change", preventDuplicateAction);
}

// เพิ่ม event listener ที่ป้องกันการกระทำ
addressSelect.addEventListener("change", preventDuplicateAction);




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
            if (response.result == 1) {
                const quantityInput = document.getElementById('amount' + pro_id);
                const priceInput = document.getElementById('price' + pro_id);
                const price_idInput = document.getElementById('price_id' + pro_id);
                const total = document.getElementById('total' + pro_id);
                
                const quantity = parseFloat(quantityInput.value);

                const maxIndex = response.datalist.amount_conditions.reduce((maxIndex, condition, index) => {
                    if (parseFloat(condition) <= quantity) {
                        return index > maxIndex ? index : maxIndex;
                    }
                    return maxIndex;
                }, -1);

                if (maxIndex !== -1) {
                    priceInput.value = response.datalist.prices[maxIndex];
                    price_idInput.value = response.datalist.price_id[maxIndex];
                    console.log(response.datalist.prices[maxIndex]);
                    let result = parseFloat(response.datalist.prices[maxIndex] * quantity).toFixed(2);
                    var val = Math.round(Number(result) * 100) / 100;
                    var parts = val.toString().split(".");
                    var num = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
                    total.innerHTML = num;
                    amount = quantityInput.value;
                    price_id = price_idInput.value;
                    price = priceInput.value;

                                    // Update dataOrderList.amount in local storage
                   // Update dataOrderList.amount in local storage


let dataOrderList = localStorage.getItem("dataOrderList");
dataOrderList = dataOrderList ? JSON.parse(dataOrderList) : { pro_id: [], price_id: [], amount: [] };

// Find the index of pro_id in pro_id array
let proIndex = dataOrderList.pro_id.indexOf(pro_id);

// If pro_id is not in the array, push it and initialize the corresponding amount
if (proIndex === -1) {
    dataOrderList.pro_id.push(pro_id);
    dataOrderList.price_id.push(price_id);
    dataOrderList.amount.push(quantity);
} else {
    // Update the quantity for the specific pro_id
    dataOrderList.amount[proIndex] = quantity;
    // dataOrderList.price[proIndex] = price;
    dataOrderList.price_id[proIndex] = price_id;
}
console.log(dataOrderList.amount[proIndex]);

localStorage.setItem("dataOrderList", JSON.stringify(dataOrderList));

                    // Update allAmount and allTotal
getCartDetail();
                }
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}



          


function removeItemFromLocalStorage(productId) {
    Swal.fire({
                title: 'แน่ใจหรือไม่?',
                text: 'คุณต้องการลบรายการนี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'YES'
            }).then((result) => {
                if (result.isConfirmed) {
                    let dataOrderList = localStorage.getItem("dataOrderList");
    dataOrderList = dataOrderList ? JSON.parse(dataOrderList) : {};

    // Find the index of the productId in dataOrderList.pro_id
    let index = dataOrderList.pro_id.indexOf(productId);

    // If the productId is found, remove the item from each array in dataOrderList
    if (index !== -1) {
        dataOrderList.pro_id.splice(index, 1);
        dataOrderList.price_id.splice(index, 1);
        dataOrderList.amount.splice(index, 1);

        // Update the local storage
        localStorage.setItem("dataOrderList", JSON.stringify(dataOrderList));
    }
    getCartDetail();
                }
            });
}





function setOrder(){
    let customer_profile = localStorage.getItem("customer_profile");
    customer_profile = JSON.parse(customer_profile);
    let cus_id = customer_profile.id;
    console.log(cus_id);
    let dataOrderList = localStorage.getItem("dataOrderList");
    let selectElement = document.getElementById("addressSelect");
    dataOrderList = JSON.parse(dataOrderList);
    let pro_id = dataOrderList.pro_id;
    let price_id = dataOrderList.price_id;
    let amount = dataOrderList.amount;
    let address_id = JSON.parse(selectElement.value).address_id;

    let paymentMethodElements = document.getElementsByName("paymentMethod");
    let selectedPaymentMethod;

        for (let i = 0; i < paymentMethodElements.length; i++) {
            if (paymentMethodElements[i].checked) {
                selectedPaymentMethod = paymentMethodElements[i].value;
                break;
            }
        }

        // ดึง radio buttons ของ "shippingMethod"
        let shippingMethodElements = document.getElementsByName("shippingMethod");
        let selectedShippingMethod;

        for (let i = 0; i < shippingMethodElements.length; i++) {
            if (shippingMethodElements[i].checked) {
                selectedShippingMethod = shippingMethodElements[i].value;
                break;
            }
        }
        console.log("add : "+address_id)
if (typeof address_id !== "undefined"){
    let dataId = {
        "cus_id": cus_id,
        "pro_id":  pro_id,
        "price_id": price_id,
        "amount":  amount,
        "shipping_cost": shipping,
        "address_id":address_id,
        "pay_type":selectedPaymentMethod,
        "shipping_type":selectedShippingMethod
    };
   
    console.log(dataId);
    let uri = "http://127.0.0.1:8080/project/api/set_order.php";
    
    $.ajax({
        type: "POST",
        url: uri,
        data: JSON.stringify(dataId),
        success: function (response) {
            console.log(response.message);
            if (response.result == 1) {
                Swal.fire({
                        position: "center",
                        icon: "success",
                        title: response.message,
                        showConfirmButton: true,
                       
                    }).then(() => {
                        localStorage.removeItem("dataOrderList");
        window.location.href = 'order_detail.php?order_id=' + response.order_id;
                    });
                    
                
            }else{
                Swal.fire({
                            title: response.message,
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
            }
            },
            error: function (error) {
            console.log(error);
        
            }

        });
    }else{
                    Swal.fire({
                            title: "กรุณาเลือกที่อยู่!",
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
    }
}

</script>




<script>
        function addAddress(){
            let name = document.getElementById("nameN").value;
            let tel = document.getElementById("telN").value;
            let address = document.getElementById("addressN").value;
            let customer_profile = localStorage.getItem("customer_profile");
            customer_profile = JSON.parse(customer_profile);
            let cus_id = customer_profile.id;
        
            let request_data = {
                    "cus_id":cus_id,
                    "name":name,
                    "tel":tel,
                    "address":address
            }
            console.log(request_data);
            
            let uri="http://localhost:8080/project/api/addAddress.php";

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
                  
                    })
                    .then(() => {
                   getCartDetail();
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
    function handlePaymentMethodChange() {
        var pickUpStoreRadio = document.getElementById('pickUpStore');
        var shippingAddRadio = document.getElementById('shippingAdd');
        var codRadio = document.getElementById('cod');
        var transferRadio = document.getElementById('transfer');
        var labelTransferRadio = document.getElementById('labelTransfer');
        if (pickUpStoreRadio.checked) {
            // ถ้าเลือกรับหน้าร้าน ให้ซ่อน Radio โอน
            transferRadio.style.display = "none";
            labelTransferRadio.style.display = "none";
            codRadio.checked = true;
        } 
        getCartDetail();
    }

    function handlePaymentMethodChange1() {
        var transferRadio = document.getElementById('transfer').style.display  = "block";
        var labelTransferRadio = document.getElementById('labelTransfer').style.display  = "block";
        getCartDetail();
    }
</script>