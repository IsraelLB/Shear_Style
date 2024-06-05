<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Crear una nueva instancia de PHPMailer
$mail = new PHPMailer(true);

$servername = "localhost";
$username = "pw";
$password = "pw";

// Crear conexión
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$bd = mysqli_select_db($conn, 'peluqueria');
if(!$bd) {
    echo"Error al seleccionar la base de datos.";
}

$id_cita = $_GET['id_cita'];
$id_pelu = $_GET['id_pelu'];

$cita = mysqli_query($conn, "SELECT * FROM cita WHERE ID = $id_cita");
$datos_cita = mysqli_fetch_assoc($cita);
$fecha_cita = date('d-m-Y', strtotime($datos_cita['Fecha'])); //obtenemos los datos de fecha y hora
$hora_cita = $datos_cita['hora'];

$eliminar = "DELETE FROM cita WHERE ID = $id_cita";
$result = mysqli_query($conn, $eliminar);
if ($result) {
    try {
        $peluquero = mysqli_query($conn,"SELECT email,nombre FROM peluquero WHERE ID = $id_pelu");
        $datos_peluquero = mysqli_fetch_assoc($peluquero);
        // Configurar el servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shearstylesalonpw@outlook.com'; // Tu dirección de correo Gmail
        $mail->Password = 'contrasena123'; // Tu contraseña de correo Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
    
        // Configurar remitente y destinatario
        $mail->setFrom('shearstylesalonpw@outlook.com', 'ShearStyle Salon');
        $mail->addAddress($datos_peluquero['email'], $datos_peluquero['nombre']);
    
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Cancelacion de cita en ShearStyle Salon';
        $mail->Body    = 'El cliente ha cancelado su cita del '.$fecha_cita.' a las '.$hora_cita.'.';
    
        // Enviar el correo
        $mail->send();
        echo 'El correo se ha enviado correctamente.';
    } catch (Exception $e) {
        echo 'Hubo un error al enviar el correo: ', $mail->ErrorInfo;
    }
    $_SESSION['alerta'] = 'La cita se ha cancelado.';
    header('Location: index_cliente.php');
} else {
    $_SESSION['alerta'] = 'Error al cancelar cita.';
    header('Location: index_cliente.php');
}
?>