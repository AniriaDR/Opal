<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Asegúrate de que la ruta a 'autoload.php' es correcta

$servername = "localhost"; // Cambia según tu configuración
$username = "root";        // Tu usuario
$password = "";            // Tu contraseña
$dbname = "opal";          // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $personas = $_POST['personas'];
    $sala = $_POST['sala'];

    // Consulta SQL para actualizar la reserva
    $sql = "UPDATE reservas 
            SET nombre = ?, apellido = ?, email = ?, telefono = ?, fecha = ?, hora = ?, personas = ?, sala = ? 
            WHERE id = ?";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $nombre, $apellido, $email, $telefono, $fecha, $hora, $personas, $sala, $id);

    if ($stmt->execute()) {
        // Enviar correo de notificación
        $mail = new PHPMailer(true); // Instancia PHPMailer

        try {
            // Configuración del servidor SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'aniriallorando@gmail.com'; // Tu correo de Gmail
            $mail->Password = 'punj qbvd zegn esib'; // Contraseña de aplicación de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitente y destinatarios
            $mail->setFrom('aniriallorando@gmail.com', 'Notificaciones de Actualización de rseserva');
            $mail->addAddress('reservasopal@gmail.com'); // Destinatario de la notificación

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Reserva Actualizada';
            $mail->Body    = "
                Se ha actualizado la reserva con los siguientes datos:<br>
                <strong>Nombre:</strong> $nombre $apellido<br>
                <strong>Correo electrónico:</strong> $email<br>
                <strong>Teléfono:</strong> $telefono<br>
                <strong>Fecha:</strong> $fecha<br>
                <strong>Hora:</strong> $hora<br>
                <strong>Número de personas:</strong> $personas<br>
                <strong>Sala:</strong> $sala<br>
            ";

            $mail->send(); // Enviar correo

            // Redirigir a la página de mis reservas con un mensaje de éxito
            header("Location: misresv.php?mensaje=Reserva actualizada con éxito");
        } catch (Exception $e) {
            echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
