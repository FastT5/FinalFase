<?php
session_start();
include 'conexion.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) { header("Location: index.php"); exit(); }

// --- LÓGICA DE ELIMINAR PROTEGIDA (CORREGIDO) ---
if (isset($_GET['borrar'])) {
    $id = $_GET['borrar'];
    
    // 1. VERIFICAR SI HAY CITAS CON ESTE SERVICIO
    $check = $conn->query("SELECT * FROM Citas WHERE ServicioID = $id");
    
    if ($check->num_rows > 0) {
        // SI TIENE CITAS: NO BORRAR y mostrar alerta
        echo "<script>
            alert('⚠️ NO SE PUEDE ELIMINAR ESTE SERVICIO.\\n\\nMotivo: Hay citas agendadas asociadas a él.\\nSolución: Cambia su estatus a DESHABILITADA en lugar de borrarlo.'); 
            window.location.href='admin_servicios.php';
        </script>";
    } else {
        // SI NO TIENE CITAS: BORRAR
        $conn->query("DELETE FROM Servicios WHERE ID = $id");
        echo "<script>alert('Servicio eliminado correctamente.'); window.location.href='admin_servicios.php';</script>";
    }
}

// CAMBIAR ESTATUS (Habilitada/Deshabilitada)
if (isset($_GET['cambiar_estatus'])) {
    $id = $_GET['id'];
    $nuevo = ($_GET['estatus'] == 'Habilitada') ? 'Deshabilitada' : 'Habilitada';
    $conn->query("UPDATE Servicios SET Estatus = '$nuevo' WHERE ID = $id");
    header("Location: admin_servicios.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <a href="admin.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Catálogo de Servicios</h2>
            <a href="admin_servicio_form.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Agregar Nuevo</a>
        </div>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Estatus</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Costo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM Servicios");
                        while($row = $result->fetch_assoc()) {
                            $badge = ($row['Estatus'] == 'Habilitada') ? 'bg-success' : 'bg-secondary';
                            echo "<tr>
                                <td>
                                    <a href='admin_servicios.php?cambiar_estatus=1&id=".$row['ID']."&estatus=".$row['Estatus']."' 
                                       class='badge $badge text-decoration-none' title='Clic para cambiar estatus'>
                                       ".$row['Estatus']."
                                    </a>
                                </td>
                                <td class='fw-bold'>".$row['NombreServicio']."</td>
                                <td>".$row['TipoServicio']."</td>
                                <td>$".$row['Costo']."</td>
                                <td class='text-center'>
                                    <a href='admin_servicio_form.php?id=".$row['ID']."' class='btn btn-primary btn-sm'><i class='bi bi-pencil'></i></a>
                                    <a href='admin_servicios.php?borrar=".$row['ID']."' class='btn btn-danger btn-sm ms-1' onclick='return confirm(\"¿Intentar eliminar?\")'><i class='bi bi-trash'></i></a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>