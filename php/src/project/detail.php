<?php 
include "head.php";
include "nav.php";
?>







    <div class='input-group quantity mr-3' style='width: 130px;'></div>

    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5" id="content">
          <?php 
          include "detail_list.php";
          ?>





                </div>
            </div>
        
       

    <div class="container-fluid pt-5 pb-3 " >
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">รายละเอียดสินค้าสินอื่น</span></h2>
       <div id="other">


       </div>
       


    </div>




    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

<script>
function get_environment(){
   
$(document).ready(function() {
    // Check if jQuery is available
    if (typeof jQuery === 'undefined') {
        console.log('jQuery is not loaded.');
        return;
    }

    // Function to update data
    function updateData() {
        // Get data from local storage
        let customer_profile = localStorage.getItem("customer_profile");
  
        // Check if customer_profile is available
        if (customer_profile) {
            customer_profile = JSON.parse(customer_profile);
            let cus_id = customer_profile.id;
            let cus = { "cus_id": cus_id }
            
            // Check if cus_id is available
            $.ajax({
                type: "POST",
                url: "http://127.0.0.1:8080/project/api/get_count_cart.php",
                data: JSON.stringify(cus),
                async: false,
                success: function(response) {
                    if (response.result === 1) {
                        document.getElementById("cart_count").innerHTML = response.count;
                    }
                },
                error: function(error) {
                    console.log("error");
                }
            });
        }
    }

    // Call the function initially
    updateData();

    // Set interval to update data every 5 seconds
    setInterval(updateData, 1000);
});





    let customer_profile = localStorage.getItem("customer_profile");
        customer_profile = JSON.parse(customer_profile);
    
    getProductDetail();
    getProductOther();
}

</script>

































<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
    <script src='./assets/lightslider-master/src/js/lightslider.js'></script> 
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js'></script>
    <script src='./assets/cusFontend-asset/lib/easing/easing.min.js'></script>
    <script src='./assets/cusFontend-asset/lib/owlcarousel/owl.carousel.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>


    <script>
      
   </script>
    <!-- Contact Javascript File -->
    <script src='./assets/cusFontend-asset/mail/jqBootstrapValidation.min.js'></script>
    <script src='./assets/cusFontend-asset/mail/contact.js'></script>

    <!-- Template Javascript -->
    <script src='./assets/cusFontend-asset/js/main.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>