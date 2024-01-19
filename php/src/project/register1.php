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
	<title>ลงทะเบียน</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="./assets/login-asset/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/css/util1.css">
	<link rel="stylesheet" type="text/css" href="./assets/login-asset/css/main1.css">
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

	<div class="contact1" >
		<div class="container-contact1" style="box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);">
			<div class="contact1-pic js-tilt" data-tilt>
				<img src="./assets/images/<?php echo $row["img"] ?>" alt="IMG">
			</div>

			<form  class="contact1-form validate-form">
				<span class="contact1-form-title">
					ลงทะเบียน <i class="fa fa-user"></i>
				</span>

	
<div class="wrap-input100 validate-input" data-validate="Username is required">
  <input class="input100" type="email" id="email" name="email" placeholder="E-mail"  maxlength="50">
  <span class="focus-input100"></span>
  <span class="symbol-input100">
    <i class="fa fa-user" aria-hidden="true"></i>
  </span>
</div>

<div class="wrap-input100 validate-input" data-validate="Valid Password is required" >
  <input class="input100" type="password" id="password" name="password" placeholder="รหัสผ่าน" maxlength="20">
  <span class="focus-input100"></span>
  <span class="symbol-input100">
    <i class="fa fa-lock" aria-hidden="true"></i>
  </span>
</div>

<div class="wrap-input100 validate-input" data-validate="Valid Confirm Password is required" >
  <input class="input100" type="password" id="cpassword" name="cpassword" placeholder="ยืนยัน รหัสผ่าน" maxlength="20">
  <span class="focus-input100"></span>
  <span class="symbol-input100">
    <i class="fa fa-lock" aria-hidden="true"></i>
  </span>
</div>

<div class="wrap-input100 validate-input" data-validate="FirstName is required" >
  <input class="input100" type="text" id="fname" name="name" placeholder="ชื่อ" maxlength="50">
  <span class="focus-input100"></span>
  <span class="symbol-input100">
    <i class="fa fa-user" aria-hidden="true"></i>
  </span>
</div>

<div class="wrap-input100 validate-input" data-validate="Last Name is required">
  <input class="input100" type="text" id="lname" name="lname" placeholder="นามสกุล"  maxlength="50">
  <span class="focus-input100"></span>
  <span class="symbol-input100">
    <i class="fa fa-user" aria-hidden="true"></i>
  </span>
</div>

<div class="wrap-input100 validate-input" data-validate="TEL is required">
  <input class="input100" type="text" id="tel" name="tel" placeholder="เบอร์โทร" maxlength="10">
  <span class="focus-input100"></span>
  <span class="symbol-input100">
    <i class="fa fa-phone" aria-hidden="true"></i>
  </span>
</div>


				<div class="wrap-input1 validate-input" data-validate = "Address is required">
					<textarea class="input1" name="address" id="address" placeholder="ที่อยู่ : "></textarea>
					<span class="shadow-input1"></span>
				</div>


				<div class="container-contact1-form-btn">
					<button class="contact1-form-btn" onclick="register(event)" style="box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);">
						<span>
							สมัครสมาชิก
							<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
						</span>
					</button>
				</div>


				<div class="text-center p-t-20">
						<a class="txt2"  href="login1.php">
							เข้าสู่ระบบ
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
					<p id="message"></p>
			</form>
		</div>
	</div>

	<script>
   
   function register(event) {
    event.preventDefault(); // Prevent the form from submitting

    let email;
    let password;
    let cpassword;
    let fname;
    let lname;
    let tel;
    let address;
    email = document.getElementById("email").value;
    password = document.getElementById("password").value;
    cpassword = document.getElementById("cpassword").value;
    fname = document.getElementById("fname").value;
    lname = document.getElementById("lname").value;
    tel = document.getElementById("tel").value;
    address = document.getElementById("address").value;
    if (password !== cpassword) {
      Swal.fire({
                    title:  "รหัสผ่านไม่ตรงกัน!",
                    icon: 'error',
                    confirmButtonText: 'Close'
                })
        return; // Stop the function if passwords don't match
    }

    let request_data = {
        "email": email,
        "password": password,
        "fname": fname,
        "lname": lname,
        "tel": tel,
        "address": address,

    }
    // console.log(request_data);
    $.ajax({
    type: "POST",
    url: "http://localhost:8080/project/api/set_customer_register.php",
    async: false,
    data: JSON.stringify(request_data),
    success: function (response) {
        console.log("success...");
        console.log(response);
        console.log(response.result);
        console.log(response.message);

        if (response.result === 1) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: response.message,
                showConfirmButton: false,
                timer: 2000,
            });

            // Redirect after 2000 milliseconds (2 seconds)
            setTimeout(function () {
                window.location.replace("http://127.0.0.1:8080/project/login1.php");
            }, 2000);
        } else {
            // Check specifically for duplicate email case
            if (response.result === 3) {
                Swal.fire({
                    title: response.message,
                    icon: 'error',
                    confirmButtonText: 'Close'
                });
            } else {
                document.getElementById("email").value = "";
                document.getElementById("password").value = "";
                document.getElementById("email").focus();
                Swal.fire({
                    title: response.message,
                    icon: 'error',
                    confirmButtonText: 'Close'
                });
                alert("Password and Confirm Password do not match");
            }
        }
    },
    error: function (error) {
        console.log(error);
        console.log(response);
        console.log(response.result);
        console.log(response.message);

        Swal.fire({
            title: response.message,
            icon: 'error',
            confirmButtonText: 'Close'
        });
    }
});

    
}

</script>



<!--===============================================================================================-->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>

<!--===============================================================================================-->
	<script src="./assets/login-asset/js/main1.js"></script>

</body>
</html>
