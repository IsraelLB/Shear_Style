<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';
session_start(); // Inicia la sesión

if (isset($_SESSION["ID"])) {
    $id = $_SESSION["ID"]; // Recupera el ID de la sesión
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

$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$id_servicio = $_POST['servicio'];
$servicio = mysqli_query($conn,"SELECT Duracion FROM servicio WHERE ID = $id_servicio");

if (mysqli_num_rows($servicio) > 0) {
    $row = mysqli_fetch_assoc($servicio);
    $duracion = $row["Duracion"];
}

$hora_cita_fin = date('H:i:s', strtotime($hora . ' + ' . $duracion . ' minutes'));

// Consulta para verificar si hay alguna cita que se superpone con la nueva cita
$sql = "SELECT ID,email,nombre
    FROM peluquero
    WHERE id NOT IN (
        SELECT id_pelu
        FROM cita
        WHERE fecha = '$fecha'
        AND (
            (hora >= '$hora' AND hora < '$hora_cita_fin')
            OR (hora < '$hora' AND hora_fin > '$hora')
        )
    )
    Limit 1;";   

$peluquero = mysqli_query($conn,$sql);

// Si no hay superposición de citas
if (mysqli_num_rows($peluquero) > 0) {
    $row = mysqli_fetch_assoc($peluquero);
    $id_pelu=$row["ID"];
    $email_pelu= $row["email"];
    $nombre_pelu= $row["nombre"];

    $insertar = "INSERT INTO cita (id_pelu, id_serv, id_cli, Fecha, hora, hora_fin) VALUES ('$id_pelu' , '$id_servicio',$id, '$fecha', '$hora','$hora_cita_fin')";

    // Ejecutar la sentencia SQL
    if ($conn->query($insertar) === TRUE) {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shearstylesalonpw@outlook.com'; // Tu dirección de correo Gmail
        $mail->Password = 'contrasena123'; // Tu contraseña de correo Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('shearstylesalonpw@outlook.com', 'ShearStyle Salon');
        $mail->addAddress($email_pelu, $nombre_pelu);
        $fecha_formato = date('d-m-Y', strtotime($fecha)); // Formatear la fecha a un formato más legible
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Nueva cita en ShearStyle Salon';
        $mail->Body    = 'Un cliente ha reservado una cita con usted el '.$fecha_formato.' a las '.$hora.'.';
        $mail->send();

        $cliente_ = mysqli_query($conn,"SELECT email,nombre FROM cliente WHERE ID = $id");
        $cliente = mysqli_fetch_assoc($cliente_);
        $mail_cli = new PHPMailer(true);
        $mail_cli->isSMTP();
        $mail_cli->Host = 'smtp.office365.com';
        $mail_cli->SMTPAuth = true;
        $mail_cli->Username = 'shearstylesalonpw@outlook.com'; // Tu dirección de correo Gmail_cli
        $mail_cli->Password = 'contrasena123'; // Tu contraseña de correo Gmail
        $mail_cli->SMTPSecure = 'tls';
        $mail_cli->Port = 587;

        $mail_cli->setFrom('shearstylesalonpw@outlook.com', 'ShearStyle Salon');
        $mail_cli->addAddress($cliente['email'], $cliente['nombre']);
        $fecha_formato = date('d-m-Y', strtotime($fecha)); // Formatear la fecha a un formato más legible
        // Contenido del correo
        $mail_cli->isHTML(true);
        $mail_cli->Subject = 'Nueva cita en ShearStyle Salon';
        $mail_cli->Body    = 'Usted ha reservado una cita el '.$fecha_formato.' a las '.$hora.'.<br> Muchas gracias por su reserva.';
        $mail_cli->send();
        $_SESSION['alerta'] = 'La cita se ha reservado.';
        header('Location: contact.php');
    } else {
        $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
        header('Location: contact.php');
    }
} else {
    // Hay superposición de citas, mostrar una alerta
    $_SESSION['alerta'] = 'Todos los peluqueros estan ocupados para la hora y el dia seleccionados.';
    header('Location: contact.php');
}

?>
