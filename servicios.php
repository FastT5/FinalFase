<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios - DentaLife</title>
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
                    <li class="nav-item"><a class="nav-link active" href="servicios.php">SERVICIOS</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeria.php">GALERÍA</a></li>
                    <li class="nav-item"><a class="nav-link" href="sobrenos.php">SOBRE NOSOTROS</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacto.php">CONTÁCTANOS</a></li>
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
        <div class="text-center mb-5">
            <h2 class="fw-bold display-5">Nuestros Servicios</h2>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-effect">
                    <img src="img/general.jpg" class="card-img-top" style="height: 250px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Odontología General</h5>
                        <p>Limpiezas, Revisiones, Empastes.</p>
                        <a href="contacto.php" class="btn btn-primary rounded-pill w-75 fw-bold">SOLICITAR CITA</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-effect">
                    <img src="img/especialidades.jpg" class="card-img-top" style="height: 250px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Especialidades</h5>
                        <p>Endodoncia, Ortodoncia, Periodoncia.</p>
                        <a href="contacto.php" class="btn btn-primary rounded-pill w-75 fw-bold">SOLICITAR CITA</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-effect">
                    <img src="img/estetica.jpg" class="card-img-top" style="height: 250px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">Estética</h5>
                        <p>Blanqueamiento, Carillas, Diseño.</p>
                        <a href="contacto.php" class="btn btn-primary rounded-pill w-75 fw-bold">SOLICITAR CITA</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5"><p>&copy; 2025 DentaLife</p></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>