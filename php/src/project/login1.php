<?php
    session_start();
    /*print_r(session_id());
    exit;*/
?>
<?php

include './config/config_db.php';
$query = "SELECT * FROM community_enterprise limit 1";
$result = mysqli_query($conn, $query);
if ($result) {
 // Fetch associative array
 $row = mysqli_fetch_assoc($result);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>เข้าสู่ระบบ</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="./assets/login-asset/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/css/util.css">
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/css/main.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mitr">
    <style>
       
    body {
        font-family: "Mitr", serif;
    }

    </style>
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="./assets/images/<?php echo $row["img"] ?>"  alt="IMG">
				</div>

				<form method="POST" class="login100-form validate-form">
					<h3 class="text-center mb-2" >
						เข้าสู่ระบบ
					</h3>

					<div class="wrap-input100 validate-input" data-validate = "กรุณากรอกอีเมลล์">
						<input class="input100" type="email" id="email" name="email" placeholder="อีเมลล์">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "กรุณากรอกรหัสผ่าน">
						<input class="input100" type="password" id="password" name="password" placeholder="รหัสผ่าน">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="button" onclick="login()">
							เข้าสู่ระบบ
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							ลืม
						</span>
						<a class="txt2" href="#">
							อีเมลล์ / รหัสผ่าน?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt4" href="./register1.php">
							ลงทะเบียน
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>

						<a class="txt4" href="./admin/back_login.php">
						สำหรับผู้ดูแลระบบ
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

    <script>
    var session = "<?php echo session_id(); ?>";
    function login() {
        let email;
        let password;
        email = document.getElementById("email").value;
        password = document.getElementById("password").value;

        let request_data={
            "email":email,
            "password":password,
            "session":session
        }
        console.log(request_data);

        $.ajax({
            type:"POST",
            url:"http://localhost:8080/project/api/get_login.php",
            async:false,
            data:JSON.stringify(request_data),
            success:function(response){
                console.log("success...");
                console.log(response);
                console.log(response.result); 
                console.log(response.message);
                if(response.result === 1){
                    //console.log("Go To HOME.php");
                    localStorage.setItem("customer_profile",JSON.stringify(response.datalist));
                    // let customer_profile = localStorage.getItem("customer_profile");
                    // customer_profile = JSON.parse(customer_profile);
                    // console.log(customer_profile.email);
                    // return 0;
                    window.location.replace("http://127.0.0.1:8080/project/index.php");  
					//  <--?menu=productlist-->
                }else{
                    //console.log("GO TO LOGIN.php");
                    document.getElementById("email").value = "";
                    document.getElementById("password").value = "";
                    document.getElementById("email").focus();
                    Swal.fire({
                        title: response.message,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    })
                }
                
            },error:function(error){
                console.log(error);
            }
        });

        // Swal.fire({
        //     title: 'ทำรายการไม่ถูกต้อง',
        //     text: 'ทำรายการต่อหรือไม่',
        //     icon: 'error',
        //     showCancelButton: true,
        //     confirmButtonText: 'Close'
        // })
}
</script>



	
<!--===============================================================================================-->	
	<script src="./assets/login-asset/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="./assets/login-asset/vendor/bootstrap/js/popper.js"></script>
	<script src="./assets/login-asset/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="./assets/login-asset/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="./assets/login-asset/vendor/tilt/tilt.jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>