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

// Obtener las reservas
$sql = "SELECT id, nombre, apellido, email, telefono, fecha, hora, personas, sala FROM reservas";
$result = $conn->query($sql);

if (isset($_GET['mensaje'])) {
    echo "<p class='mensaje-exito'>" . htmlspecialchars($_GET['mensaje']) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservaciones|Opal</title>
    <link rel="stylesheet" href="css/misresv.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
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
            <a href="misresv.php">Mis Reservaciones</a>
            <a href="contactos.html">Contacto</a>
        </nav>
    </header>

    <main>
        <h2>Mis Reservas</h2>
    
        <div class="container">
            <?php
            // Verificar si hay reservas
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Personas</th>
                            <th>Sala</th> <!-- Nueva columna Sala -->
                            <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>";

                // Mostrar las reservas
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['nombre']) . "</td>
                            <td>" . htmlspecialchars($row['apellido']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['telefono']) . "</td>
                            <td>" . htmlspecialchars($row['fecha']) . "</td>
                            <td>" . htmlspecialchars($row['hora']) . "</td>
                            <td>" . htmlspecialchars($row['personas']) . "</td>
                            <td>" . htmlspecialchars($row['sala']) . "</td>"; 

                    // Mostrar los botones de acción (editar y eliminar)
                    echo "<td>
                            <a href='editar_reserva.php?id=" . $row['id'] . "' class='btn editar'>Editar</a>
                            <a href='eliminar_reserva.php?id=" . $row['id'] . "' class='btn eliminar' onclick='return confirm(\"¿Estás seguro de eliminar esta reserva?\");'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
            } else {
                // Si no hay reservas
                echo "<p class='mensaje-exito'>No hay reservas disponibles.</p>";
            }
            ?>
        </div>
    </main>

    <footer>
        <div class="social-links">
            <a href="https://www.facebook.com"><img src="img/face.webp" alt="Facebook"></a>
            <a href="https://www.instagram.com"><img src="img/insta.png" alt="Instagram"></a>
        </div>
        <p>© 2024 Opal. All rights reserved</p>
    </footer>

    <style>
        footer {
        background-color: #333;
        color: white;
        padding: 1.5rem 2rem;
        text-align: center;
        }

        footer .social-links a {
            margin: 0 0.5rem;
            display: inline-block;
        }

        footer .social-links img {
            width: 30px;
            height: auto;
        }

        footer p {
            margin-top: 1rem;
            font-size: 0.875rem;
        }
    </style>

</body>
</html>

<script>
   document.addEventListener("DOMContentLoaded", function() {
    // Buscar el mensaje de éxito si existe
    var mensaje = document.querySelector('.mensaje-exito');
    
    // Si el mensaje existe, configuramos un temporizador para desaparecerlo
    if (mensaje) {
        setTimeout(function() {
            // Añadimos la clase 'ocultar' para aplicar la animación de desaparición
            mensaje.classList.add('ocultar');
            
            // Después de que termine la animación (1 segundo), ocultamos el mensaje completamente
            setTimeout(function() {
                mensaje.style.display = 'none'; // Ocultamos el mensaje
            }, 1000); // 1 segundo (que es la duración de la animación)
        }, 3000); // 3000 milisegundos = 3 segundos
    }
});
</script>


<?php
// Cerrar conexión
$conn->close();
?>
