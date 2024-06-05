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
$id_cita = $_POST['id_cita'];

if ($accion == 'eliminar') {
    
    $cita_antigua = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM cita WHERE ID = $id_cita"));

    $sql = "DELETE FROM cita WHERE ID = $id_cita";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['alerta'] = 'La cita se ha cancelado.';

        $cliente_ant = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT ID,email,nombre FROM cliente WHERE ID = '".$cita_antigua["ID_CLI"]."'"));
        $peluquero_ant = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT ID,email,nombre FROM peluquero WHERE ID = '".$cita_antigua["ID_PELU"]."'"));
        $fecha_antigua_formato = date('d-m-Y', strtotime($cita_antigua["fecha"])); // Formatear la fecha a un formato más legible

        mandar_correo($peluquero_ant["email"],$peluquero_ant["nombre"],'Eliminacion de cita en ShearStyle Salon',
            'Se ha cancelado una reserva con usted el '.$fecha_antigua_formato.' a las '.$cita_antigua["hora"].'.');
                
        mandar_correo($cliente_ant["email"],$cliente_ant["nombre"],'Eliminacion de cita en ShearStyle Salon',
            'Se ha cancelado su reserva el '.$fecha_antigua_formato.' a las '.$cita_antigua["hora"].'.');
            
        header('Location: admin_cita.php');
    } else {
        $_SESSION['alerta'] = 'Hubo un error inesperado intentelo de nuevo.';
        header('Location: admin_cita.php');
    }
}
elseif($accion == 'modificar'){
    if (!empty($id_cita)){
        $cita_antigua = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM cita WHERE ID = $id_cita"));

        if(!empty($_POST['id_servicio'])){
            $id_servicio = $_POST['id_servicio'];
        }
        else{
            $id_servicio = $cita_antigua['ID_SERV'];
        }
        if(!empty($_POST['fecha'])){
            $fecha = $_POST['fecha'];
        }
        else{
            $fecha = $cita_antigua['Fecha'];
        }
        if(!empty($_POST['hora'])){
            $hora = $_POST['hora'];
        }
        else{
            $hora = $cita_antigua['hora'];
        }
        if(!empty($_POST['id_cliente'])){
            $id_cliente = $_POST['id_cliente'];
        }
        else{
            $id_cliente = $cita_antigua['ID_CLI'];
        }
        $servicio = mysqli_query($conn,"SELECT Duracion FROM servicio WHERE ID = '$id_servicio'");

        if (mysqli_num_rows($servicio) > 0) {
            $row = mysqli_fetch_assoc($servicio);
            $duracion = $row["Duracion"];
        }

        $hora_cita_fin = date('H:i:s', strtotime($hora . ' + ' . $duracion . ' minutes'));
        //Eliminar cita antigua
        $eliminar = "DELETE FROM cita WHERE ID = $id_cita";
        if($conn->query($eliminar) === TRUE){
            
            // Consulta para verificar si hay alguna cita que se superpone con la nueva cita con el nuevo peluquero
            if(!empty($_POST['id_peluquero'])){
                $id_peluquero = $_POST['id_peluquero'];

                $sql =
                "SELECT ID,email,nombre
                FROM peluquero
                WHERE id NOT IN (
                    SELECT id_pelu
                    FROM cita
                    WHERE fecha = '$fecha'
                    AND (
                        (hora >= '$hora' AND hora < '$hora_cita_fin')
                        OR (hora < '$hora' AND hora_fin > '$hora')
                    )
                ) AND id = '$id_peluquero'";

            }else{
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
            }

            $query_peluquero = mysqli_query($conn,$sql);

            // Si no hay superposición de citas
            if (mysqli_num_rows($query_peluquero) > 0) {

                $peluquero = mysqli_fetch_assoc($query_peluquero);

                $peluquero_ant = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT ID,email,nombre FROM peluquero WHERE ID = '".$cita_antigua["ID_PELU"]."'"));


                $cliente = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT ID,email,nombre FROM cliente WHERE ID = '$id_cliente'"));

                $cliente_ant = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT ID,email,nombre FROM cliente WHERE ID = '".$cita_antigua["ID_CLI"]."'"));
                $insertar = "INSERT INTO cita (id, id_pelu, id_serv, id_cli, Fecha, hora, hora_fin) 
                VALUES ('$id_cita', '".$peluquero["ID"]."' , '$id_servicio', '$id_cliente', '$fecha', '$hora', '$hora_cita_fin')";

                // Ejecutar la sentencia SQL
                if ($conn->query($insertar) === TRUE) {
                    $fecha_antigua_formato = date('d-m-Y', strtotime($cita_antigua["Fecha"])); // Formatear la fecha a un formato más legible
                    $fecha_formato = date('d-m-Y', strtotime($fecha)); // Formatear la fecha a un formato más legible
                    

                   //Mails reserva cancelada
                    mandar_correo($peluquero_ant["email"],$peluquero_ant["nombre"],'Modificación de cita en ShearStyle Salon',
                    'Se ha cancelado una reserva con usted el '.$fecha_antigua_formato.' a las '.$cita_antigua["hora"].'.');
                    
                    mandar_correo($cliente_ant["email"],$cliente_ant["nombre"],'Modificación de cita en ShearStyle Salon',
                    'Se ha cancelado su reserva el '.$fecha_antigua_formato.' a las '.$cita_antigua["hora"].'.');

                    //Mails nueva reserva 
                    mandar_correo($peluquero["email"],$peluquero["nombre"],'Modificación de cita en ShearStyle Salon',
                    'Se ha reservado una cita con usted el '.$fecha_formato.' a las '.$hora.'.');

                
                    mandar_correo($cliente['email'],$cliente['nombre'],'Modificación de cita en ShearStyle Salon',
                    'Se le ha reservado una cita el '.$fecha_formato.' a las '.$hora.'.<br> Muchas gracias por su reserva.');
                    header('Location: admin_cita.php');
                }
                else{
                    $_SESSION['alerta'] = 'No se ha podido modificar la cita, intentelo de nuevo.';
                    header('Location: admin_cita.php');
                
                }
            }
            else{
                //volvemos a insertar la cita antigua
                $insertar = "INSERT INTO cita (id, id_pelu, id_serv, id_cli, Fecha, hora, hora_fin) 
            VALUES ('" . $cita_antigua['ID'] . "', '" . $cita_antigua['ID_PELU'] . "', '" . $cita_antigua['ID_SERV'] . "',
             '" . $cita_antigua['ID_CLI'] . "', '" . $cita_antigua['Fecha'] . "', '" . $cita_antigua['hora'] . "', '" . $cita_antigua['hora_fin'] . "')";

                if ($conn->query($insertar) === TRUE) {
                    $_SESSION['alerta'] = 'No se ha podido modificar la cita, se ha vuelto a la cita anterior.';
                    header('Location: admin_cita.php');
                } else {
                    $_SESSION['alerta'] = 'Hubo un error inesperado, se ha eliminado la cita anterior.';
                    header('Location: admin_cita.php');
                }
            }
        }

    }

}
elseif($accion == 'crear'){
    $id_cita = $_POST['id_cita'];
    $id_servicio = $_POST['id_servicio'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $id_cliente = $_POST['id_cliente'];

    if(!empty($id_cita)){
        $query_citas = mysqli_query($conn,"SELECT ID FROM cita WHERE ID = '$id_cita'");
        if (mysqli_num_rows($query_citas) > 0) {
            $id_cita = '';
        }
    }

    if(!empty($id_servicio) and !empty($fecha) and !empty($hora) and !empty($id_cliente)){

        $servicio = mysqli_query($conn,"SELECT Duracion FROM servicio WHERE ID = '$id_servicio'");

        if (mysqli_num_rows($servicio) > 0) {
            $row = mysqli_fetch_assoc($servicio);
            $duracion = $row["Duracion"];
        }

        $hora_cita_fin = date('H:i:s', strtotime($hora . ' + ' . $duracion . ' minutes'));

        if(!empty($_POST['id_peluquero'])){
            $id_peluquero = $_POST['id_peluquero'];

            $sql =
            "SELECT ID,email,nombre
            FROM peluquero
            WHERE id NOT IN (
                SELECT id_pelu
                FROM cita
                WHERE fecha = '$fecha'
                AND (
                    (hora >= '$hora' AND hora < '$hora_cita_fin')
                    OR (hora < '$hora' AND hora_fin > '$hora')
                )
                AND id_pelu = '$id_peluquero'
            )";

        }else{
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
        }

        $query_peluquero = mysqli_query($conn,$sql);

        // Si no hay superposición de citas
        if (mysqli_num_rows($query_peluquero) > 0) {

            $peluquero = mysqli_fetch_assoc($query_peluquero);

            if(!empty($_POST['id_peluquero'])){
                $id_peluquero = $_POST['id_peluquero'];
            }
            else{
                $id_peluquero = $peluquero["ID"];
            }


            $insertar = "INSERT INTO cita (id, id_pelu, id_serv, id_cli, Fecha, hora, hora_fin) 
            VALUES ('$id_cita', '$id_peluquero' , '$id_servicio', '$id_cliente', '$fecha', '$hora', '$hora_cita_fin')";

            var_dump($insertar);

            // Ejecutar la sentencia SQL
            if ($conn->query($insertar) === TRUE) {
                $cliente = mysqli_fetch_assoc(mysqli_query($conn,
                "SELECT ID,email,nombre FROM cliente WHERE ID = '$id_cliente'"));

                $fecha_formato = date('d-m-Y', strtotime($fecha)); // Formatear la fecha a un formato más legible

                mandar_correo($peluquero["email"],$peluquero["nombre"],'Nueva cita en ShearStyle Salon',
                'Se ha reservado una cita con usted el '.$fecha_formato.' a las '.$hora.'.');

                mandar_correo($cliente['email'],$cliente['nombre'],'Nueva cita en ShearStyle Salon',
                'Se le ha reservado una cita el '.$fecha_formato.' a las '.$hora.'.<br> Muchas gracias por su reserva.');
                
                header('Location: admin_cita.php');
            }
            else{
                $_SESSION['alerta'] = 'No se ha podido crear la cita, intentelo de nuevo.';
                header('Location: admin_cita.php');
            }
        }
        else{
            $_SESSION['alerta'] = 'No se ha podido crear la cita debido a la disponiblidad de los peluqueros.';
            header('Location: admin_cita.php');
        }

        
    }
    else{
        $_SESSION['alerta'] = 'No se ha podido crear la cita, deben cumplimentarse todos datos (id de cita no obligatorio).';
        header('Location: admin_cita.php');
    }

}

?>
