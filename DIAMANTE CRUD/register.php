<?php
session_start();
include 'config/database.php';

$error = "";
$success = "";

if ($_POST) {
    $usuario = $_POST['nombre_usuario'];
    $password = $_POST['contrasena'];
    $tipo_doc = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    
    // Verificar si existe
    $check = "SELECT id_usuario FROM Usuario WHERE nombre_usuario = '$usuario' OR documento_usuario = '$documento'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "El usuario o documento ya existe";
    } else {
        // Insertar nuevo usuario
        $insert = "INSERT INTO Usuario (nombre_usuario, contrasena_usuario, tipo_documento_usuario, documento_usuario, telefono_usuario, email_usuario, id_rol) 
                   VALUES ('$usuario', '$password', '$tipo_doc', '$documento', '$telefono', '$email', 3)";
        
        if (mysqli_query($conn, $insert)) {
            $success = "Usuario registrado exitosamente. Puedes iniciar sesión.";
        } else {
            $error = "Error al registrar usuario";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Diamante Azul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <div class="col-md-6 d-flex align-items-center justify-content-center">
                <div class="auth-form">
                    <div class="text-center mb-4">
                        <h1 class="h3 mb-3 fw-bold text-primary">
                            <i class="fas fa-gem me-2"></i>Diamante Azul
                        </h1>
                        <h2 class="h4 text-muted">Crear Cuenta</h2>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control" name="nombre_usuario" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="contrasena" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo Documento</label>
                                <select class="form-select" name="tipo_documento" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="CC">Cédula</option>
                                    <option value="TI">Tarjeta de Identidad</option>
                                    <option value="CE">Cédula Extranjería</option>
                                    <option value="PP">Pasaporte</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Documento</label>
                                <input type="text" class="form-control" name="documento" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Registrarse
                        </button>
                    </form>
                    
                    <div class="text-center">
                        <p class="mb-0">¿Ya tienes cuenta? <a href="login.php" class="text-primary">Inicia sesión</a></p>
                        <a href="index.php" class="text-muted">Volver al inicio</a>
                    </div>
                    
                    <!-- Botón modo oscuro -->
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-secondary" id="themeToggle">
                            <i class="fas fa-moon" id="themeIcon"></i> Modo Oscuro
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 auth-bg d-none d-md-flex align-items-center justify-content-center">
                <div class="text-center text-white">
                    <i class="fas fa-gem display-1 mb-4"></i>
                    <h3>Únete a nosotros</h3>
                    <p class="lead">Crea tu cuenta y accede a todos nuestros servicios</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>