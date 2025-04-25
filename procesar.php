<?php
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "formulario_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Paso actual
$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;
$action = $_POST['action'] ?? '';

// Guardar los datos del paso actual en la sesión
foreach ($_POST as $key => $value) {
    if (!in_array($key, ['step', 'action', 'confirmarEmail', 'confirmarPassword'])) {
        $_SESSION['form_data'][$key] = $value;
    }
}

// Si se hace clic en "Siguiente", redirige al siguiente paso
if ($action === 'next') {
    $nextStep = $step + 1;
    header("Location: index.php?step=$nextStep");
    exit();
}

// Si se hace clic en "Registrar Propietario", insertar en la BD
if ($action === 'register') {
    $datos = $_SESSION['form_data'];

    // Validar campos requeridos
    $requeridos = ['tipoDocumento', 'numeroId', 'genero', 'fechaNacimiento', 'nombre', 'apellido', 'telefono', 'ciudad', 'direccion', 'email', 'password'];
    foreach ($requeridos as $campo) {
        if (empty($datos[$campo])) {
            header("Location: index.php?step=4&mensaje=Todos los campos son obligatorios.&tipo=error");
            exit();
        }
    }

    // Encriptar contraseña
    $passwordHash = password_hash($datos['password'], PASSWORD_BCRYPT);

    // Preparar e insertar
    $stmt = $conexion->prepare("INSERT INTO propietarios (tipo_documento, numero_id, genero, fecha_nacimiento, nombre, apellido, telefono, ciudad, direccion, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssssss",
        $datos['tipoDocumento'],
        $datos['numeroId'],
        $datos['genero'],
        $datos['fechaNacimiento'],
        $datos['nombre'],
        $datos['apellido'],
        $datos['telefono'],
        $datos['ciudad'],
        $datos['direccion'],
        $datos['email'],
        $passwordHash
    );

    if ($stmt->execute()) {
        session_destroy(); // Limpiar datos después del registro
        header("Location: index.php?step=1&mensaje=Registro exitoso.&tipo=exito");
        exit();
    } else {
        header("Location: index.php?step=4&mensaje=Error al registrar: " . $conexion->error . "&tipo=error");
        exit();
    }
}
?>
