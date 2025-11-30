<?php
session_start();
include 'conexion.php';

// Seguridad: Solo Admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) { header("Location: index.php"); exit(); }

// ELIMINAR USUARIO
if (isset($_GET['borrar'])) {
    $id = $_GET['borrar'];
    if ($id != $_SESSION['usuario_id']) { // Evitar auto-borrarse
        $conn->query("DELETE FROM Usuarios WHERE ID=$id");
        header("Location: admin_usuarios.php");
    } else {
        echo "<script>alert('No puedes eliminar tu propia cuenta.'); window.location.href='admin_usuarios.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <a href="admin.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark"><i class="bi bi-people-fill"></i> Usuarios del Sistema</h2>
            <a href="admin_usuario_form.php" class="btn btn-primary fw-bold"><i class="bi bi-person-plus-fill"></i> Nuevo Usuario</a>
        </div>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta uniendo con la tabla Roles para ver el nombre (Admin, Paciente, etc.)
                        $sql = "SELECT Usuarios.*, Roles.NombreRol 
                                FROM Usuarios 
                                JOIN Roles ON Usuarios.RolID = Roles.ID 
                                ORDER BY Usuarios.ID ASC";
                        $res = $conn->query($sql);
                        
                        if ($res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                $img = !empty($row['Foto']) ? $row['Foto'] : 'doctor1.jpg'; // Foto default
                                
                                // Colores según rol
                                $badge = "bg-secondary";
                                if($row['RolID'] == 1) $badge = "bg-danger"; // Admin
                                if($row['RolID'] == 2) $badge = "bg-success"; // Doctor/Empleado
                                if($row['RolID'] == 3) $badge = "bg-primary"; // Paciente

                                echo "<tr>
                                    <td class='ps-4 fw-bold'>#".$row['ID']."</td>
                                    <td>
                                        <img src='img/$img' class='rounded-circle border' width='40' height='40' style='object-fit:cover'>
                                    </td>
                                    <td>".$row['Nombre']."</td>
                                    <td>".$row['Correo']."</td>
                                    <td><span class='badge $badge'>".$row['NombreRol']."</span></td>
                                    <td class='text-center'>
                                        <a href='admin_usuario_form.php?id=".$row['ID']."' class='btn btn-outline-primary btn-sm' title='Editar'>
                                            <i class='bi bi-pencil-square'></i>
                                        </a>
                                        <a href='admin_usuarios.php?borrar=".$row['ID']."' class='btn btn-outline-danger btn-sm ms-1' onclick='return confirm(\"¿Eliminar usuario permanentemente?\")' title='Eliminar'>
                                            <i class='bi bi-trash'></i>
                                        </a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-5'>No hay usuarios.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>