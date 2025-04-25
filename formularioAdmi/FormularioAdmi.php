<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <link rel="stylesheet" href="FormularioAdmi.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Registro</h2>
            
            <?php
            // Mostrar mensajes de éxito o error
            if (isset($_GET['mensaje'])) {
                $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'error';
                echo '<div class="mensaje ' . $tipo . '">' . htmlspecialchars($_GET['mensaje']) . '</div>';
            }
            ?>
            
            <form action="procesar.php" method="POST" enctype="multipart/form-data">
                <div class="image-upload-container">
                    <div class="image-upload-wrapper">
                        <div class="image-upload-label" id="imageUploadLabel">
                            <?php if (isset($_SESSION['temp_image']) && !empty($_SESSION['temp_image'])): ?>
                                <img src="<?php echo $_SESSION['temp_image']; ?>" alt="Vista previa" class="image-preview" id="imagePreview">
                            <?php else: ?>
                                <div class="upload-placeholder">
                                    <span>Subir Imagen</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="image-options-menu" id="imageOptionsMenu" style="display: none;">
                            <button type="button" id="selectFromFiles" class="option-button">Buscar en archivos</button>
                            <button type="button" id="useCamera" class="option-button">Usar cámara</button>
                        </div>
                        
                        <input type="file" id="imagen" name="imagen" accept="image/*" class="image-upload-input">
                        <input type="hidden" name="imagen_data" id="imagen_data" value="<?php echo isset($_SESSION['temp_image']) ? $_SESSION['temp_image'] : ''; ?>">
                    </div>
                </div>
                
                <div id="cameraContainer" class="camera-container" style="display: none;">
                    <video id="video" autoplay playsinline muted class="camera-preview"></video>
                    <div class="camera-controls">
                        <button type="button" id="capturePhoto" class="camera-button capture">Capturar</button>
                        <button type="button" id="cancelCamera" class="camera-button cancel">Cancelar</button>
                    </div>
                    <canvas id="canvas" style="display: none;"></canvas>
                </div>

                <div class="form-group">
                    <label for="tipoRol">Tipo rol:</label>
                    <select id="tipoRol" name="tipoRol" required>
                        <option value="">Seleccione un rol</option>
                        <option value="A" <?php echo isset($_SESSION['form_data']['tipoRol']) && $_SESSION['form_data']['tipoRol'] === 'A' ? 'selected' : ''; ?>>Administrador</option>
                        <option value="V" <?php echo isset($_SESSION['form_data']['tipoRol']) && $_SESSION['form_data']['tipoRol'] === 'V' ? 'selected' : ''; ?>>Veterinario</option>
                        <option value="P" <?php echo isset($_SESSION['form_data']['tipoRol']) && $_SESSION['form_data']['tipoRol'] === 'P' ? 'selected' : ''; ?>>Propietario</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tipoDocumento">Tipo de documento:</label>
                    <select id="tipoDocumento" name="tipoDocumento" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="CC" <?php echo isset($_SESSION['form_data']['tipoDocumento']) && $_SESSION['form_data']['tipoDocumento'] === 'CC' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                        <option value="CE" <?php echo isset($_SESSION['form_data']['tipoDocumento']) && $_SESSION['form_data']['tipoDocumento'] === 'CE' ? 'selected' : ''; ?>>Cédula de Extranjería</option>
                        <option value="PP" <?php echo isset($_SESSION['form_data']['tipoDocumento']) && $_SESSION['form_data']['tipoDocumento'] === 'PP' ? 'selected' : ''; ?>>Pasaporte</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="numeroId">Número de documento:</label>
                    <input 
                        type="number" 
                        id="numeroId" 
                        name="numeroId" 
                        inputmode="numeric" 
                        style="appearance: textfield; -moz-appearance: textfield;" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                        required 
                        minlength="6" 
                        maxlength="10"
                        value="<?php echo isset($_SESSION['form_data']['numeroId']) ? htmlspecialchars($_SESSION['form_data']['numeroId']) : ''; ?>"
                    >
                </div>

                <div id="checklist">
                    <div class="radio-option">
                        <input 
                            value="Mujer" 
                            type="radio" 
                            id="01" 
                            name="genero" 
                            <?php echo isset($_SESSION['form_data']['genero']) && $_SESSION['form_data']['genero'] === 'Mujer' ? 'checked' : ''; ?> 
                            required
                        >
                        <label for="01">Mujer</label>
                    </div>
                    <div class="radio-option">
                        <input 
                            value="Hombre" 
                            type="radio" 
                            id="02" 
                            name="genero" 
                            <?php echo isset($_SESSION['form_data']['genero']) && $_SESSION['form_data']['genero'] === 'Hombre' ? 'checked' : ''; ?> 
                            required
                        >
                        <label for="02">Hombre</label>
                    </div>
                    <div class="radio-option">
                        <input 
                            value="No identificado" 
                            type="radio" 
                            id="03" 
                            name="genero" 
                            <?php echo isset($_SESSION['form_data']['genero']) && $_SESSION['form_data']['genero'] === 'No identificado' ? 'checked' : ''; ?> 
                            required
                        >
                        <label for="03">No identificado</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fechaNacimiento">Fecha de nacimiento:</label>
                    <input 
                        type="date" 
                        id="fechaNacimiento" 
                        name="fechaNacimiento" 
                        required
                        value="<?php echo isset($_SESSION['form_data']['fechaNacimiento']) ? htmlspecialchars($_SESSION['form_data']['fechaNacimiento']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input 
                        type="text" 
                        id="telefono" 
                        name="telefono" 
                        required 
                        pattern="[0-9]+" 
                        minlength="10" 
                        maxlength="10"
                        value="<?php echo isset($_SESSION['form_data']['telefono']) ? htmlspecialchars($_SESSION['form_data']['telefono']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        required 
                        minlength="3" 
                        maxlength="30" 
                        pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
                        value="<?php echo isset($_SESSION['form_data']['nombre']) ? htmlspecialchars($_SESSION['form_data']['nombre']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input 
                        type="text" 
                        id="apellido" 
                        name="apellido" 
                        required 
                        minlength="3" 
                        maxlength="30" 
                        pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
                        value="<?php echo isset($_SESSION['form_data']['apellido']) ? htmlspecialchars($_SESSION['form_data']['apellido']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="confirmarEmail">Confirmar Email:</label>
                    <input 
                        type="email" 
                        id="confirmarEmail" 
                        name="confirmarEmail" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <div class="password-container">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            minlength="8" 
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                        >
                        <button type="button" class="password-toggle" id="togglePassword">👁️‍🗨️</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmarPassword">Confirmar Contraseña:</label>
                    <div class="password-container">
                        <input 
                            type="password" 
                            id="confirmarPassword" 
                            name="confirmarPassword" 
                            required 
                            minlength="8"
                        >
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">👁️‍🗨️</button>
                    </div>
                </div>

                <button type="submit" class="submit-button">Registrar Administrador</button>
            </form>
        </div>
    </div>

    <script>
        // Funcionalidad para mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            togglePasswordVisibility('password', this);
        });
        
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            togglePasswordVisibility('confirmarPassword', this);
        });
        
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '👁️';
            } else {
                input.type = 'password';
                button.textContent = '👁️‍🗨️';
            }
        }
        
        // Funcionalidad para subir imagen
        const imageUploadLabel = document.getElementById('imageUploadLabel');
        const imageOptionsMenu = document.getElementById('imageOptionsMenu');
        const fileInput = document.getElementById('imagen');
        const selectFromFiles = document.getElementById('selectFromFiles');
        const useCamera = document.getElementById('useCamera');
        const cameraContainer = document.getElementById('cameraContainer');
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const capturePhoto = document.getElementById('capturePhoto');
        const cancelCamera = document.getElementById('cancelCamera');
        const imagePreview = document.getElementById('imagePreview');
        const imagenData = document.getElementById('imagen_data');
        
        let stream = null;
        
        // Mostrar/ocultar menú de opciones
        imageUploadLabel.addEventListener('click', function(e) {
            e.preventDefault();
            if (cameraContainer.style.display === 'flex') {
                stopCamera();
            } else {
                imageOptionsMenu.style.display = imageOptionsMenu.style.display === 'none' ? 'block' : 'none';
            }
        });
        
        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.image-upload-label') && !e.target.closest('.image-options-menu')) {
                imageOptionsMenu.style.display = 'none';
            }
        });
        
        // Seleccionar archivo
        selectFromFiles.addEventListener('click', function() {
            fileInput.click();
            imageOptionsMenu.style.display = 'none';
        });
        
        // Manejar cambio de archivo
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!imagePreview) {
                        // Si no existe el elemento de vista previa, crearlo
                        const newPreview = document.createElement('img');
                        newPreview.id = 'imagePreview';
                        newPreview.className = 'image-preview';
                        newPreview.alt = 'Vista previa';
                        imageUploadLabel.innerHTML = '';
                        imageUploadLabel.appendChild(newPreview);
                        imagePreview = newPreview;
                    }
                    imagePreview.src = e.target.result;
                    imagenData.value = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Iniciar cámara
        useCamera.addEventListener('click', function() {
            startCamera();
            imageOptionsMenu.style.display = 'none';
        });
        
        // Función para iniciar la cámara
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user' }
                });
                
                video.srcObject = stream;
                cameraContainer.style.display = 'flex';
                
                video.onloadedmetadata = function() {
                    video.play();
                };
            } catch (err) {
                console.error('Error al acceder a la cámara: ', err);
                alert('No se pudo acceder a la cámara. Verifica los permisos.');
            }
        }
        
        // Función para detener la cámara
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            cameraContainer.style.display = 'none';
        }
        
        // Capturar foto
        capturePhoto.addEventListener('click', function() {
            if (video && canvas) {
                // Configurar el canvas con las dimensiones del video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                
                // Dibujar el frame actual del video en el canvas
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Convertir el canvas a una URL de datos
                const dataUrl = canvas.toDataURL('image/jpeg');
                
                if (!imagePreview) {
                    // Si no existe el elemento de vista previa, crearlo
                    const newPreview = document.createElement('img');
                    newPreview.id = 'imagePreview';
                    newPreview.className = 'image-preview';
                    newPreview.alt = 'Vista previa';
                    imageUploadLabel.innerHTML = '';
                    imageUploadLabel.appendChild(newPreview);
                    imagePreview = newPreview;
                }
                
                imagePreview.src = dataUrl;
                imagenData.value = dataUrl;
                
                // Detener la cámara
                stopCamera();
            }
        });
        
        // Cancelar cámara
        cancelCamera.addEventListener('click', stopCamera);
        
        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const confirmarEmail = document.getElementById('confirmarEmail').value;
            const password = document.getElementById('password').value;
            const confirmarPassword = document.getElementById('confirmarPassword').value;
            const fechaNacimiento = new Date(document.getElementById('fechaNacimiento').value);
            const hoy = new Date();
            
            // Validar edad
            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            const mes = hoy.getMonth() - fechaNacimiento.getMonth();
            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                edad--;
            }
            
            if (edad < 18) {
                e.preventDefault();
                alert('Debes ser mayor de 18 años.');
                return;
            }
            
            // Validar que los emails coincidan
            if (email !== confirmarEmail) {
                e.preventDefault();
                alert('Los emails no coinciden.');
                return;
            }
            
            // Validar que las contraseñas coincidan
            if (password !== confirmarPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden.');
                return;
            }
            
            // Validar formato de contraseña
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordPattern.test(password)) {
                e.preventDefault();
                alert('La contraseña debe contener al menos una letra minúscula, una mayúscula, un número y un carácter especial.');
                return;
            }
        });
    </script>
</body>
</html>