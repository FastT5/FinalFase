<?php 
// 1. Iniciar sesión y conexión
if (session_status() === PHP_SESSION_NONE) session_start(); 
include 'conexion.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
                                <?php if ($_SESSION['rol'] == 3): // Solo para Pacientes ?>
                                    <li><a class="dropdown-item" href="mis_citas.php"><i class="bi bi-calendar-check"></i> Mis Citas</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>

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
            <span class="badge bg-success px-3 py-2 rounded-pill mb-2">Resultados Reales</span>
            <h2 class="fw-bold display-5">Casos de Éxito</h2>
            <p class="text-muted">La transformación de nuestros pacientes</p>
        </div>

        <div class="row g-4">
            <?php
            // CONSULTA A LA BASE DE DATOS
            $sql = "SELECT * FROM Galeria ORDER BY ID DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($caso = $result->fetch_assoc()) {
                    // Generamos la tarjeta para cada caso
                    echo '
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow h-100 hover-effect">
                            <div class="card-body p-2">
                                <h5 class="card-title fw-bold text-center mb-3">'.$caso['Titulo'].'</h5>
                                
                                <div class="row g-1">
                                    <div class="col-6 position-relative">
                                        <span class="badge bg-secondary position-absolute top-0 start-0 m-1">Antes</span>
                                        <img src="img/'.$caso['FotoAntes'].'" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;" alt="Antes">
                                    </div>
                                    <div class="col-6 position-relative">
                                        <span class="badge bg-success position-absolute top-0 end-0 m-1">Después</span>
                                        <img src="img/'.$caso['FotoDespues'].'" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;" alt="Después">
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <small class="text-muted"><i class="bi bi-calendar3"></i> Realizado: '.$caso['Fecha'].'</small>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 text-center pb-3">
                                <a href="contacto.php" class="btn btn-outline-success btn-sm rounded-pill w-100">¡Quiero un cambio así!</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                // MENSAJE SI NO HAY FOTOS AÚN
                echo '
                <div class="col-12 text-center py-5">
                    <div class="alert alert-light shadow-sm" role="alert">
                        <h4 class="text-muted"><i class="bi bi-images"></i> Aún no hay casos publicados</h4>
                        <p>Pronto subiremos nuestras historias de éxito.</p>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 DentaLife - Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>