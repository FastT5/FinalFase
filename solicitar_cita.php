<?php
session_start();
include 'conexion.php';

// Solo Pacientes
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) { header("Location: login.php"); exit(); }

$servicio_preseleccionado = isset($_GET['servicio_id']) ? $_GET['servicio_id'] : '';

// PROCESAR SOLICITUD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente_id = $_SESSION['usuario_id'];
    $servicio_id = $_POST['servicio'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha']; // Ahora recibimos fecha y hora exacta

    // Insertamos la FECHA EXACTA que pidió el paciente
    $sql = "INSERT INTO Solicitudes (PacienteID, ServicioID, TelefonoContacto, FechaSolicitada) 
            VALUES ('$paciente_id', '$servicio_id', '$telefono', '$fecha')";
    
    if ($conn->query($sql)) {
        echo "<script>alert('¡Solicitud enviada! El doctor revisará la fecha seleccionada y la confirmará.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error al enviar solicitud.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">DENTALIFE 🦷</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Volver</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="text-center mb-3 text-primary">Agenda tu Cita</h3>
                    <p class="text-muted text-center small mb-4">Elige el día y la hora que prefieras.</p>
                    
                    <form method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tratamiento</label>
                            <select name="servicio" class="form-select" required>
                                <option value="">Selecciona...</option>
                                <?php
                                $servs = $conn->query("SELECT * FROM Servicios WHERE Estatus='Habilitada'");
                                while($row = $servs->fetch_assoc()) {
                                    $selected = ($row['ID'] == $servicio_preseleccionado) ? 'selected' : '';
                                    echo "<option value='".$row['ID']."' $selected>".$row['NombreServicio']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Teléfono de Contacto</label>
                            <input type="tel" name="telefono" class="form-control" placeholder="Ej: 811 555 9999" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha y Hora Deseada</label>
                            <input type="datetime-local" name="fecha" class="form-control" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                            <div class="form-text">Sujeto a confirmación por el doctor.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">SOLICITAR ESTA FECHA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>