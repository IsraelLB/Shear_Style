<!DOCTYPE html>
<html lang="en">
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["ID"]) AND $_SESSION["Rol"] == "admin") {
    $id = $_SESSION["ID"]; // Recupera el ID de la sesión
}else{
    echo"No se ha recuperado el id de la sesion";
    header("Location: login.php");
    exit; // Asegura que el script se detenga después de la redirección

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
                    <a href="index_admin.php">Home</a>
                </li>
                <li>
                    <a href="admin_usuario.php">Usuarios</a>
                </li>
                <li>
                    <a href="admin_servicio.php">Servicios</a>
                </li>

                <li>
                    <a href="admin_cita.php">Citas</a>

                </li>

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