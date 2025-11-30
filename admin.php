<?php
session_start();
include 'conexion.php';

// 1. SEGURIDAD
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: index.php");
    exit();
}

// 2. CONSULTAS PARA ESTADÍSTICAS (DASHBOARD)
// Usamos @ para evitar errores si las tablas están vacías
$total_pacientes = $conn->query("SELECT count(*) as total FROM Usuarios WHERE RolID = 3")->fetch_assoc()['total'];
$total_doctores = $conn->query("SELECT count(*) as total FROM Usuarios WHERE RolID = 2")->fetch_assoc()['total'];
$total_citas = $conn->query("SELECT count(*) as total FROM Citas")->fetch_assoc()['total'];
$citas_pendientes = $conn->query("SELECT count(*) as total FROM Solicitudes WHERE Estatus = 'Pendiente'")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .card-hover:hover { transform: translateY(-5px); transition: 0.3s; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark sticky-top shadow px-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-shield-lock-fill text-warning"></i> DENTALIFE ADMIN
            </a>
            <div class="d-flex align-items-center text-white gap-3">
                <span class="d-none d-md-block">Bienvenido, <?php echo $_SESSION['nombre']; ?></span>
                <a href="logout.php" class="btn btn-danger btn-sm fw-bold"><i class="bi bi-box-arrow-right"></i> Salir</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <h4 class="mb-4 text-secondary fw-bold"><i class="bi bi-speedometer2"></i> Resumen General</h4>
        <div class="row g-3 mb-5">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow border-0 card-hover h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold m-0"><?php echo $total_pacientes; ?></h3>
                            <small class="text-white-50">Pacientes</small>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow border-0 card-hover h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold m-0"><?php echo $total_doctores; ?></h3>
                            <small class="text-white-50">Doctores</small>
                        </div>
                        <i class="bi bi-heart-pulse fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info shadow border-0 card-hover h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold m-0 text-white"><?php echo $total_citas; ?></h3>
                            <small class="text-white-50">Citas Agendadas</small>
                        </div>
                        <i class="bi bi-calendar-check fs-1 text-white opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow border-0 card-hover h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold m-0 text-dark"><?php echo $citas_pendientes; ?></h3>
                            <small class="text-dark opacity-75">Solicitudes Nuevas</small>
                        </div>
                        <i class="bi bi-bell fs-1 text-dark opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-4 text-secondary fw-bold"><i class="bi bi-grid-fill"></i> Módulos de Gestión</h4>
        <div class="row g-4">
            
            <div class="col-md-4">
                <a href="admin_usuarios.php" class="text-decoration-none">
                    <div class="card shadow-sm border-0 card-hover text-center py-4">
                        <div class="card-body">
                            <i class="bi bi-person-gear fs-1 text-dark mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">Usuarios</h5>
                            <p class="text-muted small">Crear Admins, Empleados y Pacientes.</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="admin_doctores.php" class="text-decoration-none">
                    <div class="card shadow-sm border-0 card-hover text-center py-4">
                        <div class="card-body">
                            <i class="bi bi-person-badge fs-1 text-success mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">Doctores</h5>
                            <p class="text-muted small">Equipo médico, especialidades y fotos.</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="admin_servicios.php" class="text-decoration-none">
                    <div class="card shadow-sm border-0 card-hover text-center py-4">
                        <div class="card-body">
                            <i class="bi bi-tools fs-1 text-primary mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">Servicios</h5>
                            <p class="text-muted small">Tratamientos, precios y descripciones.</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="admin_galeria.php" class="text-decoration-none">
                    <div class="card shadow-sm border-0 card-hover text-center py-4">
                        <div class="card-body">
                            <i class="bi bi-images fs-1 text-warning mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">Galería</h5>
                            <p class="text-muted small">Subir casos de éxito (Antes y Después).</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6">
                <a href="admin_info.php" class="text-decoration-none">
                    <div class="card shadow-sm border-0 card-hover text-center py-4">
                        <div class="card-body">
                            <i class="bi bi-pencil-square fs-1 text-info mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">Información</h5>
                            <p class="text-muted small">Editar Historia, Misión y Visión.</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>