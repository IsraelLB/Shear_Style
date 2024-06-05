<!DOCTYPE html>
<html lang="en">
<?php
session_start(); // Inicia la sesión

if (isset($_SESSION["ID"])) {
    $id = $_SESSION["ID"]; // Recupera el ID de la sesión
}
?>
<?php
$servername = "localhost";
$username = "pw";
$password = "pw";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$bd = mysqli_select_db($conn, 'peluqueria');
if(!$bd) {
    echo"Error al seleccionar la base de datos.";
}
?>
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Peluqueros ShearStyle</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- owl css -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- responsive-->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- awesome fontfamily -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>
<!-- body -->

<body class="main-layout">
    <!-- loader  -->
    <div class="loader_bg">
        <div class="loader"><img src="images/loading.gif" alt="" /></div>
    </div>

    <div class="wrapper">
        <!-- end loader -->

        <div class="sidebar">
            <!-- Sidebar  -->
            <nav id="sidebar">

                <div id="dismiss">
                    <i class="fa fa-arrow-left"></i>
                </div>

                <ul class="list-unstyled components">

                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="about.php">Sobre Nosotros</a>
                    </li>
                    <li>
                        <a href="service.php">Servicios</a>
                    </li>
                    <li>
                        <a href="pricing.php">Precios</a>
                    </li>

                    <li class="active">
                        <a href="barbers.php">Nuestros Peluqueros</a>

                    </li>

                    <?php 
                        if (isset($_SESSION["ID"])) {
                            echo " <li><a href='contact.php'>Solicita una cita</a> </li>";
                        } else{
                            echo "<li><a href='login.php'>Solicita una cita</a></li>";
                        }
                        ?>
                </ul>

            </nav>
        </div>

        <div id="content">
         <!-- header -->
         <header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="full">
                            <a class="logo" href="index.php"><img src="images/logo.png" alt="#" /></a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="full">
                            <div class="right_header_info">
                                <ul>
                                    <li class="dinone"><img style="margin-right: 15px;margin-left: 15px;" src="images/phone_icon.png" alt="#"><a href="#">956 14 32 56</a></li>
                                    <li class="dinone"><img style="margin-right: 15px;" src="images/mail_icon.png" alt="#"><a href="#">ShearStyle@gmail.com</a></li>
                                    <?php
                                    if (isset($_SESSION["ID"])) {
                                        echo "<li class='button_user'><a class='button active' href='logout.php'>Cerrar Sesión</a></li>";

                                        
                                        
                                        echo "<li class='button_user'><a class='button active' href='index_cliente.php'>Mi cuenta</a></li>";
                                    } else{
                                        
                                    echo "<li class='button_user'><a class='button active' href='login.php'>Iniciar Sesión</a></li>";
                                        echo "<li class='button_user'> <a class='button active' href='register.php'>Regístrate</a></li>";
                                    }
                                    ?>


                                    <li>
                                        <button type="button" id="sidebarCollapse">
                                            <a href="#">MENU</a>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- end header -->
            <div class="yellow_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="title">
                                <h2>Our Barbers</h2>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- our barbers -->
            <!-- section -->
            <section class="resip_section">
                <div class="container">

                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="owl-carousel owl-theme">
                            <?php
                                        $servicios = mysqli_query($conn,'SELECT nombre,foto FROM peluquero');
                                        while($row = mysqli_fetch_array($servicios)){
                                            $foto = base64_encode($row['foto']);
                                            echo "<div class='item'>
                                                <div class='product_blog_img'>
                                                    <img src='data:image/png;base64,".$foto."' alt='#' />
                                                </div>
                                                <div class='product_blog_cont'>
                                                    <h3>".$row['nombre']."</h3>
                                                    <h4>Peluquero</h4>
                                                </div>
                                            </div>";
                                        }
                                    ?>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- end our barbers -->

            <!-- footer -->

    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer_logo">
                        <a href="index.php"><img src="images/logo1.png" alt="logo" /></a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="address">
                        <h3>Drección</h3>
                        <p>
                            Dirección: 73 Gran Vía, Madrid, España
                            <br> Tel: +34 9561432568
                            <br> Email: ShearStyle@gmail.com</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="lik">

                        <li> <img src="images/fb.png" alt="#" /></li>
                        <li> <img src="images/tw.png" alt="#" /></li>
                        <li> <img src="images/you.png" alt="#" /></li>

                    </ul>

                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <p>© 2019 All Rights Reserved. </p>
            </div>
        </div>
    </div>


<!-- end footer -->

        </div>

        <div class="overlay"></div>
        <!-- Javascript files-->
        <script src="js/jquery.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/custom.js"></script>
        <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>

        <script src="js/jquery-3.0.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#sidebar").mCustomScrollbar({
                    theme: "minimal"
                });

                $('#dismiss, .overlay').on('click', function() {
                    $('#sidebar').removeClass('active');
                    $('.overlay').removeClass('active');
                });

                $('#sidebarCollapse').on('click', function() {
                    $('#sidebar').addClass('active');
                    $('.overlay').addClass('active');
                    $('.collapse.in').toggleClass('in');
                    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
                });
            });
        </script>

        <style>
            #owl-demo .item {
                margin: 3px;
            }
            
            #owl-demo .item img {
                display: block;
                width: 100%;
                height: auto;
            }
        </style>

        <script>
            $(document).ready(function() {
                var owl = $('.owl-carousel');
                owl.owlCarousel({
                    margin: 10,
                    nav: true,
                    loop: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 2
                        },
                        1000: {
                            items: 3
                        }
                    }
                })
            })
        </script>

</body>

</html>