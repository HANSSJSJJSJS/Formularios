<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Propietario</title>
    <link rel="stylesheet" href="formulario.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Registro de Propietario</h2>

            <?php
            // Mostrar mensajes de éxito o error
            if (isset($_GET['mensaje'])) {
                $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'error';
                echo '<div class="mensaje ' . $tipo . '">' . htmlspecialchars($_GET['mensaje']) . '</div>';
            }
            
            // Obtener el paso actual
            $currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
            $totalSteps = 4;
            
            // Calcular el progreso
            $progress = ($currentStep / $totalSteps) * 100;
            ?>

            <!-- Barra de progreso -->
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                <div class="progress-steps">
                    <?php for ($i = 0; $i < $totalSteps; $i++) { ?>
                        <div class="progress-step <?php echo $currentStep > $i ? 'completed' : ''; ?> <?php echo $currentStep === $i + 1 ? 'active' : ''; ?>">
                            <div class="step-number"><?php echo $i + 1; ?></div>
                            <div class="step-label">
                                <?php 
                                if ($i === 0) echo "Información Personal";
                                if ($i === 1) echo "Datos Personales";
                                if ($i === 2) echo "Ubicación";
                                if ($i === 3) echo "Cuenta";
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <form action="procesar.php" method="POST">
                <input type="hidden" name="step" value="<?php echo $currentStep; ?>">
                
                <?php if ($currentStep === 1) { ?>
                <!-- Paso 1: Información Personal -->
                <div class="form-section">
                    <h3 class="section-title">Información Personal</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipoDocumento">Tipo de documento</label>
                            <div class="input-container">
                                <select id="tipoDocumento" name="tipoDocumento" required>
                                    <option value="">Seleccione un tipo</option>
                                    <option value="CC" <?php echo isset($_SESSION['form_data']['tipoDocumento']) && $_SESSION['form_data']['tipoDocumento'] === 'CC' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                                    <option value="CE" <?php echo isset($_SESSION['form_data']['tipoDocumento']) && $_SESSION['form_data']['tipoDocumento'] === 'CE' ? 'selected' : ''; ?>>Cédula de Extranjería</option>
                                    <option value="PP" <?php echo isset($_SESSION['form_data']['tipoDocumento']) && $_SESSION['form_data']['tipoDocumento'] === 'PP' ? 'selected' : ''; ?>>Pasaporte</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="numeroId">Número de documento</label>
                            <div class="input-container">
                                <input type="number" id="numeroId" name="numeroId" inputmode="numeric" 
                                    style="appearance: textfield; -moz-appearance: textfield;" 
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                                    required minlength="6" maxlength="10"
                                    value="<?php echo isset($_SESSION['form_data']['numeroId']) ? htmlspecialchars($_SESSION['form_data']['numeroId']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Género</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input value="Mujer" type="radio" id="01" name="genero" <?php echo isset($_SESSION['form_data']['genero']) && $_SESSION['form_data']['genero'] === 'Mujer' ? 'checked' : ''; ?> required>
                                    <label for="01">Mujer</label>
                                </div>
                                <div class="radio-option">
                                    <input value="Hombre" type="radio" id="02" name="genero" <?php echo isset($_SESSION['form_data']['genero']) && $_SESSION['form_data']['genero'] === 'Hombre' ? 'checked' : ''; ?> required>
                                    <label for="02">Hombre</label>
                                </div>
                                <div class="radio-option">
                                    <input value="No identificado" type="radio" id="03" name="genero" <?php echo isset($_SESSION['form_data']['genero']) && $_SESSION['form_data']['genero'] === 'No identificado' ? 'checked' : ''; ?> required>
                                    <label for="03">No identificado</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fechaNacimiento">Fecha de nacimiento</label>
                            <div class="input-container">
                                <input type="date" id="fechaNacimiento" name="fechaNacimiento" required
                                    value="<?php echo isset($_SESSION['form_data']['fechaNacimiento']) ? htmlspecialchars($_SESSION['form_data']['fechaNacimiento']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($currentStep === 2) { ?>
                <!-- Paso 2: Datos Personales -->
                <div class="form-section">
                    <h3 class="section-title">Datos Personales</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <div class="input-container">
                                <input type="text" id="nombre" name="nombre" required minlength="3" maxlength="30" 
                                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
                                    value="<?php echo isset($_SESSION['form_data']['nombre']) ? htmlspecialchars($_SESSION['form_data']['nombre']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <div class="input-container">
                                <input type="text" id="apellido" name="apellido" required minlength="3" maxlength="30" 
                                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
                                    value="<?php echo isset($_SESSION['form_data']['apellido']) ? htmlspecialchars($_SESSION['form_data']['apellido']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <div class="input-container">
                                <input type="text" id="telefono" name="telefono" required pattern="[0-9]+" 
                                    minlength="10" maxlength="10"
                                    value="<?php echo isset($_SESSION['form_data']['telefono']) ? htmlspecialchars($_SESSION['form_data']['telefono']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($currentStep === 3) { ?>
                <!-- Paso 3: Ubicación -->
                <div class="form-section">
                    <h3 class="section-title">Ubicación</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ciudad">Ciudad</label>
                            <div class="input-container">
                                <input type="text" id="ciudad" name="ciudad" required minlength="3" maxlength="30" 
                                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
                                    value="<?php echo isset($_SESSION['form_data']['ciudad']) ? htmlspecialchars($_SESSION['form_data']['ciudad']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <div class="input-container">
                                <input type="text" id="direccion" name="direccion" required minlength="3" maxlength="30" 
                                    pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s0-9\-#.]+"
                                    value="<?php echo isset($_SESSION['form_data']['direccion']) ? htmlspecialchars($_SESSION['form_data']['direccion']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($currentStep === 4) { ?>
                <!-- Paso 4: Datos de Cuenta -->
                <div class="form-section">
                    <h3 class="section-title">Datos de Cuenta</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-container">
                                <input type="email" id="email" name="email" required
                                    value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirmarEmail">Confirmar Email</label>
                            <div class="input-container">
                                <input type="email" id="confirmarEmail" name="confirmarEmail" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="input-container">
                                <input type="password" id="password" name="password" required minlength="8" 
                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&.]{8,}$">
                                <button type="button" class="password-toggle" onclick="togglePassword('password')" tabindex="-1">
                                    👁️‍🗨️
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirmarPassword">Confirmar Contraseña</label>
                            <div class="input-container">
                                <input type="password" id="confirmarPassword" name="confirmarPassword" required minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('confirmarPassword')" tabindex="-1">
                                    👁️‍🗨️
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <!-- Botones de navegación -->
                <div class="navigation-buttons">
                    <?php if ($currentStep > 1) { ?>
                        <a href="index.php?step=<?php echo $currentStep - 1; ?>" class="nav-button back-button">Volver</a>
                    <?php } ?>

                    <?php if ($currentStep < $totalSteps) { ?>
                        <button type="submit" name="action" value="next" class="nav-button next-button">Siguiente</button>
                    <?php } else { ?>
                        <button type="submit" name="action" value="register" class="submit-button">Registrar Propietario</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            
            if (field.type === "password") {
                field.type = "text";
                button.textContent = "👁️";
            } else {
                field.type = "password";
                button.textContent = "👁️‍🗨️";
            }
        }

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            if (document.querySelector('input[name="action"]').value === 'next') {
                // Validación para el paso 1
                if (<?php echo $currentStep; ?> === 1) {
                    const fechaNacimiento = new Date(document.getElementById('fechaNacimiento').value);
                    const hoy = new Date();
                    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
                    
                    if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                        edad--;
                    }
                    
                    if (edad < 18) {
                        e.preventDefault();
                        alert('Debes ser mayor de 18 años.');
                    }
                }
                // Validación para el paso 4
                else if (<?php echo $currentStep; ?> === 4) {
                    const email = document.getElementById('email').value;
                    const confirmarEmail = document.getElementById('confirmarEmail').value;
                    const password = document.getElementById('password').value;
                    const confirmarPassword = document.getElementById('confirmarPassword').value;
                    
                    if (email !== confirmarEmail) {
                        e.preventDefault();
                        alert('Los emails no coinciden.');
                    }
                    
                    if (password !== confirmarPassword) {
                        e.preventDefault();
                        alert('Las contraseñas no coinciden.');
                    }
                }
            }
        });
    </script>
</body>
</html>