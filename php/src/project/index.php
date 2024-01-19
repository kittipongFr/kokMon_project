<?php 
include "head.php";
include "nav.php";
?>



<!-- Shop Start -->
<div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-2 col-md-4">
              
                <!-- Size End -->
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-8 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <button class="btn btn-sm btn-light"><i class="fa fa-th-large"></i></button>
                                <button class="btn btn-sm btn-light ml-2"><i class="fa fa-bars"></i></button>
                            </div>
                            <div class="ml-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">ลำดับ</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">ล่าสุด</a>
                                        <a class="dropdown-item" href="#">ความนิยม</a>
                                        <a class="dropdown-item" href="#">คะเเนนที่ดีที่สุด</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   <div id="content">

                    <?php 
                    include "_product_list.php";
                    ?>


                   </div>



                    <!-- ปปปป -->
                </div>
            </div>
            <!-- Shop Product End -->
        </div>



        <div class="col-lg-2 col-md-4">
        </div>


    </div>
    <!-- Shop End -->

   




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


            getProductList()
        }



       

    </script>














    <?php
// include "footer.php";
include "footerjs.php";
    ?>

 