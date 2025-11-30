<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">DENTALIFE 🦷</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="servicios.php">SERVICIOS</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeria.php">GALERÍA</a></li>
                    <li class="nav-item"><a class="nav-link" href="sobrenos.php">SOBRE NOSOTROS</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contacto.php">CONTÁCTANOS</a></li>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1): ?>
                        <li class="nav-item"><a class="nav-link text-warning fw-bold" href="admin.php">ADMIN</a></li>
                    <?php endif; ?>
                </ul>
                <div class="ms-3">
                    <?php if (isset($_SESSION['nombre'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-light text-primary fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <?php echo $_SESSION['nombre']; ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-light text-primary fw-bold">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-5 mb-4">
                <h3 class="fw-bold mb-4">Contáctanos</h3>
                <p>Envíanos un mensaje o visítanos.</p>
                <form class="p-4 bg-light rounded shadow-sm">
                    <div class="mb-3"><input type="text" class="form-control" placeholder="Nombre"></div>
                    <div class="mb-3"><input type="email" class="form-control" placeholder="Correo"></div>
                    <div class="mb-3"><textarea class="form-control" rows="4" placeholder="Mensaje"></textarea></div>
                    <button type="button" class="btn btn-success w-100">ENVIAR</button>
                </form>
            </div>
            <div class="col-md-7">
                <h3 class="fw-bold mb-4">Ubicación</h3>
                <div class="ratio ratio-16x9 border rounded shadow-sm">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115049.71189333555!2d-100.389979!3d25.686614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8662958490b6a2eb%3A0x63351d1469046200!2" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5"><p>&copy; 2025 DentaLife</p></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>