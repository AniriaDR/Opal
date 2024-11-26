<?php
// Importar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir los archivos de PHPMailer
require 'vendor/autoload.php';

// Conexión a la base de datos
$servername = "localhost"; // Cambia según tu configuración
$username = "root";        // Tu usuario
$password = "";            // Tu contraseña
$dbname = "opal";          // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$personas = $_POST['personas'];
$sala = $_POST['sala']; // Sala seleccionada

// Usar una consulta preparada para evitar inyecciones SQL
$stmt = $conn->prepare("INSERT INTO reservas (nombre, apellido, email, telefono, fecha, hora, personas, sala) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $nombre, $apellido, $email, $telefono, $fecha, $hora, $personas, $sala);

// Ejecutar la consulta para insertar la reserva
if ($stmt->execute()) {
    // Configuración del servidor SMTP
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                   // Usar SMTP
        $mail->Host = 'smtp.gmail.com';                    // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;                            // Habilitar autenticación SMTP
        $mail->Username = 'aniriallorando@gmail.com';           // Cambia por tu correo
        $mail->Password = 'punj qbvd zegn esib';                 // Contraseña o clave de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Encriptación TLS
        $mail->Port = 587;                                 // Puerto SMTP de TLS

        // Remitente y destinatario
        $mail->setFrom('aniriallorando@gmail.com', 'Opal Reservas'); // Tu correo y nombre de remitente
        $mail->addAddress('reservasopal@gmail.com');         // Dirección del destinatario fijo

        // Contenido del correo
        $mail->isHTML(true); // Configurar el correo como HTML
        $mail->Subject = 'Nueva reserva recibida'; // Asunto del correo

        // Cuerpo del mensaje
        $mail->Body = "
            <h1>Nueva reserva recibida</h1>
            <p><strong>Nombre:</strong> $nombre</p>
            <p><strong>Apellido:</strong> $apellido</p>
            <p><strong>Correo electrónico:</strong> $email</p>
            <p><strong>Teléfono:</strong> $telefono</p>
            <p><strong>Fecha:</strong> $fecha</p>
            <p><strong>Hora:</strong> $hora</p>
            <p><strong>Número de personas:</strong> $personas</p>
            <p><strong>Sala:</strong> $sala</p>
        ";

        // Enviar el correo
        $mail->send();

        // Si el correo se envía correctamente, redirigir a otra página
        header("Location: resv4.html");
        exit();
    } catch (Exception $e) {
        echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
    }

} else {
    // Si hay un error al guardar la reserva
    echo "Error al guardar la reserva: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
