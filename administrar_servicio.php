<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

session_start(); // Inicia la sesión

if (isset($_SESSION["ID"]) AND $_SESSION["Rol"] == "admin") {
    $id = $_SESSION["ID"]; // Recupera el ID de la sesión
}
else{
    echo"No se ha recuperado el id de la sesion";
    header("Location: login.php");
    exit; // Asegura que el script se detenga después de la redirección

}

// Conectar con la base de datos
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

$accion = $_POST['accion'];
$id = $_POST['id'];

$sql="SELECT * FROM servicio WHERE ID = $id";
$query_servicio = mysqli_query($conn,$sql);
$servicio = mysqli_fetch_assoc($query_servicio);

if ($accion == 'eliminar') {
    $sql = "DELETE FROM servicio WHERE ID = $id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['alerta'] = 'El servicio se ha eliminado.';
        header('Location: admin_servicio.php');
    } else {
        $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
        header('Location: admin_servicio.php');
    }
}
elseif($accion == 'modificar' and mysqli_num_rows($query_servicio) > 0){
    if(!empty($_POST['nombre'])){
        $nombre = $_POST['nombre'];
    }
    else{
        $nombre = $servicio['Nombre'];
    }
    if(!empty($_POST['descripcion'])){
        $descripcion = $_POST['descripcion'];
    }
    else{
        $descripcion = $servicio['Descripcion'];
    }
    if(!empty($_POST['duracion'])){
        $duracion = $_POST['duracion'];
    }
    else{
        $duracion = $servicio['Duracion'];
    }
    if(!empty($_POST['precio'])){
        $precio = $_POST['precio'];
    }
    else{
        $precio = $servicio['Precio'];
    }

    $sql = "UPDATE servicio SET Nombre = '$nombre', Descripcion = '$descripcion', Duracion = '$duracion', Precio = '$precio' WHERE ID = $id";
    if($conn->query($sql) === TRUE){
        $_SESSION['alerta'] = 'El servicio se ha modificado.';
        header('Location: admin_servicio.php');
    }
    else{
        $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
        header('Location: admin_servicio.php');
    }
}
elseif($accion == 'crear'){
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];

    $sql = "INSERT INTO servicio (ID,Nombre, Descripcion, Duracion, Precio) VALUES ('$id','$nombre', '$descripcion', '$duracion', '$precio')";
    if($conn->query($sql) === TRUE){
        $_SESSION['alerta'] = 'El servicio se ha creado.';
        header('Location: admin_servicio.php');
    }
    else{
        $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
        header('Location: admin_servicio.php');
    }
}

?>