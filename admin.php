<?php
session_start();
include 'conexion.php';

// SEGURIDAD
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: index.php");
    exit();
}

// Lógica para Eliminar Usuario y Citas (Se mantiene igual)
if (isset($_GET['eliminar_usuario'])) {
    $id = $_GET['eliminar_usuario'];
    if ($id != $_SESSION['usuario_id']) {
        $conn->query("DELETE FROM Usuarios WHERE ID = $id");
        header("Location: admin.php");
    } else {
        echo "<script>alert('No puedes eliminar tu propia cuenta.');</script>";
    }
}
// ... (resto de lógica de citas se mantiene implícita o puedes copiarla del anterior si la necesitas, 
// pero aquí te doy la estructura visual principal actualizada)

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-shield-lock"></i> ADMIN PANEL</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4">Bienvenido, <?php echo $_SESSION['nombre']; ?></h2>

        <div class="row g-3 mb-5">
            <div class="col-md-3">
                <a href="admin_servicios.php" class="btn btn-primary w-100 py-4 shadow-sm">
                    <i class="bi bi-tools fs-1 d-block mb-2"></i> Servicios
                </a>
            </div>
            <div class="col-md-3">
                <a href="admin_doctores.php" class="btn btn-success w-100 py-4 shadow-sm">
                    <i class="bi bi-person-badge fs-1 d-block mb-2"></i> Doctores
                </a>
            </div>
            <div class="col-md-3">
                <a href="admin_galeria.php" class="btn btn-warning w-100 py-4 shadow-sm text-dark">
                    <i class="bi bi-images fs-1 d-block mb-2"></i> Galería
                </a>
            </div>
            <div