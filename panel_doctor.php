<?php
session_start();
include 'conexion.php';

// Seguridad: Solo Empleados/Doctores (Rol 2)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) { header("Location: login.php"); exit(); }

// --- LÓGICA DE FILTRADO POR SUCURSAL ---
$sucursal_id = isset($_GET['sucursal']) ? $_GET['sucursal'] : '';
$filtro_sql = "";
if ($sucursal_id != "") {
    $filtro_sql = " AND Citas.SucursalID = $sucursal_id";
}

// --- LÓGICA DE ACCIONES (Confirmar/Cancelar desde la tabla) ---
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $nuevo_estado = ($_GET['accion'] == 'confirmar') ? 'Confirmada' : 'Cancelada';
    $conn->query("UPDATE Citas SET EstatusCita = '$nuevo_estado' WHERE ID = $id");
    header("Location: panel_doctor.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Médica - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success sticky-top shadow px-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-hospital-fill"></i> Panel de Gestión</a>
            <div class="d-flex gap-3 align-items-center text-white">
                <span>Hola, <?php echo $_SESSION['nombre']; ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                
                <a href="empleado_agendar.php" class="btn btn-primary fw-bold shadow-sm">
                    <i class="bi bi-plus-circle-fill"></i> Agendar Nueva Cita
                </a>

                <form class="d-flex align-items-center gap-2" method="GET">
                    <label class="fw-bold text-secondary mb-0">Ver Sucursal:</label>
                    <select name="sucursal" class="form-select form-select-sm w-auto border-success" onchange="this.form.submit()">
                        <option value="">-- Todas --</option>
                        <?php
                        $sucs = $conn->query("SELECT * FROM Sucursales");
                        while($row = $sucs->fetch_assoc()) {
                            $sel = ($row['ID'] == $sucursal_id) ? 'selected' : '';
                            echo "<option value='".$row['ID']."' $sel>".$row['NombreSucursal']."</option>";
                        }
                        ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <h5 class="m-0 fw-bold text-success"><i class="bi bi-calendar3"></i> Agenda General</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th class="ps-4">Fecha/Hora</th>
                            <th>Paciente</th>
                            <th>Tratamiento</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta Maestra
                        $sql = "SELECT Citas.*, 
                                       Usuarios.Nombre as Paciente, 
                                       Servicios.NombreServicio, 
                                       Sucursales.NombreSucursal
                                FROM Citas
                                JOIN Usuarios ON Citas.PacienteID = Usuarios.ID
                                JOIN Servicios ON Citas.ServicioID = Servicios.ID
                                JOIN Sucursales ON Citas.SucursalID = Sucursales.ID
                                WHERE 1=1 $filtro_sql
                                ORDER BY Citas.FechaHora DESC";
                        
                        $res = $conn->query($sql);

                        if ($res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                $fecha = date('d/m/Y h:i A', strtotime($row['FechaHora']));
                                
                                // Estilos de etiquetas
                                $badge = "bg-secondary";
                                if($row['EstatusCita'] == 'Confirmada') $badge = "bg-success";
                                if($row['EstatusCita'] == 'Pendiente') $badge = "bg-warning text-dark";
                                if($row['EstatusCita'] == 'Cancelada') $badge = "bg-danger";

                                // --- CORRECCIÓN AQUÍ: Preparamos los botones antes de imprimir ---
                                $btn_confirmar = "";
                                if($row['EstatusCita'] != 'Confirmada') {
                                    $btn_confirmar = "<a href='panel_doctor.php?accion=confirmar&id=".$row['ID']."' class='btn btn-success btn-sm me-1' title='Confirmar'><i class='bi bi-check-lg'></i></a>";
                                }

                                $btn_cancelar = "";
                                if($row['EstatusCita'] != 'Cancelada') {
                                    $btn_cancelar = "<a href='panel_doctor.php?accion=cancelar&id=".$row['ID']."' class='btn btn-outline-danger btn-sm' title='Cancelar' onclick='return confirm(\"¿Cancelar esta cita?\")'><i class='bi bi-x-lg'></i></a>";
                                }

                                echo "<tr>
                                    <td class='ps-4 fw-bold text-dark'>$fecha</td>
                                    <td><i class='bi bi-person'></i> ".$row['Paciente']."</td>
                                    <td>".$row['NombreServicio']."</td>
                                    <td><small class='text-muted'>".$row['NombreSucursal']."</small></td>
                                    <td><span class='badge $badge rounded-pill'>".$row['EstatusCita']."</span></td>
                                    <td class='text-center'>
                                        $btn_confirmar
                                        $btn_cancelar
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>No hay citas en la agenda para esta selección.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>