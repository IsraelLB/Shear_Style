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

<div class="loader_bg">
    <div class="loader"><img src="images/loading.gif" alt="" /></div>
</div>

<div class="wrapper">
    
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

        <form method="post" action="">
            <label for="fecha">Buscar por fecha:</label>
            <input type="date" id="fecha" name="fecha">
            <input type="submit" value="Buscar">
        </form>

        <form method="post" action="">
            <label for="peluquero">Buscar por peluquero:</label>
            <input type="int" id="peluquero" name="peluquero">
            <input type="submit" value="Buscar">
        </form>

        <form method="post" action="">
            <label for="cliente">Buscar por cliente:</label>
            <input type="int" id="cliente" name="cliente">
            <input type="submit" value="Buscar">
        </form>


        <?php
        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
        $peluquero = isset($_POST['peluquero']) ? $_POST['peluquero'] : '';
        $cliente = isset($_POST['cliente']) ? $_POST['cliente'] : '';

        if ($fecha) {
         $citas = mysqli_query($conn,"SELECT * FROM cita WHERE Fecha = '$fecha' ORDER BY hora DESC");
        } elseif($peluquero) {
            $citas = mysqli_query($conn,"SELECT * FROM cita WHERE ID_PELU = $peluquero ORDER BY Fecha DESC, hora DESC");
        } elseif($cliente) {
            $citas = mysqli_query($conn,"SELECT * FROM cita WHERE ID_CLI = $cliente ORDER BY Fecha DESC, hora DESC");
        } else {
            //Mostrar solo las 5 primeras citas
            $citas = mysqli_query($conn,"SELECT * FROM cita ORDER BY Fecha DESC, hora DESC");  
        }


        echo "<table style='margin: auto; width: 50%; border-collapse: collapse; text-align: center;'>";
        echo "<tr style='background-color: #f2f2f2;'><th>Id</th><th>Id Servicio</th><th>Hora</th><th>Fecha</th><th>Cliente</th><th>Id Cliente</th><th>Peluquero</th><th>Id Peluquero</th><th> </th></tr>";
        $n = isset($_POST['n']) ? $_POST['n'] : 5;
        if (mysqli_num_rows($citas) > 0) {
            $i = 0;
            while($row = mysqli_fetch_assoc($citas) and $i < $n) {
                if($i >= $n-5){
                    $cliente = mysqli_query($conn,"SELECT nombre FROM cliente WHERE ID = $row[ID_CLI]");
                    
                    $peluquero = mysqli_query($conn,"SELECT nombre FROM peluquero WHERE ID = $row[ID_PELU]");
                    $fecha_formato = date('d-m-Y', strtotime($row["Fecha"]));  // Pone la fecha en formato dd-mm-aaaa
                    $nombre_cli = mysqli_fetch_assoc($cliente);
        
                    $nombre_pel = mysqli_fetch_assoc($peluquero);
                    echo "<tr><td>" . $row["ID"] . "</td><td>". $row["ID_SERV"] ."</td><td>". $row["hora"] . "</td><td>". $fecha_formato  . "</td><td>"
                    . $nombre_cli['nombre'] . "</td><td>" . $row["ID_CLI"] ."</td><td>" . $nombre_pel['nombre'] . "</td><td>". $row["ID_PELU"] ."</td>";
                    
                    echo "<td>";
                    echo "<form  method='post' action='administrar_reserva.php'>";
                        echo "<input type='hidden' name='id_cita' value='".$row['ID']."'>";
                        echo "<button type='submit' name='accion' value='eliminar' class='btn btn-danger' 
                        onclick=\"return confirm('¿Estás seguro de que quieres eliminar esta cita?');\">Eliminar</button>";
                    echo "</form>";
                    echo "</td>";
                

                }
                $i++;
            }

        } else {
            echo "<tr><td colspan='9'>No hay citas para esta selección.</td></tr>";
        }
?>
        <tr><td colspan='9'></td></tr>
        <tr><td colspan='9'></td></tr>
        <tr><td colspan='9'></td></tr>

        <tr>
        <form method='post' action='administrar_reserva.php'>
            <td><input type='number' name='id_cita'></td>
            <td><input type='number' name='id_servicio'></td>
            <td><input type='time' name='hora'></td>
            <td><input type='date' name='fecha'></td>
            <td></td>
            <td><input type='number' name='id_cliente'></td>
            <td></td>
            <td><input type='number' name='id_peluquero'></td>
            <td><input type='submit' name = 'accion' value='modificar'></td>
            <td><input type='submit' name = 'accion' value='crear'></td>
        </form>
        </tr>

        <tr><td colspan='9'></td></tr>
        <tr><td colspan='9'></td></tr>
        <tr><td colspan='9'></td></tr>
        
        <!--Boton siguiente y anterior para mostrar las 5 siquientes-->
        <tr>
        
        <?php if($n > 5){
            echo "<form method='post' action='admin_cita.php'>";
            echo "<input type='hidden' name='n' value='".($n-5)."'>";
            echo "<td> <input type='submit' value='Anterior'></td>";
            echo "</form>";
            echo "<td colspan='7'></td>";
        }
        else{
            echo "<td colspan='8'></td>";
        }?>
    

        <td>    
        <?php if($n < mysqli_num_rows($citas)){
            echo "<form method='post' action='admin_cita.php'>";
            echo "<input type='hidden' name='n' value='".($n+5)."'>";
            echo "<input type='submit' value='Siguiente'>";
            echo "</form>";
        }?>
        </td>
        </tr>

        </table>

        <br>
        <br>

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