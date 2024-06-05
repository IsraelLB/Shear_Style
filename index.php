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


// Crear admin si no existe

$admin = mysqli_query($conn, "SELECT ID FROM administrador");

if(mysqli_num_rows($admin) == 0){
    $contrasena = "admin";

    $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);


    $insertar = "INSERT INTO administrador (Nombre, Email,Contraseña) VALUES ('admin', 'admin@gmail.com', '$hashedPassword')";
    if (mysqli_query($conn, $insertar)) {
    } else {
    }

    //Compruebo que la contraseña hasheada de la base de datos coincide con 'admin' a partir de password_verify
    $sql = "SELECT Contraseña FROM administrador WHERE Nombre='admin'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //Imprimir contraseña almacenada en la base de datos
    $succes = password_verify($contrasena, $row['Contraseña']);

    if ($succes) {
        echo "<script>alert('Contraseña correcta');</script>";
    
    } else {
        echo "<script>alert('Contraseña incorrecta');</script>";
    }
    
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
    <title>ShearStyle Salon</title>
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
        
        <!-- start slider section -->
        <div class="slider_section banner_bg">
            <img src="images/banner.jpg">
            <div class="container">
                <div class="text_box">
                    <span>Stylish Hair</span>
                    <h1>Crea tu<br>
                        look perfecto</h1>
                        <?php 
                        if (isset($_SESSION["ID"])) {
                            echo "<a href='contact.php'>Solicita una cita</a>";
                        } else{
                            echo "<a href='login.php'>Solicita una cita</a>";
                        }
                        ?>
                </div>
            </div>
        </div>

        <!-- end slider section -->

        <!-- about -->
        <div id="about" class="about">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="about_box">
                            <h2>Sobre <strong class="white"> Nosotros</strong></h2>
                            <p>Bienvenidos a Shear Style, el salón que cuida de tu imagen con profesionalidad y creatividad. Somos un equipo de estilistas expertos en las últimas tendencias y técnicas de corte, color y peinado. Ofrecemos una atención personalizada y adaptada a tu estilo, tanto si quieres un cambio radical como si prefieres mantener tu look. En Shear Style encontrarás un ambiente acogedor y moderno, con los mejores productos y herramientas para tu cabello. También tenemos servicios especiales para ocasiones especiales. Sea cual sea tu estilo, en Shear Style lo hacemos realidad. Ven a visitarnos y déjate asesorar por nuestros maestros del color y la forma. Te esperamos con una sonrisa.

                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="about_img">
                            <figure><img src="images/about_img.png" alt="#" /></figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end about -->
        <!-- service -->
        <div id="service" class="service">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ourheading">
                            <h2>Nuestros<strong class="white_ll"> Servicios</strong></h2>

                        </div>
                    </div>
                </div>

                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">

                            <div class="container">
                                <div class="carousel-caption">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser1.png"></figure>
                                                <h3>Afeitado Limpio</h3>
                                                <p>Experimenta la suavidad y precisión en cada pasada.</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser.png"></figure>
                                                <h3> Variedad de cortes de pelo</h3>
                                                <p>Descubre estilos únicos que reflejan tu personalidad.</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser3.png"></figure>
                                                <h3>Mascarillas Faciales</h3>
                                                <p>Transforma tu piel con cuidado y rejuvenecimiento.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">

                            <div class="container">
                                <div class="carousel-caption">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser1.png"></figure>
                                                <h3>Afeitado Limpio</h3>
                                                <p>Experimenta la suavidad y precisión en cada pasada.</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser.png"></figure>
                                                <h3>Variedad de cortes de pelo</h3>
                                                <p>Descubre estilos únicos que reflejan tu personalidad.</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser3.png"></figure>
                                                <h3>Mascarillas Faciales</h3>
                                                <p>Transforma tu piel con cuidado y rejuvenecimiento.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">

                            <div class="container">
                                <div class="carousel-caption">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser1.png"></figure>
                                                <h3>Afeitado Limpio</h3>
                                                <p>Experimenta la suavidad y precisión en cada pasada.</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser.png"></figure>
                                                <h3> Variedad de cortes de pelo</h3>
                                                <p>Descubre estilos únicos que reflejan tu personalidad.</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <div class="service_box">
                                                <figure><img src="images/ser3.png"></figure>
                                                <h3>Mascarillas Faciales</h3>
                                                <p>Transforma tu piel con cuidado y rejuvenecimiento.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!-- end service -->
        <!-- pricing -->
        <div class="pricing">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ourheading">
                            <h2>Tabla<strong class="white"> Precios</strong></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mar_bottom">
                        <div class="pricing_img">
                            <figure><img src="images/vvv.png" alt="#" />
                            </figure>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 pad_left">
                        <div class="pricing_box">
                            <div class="list">
                                <ul>
                                    <?php
                                        $servicios = mysqli_query($conn,'SELECT nombre,precio FROM servicio');
                                        $i=0;
                                        while($row = mysqli_fetch_array($servicios)){
                                            echo "<li><span class='float-left'>".$row['nombre']."</span><span class='float-right'>$ ".$row['precio']."</span></li>";
                                            $i++;
                                            if($i==4){
                                                break;
                                            }
                                        }
                                    ?>
                                </ul>
                                <ul>
                                <?php
                                        while($row = mysqli_fetch_array($servicios)){
                                            echo "<li><span class='float-left'>".$row['nombre']."</span><span class='float-right'>$ ".$row['precio']."</span></li>";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="opening">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ourheading">
                                    <h2>Horario<strong class="white"> ShearStyle</strong></h2>
                                </div>
                            </div>
                        </div>
                        <div class="opening_bg">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="times">
                                        <ul>
                                            <li><span>Lunes</span><span class="float-right">9:00       <strong class="bbbb">21:00</strong></span></li>
                                            <li><span>Martes </span><span class="float-right">9:00      <strong class="bbbb">21:00</strong></span></li>
                                            <li><span>Miércoles</span><span class="float-right">9:00      <strong class="bbbb">21:00</strong></span></li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="times">
                                        <ul>
                                            <li><span>Jueves </span><span class="float-right">9:00       <strong class="bbbb">21:00</strong></span></li>
                                            <li><span>Viernes</span><span class="float-right">9:00      <strong class="bbbb">21:00</strong></span></li>
                                            <li><span>Sábado</span><span class="float-right">9:00      <strong class="bbbb">21:00</strong></span></li>
                                            <li><span>Domingo</span><span class="float-right">Cerrado</span> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end Pricing -->

            </div>

            <!-- our barbers -->
            <!-- section -->
            <section class="resip_section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ourheading">
                                <h2>Nuestros<strong class="white"> Peluqueros</strong></h2>
                            </div>
                        </div>
                    </div>
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