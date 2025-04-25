<?php
session_start();

// Configuración de la conexión a la base de datos
$servidor = "localhost";
$usuario = "usuario_db";
$password = "password_db";
$basedatos = "nombre_db";

// Función para validar la fecha de nacimiento (mayor de 18 años)
function validarEdad($fechaNacimiento) {
    $fechaNac = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNac)->y;
    return $edad >= 18;
}

// Función para validar el formato de email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para validar la contraseña
function validarPassword($password) {
    // Al menos una letra minúscula, una mayúscula, un número y un carácter especial
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    return preg_match($pattern, $password);
}

// Función para guardar una imagen
function guardarImagen($imagenData, $numeroId) {
    if (empty($imagenData)) {
        return null;
    }
    
    // Verificar si es una imagen base64
    if (strpos($imagenData, 'data:image') === 0) {
        // Extraer el tipo de imagen y los datos
        $partes = explode(';base64,', $imagenData);
        $tipoImagen = explode(':', $partes[0])[1];
        $extension = explode('/', $tipoImagen)[1];
        $datos = base64_decode($partes[1]);
        
        // Crear un nombre de archivo único
        $nombreArchivo = 'uploads/admin_' . $numeroId . '_' . time() . '.' . $extension;
        
        // Crear el directorio si no existe
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        // Guardar la imagen
        file_put_contents($nombreArchivo, $datos);
        return $nombreArchivo;
    }
    
    return null;
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validar campos obligatorios
        $camposRequeridos = [
            'tipoRol', 'tipoDocumento', 'numeroId', 'genero', 'fechaNacimiento',
            'telefono', 'nombre', 'apellido', 'email', 'password', 'confirmarEmail', 'confirmarPassword'
        ];
        
        foreach ($camposRequeridos as $campo) {
            if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                throw new Exception("El campo $campo es obligatorio");
            }
        }
        
        // Validar que los emails coincidan
        if ($_POST['email'] !== $_POST['confirmarEmail']) {
            throw new Exception("Los emails no coinciden");
        }
        
        // Validar formato de email
        if (!validarEmail($_POST['email'])) {
            throw new Exception("El formato del email no es válido");
        }
        
        // Validar que las contraseñas coincidan
        if ($_POST['password'] !== $_POST['confirmarPassword']) {
            throw new Exception("Las contraseñas no coinciden");
        }
        
        // Validar formato de contraseña
        if (!validarPassword($_POST['password'])) {
            throw new Exception("La contraseña debe contener al menos una letra minúscula, una mayúscula, un número y un carácter especial");
        }
        
        // Validar edad
        if (!validarEdad($_POST['fechaNacimiento'])) {
            throw new Exception("Debes ser mayor de 18 años");
        }
        
        // Guardar la imagen si existe
        $rutaImagen = null;
        if (isset($_POST['imagen_data']) && !empty($_POST['imagen_data'])) {
            $rutaImagen = guardarImagen($_POST['imagen_data'], $_POST['numeroId']);
        } elseif (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Si se subió un archivo directamente
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = 'uploads/admin_' . $_POST['numeroId'] . '_' . time() . '.' . $extension;
            
            // Crear el directorio si no existe
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            // Mover el archivo subido
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $nombreArchivo)) {
                $rutaImagen = $nombreArchivo;
            }
        }
        
        // Crear conexión a la base de datos
        $conn = new mysqli($servidor, $usuario, $password, $basedatos);
        
        // Verificar conexión
        if ($conn->connect_error) {
            throw new Exception("Error de conexión: " . $conn->connect_error);
        }
        
        // Establecer charset a utf8
        $conn->set_charset("utf8");
        
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id FROM administradores WHERE email = ?");
        $stmt->bind_param("s", $_POST['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            throw new Exception("El email ya está registrado");
        }
        
        // Verificar si el número de documento ya existe
        $stmt = $conn->prepare("SELECT id FROM administradores WHERE tipo_documento = ? AND numero_id = ?");
        $stmt->bind_param("ss", $_POST['tipoDocumento'], $_POST['numeroId']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            throw new Exception("El número de documento ya está registrado");
        }
        
        // Preparar la consulta SQL
        $stmt = $conn->prepare("INSERT INTO administradores (tipo_rol, tipo_documento, numero_id, genero, fecha_nacimiento, telefono, nombre, apellido, email, password, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Hash de la contraseña
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $stmt->bind_param(
            "sssssssssss",
            $_POST['tipoRol'],
            $_POST['tipoDocumento'],
            $_POST['numeroId'],
            $_POST['genero'],
            $_POST['fechaNacimiento'],
            $_POST['telefono'],
            $_POST['nombre'],
            $_POST['apellido'],
            $_POST['email'],
            $passwordHash,
            $rutaImagen
        );
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Limpiar los datos de sesión
            unset($_SESSION['form_data']);
            unset($_SESSION['temp_image']);
            
            // Redirigir con mensaje de éxito
            header("Location: index.php?mensaje=Registro completado con éxito. Ya puedes iniciar sesión.&tipo=exito");
            exit();
        } else {
            throw new Exception("Error al registrar: " . $stmt->error);
        }
        
        // Cerrar la conexión
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        // Guardar los datos del formulario en la sesión para recuperarlos
        $_SESSION['form_data'] = $_POST;
        if (isset($_POST['imagen_data'])) {
            $_SESSION['temp_image'] = $_POST['imagen_data'];
        }
        
        header("Location: index.php?mensaje=" . urlencode($e->getMessage()) . "&tipo=error");
        exit();
    }
}
?>