<?php
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

// Verificar si se ha pasado un ID para editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la información de la reserva
    $sql = "SELECT id, nombre, apellido, email, telefono, fecha, hora, personas, sala FROM reservas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Si no se encuentra la reserva
    if (!$row) {
        echo "Reserva no encontrada";
        exit;
    }
} else {
    echo "ID no proporcionado";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva - Opal</title>
    <link rel="stylesheet" href="css/misresv.css">
</head>
<body>

    <header class="header-top">
        <div class="logo">
            <a href="index.html">
                <img src="img/logo opa.png" alt="Logo de Opal">
            </a>
        </div>
        <nav class="main-nav">
            <a href="index.html">Inicio</a>
            <a href="menu.html">Menú</a>
            <a href="reserva.html">Reservar</a>
            <a href="mis_reservas.php">Mis Reservaciones</a>
            <a href="contactos.html">Contacto</a>
        </nav>
    </header>

    <main>
        <h2>Editar Reserva</h2>

        <div class="container">
            <form action="actualizar_reserva.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required 
                maxlength="20" pattern="^[A-Za-z]+$" title="Solo se permiten letras (sin números ni caracteres especiales)" value="<?php echo htmlspecialchars($row['nombre']); ?>">

                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" id="apellido" 
                maxlength="40" pattern="^[A-Za-z]+$" title="Solo se permiten letras (sin números ni caracteres especiales)" value="<?php echo htmlspecialchars($row['apellido']); ?>">

                <label for="email">Correo:</label>
                <input type="email" name="email" id="email" 
                pattern="^[a-zA-Z0-9._%+-]+@(icloud\.com|gmail\.com|hotmail\.com|yahoo\.com)$" 
                title="Solo se permiten correos electrónicos con los dominios: @icloud.com, @gmail.com, @hotmail.com, o @yahoo.com" value="<?php echo htmlspecialchars($row['email']); ?>">

                <label for="telefono">Teléfono:</label>
                <input type="tel" name="telefono" id="telefono" 
                maxlength="10" pattern="^[0-9]{10}$" title="Solo se permiten 10 dígitos numéricos" value="<?php echo htmlspecialchars($row['telefono']); ?>">

                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" id="fecha" min="" title="Seleccione una fecha a partir de hoy" value="<?php echo htmlspecialchars($row['fecha']); ?>">

                <label for="hora">Hora:</label>
                <input type="time" name="hora" id="hora" 
                min="11:00" max="18:00" title="La hora de reserva debe ser entre las 11:00 AM y las 6:00 PM" value="<?php echo htmlspecialchars($row['hora']); ?>">

                <label for="personas">Personas:</label>
                <input type="number" name="personas" id="personas" 
                min="2" max="10" title="El número de personas debe ser entre 2 y 10" value="<?php echo htmlspecialchars($row['personas']); ?>">

                <label for="sala">Sala:</label>
                <select name="sala" id="sala">
                    <option value="Normal" <?php echo ($row['sala'] == 'Normal') ? 'selected' : ''; ?>>Normal</option>
                    <option value="VIP" <?php echo ($row['sala'] == 'VIP') ? 'selected' : ''; ?>>VIP</option>
                </select>

                <button type="submit" class="btn">Actualizar Reserva</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="social-icons">
            <a href="#"><img src="facebook.png" alt="Facebook"></a>
            <a href="#"><img src="twitter.png" alt="Twitter"></a>
            <a href="#"><img src="instagram.png" alt="Instagram"></a>
            <a href="#"><img src="youtube.png" alt="YouTube"></a>
        </div>
        <p>© 2024 Opal. All rights reserved</p>
    </footer>

</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0]; // Obtiene la fecha actual en formato YYYY-MM-DD
        document.getElementById('fecha').setAttribute('min', today); // Establece la fecha mínima
    });
</script>

<?php
// Cerrar conexión
$conn->close();
?>
