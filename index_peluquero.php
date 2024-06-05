<!DOCTYPE html>
<html lang="en">
    
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["ID"])) {
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


<div class="wrapper">
    <!-- end loader -->


    <div id="content">
        <!-- header -->
        <!-- header -->
        <header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="full">
                            <a class="logo" href="index_peluquero.php"><img src="images/logo.png" alt="#" /></a>
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

                                    } 
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- end header -->
        <!-- end header -->
        <form method="post" action="">
            <label for="fecha">Buscar por fecha:</label>
            <input type="date" id="fecha" name="fecha">
            <input type="submit" value="Buscar">
        </form>

        <?php
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
        if ($fecha) {
         $citas = mysqli_query($conn,"SELECT ID, ID_CLI, hora, Fecha FROM cita WHERE ID_PELU = $id AND Fecha = '$fecha' ORDER BY hora DESC");
        } else {
            $citas = mysqli_query($conn,"SELECT ID, ID_CLI, hora, Fecha FROM cita WHERE ID_PELU = $id ORDER BY Fecha DESC, hora DESC");
        }

        if (mysqli_num_rows($citas) > 0) {
            echo "<table style='margin: auto; width: 50%; border-collapse: collapse; text-align: center;'>";
            echo "<tr style='background-color: #f2f2f2;'><th>Hora</th><th>Fecha</th><th>Cliente</th><th> </th></tr>";
            while($row = mysqli_fetch_assoc($citas)) {
                $cliente = mysqli_query($conn,"SELECT nombre FROM cliente WHERE ID = $row[ID_CLI]");
                $fecha_cita = strtotime($row["Fecha"]);  // Convierte la fecha de la cita a timestamp
                $fecha_actual = strtotime(date('Y-m-d'));
                $fecha_formato = date('d-m-Y', $fecha_cita);  // Pone la fecha en formato dd-mm-aaaa
                $nombre_cli = mysqli_fetch_assoc($cliente);
                echo "<tr><td>". $row["hora"] . "</td><td>". $fecha_formato  . "</td><td>". $nombre_cli['nombre'] . "</td>";
                if ($fecha_cita > $fecha_actual) {
                    echo "<td><a href='eliminar_cita.php?id_cita=".$row["ID"]."&id_cliente=".$row["ID_CLI"]."' class='btn btn-danger'>Eliminar</a></td></tr>";
                } else {
                    echo "<td>REALIZADA</td></tr>";
                }
            }
            echo "</table>";
        } else {
            echo "No hay citas para este peluquero en la fecha seleccionada.";
        }
        ?>

            <!-- footer -->

            <div class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="footer_logo">
                                <a href="index_peluquero.php"><img src="images/logo1.png" alt="logo" /></a>
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
    if (isset($_SESSION['alerta'])) {           //manda una alerta dependiendo del mensaje que se le pase desde eliminar_cita
        echo "<script type='text/javascript'>alert('" . $_SESSION['alerta'] . "');</script>";
        unset($_SESSION['alerta']);
    }
    ?>
</body>

</html>