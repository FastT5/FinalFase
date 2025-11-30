<?php
session_start();
include 'conexion.php';

// Solo Doctores
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) { header("Location: login.php"); exit(); }

// ASIGNAR CITA (CONFIRMAR)
if (isset($_POST['asignar_cita'])) {
    $solicitud_id = $_POST['solicitud_id'];
    $fecha_final = $_POST['fecha_final']; // La fecha que el doctor aprobó
    $doctor_id = $_SESSION['usuario_id'];
    
    // Traemos datos originales
    $datos = $conn->query("SELECT * FROM Solicitudes WHERE ID = $solicitud_id")->fetch_assoc();
    
    // Insertamos en CITAS REALES
    $sql_insert = "INSERT INTO Citas (FechaHora, EstatusCita, PacienteID, EmpleadoID, ServicioID) 
                   VALUES ('$fecha_final', 'Pendiente', '".$datos['PacienteID']."', '$doctor_id', '".$datos['ServicioID']."')";
    
    if ($conn->query($sql_insert)) {
        // Marcamos solicitud como procesada
        $conn->query("UPDATE Solicitudes SET Estatus='Asignada' WHERE ID=$solicitud_id");
        echo "<script>alert('¡Cita confirmada para el ".$fecha_final."!'); window.location.href='panel_doctor.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">👨‍⚕️ Panel Dr. <?php echo $_SESSION['nombre']; ?></a>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container py-5">
        <h3 class="mb-4 text-secondary">Solicitudes de Citas</h3>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Paciente</th>
                            <th>Contacto</th>
                            <th>Servicio Solicitado</th>
                            <th>Fecha Solicitada</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT Solicitudes.*, Usuarios.Nombre as Paciente, Servicios.NombreServicio 
                                FROM Solicitudes 
                                JOIN Usuarios ON Solicitudes.PacienteID = Usuarios.ID
                                JOIN Servicios ON Solicitudes.ServicioID = Servicios.ID
                                WHERE Solicitudes.Estatus = 'Pendiente'
                                ORDER BY FechaSolicitada ASC";
                        $res = $conn->query($sql);

                        if ($res->num_rows > 0) {
                            while($row = $res->fetch_assoc()) {
                                // Formatear fecha para que el input la entienda (YYYY-MM-DDTHH:MM)
                                $fecha_input = date('Y-m-d\TH:i', strtotime($row['FechaSolicitada']));
                                
                                echo "<tr>
                                    <td class='fw-bold'>".$row['Paciente']."</td>
                                    <td>".$row['TelefonoContacto']."</td>
                                    <td><span class='badge bg-info text-dark'>".$row['NombreServicio']."</span></td>
                                    <td>
                                        <span class='text-primary fw-bold'>".date('d/M/Y h:i A', strtotime($row['FechaSolicitada']))."</span>
                                    </td>
                                    <td>
                                        <form method='POST' class='d-flex gap-2 align-items-center'>
                                            <input type='hidden' name='solicitud_id' value='".$row['ID']."'>
                                            
                                            <input type='datetime-local' name='fecha_final' class='form-control form-control-sm' 
                                                   value='".$fecha_input."' required>
                                            
                                            <button type='submit' name='asignar_cita' class='btn btn-success btn-sm fw-bold'>CONFIRMAR</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>No tienes solicitudes pendientes.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>