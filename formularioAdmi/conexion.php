<?php
$host = "localhost";
$usuario = "root";
$contrasena = ""; // Por defecto, sin contraseña en XAMPP
$basedatos = "formulario_db"; // Asegúrate de crearla primero en MySQL

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $basedatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    echo "Conexión exitosa a la base de datos.";
}

// Intentar realizar una consulta simple (como obtener tablas)
$query = "SHOW TABLES"; // Muestra todas las tablas en la base de datos
$result = $conn->query($query);

if ($result) {
    echo "Consulta exitosa, las tablas en la base de datos son:<br>";
    while ($row = $result->fetch_row()) {
        echo $row[0] . "<br>"; // Imprime el nombre de cada tabla
    }
} else {
    echo "Error al realizar la consulta: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>