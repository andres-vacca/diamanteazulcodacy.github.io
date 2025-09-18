<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diamante Azul - Casa de Compra y Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-gem me-2"></i>Diamante Azul
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#productos">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button class="btn btn-outline-light me-2" id="themeToggle">
                            <i class="fas fa-moon" id="themeIcon"></i>
                        </button>
                    </li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?php echo $_SESSION['nombre_usuario']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-light me-2" href="login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light" href="register.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-primary mb-4">
                        Bienvenido a <span class="text-gradient">Diamante Azul</span>
                    </h1>
                    <p class="lead mb-4">
                        Tu casa de confianza para compra, venta y empeño de productos de valor. 
                        Más de 20 años de experiencia nos respaldan.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#productos" class="btn btn-primary btn-lg">Ver Productos</a>
                        <a href="#servicios" class="btn btn-outline-primary btn-lg">Nuestros Servicios</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <i class="fas fa-gem display-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos Section -->
    <section id="productos" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nuestros Productos</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-ring display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Joyas</h5>
                            <p class="card-text">Anillos, collares, pulseras y más joyas de oro, plata y piedras preciosas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-laptop display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Electrónicos</h5>
                            <p class="card-text">Computadoras, celulares, tablets y equipos electrónicos de última generación.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-tools display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Herramientas</h5>
                            <p class="card-text">Herramientas profesionales y equipos especializados para diversos oficios.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Nuestros Servicios</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="service-card">
                        <i class="fas fa-shopping-cart text-primary mb-3"></i>
                        <h4>Compra y Venta</h4>
                        <p>Compramos y vendemos productos de valor con los mejores precios del mercado.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="service-card">
                        <i class="fas fa-handshake text-primary mb-3"></i>
                        <h4>Empeño</h4>
                        <p>Servicio de empeño con tasas preferenciales y plazos flexibles.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto Section -->
    <section id="contacto" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Contáctanos</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-info">
                        <div class="mb-4">
                            <i class="fas fa-map-marker-alt text-primary me-3"></i>
                            <span>Calle Principal #123, Ciudad</span>
                        </div>
                        <div class="mb-4">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <span>+57 300 123 4567</span>
                        </div>
                        <div class="mb-4">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <span>info@diamanteazul.com</span>
                        </div>
                        <div class="mb-4">
                            <i class="fas fa-clock text-primary me-3"></i>
                            <span>Lun - Vie: 8:00 AM - 6:00 PM</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form class="contact-form">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Nombre completo" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Correo electrónico" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="Mensaje" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-gem me-2"></i>Diamante Azul</h5>
                    <p>Tu casa de confianza para compra, venta y empeño.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2024 Diamante Azul. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>