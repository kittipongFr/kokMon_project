<?php

include 'config/config_db.php';
$query = "SELECT * FROM community_enterprise limit 1";
$result = mysqli_query($conn, $query);
if ($result) {
 // Fetch associative array
 $row = mysqli_fetch_assoc($result);

}
?>

<body onload="get_environment()">

<script>
       function logOut() {
      Swal.fire({
                        position: "center",
                        icon: "info",
                        title: "คุณต้องการออกจากระบบใช่ไหม?",
                        showConfirmButton: true,
                        showCancelButton: true,
                       
                    }).then((result) => {
                        if (result.isConfirmed) {
                          localStorage.removeItem("customer_profile");
  window.location.replace("http://127.0.0.1:8080/project/logout.php");  
                          
                        }
                    });

}
</script>
</script>
                <div class="d-inline-flex align-items-center d-block d-lg-none">
                    <a href="" class="btn px-0 ml-2">
                        <i class="fas fa-heart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">0</span>
                    </a>
                    <a href="" class="btn px-0 ml-2">
                        <i class="fas fa-shopping-cart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">0</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-6">
                <a href="" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2">วิสาหกิจ</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1"><?php echo $row["name"]; ?> </span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for products">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
                   
                    </div>
        </div>
        
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="index.php" class="nav-item nav-link active">หน้าเเรก</a>
   
                            <a href="order_history.php" class="nav-item nav-link">ประวัติการสั่งชื้อ</a>
                            <!-- <a href="contact.php" class="nav-item nav-link">ติดต่อ</a> -->
                        </div>





                      
    <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
        <!-- <a href="" class="btn px-0">
            <i class="fas fa-heart text-primary"></i>
            <span class="badge text-secondary border border-secondary rounded-circle"  style="padding-bottom: 2px;">0</span>
        </a> -->
        <a href='cart.php' class="btn px-0 ml-3">
            <i class="fas fa-shopping-cart text-primary"></i>
            <span class="badge text-secondary border border-secondary rounded-circle" id="cart_count"  style="padding-bottom: 2px;">0</span>

        </a>

                           
                        <button type="button" class="btn btn-sm btn-dark dropdown-toggle" data-toggle="dropdown"><span id="cusName">บัญชีของฉัน <a class="mx-2"><i class="fa fa-circle text-danger"></i> Offline</a></span> <i class="fas fa-o"></i></a> <i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item"  href="./login1.php" type="button">เข้าสู่ระบบ</a>
                            <a class="dropdown-item" href="./register1.php" type="button">ลงทะเบียน</a>
                            <button class="dropdown-item" onclick="logOut()"  type="button">ออกจากระบบ</button>
                        </div>
                    </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            getCus();
});

function getCus(){
    if(localStorage.getItem("customer_profile")){
    let cusList = localStorage.getItem("customer_profile");
    let cusParse = JSON.parse(cusList);
    let cus_name = cusParse.fname+" "+cusParse.lname ;
    document.getElementById("cusName").innerHTML = cus_name + `<a class="mx-2"><i class="fa fa-circle text-success"></i> Online</a>`;
    }
 
}
    </script>