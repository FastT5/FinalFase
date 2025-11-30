<?php 
if (session_status() === PHP_SESSION_NONE) session_start(); 
include 'conexion.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contáctanos - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($_SESSION['rol'] == 3): ?>
                                    <li><a class="dropdown-item" href="mis_citas.php">Mis Citas</a></li>
                                <?php elseif ($_SESSION['rol'] == 2): ?>
                                    <li><a class="dropdown-item" href="panel_doctor.php">Panel Médico</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Cerrar Sesión</a></li>
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
                <h3 class="fw-bold mb-4">Envíanos un Mensaje</h3>
                <div class="mb-4">
                    <p class="mb-1"><i class="bi bi-telephone-fill text-primary me-2"></i> Teléfono: +52 811-456-7890</p>
                    <p><i class="bi bi-envelope-fill text-primary me-2"></i> contacto@dentalife.com</p>
                </div>

                <form class="p-4 bg-light rounded shadow-sm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" class="form-control" placeholder="Tu nombre">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" class="form-control" placeholder="tucorreo@ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mensaje</label>
                        <textarea class="form-control" rows="4" placeholder="Escribe aquí tus dudas..."></textarea>
                    </div>
                    <button type="button" class="btn btn-success w-100 fw-bold">ENVIAR MENSAJE</button>
                </form>
            </div>

            <div class="col-md-7">
                <h3 class="fw-bold mb-4">Nuestras Ubicaciones</h3>
                
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary"><i class="bi bi-geo-alt-fill"></i> Sucursal Cadereyta</h5>
                        <p class="text-muted small mb-2">
                            Gonzalitos 100 Oriente, entre Mutualismo y 20 de Noviembre.<br>
                            CP 67480, Cadereyta Jiménez, N.L.
                        </p>
                        <div class="ratio ratio-21x9 border rounded">
                            <iframe src="https://maps.google.com/maps?q=Gonzalitos+100+Oriente+Cadereyta+Jimenez&output=embed" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary"><i class="bi bi-geo-alt-fill"></i> Sucursal San Nicolás</h5>
                        <p class="text-muted small mb-2">
                            S. Cristóbal, Villas de San Cristobal 2do Sector.<br>
                            CP 66478, San Nicolás de los Garza, N.L.
                        </p>
                        <div class="ratio ratio-21x9 border rounded">
                            <iframe src="https://maps.google.com/maps?q=Villas+de+San+Cristobal+2do+Sector+San+Nicolas&output=embed" allowfullscreen="" loading="lazy"></iframe>
                        </div>
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