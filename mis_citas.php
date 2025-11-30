<?php
session_start();
include 'conexion.php';

// 1. Solo Pacientes (Rol 3)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: login.php");
    exit();
}

$id_paciente = $_SESSION['usuario_id'];

// 2. Lógica para CANCELAR
if (isset($_POST['cancelar_solicitud'])) {
    $id_sol = $_POST['id_solicitud'];
    $conn->query("UPDATE Solicitudes SET Estatus = 'Cancelada' WHERE ID = $id_sol");
    header("Location: mis_citas.php");
}

if (isset($_POST['cancelar_cita'])) {
    $id_cita = $_POST['id_cita'];
    $conn->query("UPDATE Citas SET EstatusCita = 'Cancelada' WHERE ID = $id_cita");
    header("Location: mis_citas.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Citas - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">DENTALIFE 🦷</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-light btn-sm">Volver al Inicio</a>
                <a href="logout.php" class="btn btn-danger btn-sm ms-2">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4 fw-bold text-primary"><i class="bi bi-calendar-check"></i> Mi Panel de Citas</h2>

        <div class="row">
            
            <div class="col-lg-6 mb-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="bi bi-hourglass-split"></i> En Lista de Espera
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Solicitudes enviadas esperando confirmación.</p>
                        
                        <?php
                        // Traemos Solicitudes Pendientes + Nombre de Sucursal
                        $sql_sol = "SELECT Solicitudes.*, Servicios.NombreServicio, Sucursales.NombreSucursal 
                                    FROM Solicitudes 
                                    JOIN Servicios ON Solicitudes.ServicioID = Servicios.ID
                                    JOIN Sucursales ON Solicitudes.SucursalID = Sucursales.ID
                                    WHERE PacienteID = $id_paciente AND Solicitudes.Estatus = 'Pendiente'
                                    ORDER BY FechaSolicitada ASC";
                        $res_sol = $conn->query($sql_sol);

                        if ($res_sol && $res_sol->num_rows > 0) {
                            while($row = $res_sol->fetch_assoc()) {
                                $fecha = date('d/M/Y h:i A', strtotime($row['FechaSolicitada']));
                                echo '
                                <div class="alert alert-warning">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-bold mb-1">'.$row['NombreServicio'].'</h6>
                                        <form method="POST">
                                            <input type="hidden" name="id_solicitud" value="'.$row['ID'].'">
                                            <button type="submit" name="cancelar_solicitud" class="btn btn-sm text-danger border-0 p-0" title="Cancelar Solicitud" onclick="return confirm(\'¿Cancelar?\')"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    </div>
                                    <small class="d-block"><i class="bi bi-clock"></i> '.$fecha.'</small>
                                    <small class="d-block text-muted"><i class="bi bi-geo-alt"></i> '.$row['NombreSucursal'].'</small>
                                </div>';
                            }
                        } else {
                            echo '<div class="text-center text-muted py-3">No tienes solicitudes pendientes.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="bi bi-check-circle"></i> Agenda Confirmada
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">¡Ya tienes fecha! Por favor asiste puntual.</p>

                        <?php
                        // CORRECCIÓN AQUÍ: Mostrar tanto 'Pendiente' como 'Confirmada'
                        $sql_citas = "SELECT Citas.*, Servicios.NombreServicio, Usuarios.Nombre as Doctor, Sucursales.NombreSucursal
                                      FROM Citas 
                                      JOIN Servicios ON Citas.ServicioID = Servicios.ID
                                      JOIN Usuarios ON Citas.EmpleadoID = Usuarios.ID
                                      JOIN Sucursales ON Citas.SucursalID = Sucursales.ID
                                      WHERE PacienteID = $id_paciente 
                                      AND (EstatusCita = 'Pendiente' OR EstatusCita = 'Confirmada')
                                      ORDER BY FechaHora ASC";
                        
                        $res_citas = $conn->query($sql_citas);

                        if ($res_citas && $res_citas->num_rows > 0) {
                            while($cita = $res_citas->fetch_assoc()) {
                                $fecha = date('d/M/Y h:i A', strtotime($cita['FechaHora']));
                                echo '
                                <div class="card mb-3 border-success shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title text-success fw-bold">'.$cita['NombreServicio'].'</h5>
                                            <span class="badge bg-success">Confirmada</span>
                                        </div>
                                        <p class="card-text mb-1"><i class="bi bi-calendar-event"></i> <strong>'.$fecha.'</strong></p>
                                        <p class="card-text mb-1 small"><i class="bi bi-geo-alt-fill"></i> '.$cita['NombreSucursal'].'</p>
                                        <p class="card-text mb-2 small text-muted"><i class="bi bi-person-badge"></i> Dr. '.$cita['Doctor'].'</p>
                                        
                                        <form method="POST" class="text-end">
                                            <input type="hidden" name="id_cita" value="'.$cita['ID'].'">
                                            <button type="submit" name="cancelar_cita" class="btn btn-outline-danger btn-sm" onclick="return confirm(\'¿Seguro que deseas cancelar esta cita confirmada?\')">
                                                Cancelar Cita
                                            </button>
                                        </form>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '<div class="text-center text-muted py-3">No tienes citas programadas. <br><a href="servicios.php" class="fw-bold text-success">¡Agenda una aquí!</a></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div> <div class="mt-5">
            <h4 class="text-secondary border-bottom pb-2">Historial / Canceladas</h4>
            <div class="table-responsive">
                <table class="table table-striped text-muted">
                    <thead><tr><th>Fecha</th><th>Servicio</th><th>Estado</th></tr></thead>
                    <tbody>
                        <?php
                        $sql_hist = "SELECT Citas.FechaHora, Citas.EstatusCita, Servicios.NombreServicio 
                                     FROM Citas JOIN Servicios ON Citas.ServicioID = Servicios.ID
                                     WHERE PacienteID = $id_paciente AND (EstatusCita = 'Cancelada' OR EstatusCita = 'Completada')
                                     UNION
                                     SELECT Solicitudes.FechaSolicitada, Solicitudes.Estatus, Servicios.NombreServicio
                                     FROM Solicitudes JOIN Servicios ON Solicitudes.ServicioID = Servicios.ID
                                     WHERE PacienteID = $id_paciente AND (Solicitudes.Estatus = 'Cancelada' OR Solicitudes.Estatus = 'Rechazada')
                                     ORDER BY 1 DESC LIMIT 5";
                        $res_hist = $conn->query($sql_hist);
                        
                        if($res_hist && $res_hist->num_rows > 0) {
                            while($hist = $res_hist->fetch_assoc()) {
                                echo "<tr>
                                    <td>".date('d/m/Y', strtotime($hist['FechaHora']))."</td>
                                    <td>".$hist['NombreServicio']."</td>
                                    <td>".$hist['EstatusCita']."</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>Historial vacío.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>