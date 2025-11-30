<?php
session_start();
include 'conexion.php';

// SEGURIDAD: Si no es admin (Rol 1), fuera.
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    echo "<script>alert('Acceso denegado.'); window.location.href='index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Panel Administrativo</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
        </div>
    </nav>
    <div class="container py-5">
        <h2>Bienvenido Admin: <?php echo $_SESSION['nombre']; ?></h2>
        <div class="card mt-4">
            <div class="card-header">Usuarios Registrados</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th></tr></thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM Usuarios"; // Puedes mejorar esto con JOIN a Roles
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>".$row['ID']."</td><td>".$row['Nombre']."</td><td>".$row['Correo']."</td><td>".$row['RolID']."</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>