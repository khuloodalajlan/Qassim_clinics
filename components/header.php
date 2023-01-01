<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <title> Qassim clinics</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Favicon-->
    <link rel="shortcut icon" href="../theme/images/logo.png" type="image/x-icon">

    <!-- THEME CSS
	================================================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../theme/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Themify -->
    <link rel="stylesheet" href="../theme/plugins/themify/css/themify-icons.css">
    <link rel="stylesheet" href="../theme/plugins/slick-carousel/slick-theme.css">
    <link rel="stylesheet" href="../theme/plugins/slick-carousel/slick.css">
    <!-- Slick Carousel -->
    <link rel="stylesheet" href="../theme/plugins/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="../theme/plugins/owl-carousel/owl.theme.default.min.css">
    <link rel="stylesheet" href="../theme/plugins/magnific-popup/magnific-popup.css">
    <!-- manin stylesheet -->
    <link rel="stylesheet" href="../theme/css/style.css">
    <!-- initialize jQuery Library -->
    <script src="../theme/plugins/jquery/jquery.js"></script>
</head>

<body>
    <header class="header-top bg-grey justify-content-center py-2 d-lg-none">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navigation-2 navigation">
                <a class="navbar-brand" href="#">
                    <img src="../theme/images/logo.png" alt="" class="img-fluid">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="ti-menu"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul id="menu" class="menu navbar-nav mx-auto">


                        <?php if (!isset($_SESSION['id'])) {
                        ?>


                            <li class="nav-item">
                                <a class="nav-link" href="../auth/login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../auth/register.php">register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../clinics/index.php">Clinics</a>
                            </li>

                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link text-centered" style="text-align: center;" href="#"><?php echo $_SESSION['user']['full_name'] ?></a>
                            </li>
                            <?php if ($_SESSION['user']['role'] == 'admin') { ?>


                                <li class="nav-item">
                                    <a class="nav-link" href="../clinics/index.php">Clinics</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../comments/index.php">Comments</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../users/index.php">Users</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="../categorys/index.php">Category</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="../citys/index.php">Citys</a>
                                </li>
                            <?php  } else if ($_SESSION['user']['role'] == 'user') { ?>

                                <li class="nav-item">
                                    <a class="nav-link" href="../clinics/index.php">Clinics</a>
                                </li>
                            <?php   } ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../auth/logout.php">Logout</a>
                            </li>
                        <?php }     ?>

                    </ul>

                    <ul class="list-inline mb-0 d-block d-lg-none">
                        <li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-linkedin"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-pinterest"></i></a></li>
                    </ul>
                    <div class="bg-grey">
                        © 2022 All right reserved
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="section-padding pb-0">
        <div class="sidebar d-none d-lg-block">
            <div class="sidebar-sticky">
                <div class="logo-wrapper">
                    <a class="navbar-brand" href="#">
                        <img src="../theme/images/logo.png" alt="" class="img-fluid">
                    </a>
                </div>

                <div class="main-menu">
                    <nav class="navbar navbar-expand-lg p-0">
                        <div class="navbar-collapse collapse" id="navbarsExample09" style="">
                            <ul class="list-unstyled ">
                                <li class="nav-item dropdown d-none">
                                    <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Posts Format +</a>
                                    <div class="dropdown-menu" aria-labelledby="dropdown03">
                                        <a class="dropdown-item" href="post-video.html">Video Formats</a>
                                        <a class="dropdown-item" href="post-audio.html">Audio Format</a>
                                        <a class="dropdown-item" href="post-link.html">Quote Format</a>
                                        <a class="dropdown-item" href="post-gallery.html">Gallery Format</a>
                                        <a class="dropdown-item" href="post-image.html">Image Format</a>
                                    </div>
                                </li>


                                <?php if (!isset($_SESSION['id'])) {
                                ?>


                                    <li class="nav-item">
                                        <a class="nav-link" href="../auth/login.php">Login</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="../auth/register.php">register</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="../clinics/index.php">Clinics</a>
                                    </li>

                                <?php } else { ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-centered" style="text-align: center;" href="#"><?php echo $_SESSION['user']['full_name'] ?></a>
                                    </li>
                                    <?php if ($_SESSION['user']['role'] == 'admin') { ?>


                                        <li class="nav-item">
                                            <a class="nav-link" href="../clinics/index.php">Clinics</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="../comments/index.php">Comments</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="../users/index.php">Users</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="../categorys/index.php">Category</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="../citys/index.php">Citys</a>
                                        </li>
                                    <?php  } else if ($_SESSION['user']['role'] == 'user') { ?>

                                        <li class="nav-item">
                                            <a class="nav-link" href="../clinics/index.php">Clinics</a>
                                        </li>
                                    <?php   } ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="../auth/logout.php">Logout</a>
                                    </li>
                                <?php }     ?>

                            </ul>
                        </div>
                    </nav>
                </div>

                <div class="header-social-wrapper">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-linkedin"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="ti-pinterest"></i></a></li>
                    </ul>
                    <div class="bg-grey">
                        © 2022 All right reserved
                    </div>

                </div>

            </div>

        </div>