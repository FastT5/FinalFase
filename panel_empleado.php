<?php
session_start();
include 'conexion.php';

// 1. Seguridad: Solo Empleados (Rol 2)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) { header("Location: login.php"); exit(); }

// 2. Lógica del Filtro de Sucursal
$sucursal_filtro = isset($_GET['sucursal']) ? $_GET['sucursal'] : '';
$where_sucursal = "";
if ($sucursal_filtro != "") {
    $where_sucursal = " AND Citas.SucursalID = $sucursal_filtro";
}

// 3. Lógica para Confirmar/Cancelar desde la tabla
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $nuevo_estado = ($_GET['accion'] == 'confirmar') ? 'Confirmada' : 'Cancelada';
    $conn->query("UPDATE Citas SET EstatusCita = '$nuevo_estado' WHERE ID = $id");
    header("Location: panel_empleado.php?sucursal=$sucursal_filtro");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Empleado - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-hospital"></i> Panel Empleado</a>
            <div class="d-flex gap-3 align-items-center">
                <span class="text-white small">Hola, <?php echo $_SESSION['nombre']; ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                
                <a href="empleado_agendar.php" class="btn btn-primary fw-bold">
                    <i class="bi bi-plus-circle"></i> Agendar Nueva Cita
                </a>

                <form class="d-flex align-items-center gap-2" method="GET">
                    <label class="fw-bold text-muted">Filtrar por Sucursal:</label>
                    <select name="sucursal" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                        <option value="">-- Ver Todas --</option>
                        <?php
                        $sucs = $conn->query("SELECT * FROM Sucursales");
                        while($row = $sucs->fetch_assoc()) {
                            $sel = ($row['ID'] == $sucursal_filtro) ? 'selected' : '';
                            echo "<option value='".$row['ID']."' $sel>".$row['NombreSucursal']."</option>";
                        }
                        ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-white">
                <h5 class="m-0 text-secondary fw-bold">Agenda de Citas</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Fecha/Hora</th>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Servicio</th>
                            <th>Sucursal</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT Citas.*, 
                                       P.Nombre as Paciente, 
                                       D.Nombre as Doctor, 
                                       S.NombreServicio, 
                                       Suc.NombreSucursal
                                FROM Citas
                                JOIN Usuarios P ON Citas.PacienteID = P.ID
                                JOIN Usuarios D ON Citas.EmpleadoID = D.ID
                                JOIN Servicios S ON Citas.ServicioID = S.ID
                                JOIN Sucursales Suc ON Citas.SucursalID = Suc.ID
                                WHERE 1=1 $where_sucursal
                                ORDER BY Citas.FechaHora DESC";
                        
                        $res = $conn->query($sql);

                        if ($res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                $fecha = date('d/m/Y h:i A', strtotime($row['FechaHora']));
                                
                                // Color del estado
                                $badge = "bg-secondary";
                                if($row['EstatusCita'] == 'Confirmada') $badge = "bg-success";
                                if($row['EstatusCita'] == 'Pendiente') $badge = "bg-warning text-dark";
                                if($row['EstatusCita'] == 'Cancelada') $badge = "bg-danger";

                                echo "<tr>
                                    <td class='ps-4 fw-bold'>$fecha</td>
                                    <td>".$row['Paciente']."</td>
                                    <td>Dr. ".$row['Doctor']."</td>
                                    <td>".$row['NombreServicio']."</td>
                                    <td><small>".$row['NombreSucursal']."</small></td>
                                    <td><span class='badge $badge'>".$row['EstatusCita']."</span></td>
                                    <td class='text-center'>
                                        <a href='panel_empleado.php?accion=confirmar&id=".$row['ID']."&sucursal=$sucursal_filtro' class='btn btn-success btn-sm' title='Confirmar'><i class='bi bi-check'></i></a>
                                        <a href='panel_empleado.php?accion=cancelar&id=".$row['ID']."&sucursal=$sucursal_filtro' class='btn btn-outline-danger btn-sm' title='Cancelar' onclick='return confirm(\"¿Cancelar cita?\")'><i class='bi bi-x'></i></a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center py-5 text-muted'>No hay citas registradas con este filtro.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>