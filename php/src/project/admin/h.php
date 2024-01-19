<script>
  document.addEventListener("DOMContentLoaded", function () {
    
    checkLogin();
});
function checkLogin() {

  if (localStorage.getItem("member_profile")) {
        let member_profileList = localStorage.getItem("member_profile");
        let member_profile = JSON.parse(member_profileList);
        mem_id = member_profile.id;


        console.log("mem555 "+mem_id);

        formData = {
            "mem_id": mem_id, 
        };
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "http://localhost:8080/project/api/admin/check_login.php",
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
                  window.location.replace("http://127.0.0.1:8080/project/admin/back_login.php");
                });
                    }
                
            },
            error: function (error) {
                console.log(formData);
                console.log(error);

                Swal.fire({
                    title: "Error occurred",
                    icon: 'error',
                    confirmButtonText: 'Close'
                  }).then((result) => {  
                  window.location.replace("http://127.0.0.1:8080/project/admin/back_login.php");
                });
            }
        });
  }else{
    Swal.fire({
                    title: "คุณไม่มีสิทธิ์ใช้งานหน้านี้",
                    icon: 'error',
                    confirmButtonText: 'Close'
                  }).then((result) => {
                  
                                  window.location.replace("http://127.0.0.1:8080/project/index.php");
                             
              });
  }  
   
}
</script>



<!doctype html>
<html lang="en">
  <head>
 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <title>วิสาหกิจบ้านโคกมอน</title>
    <!-- Tell the browser to be responsive to screen width -->

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <!-- <link href="../assets/bower_components/font-awesome/css/font-awesome.min.css"rel="stylesheet"> -->
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/bower_components/DataTables-1.13.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="../assets/bower_components/Ionicons/css/ionicons.min.css"> -->
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-xxx" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="../assets/bower_components/DataTables-1.13.8/css/dataTables.bootstrap.min.css"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../assets/dist/css/skins/_all-skins.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="../assets/fonts/font.css"> -->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mitr">

    <style>
        /* .boxs{
             box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
        } */
    body {
        font-family: "Mitr", serif;
    }

    .card .card-body{
    font-family: "Mitr", serif;
    }
    
    </style>

		<link rel="stylesheet" href="../css/style.css">



    
    </head>






    








<!-- <!DOCTYPE html>
<html>
  <head> 

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>วิสาหกิจบ้านโคกมอน</title> -->
    <!-- Tell the browser to be responsive to screen width -->

    <!-- <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
 
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/bower_components/DataTables-1.13.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
     Ionicons -->
   
    <!-- DataTables -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-xxx" crossorigin="anonymous" /> -->
   
    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="../assets/dist/css/AdminLTE.min.css"> -->
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <!-- <link rel="stylesheet" href="../assets/dist/css/skins/_all-skins.min.css">
  -->
    <!-- Google Font -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mitr"> -->

    <!-- <style>
        /* .boxs{
             box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
        } */
    body {
        font-family: "Mitr", serif;
    }

    .card .card-body{
    font-family: "Mitr", serif;
    }
    
    </style> --> 




    
    </head>