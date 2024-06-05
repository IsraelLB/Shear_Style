<!DOCTYPE html>
<html lang="en">
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    <title>Contactos ShearStyle</title>
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

                    <li class="active">
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

                    <li>
                        <a href="barbers.php">Nuestros Peluqueros</a>

                    </li>

                    <li>
                        <a href="contact.php">Solicita una Cita</a>
                    </li>
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
                                <h2>Tú eliges cuándo</h2>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- contact -->
            <div id="contact" class="contact">
                <div class="container">
                    <div class="row">
                        <div class=" col-md-12">
                            <div class="title">
                                <h2>Solicita una<strong class="white"> Cita</strong></h2>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                            <form class="main_form" action="procesar_reserva.php" method="post">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <input class="form-control" id="fecha" placeholder="Fecha" type="date" name="fecha" min="<?php echo date('Y-m-d'); ?>" max= "<?php echo date('Y-m-d', strtotime('+5 months')); ?>"> 
                                        
                                    </div>

                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <input class="form-control" type="time" id="hora" name="hora" min="10:00" max="21:00" oninput="timeStep(this)">

                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                                    <script>
                                    function timeStep(input) {
                                        var value = $(input).val();
                                        var time = value.split(':');
                                        var minutes = Math.round(time[1] / 15) * 15;
                                        if (minutes >= 60) {
                                            var hours = time[0];
                                            hours++;
                                            minutes = 0;
                                            if (hours < 10) hours = '0' + hours;
                                        }
                                        if (minutes < 10) minutes = '0' + minutes;
                                        $(input).val(time[0] + ':' + minutes);
                                    }
                                    </script>

                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label for="opciones"><h1 style="color:white">Selecciona una opción:<br></h1></label>
                                            <select id="servicio" name="servicio" style="width: 200px; background-color: #00aeef;border: #00aeef solid 2px;
                                            border-radius: inherit;
                                            margin-bottom: 25px;
                                            padding: 12px 20px;
                                            background: #00aeef;
                                           color: #fff;
                                            font-family: poppins; margin-left: 20px;">
                                            <option value="opcion0" disabled selected>Selecciona</option>
                                            <?php
                                                $servicios = mysqli_query($conn,'SELECT ID,nombre,precio FROM servicio');
                                            while($row = mysqli_fetch_array($servicios)){
                                                echo "<option value=".$row['ID']." >".$row['nombre']." ".$row['precio']."€"."</option>";
                                            }
                                            ?>
                                            </select>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <button class="send">Confirmar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="img-box">
                                <figure><img src="images/img.jpg" alt="img" /></figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end contact -->
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
    <?php
    if (isset($_SESSION['alerta'])) {           //manda una alerta dependiendo del mensaje que se le pase desde procesar_reserva
        echo "<script type='text/javascript'>alert('" . $_SESSION['alerta'] . "');</script>";
        unset($_SESSION['alerta']);
    }
    ?>

</body>
</html>