<script>

//   document.addEventListener("DOMContentLoaded", function () {
    
//     checkLogin();
// });
function checkLogin() {

  if (localStorage.getItem("customer_profile")) {
        let customer_profileList = localStorage.getItem("customer_profile");
        let customer_profile = JSON.parse(customer_profileList);
        cus_id = customer_profile.id;


        console.log("cus555 "+cus_id);

        formData = {
            "cus_id": cus_id, 
        };
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "http://localhost:8080/project/api/check_login.php",
            processData: false,
            contentType: false,
            data: JSON.stringify(formData),
            success: function (response) {
                console.log("success...");
                console.log(response);
                if (response.result === 1) {
    
                     console.log(response.message)
      
                } else {
                        Swal.fire({
                            title: response.message,
                            icon: 'error',
                            confirmButtonText: 'Close'
                          }).then((result) => {  
                  window.location.replace("http://127.0.0.1:8080/project/login1.php");
                });
                    }
                
            },
            error: function (error) {
                console.log(formData);
                console.log(error);

                Swal.fire({
                    title: "กรุณาเข้าสู่ระบบ !",
                    icon: 'error',
                    confirmButtonText: 'Close'
                  }).then((result) => {  
                  window.location.replace("http://127.0.0.1:8080/project/login1.php");
                });
            }
        });
  }else{
    Swal.fire({
                    title: "กรุณาเข้าสู่ระบบ !",
                    icon: 'error',
                    confirmButtonText: 'Close'
                  }).then((result) => {
                  
                                  window.location.replace("http://127.0.0.1:8080/project/login1.php");
                             
              });
  }  
   
}
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title class="fas fa-band-aid">ธูปหอมโคกมอน</title>
    <link rel="icon" type="image/x-icon" href="./img/earth.png">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
  
    <link href="./assets/cusFontend-asset/lib/animate/animate.min.css" rel="stylesheet">
    <link href="./assets/cusFontend-asset/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="./assets/cusFontend-asset/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mitr">
    
    <link rel="stylesheet"  href="./assets/lightslider-master/src/css/lightslider.css"/>
    <style>
    	ul{
			list-style: none outside none;
		    padding-left: 0;
            margin: 0;
		}
        .demo .item{
            margin-bottom: 60px;
        }
		.content-slider li{
		    background-color: #ed3020;
		    text-align: center;
		    color: #FFF;
		}
		.content-slider h3 {
		    margin: 0;
		    padding: 70px 0;
		}
		.demo{
			width: 800px;
		}
    </style>



    <style>
       
    body {
        font-family: "Mitr", serif;
    }

    </style>


</head>