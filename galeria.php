<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería - DentaLife</title>
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
                    <li class="nav-item"><a class="nav-link active" href="galeria.php">GALERÍA</a></li>
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
                                <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nombre']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-danger" href="logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-light text-primary fw-bold">Login <i class="bi bi-person-circle"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Casos de Éxito</h2>
            <p class="text-muted">Resultados reales de pacientes felices</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow p-3">
                    <div class="position-relative">
                        <img src="img/antes1.jpg" class="img-fluid rounded mb-2" alt="Antes">
                        <img src="img/despues1.jpg" class="img-fluid rounded" alt="Después">
                        <span class="position-absolute top-0 end-0 badge bg-success m-2">Completado</span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-bold m-0">Don Polo</h5>
                            <small class="text-muted">12/07/24</small>
                        </div>
                        <i class="bi bi-eye fs-4 text-secondary"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow p-3">
                    <div class="position-relative">
                        <img src="img/antes2.jpg" class="img-fluid rounded mb-2" alt="Antes">
                        <img src="img/despues2.jpg" class="img-fluid rounded" alt="Después">
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-bold m-0">Fernanda</h5>
                            <small class="text-muted">12/10/24</small>
                        </div>
                        <i class="bi bi-eye fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 DentaLife - Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>