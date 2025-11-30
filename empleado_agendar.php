<?php
session_start();
include 'conexion.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 2) { header("Location: login.php"); exit(); }

// GUARDAR CITA (INSERT)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente_id = $_POST['paciente']; // ID seleccionado del dropdown
    $servicio_id = $_POST['servicio'];
    $sucursal_id = $_POST['sucursal'];
    $fecha = $_POST['fecha'];
    $doctor_id = $_SESSION['usuario_id']; // El doctor que está logueado crea la cita

    // Creamos la cita DIRECTAMENTE como Confirmada
    $sql = "INSERT INTO Citas (FechaHora, EstatusCita, PacienteID, EmpleadoID, ServicioID, SucursalID) 
            VALUES ('$fecha', 'Confirmada', '$paciente_id', '$doctor_id', '$servicio_id', '$sucursal_id')";
    
    if ($conn->query($sql)) {
        echo "<script>alert('¡Cita agendada con éxito!'); window.location.href='panel_doctor.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar Cita - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="m-0 fw-bold">Nueva Cita (Mostrador)</h5>
                        <a href="panel_doctor.php" class="btn btn-sm btn-light text-primary fw-bold">Volver</a>
                    </div>
                    
                    <div class="card-body p-4">
                        <form method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">Seleccionar Paciente</label>
                                <select name="paciente" class="form-select border-success" required>
                                    <option value="">Busca en la lista...</option>
                                    <?php
                                    // Traemos solo usuarios con Rol 3 (Pacientes)
                                    $res = $conn->query("SELECT * FROM Usuarios WHERE RolID=3 ORDER BY Nombre ASC");
                                    while($row = $res->fetch_assoc()) {
                                        echo "<option value='".$row['ID']."'>".$row['Nombre']." (".$row['Correo'].")</option>";
                                    }
                                    ?>
                                </select>
                                <div class="form-text small">¿No aparece? Debe registrarse primero en el sistema.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Ubicación</label>
                                <select name="sucursal" class="form-select" required>
                                    <option value="">Selecciona Sucursal...</option>
                                    <?php
                                    $res = $conn->query("SELECT * FROM Sucursales");
                                    while($row = $res->fetch_assoc()) {
                                        echo "<option value='".$row['ID']."'>".$row['NombreSucursal']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tratamiento</label>
                                <select name="servicio" class="form-select" required>
                                    <option value="">Selecciona Servicio...</option>
                                    <?php
                                    $res = $conn->query("SELECT * FROM Servicios WHERE Estatus='Habilitada'");
                                    while($row = $res->fetch_assoc()) {
                                        echo "<option value='".$row['ID']."'>".$row['NombreServicio']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Fecha y Hora</label>
                                <input type="datetime-local" name="fecha" class="form-control" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">CONFIRMAR Y AGENDAR</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>