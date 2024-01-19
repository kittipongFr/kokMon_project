<script>
// ตรวจสอบว่ามีข้อมูลการล็อกอินอยู่ใน localStorage หรือไม่
if (localStorage.getItem("customer_profile")) {
    // มีข้อมูลการล็อกอิน ทำตามที่คุณต้องการ
    console.log("User is logged in.");
    // เพิ่มโค้ดอื่น ๆ ที่ต้องการทำในกรณีล็อกอินแล้ว
} else {
    // ไม่มีข้อมูลการล็อกอิน ส่งผู้ใช้กลับไปยังหน้า login
    console.log("User is not logged in. Redirecting to login page...");
    window.location.href = "http://127.0.0.1:8080/project/login1.php";
}
</script>


<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <style>
    body {
        font-family: "Sriracha", serif;
    }


    </style>
</head>
<body  onload="getEnvironment()">
   <header style="background-color: yellow;">
        <h1>Header</h1>
        <div align="right">
            <i class="fa fa-shopping-cart text-white" style="font-size:22px"></i>
            [<label id="num_cart" >22</label>]
            [<label id="total_price" >22</label>]
            <label id="customer_profile_name">customer_name</label>
            <input type="input" id="customer_profile_id"/>
            <input type="input" id="customer_profile_email"/>
        </div>
    </header>

    <div class="row" >
   <nav class=" menu">
   <h2>Menu</h2>
            <ul>
            <li><a href="./home.php?menu=productlist">Menu 1</a></li>
                <li><a href="./home.php?menu=xxx">Menu 2</a></li>
                <!-- Add more menu items here -->
            </ul>
   </nav>
    <div class="column content">
    <h2>สินค้า</h2>


        <div id="content">
          
        <?php 
        if(@$_GET['menu']==="productlist"){
            include "_product_list.php";
        }else{

        }
        
        
        ?>
         



        </div>        

    </div>


    </div>

    <!-- <footer >
        <p>Footer</p>
    </footer> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type=text/javascript>
    function getEnvironment(){
        let customer_profile = localStorage.getItem("customer_profile");
        customer_profile = JSON.parse(customer_profile);
        console.log(customer_profile.email);  
        console.log(customer_profile.name);    
        document.getElementById("customer_profile_name").innerHTML =  customer_profile.name;
        document.getElementById("customer_profile_id").value =  customer_profile.id;
        document.getElementById("customer_profile_email").value =  customer_profile.name;
        getProductList();
   
    }
   

</script>




</body>
</html>
