<?php
session_start();
require_once('../config/config.php');
 
 
?>

<?php require_once('../components/header.php'); ?>
<style>
    body {

        align-items: center;

        background-color: #f5f5f5;
    }

    .form-signin {
        width: 100%;
        max-width: 630px;
        padding: 15px;
        margin: auto;
    }

    .form-signin .checkbox {
        font-weight: 400;
    }

    .form-signin .form-floating:focus-within {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>
<div class="content">
    <div class="row text-center">

        <main class="form-signin">
         
               <!-- <h1 class="h10 mb-1 fw-normal">About Us</h1>-->
               <div class="banner-text">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-heading">
                        <br><br><br><br>
                        <h1 class="banner-title" style="text-align: center;color :black;font-size: 40px;">About Us</h1>
                        <h1 class="banner-text" style="text-align: center;color :black;font-size: 20px;">The site was created to facilitate the search for the best clinics in the Qassim regions and to save time and effort for visitors. </h1>
             
                        <nav aria-label="breadcrumb">

</nav>
</div>
</div><!-- Col end -->
</div><!-- Row end -->
</div><!-- Container end -->
</div><!-- Banner text end -->
          
        </main>



    </div>

</div>

<?php require_once('../components/footer.php'); ?>