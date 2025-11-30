<?php 
// 1. Iniciar sesión y conexión
if (session_status() === PHP_SESSION_NONE) session_start(); 
include 'conexion.php'; 

// 2. Obtener la información de la base de datos
$info = $conn->query("SELECT * FROM Informacion WHERE ID = 1")->fetch_assoc();

// Valores por defecto por si la BD está vacía
if (!$info) {
    $info = [
        'Historia' => 'Historia pendiente.',
        'Mision' => 'Misión pendiente.',
        'Vision' => 'Visión pendiente.'
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - DentaLife</title>
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
                    <li class="nav-item"><a class="nav-link" href="galeria.php">GALERÍA</a></li>
                    <li class="nav-item"><a class="nav-link active" href="sobrenos.php">SOBRE NOSOTROS</a></li>
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
        
        <div id="carouselInfo" class="carousel slide shadow rounded-4 overflow-hidden mb-5" data-bs-ride="carousel">
            
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselInfo" data-bs-slide-to="0" class="active bg-dark"></button>
                <button type="button" data-bs-target="#carouselInfo" data-bs-slide-to="1" class="bg-dark"></button>
                <button type="button" data-bs-target="#carouselInfo" data-bs-slide-to="2" class="bg-dark"></button>
            </div>

            <div class="carousel-inner">
                
                <div class="carousel-item active" data-bs-interval="5000">
                    <div class="p-5 bg-light text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 400px;">
                        <div class="bg-white rounded-circle p-4 mb-3 shadow-sm">
                            <i class="bi bi-hourglass-split fs-1 text-primary"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Nuestra Historia</h2>
                        <p class="lead text-muted w-75">
                            <?php echo nl2br($info['Historia']); ?>
                        </p>
                    </div>
                </div>

                <div class="carousel-item" data-bs-interval="5000">
                    <div class="p-5 bg-primary text-white text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 400px;">
                        <div class="bg-white rounded-circle p-4 mb-3 shadow-sm">
                            <i class="bi bi-lightbulb fs-1 text-primary"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Nuestra Misión</h2>
                        <p class="lead w-75">
                            <?php echo nl2br($info['Mision']); ?>
                        </p>
                    </div>
                </div>

                <div class="carousel-item" data-bs-interval="5000">
                    <div class="p-5 bg-light text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 400px;">
                        <div class="bg-white rounded-circle p-4 mb-3 shadow-sm">
                            <i class="bi bi-eye fs-1 text-primary"></i>
                        </div>
                        <h2 class="fw-bold mb-3">Nuestra Visión</h2>
                        <p class="lead text-muted w-75">
                            <?php echo nl2br($info['Vision']); ?>
                        </p>
                    </div>
                </div>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselInfo" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselInfo" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

        <h2 class="text-center fw-bold mb-5">Nuestro Equipo Médico</h2>
        <div class="row justify-content-center">
            <?php
            // Consultar Doctores (RolID = 2)
            $sql_docs = "SELECT * FROM Usuarios WHERE RolID = 2";
            $res_docs = $conn->query($sql_docs);

            if ($res_docs->num_rows > 0) {
                while($doc = $res_docs->fetch_assoc()) {
                    
                    // LÓGICA FOTO DOCTOR
                    $imgDoc = !empty($doc['Foto']) ? "img/".$doc['Foto'] : "img/doctor1.jpg";

                    echo '
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100 hover-effect">
                            <div class="row g-0 align-items-center h-100">
                                <div class="col-4">
                                    <img src="'.$imgDoc.'" class="img-fluid rounded-start h-100 object-fit-cover" alt="Dr" style="min-height: 150px;">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-primary">Dr. '.$doc['Nombre'].'</h5>
                                        <p class="card-text fw-bold text-uppercase badge bg-light text-dark border">'.$doc['Especialidad'].'</p>
                                        <p class="card-text small text-muted">Profesional certificado comprometido con tu salud dental.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="alert alert-warning text-center w-100">Aún no hay doctores registrados en el sistema.</div>';
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