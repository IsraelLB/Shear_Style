<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

//funcion mandar correo

function mandar_correo($email,$nombre,$subject,$body){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'shearstylesalonpw@outlook.com'; // Tu dirección de correo Gmail
    $mail->Password = 'contrasena123'; // Tu contraseña de correo Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('shearstylesalonpw@outlook.com', 'ShearStyle Salon');
    $mail->addAddress($email, $nombre);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->send();
}



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
$id_usuario = $_POST['id_usuario'];
$rol = $_POST['rol'];

$sql="SELECT * FROM $rol WHERE ID = $id_usuario";
$query_usuario = mysqli_query($conn,$sql);
$usuario = mysqli_fetch_assoc($query_usuario);

if ($accion == 'eliminar') {
    $sql = "DELETE FROM $rol WHERE ID = $id_usuario";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['alerta'] = 'El usuario se ha eliminado.';
        header('Location: admin_usuario.php');
    } else {
        $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
        header('Location: admin_usuario.php');
    }
}
elseif($accion == 'modificar' and mysqli_num_rows($query_usuario) > 0){
    if(!empty($_POST['nombre'])){
        $nombre = $_POST['nombre'];
    }
    else{
        $nombre = $usuario['Nombre'];
    }
    if(!empty($_POST['email'])){
        $email = $_POST['email'];
    }
    else{
        $email = $usuario['Email'];
    }
    if(!empty($_POST['telefono'])){
        $telefono = $_POST['telefono'];
    }
    else{
        $telefono = $usuario['Telefono'];
    }
    if(!empty($_POST['contrasena'])){
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    }
    else{
        $contrasena = password_hash($usuario['contraseña'], PASSWORD_DEFAULT);
    }
    if(!empty($_POST['foto'])){
        $foto = $_POST['foto'];
    }
    else{
        $foto = $usuario['Foto'];
    }

    
    if($rol == 'peluquero'){
        $sql = "UPDATE $rol SET Nombre = '$nombre', Email = '$email', Telefono = '$telefono', contraseña = '$contrasena', Foto = '$foto' WHERE ID = $id_usuario"; 
        if($conn->query($sql) === TRUE){
            $_SESSION['alerta'] = 'El usuario se ha modificado.';
            header('Location: admin_usuario.php');
        }
        else{
            $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
            header('Location: admin_usuario.php');
        }
    }
    elseif($rol == 'cliente'){
        $sql = "UPDATE $rol SET Nombre = '$nombre', Email = '$email', Telefono = '$telefono', contraseña = '$contrasena' WHERE ID = $id_usuario"; 
        if($conn->query($sql) === TRUE){
            $_SESSION['alerta'] = 'El usuario se ha modificado.';
            header('Location: admin_usuario.php');
        }
        else{
            $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
            header('Location: admin_usuario.php');
        }
    }
    elseif($rol == 'admin'){
        $sql = "UPDATE $rol SET Nombre = '$nombre', Email = '$email', contraseña = '$contrasena' WHERE ID = $id_usuario"; 
        if($conn->query($sql) === TRUE){
            $_SESSION['alerta'] = 'El usuario se ha modificado.';
            header('Location: admin_usuario.php');
        }
        else{
            $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
            header('Location: admin_usuario.php');
        }
    }
}
elseif($accion == 'crear'){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $foto = $_POST['foto'];
    if($rol == 'peluquero'){
        $sql = "INSERT INTO $rol (ID,Nombre, Email, Telefono, contraseña, Foto) VALUES ('$id_usuario','$nombre', '$email', '$telefono', '$contrasena', '$foto')";
        if($conn->query($sql) === TRUE){
            $_SESSION['alerta'] = 'El usuario se ha creado.';
            mandar_correo($email,$nombre,'Bienvenido a ShearStyle Salon','Hola '.$nombre.'! Bienvenido a ShearStyle Salon, esperamos que disfrutes de nuestros servicios.');
            header('Location: admin_usuario.php');
        }
        else{
            $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
            header('Location: admin_usuario.php');
        }
    }
    elseif($rol == 'cliente'){
        $sql = "INSERT INTO $rol (ID,Nombre, Email, Telefono, contraseña) VALUES ('$id_usuario','$nombre', '$email', '$telefono', '$contrasena')";
        if($conn->query($sql) === TRUE){
            $_SESSION['alerta'] = 'El usuario se ha creado.';
            mandar_correo($email,$nombre,'Bienvenido a ShearStyle Salon','Hola '.$nombre.'! Bienvenido a ShearStyle Salon, esperamos que disfrutes de nuestros servicios.');
            header('Location: admin_usuario.php');
        }
        else{
            $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
            header('Location: admin_usuario.php');
        }
    }
    elseif($rol == 'admin'){
        $sql = "INSERT INTO $rol (ID,Nombre, Email, contraseña) VALUES ('$id_usuario','$nombre', '$email', '$contrasena')";
        if($conn->query($sql) === TRUE){
            $_SESSION['alerta'] = 'El usuario se ha creado.';
            mandar_correo($email,$nombre,'Bienvenido a ShearStyle Salon','Hola '.$nombre.'! Bienvenido a ShearStyle Salon, esperamos que disfrutes de nuestros servicios.');
            header('Location: admin_usuario.php');
        }
        else{
            $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
            header('Location: admin_usuario.php');
        }
    }
    
}

?>