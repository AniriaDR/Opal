<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Esta línea ya incluye todas las dependencias

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "opal");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el ID de la reserva está disponible
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos de la reserva antes de eliminarla
    $query = "SELECT * FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reserva = $result->fetch_assoc();

    if ($reserva) {
        // Preparar la consulta de eliminación
        $sql = "DELETE FROM reservas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            // Enviar correo de notificación
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'aniriallorando@gmail.com'; // Cambia por tu correo
                $mail->Password = 'punj qbvd zegn esib'; // Cambia por tu contraseña de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Configuración del correo
                $mail->setFrom('aniriallorando@gmail.com', 'Opal Notificaciones'); // Cambia por tu correo y nombre
                $mail->addAddress('reservasopal@gmail.com'); // Cambia por el correo de destino

                $mail->isHTML(true);
                $mail->Subject = 'Reserva Eliminada';
                $mail->Body = "
                    <h1>Notificación de Reserva Eliminada</h1>
                    <p>Se ha eliminado la siguiente reserva:</p>
                    <ul>
                        <li><strong>Nombre:</strong> {$reserva['nombre']} {$reserva['apellido']}</li>
                        <li><strong>Correo:</strong> {$reserva['email']}</li>
                        <li><strong>Teléfono:</strong> {$reserva['telefono']}</li>
                        <li><strong>Fecha:</strong> {$reserva['fecha']}</li>
                        <li><strong>Hora:</strong> {$reserva['hora']}</li>
                        <li><strong>Personas:</strong> {$reserva['personas']}</li>
                        <li><strong>Sala:</strong> {$reserva['sala']}</li>
                    </ul>
                ";

                $mail->send();
            } catch (Exception $e) {
                echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
            }

            // Redirigir a misresv.php con un mensaje de éxito
            header("Location: misresv.php?mensaje=Reserva eliminada correctamente");
            exit();
        } else {
            echo "Error al eliminar la reserva: " . $conn->error;
        }
    } else {
        // Si no se encuentra la reserva, redirigir con un mensaje de error
        header("Location: misresv.php?mensaje=Reserva no encontrada");
        exit();
    }
} else {
    // Si no se recibe el ID, redirigir con un mensaje de error
    header("Location: misresv.php?mensaje=Error al eliminar la reserva");
    exit();
}
?>
