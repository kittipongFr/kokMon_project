
  <body onload="getOrderDetail()">
		

  <?php

   include '../config/config_db.php';
   $query = "SELECT * FROM community_enterprise limit 1";
   $result = mysqli_query($conn, $query);
   if ($result) {
    // Fetch associative array
    $row = mysqli_fetch_assoc($result);

   }
   ?>

<style>
  /* สไตล์เพิ่มเติมสามารถปรับแต่งตามต้องการ */
  .submenu {
    list-style-type: none;
    padding: 0;
  }

  .submenu li {
    margin-left: 0; /* ทำให้รายการชิดซ้าย */
  }

  .submenu a {
    display: block;
    padding: 12px 16px;
    text-decoration: none;
    color: black;
  }

  .submenu a:hover {
    background-color: #07257C;
    
  }
</style>

		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar" class="active">
      <img src="../assets/images/<?php echo $row["img"] ?>" class="img-fluid " alt="...">
				
        <ul class="list-unstyled components mb-5">


          <li class="">
            <a  href="#"><span class="fa fa-home"></span>หน้าแรก</a>
          </li>
          <li>


          <li class="">
    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'member.php') !== false || strpos($_SERVER['REQUEST_URI'], 
    'product.php') !== false || strpos($_SERVER['REQUEST_URI'], 'community_enterprise.php') !== false || strpos($_SERVER['REQUEST_URI'], 
    'material.php') !== false) ? 'active' : ''; ?>" href="#">
        <span class="fas fa-users-cog"></span> ข้อมูลพื้นฐาน
    </a>
    <ul class="submenu collapse bg-white ">

    <li class="">
            <a href="community_enterprise.php" class="text-black"><span class="fas fa-building"></span>วิสาหกิจ</a>
          </li>
        <li class="text-black" ><a href="member.php" class="text-black"><span class="fas fa-users"></span>สมาชิก</a></li>
        <li><a href="product.php" class="text-black"><span class="fas fa-box"></span>สินค้า</a></li>
        <li class="">
            <a href="material.php" class="text-black"><span class="fas fa-dolly"></span>วัตถุดิบ</a>
          </li>
    </ul>
</li>


          <li>
              <a href="receive_mt.php"><span class="fas fa-receipt"></span>รับวัตถุดิบ</a>
          </li>
          <li>
            <a href="manufacture.php"><span class="fas fa-industry"></span>ผลิต</a>
          </li>
          <li>
            <a href="mt_used.php"><span class="fas fa-shapes"></span>วัตถุดิบใช้ไป</a>
          </li>

          <li class="">
    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'order.php') !== false || strpos($_SERVER['REQUEST_URI'], 
    'order_history.php') !== false) ? 'active' : ''; ?>" href="#">
        <span class="fas fa-cash-register"></span> คำสั่งซื้อ
    </a>
    <ul class="submenu collapse bg-white ">
        <li class="text-black" ><a class="nav-link text-black" href="./order.php"><span class="fas fa-file-invoice-dollar"></span>คำสั่งซื้อล่าสุด</a></li>
        <li><a class="nav-link  text-black" href="./order_history.php"><span class="fas fa-history"></span>ประวัติคำสั่งซื้อ</a></li>
    </ul>
</li>
          </ul>

        <div class="footer">
        	<p>
          <?php echo $row["name"]  ?> :
          <?php echo $row["address"]  ?>
  	</p>
        </div>
    	</nav>



      <div id="content" class="p-4 p-md-5">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">

    <button type="button" id="sidebarCollapse" class="btn btn-primary">
      <i class="fa fa-bars"></i>
      <span class="sr-only">Toggle Menu</span>
    </button>
  
    <div class="d-flex" >

    <span>ADMIN : &nbsp</span>
<span id="adminName">กิตติพงษ์ พาบุดดา</span>
<a class="mx-2"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
        </nav>




<script>
  document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll('.nav-link').forEach(function(element){
    
    element.addEventListener('click', function (e) {

      let nextEl = element.nextElementSibling;
      let parentEl  = element.parentElement;	

        if(nextEl) {
            e.preventDefault();	
            let mycollapse = new bootstrap.Collapse(nextEl);
            
            if(nextEl.classList.contains('show')){
              mycollapse.hide();
            } else {
                mycollapse.show();
                // find other submenus with class=show
                var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                // if it exists, then close all of them
                if(opened_submenu){
                  new bootstrap.Collapse(opened_submenu);
                }
            }
        }
    }); // addEventListener
  }) // forEach
}); 
// DOMContentLoaded  end
</script>



        <script>
document.addEventListener('DOMContentLoaded', function () {
    getAdmin();
});

function getAdmin(){
    let memList = localStorage.getItem("member_profile");
    let memParse = JSON.parse(memList);
    let mem_name = memParse.fname+" "+memParse.lname ;

    document.getElementById("adminName").innerHTML = mem_name;
 
}


    function logOut() {
      Swal.fire({
                        position: "center",
                        icon: "info",
                        title: "คุณต้องการออกจากระบบใช่ไหม?",
                        showConfirmButton: true,
                        showCancelButton: true,
                       
                    }).then((result) => {
                        if (result.isConfirmed) {
                          localStorage.removeItem("member_profile");
  window.location.replace("http://127.0.0.1:8080/project/admin/logout.php");  
                          
                        }
                    });

}
</script>







<script>
 document.addEventListener("DOMContentLoaded", function () {
  var currentUrl = window.location.href;

  var sidebarLinks = document.querySelectorAll('.list-unstyled a');

  // Loop through each anchor element
  sidebarLinks.forEach(function (link) {
    // Check if the current URL starts with the href attribute of the link
    if (currentUrl.startsWith(link.href)) {
      // Add the "active" class to the parent li element
      link.parentNode.classList.add('active');
    }
  });
});

</script>





<style>
  /* เพิ่มสีสำหรับคลาส "active" */
.active {
  background-color: #0A32A3; /* เปลี่ยนสีตามที่ต้องการ */
  color: #F2F3F7; /* เปลี่ยนสีตัวอักษรตามที่ต้องการ */
}
</style>






<!-- <style>
  /* เอาเส้นใต้อักษรออก */
  a {
    text-decoration: none;
    cursor: pointer; /* ต้องการในกรณีที่มีไอคอน FontAwesome */
  }
</style>
 
 <aside class="main-sidebar">
  sidebar: style can be found in sidebar.less -->
  <!-- <section class="sidebar"> -->
    <!-- Sidebar user panel (optional) -->
    <!-- <div class="user-panel">
      <div class="pull-left image">
        <img src="../assets/images/lg.png" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>คุณ </p>
        Status -->
        <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree">
        <li>
        <a href="index.php" style="text-decoration: none;"><i class="fa fa-home"></i>
          <span> หน้าหลัก</span>
        </a>
      </li> --> 
<!--       
           <li class="">
        <a href=""><i class="fa fa-cogs"></i> <span>จัดการข้อมูลระบบ</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-down pull-right"></i>
        </span>
      </a>
    </li> -->
    

  
      <!-- <li>
        <a href="member.php" style="text-decoration: none;"><i class="glyphicon glyphicon-record"></i>
          <span> จัดการสมาชิก</span>
        </a>
      </li>

      <li>
     <a href="product.php" style="text-decoration: none;"><i class="glyphicon glyphicon-record"></i>
       <span> จัดการสินค้า </span>
     </a>
   </li>

      <li>
        <a href="receive_mt.php" style="text-decoration: none;"><i class="glyphicon glyphicon-record"></i>
          <span> รับวัตถุดิบ </span>
        </a>
      <li>
        <a href="manufacture.php" style="text-decoration: none;"><i class="glyphicon glyphicon-record"></i>
          <span> การผลิต </span>
        </a>
      </li>
      <li>
        <a href="mt_used.php" style="text-decoration: none;"> <i class="glyphicon glyphicon-record"></i>
          <span> บันทึกวัตถุดิบใช้ไป </span>
        </a>
      </li>
      <li>
        <a href="order.php" style="text-decoration: none;"><i class="glyphicon glyphicon-record"></i>
          <span> รายการคำสั่งซื้อ </span>
        </a>
      </li>
      
           <li class="">
        <a href="report.php" style="text-decoration: none;"><i class="fa fa-cogs"></i> <span>จัดการข้อมูลรายงาน</span>
        <span class="pull-right-container">
         
        </span>
      </a>
        

 
      <li>
        <a href="./logout.php" onclick="deleteItem();"style="text-decoration: none;"><i class="glyphicon glyphicon-off"></i>
          <span> ออกจากระบบ</span>
        </a>
      </li>
    </ul>
  </section>
   /.sidebar -->
<!-- </aside>


<script>
    function deleteItem() {
  localStorage.removeItem("member_profile");
  window.location.replace("http://127.0.0.1:8080/project/admin/logout.php");  
}
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    var currentUrl = window.location.href; -->

    <!-- // Get all anchor elements inside the sidebar
    var sidebarLinks = document.querySelectorAll('.sidebar-menu a');

    // Loop through each anchor element
    sidebarLinks.forEach(function (link) {
      // Compare the href attribute with the current URL
      if (link.href === currentUrl) {
        // Add the "active" class to the parent li element
        link.parentNode.classList.add('active');
      }
    });
  });
</script> --> 