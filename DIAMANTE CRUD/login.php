<?php
session_start();
include 'config/database.php';

$error = "";

if ($_POST) {
    $usuario = $_POST['nombre_usuario'];
    $password = $_POST['contrasena'];
    
    // Consulta simple
    $query = "SELECT u.*, r.nombre_rol FROM Usuario u 
              JOIN Rol r ON u.id_rol = r.id_rol 
              WHERE u.nombre_usuario = '$usuario' AND u.contrasena_usuario = '$password' AND u.estado_usuario = 'ACTIVO'";
    
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        
        // Guardar en sesión
        $_SESSION['usuario_id'] = $user_data['id_usuario'];
        $_SESSION['nombre_usuario'] = $user_data['nombre_usuario'];
        $_SESSION['rol'] = $user_data['nombre_rol'];
        $_SESSION['id_rol'] = $user_data['id_rol'];
        
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Diamante Azul</title>
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
                        <h2 class="h4 text-muted">Iniciar Sesión</h2>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info mb-3">
                        <strong>Credenciales de prueba:</strong><br>
                        <small>
                            Admin: admin / admin123<br>
                            Empleado: empleado1 / empleado123<br>
                            Cliente: cliente1 / cliente123
                        </small>
                    </div>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="nombre_usuario" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="contrasena" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </button>
                    </form>
                    
                    <div class="text-center">
                        <p class="mb-0">¿No tienes cuenta? <a href="register.php" class="text-primary">Regístrate aquí</a></p>
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
                    <h3>Bienvenido de vuelta</h3>
                    <p class="lead">Accede a tu cuenta para gestionar tus productos y servicios</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>