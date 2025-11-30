<?php
session_start();
include 'conexion.php';

// Seguridad: Solo Empleados/Doctores (Rol 2)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) { header("Location: login.php"); exit(); }

// ---------------------------------------------------------
// 1. LÓGICA: CONFIRMAR SOLICITUD (De Solicitudes -> Citas)
// ---------------------------------------------------------
if (isset($_POST['confirmar_solicitud'])) {
    $id_solicitud = $_POST['id_solicitud'];
    $doctor_id = $_SESSION['usuario_id'];

    // a) Obtenemos los datos de la solicitud
    $sql_get = "SELECT * FROM Solicitudes WHERE ID = $id_solicitud";
    $solicitud = $conn->query($sql_get)->fetch_assoc();

    // b) Insertamos en la tabla REAL de CITAS
    $sql_insert = "INSERT INTO Citas (FechaHora, EstatusCita, MontoPagado, PacienteID, EmpleadoID, ServicioID, SucursalID) 
                   VALUES ('".$solicitud['FechaSolicitada']."', 'Confirmada', 0, '".$solicitud['PacienteID']."', '$doctor_id', '".$solicitud['ServicioID']."', '".$solicitud['SucursalID']."')";
    
    if ($conn->query($sql_insert)) {
        // c) Marcamos la solicitud como 'Procesada' para que ya no salga
        $conn->query("UPDATE Solicitudes SET Estatus = 'Asignada' WHERE ID = $id_solicitud");
        echo "<script>alert('¡Cita confirmada y agendada!'); window.location.href='panel_doctor.php';</script>";
    } else {
        echo "<script>alert('Error al confirmar: " . $conn->error . "');</script>";
    }
}

// ---------------------------------------------------------
// 2. LÓGICA: RECHAZAR SOLICITUD
// ---------------------------------------------------------
if (isset($_POST['rechazar_solicitud'])) {
    $id_solicitud = $_POST['id_solicitud'];
    $conn->query("UPDATE Solicitudes SET Estatus = 'Rechazada' WHERE ID = $id_solicitud");
    header("Location: panel_doctor.php");
}

// ---------------------------------------------------------
// 3. LÓGICA: FILTROS DE AGENDA (Tabla de abajo)
// ---------------------------------------------------------
$sucursal_id = isset($_GET['sucursal']) ? $_GET['sucursal'] : '';
$filtro_sql = "";
if ($sucursal_id != "") {
    $filtro_sql = " AND Citas.SucursalID = $sucursal_id";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Médico - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success sticky-top shadow px-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-hospital-fill"></i> Panel Dr. <?php echo $_SESSION['nombre']; ?></a>
            <div class="d-flex gap-3 align-items-center text-white">
                <a href="empleado_agendar.php" class="btn btn-light btn-sm text-success fw-bold"><i class="bi bi-plus-circle"></i> Agendar Manual</a>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        
        <h4 class="mb-3 text-warning fw-bold"><i class="bi bi-bell-fill"></i> Solicitudes Web Pendientes</h4>
        <div class="card shadow-sm border-warning mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-warning">
                            <tr>
                                <th class="ps-3">Paciente</th>
                                <th>Servicio</th>
                                <th>Fecha Solicitada</th>
                                <th>Sucursal</th>
                                <th>Contacto</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Buscamos en la tabla SOLICITUDES
                            $sql_pend = "SELECT Solicitudes.*, Usuarios.Nombre as Paciente, Servicios.NombreServicio, Sucursales.NombreSucursal 
                                         FROM Solicitudes 
                                         JOIN Usuarios ON Solicitudes.PacienteID = Usuarios.ID 
                                         JOIN Servicios ON Solicitudes.ServicioID = Servicios.ID
                                         JOIN Sucursales ON Solicitudes.SucursalID = Sucursales.ID
                                         WHERE Solicitudes.Estatus = 'Pendiente' 
                                         ORDER BY FechaSolicitada ASC";
                            $res_pend = $conn->query($sql_pend);

                            if ($res_pend->num_rows > 0) {
                                while($row = $res_pend->fetch_assoc()) {
                                    $fecha = date('d/M/Y h:i A', strtotime($row['FechaSolicitada']));
                                    echo "<tr>
                                        <td class='ps-3 fw-bold'>".$row['Paciente']."</td>
                                        <td>".$row['NombreServicio']."</td>
                                        <td class='text-primary fw-bold'>$fecha</td>
                                        <td><small class='badge bg-light text-dark border'>".$row['NombreSucursal']."</small></td>
                                        <td>".$row['TelefonoContacto']."</td>
                                        <td class='text-center'>
                                            <form method='POST' class='d-flex gap-2 justify-content-center'>
                                                <input type='hidden' name='id_solicitud' value='".$row['ID']."'>
                                                <button type='submit' name='confirmar_solicitud' class='btn btn-success btn-sm' title='Aprobar'><i class='bi bi-check-lg'></i></button>
                                                <button type='submit' name='rechazar_solicitud' class='btn btn-outline-danger btn-sm' title='Rechazar' onclick='return confirm(\"¿Rechazar solicitud?\")'><i class='bi bi-x-lg'></i></button>
                                            </form>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No hay solicitudes nuevas de internet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-between align-items-end mb-3">
            <h4 class="text-secondary fw-bold m-0"><i class="bi bi-calendar-check-fill"></i> Agenda Confirmada</h4>
            
            <form class="d-flex align-items-center gap-2" method="GET">
                <select name="sucursal" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Todas las Sucursales</option>
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

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Fecha/Hora</th>
                            <th>Paciente</th>
                            <th>Servicio</th>
                            <th>Sucursal</th>
                            <th>Doctor</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Buscamos en la tabla CITAS
                        $sql = "SELECT Citas.*, P.Nombre as Paciente, D.Nombre as Doctor, S.NombreServicio, Suc.NombreSucursal
                                FROM Citas
                                JOIN Usuarios P ON Citas.PacienteID = P.ID
                                JOIN Usuarios D ON Citas.EmpleadoID = D.ID
                                JOIN Servicios S ON Citas.ServicioID = S.ID
                                JOIN Sucursales Suc ON Citas.SucursalID = Suc.ID
                                WHERE 1=1 $filtro_sql
                                ORDER BY Citas.FechaHora DESC";
                        
                        $res = $conn->query($sql);

                        if ($res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                $fecha = date('d/m/Y h:i A', strtotime($row['FechaHora']));
                                $badge = ($row['EstatusCita'] == 'Confirmada') ? 'bg-success' : 'bg-secondary';

                                echo "<tr>
                                    <td class='ps-3 fw-bold'>$fecha</td>
                                    <td>".$row['Paciente']."</td>
                                    <td>".$row['NombreServicio']."</td>
                                    <td>".$row['NombreSucursal']."</td>
                                    <td>Dr. ".$row['Doctor']."</td>
                                    <td><span class='badge $badge'>".$row['EstatusCita']."</span></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Agenda vacía.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>