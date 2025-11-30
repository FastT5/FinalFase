<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) { header("Location: index.php"); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $historia = $conn->real_escape_string($_POST['historia']);
    $mision = $conn->real_escape_string($_POST['mision']);
    $vision = $conn->real_escape_string($_POST['vision']);
    $conn->query("UPDATE Informacion SET Historia='$historia', Mision='$mision', Vision='$vision' WHERE ID=1");
    echo "<script>alert('Información actualizada');</script>";
}

$row = $conn->query("SELECT * FROM Informacion WHERE ID=1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Información</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <a href="admin.php" class="btn btn-secondary mb-3">Volver</a>
        <div class="card shadow">
            <div class="card-header bg-info text-white"><h4>Editar "Sobre Nosotros"</h4></div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3"><label class="fw-bold">Historia</label><textarea name="historia" class="form-control" rows="5"><?php echo $row['Historia']; ?></textarea></div>
                    <div class="mb-3"><label class="fw-bold">Misión</label><textarea name="mision" class="form-control" rows="3"><?php echo $row['Mision']; ?></textarea></div>
                    <div class="mb-3"><label class="fw-bold">Visión</label><textarea name="vision" class="form-control" rows="3"><?php echo $row['Vision']; ?></textarea></div>
                    <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>