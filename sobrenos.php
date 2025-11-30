<?php 
if (session_status() === PHP_SESSION_NONE) session_start(); 
include 'conexion.php'; 

// Obtener info de la empresa
$info = $conn->query("SELECT * FROM Informacion WHERE ID = 1")->fetch_assoc();
if (!$info) {
    $info = ['Historia' => '...', 'Mision' => '...', 'Vision' => '...'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sobre Nosotros - DentaLife</title>
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
        
        <div class="row text-center mb-5">
            <div class="col-md-4 mb-3">
                <div class="p-4 h-100 rounded shadow-sm bg-light">
                    <h3 class="fw-bold">Historia</h3>
                    <p class="small text-muted text-start"><?php echo nl2br($info['Historia']); ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-4 h-100 rounded shadow-sm text-white bg-primary">
                    <h3 class="fw-bold">Misión</h3>
                    <p class="small text-start"><?php echo nl2br($info['Mision']); ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-4 h-100 rounded shadow-sm bg-light">
                    <h3 class="fw-bold">Visión</h3>
                    <p class="small text-muted text-start"><?php echo nl2br($info['Vision']); ?></p>
                </div>
            </div>
        </div>

        <h2 class="text-center fw-bold mb-4">Nuestro Equipo Médico</h2>
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
                        <div class="card border-0 shadow-sm h-100">
                            <div class="row g-0 align-items-center h-100">
                                <div class="col-4">
                                    <img src="'.$imgDoc.'" class="img-fluid rounded-start h-100 object-fit-cover" alt="Dr" style="min-height: 150px;">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">Dr. '.$doc['Nombre'].'</h5>
                                        <p class="card-text text-primary small fw-bold text-uppercase">'.$doc['Especialidad'].'</p>
                                        <p class="card-text small text-muted">Especialista certificado de DentaLife.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="alert alert-warning text-center w-100">No hay doctores registrados aún.</div>';
            }
            ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 DentaLife</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>