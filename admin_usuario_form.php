<?php
session_start();
include 'conexion.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) { header("Location: index.php"); exit(); }

$id = ""; $nombre = ""; $correo = ""; $rol_id = ""; $especialidad = ""; $foto_actual = "";
$titulo = "Registrar Nuevo Usuario";
$boton = "Crear Usuario";

// CARGAR DATOS SI ES EDICIÓN
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $titulo = "Editar Usuario";
    $boton = "Guardar Cambios";
    
    $sql = "SELECT * FROM Usuarios WHERE ID = $id";
    $row = $conn->query($sql)->fetch_assoc();
    
    $nombre = $row['Nombre'];
    $correo = $row['Correo'];
    $rol_id = $row['RolID'];
    $especialidad = $row['Especialidad'];
    $foto_actual = $row['Foto'];
}

// GUARDAR
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_edit = $_POST['id'];
    $nom = $_POST['nombre'];
    $cor = $_POST['correo'];
    $rol = $_POST['rol']; // <--- ROL SELECCIONADO
    $esp = $_POST['especialidad'];
    $pass = $_POST['password'];
    
    // Foto
    $foto = ($id_edit != "") ? $foto_actual : "doctor1.jpg";
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "img/" . $foto);
    }

    if ($id_edit != "") {
        // UPDATE
        $sql_pass = (!empty($pass)) ? ", Contrasena='$pass'" : "";
        $sql = "UPDATE Usuarios SET Nombre='$nom', Correo='$cor', RolID='$rol', Especialidad='$esp', Foto='$foto' $sql_pass WHERE ID=$id_edit";
    } else {
        // INSERT
        if(empty($pass)) $pass = "12345"; // Contraseña temporal
        $sql = "INSERT INTO Usuarios (Nombre, Correo, Contrasena, RolID, Especialidad, Foto) VALUES ('$nom', '$cor', '$pass', '$rol', '$esp', '$foto')";
    }

    if ($conn->query($sql)) header("Location: admin_usuarios.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="m-0"><?php echo $titulo; ?></h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Usuario (Rol)</label>
                                <select name="rol" class="form-select border-primary" required>
                                    <option value="">Selecciona...</option>
                                    <?php
                                    $roles = $conn->query("SELECT * FROM Roles");
                                    while($r = $roles->fetch_assoc()) {
                                        $sel = ($r['ID'] == $rol_id) ? 'selected' : '';
                                        echo "<option value='".$r['ID']."' $sel>".$r['NombreRol']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre Completo</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Correo Electrónico</label>
                                <input type="email" name="correo" class="form-control" value="<?php echo $correo; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Especialidad / Puesto</label>
                                <input type="text" name="especialidad" class="form-control" value="<?php echo $especialidad; ?>" placeholder="Solo para Doctores o Empleados">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="<?php echo ($id != "") ? '(Dejar vacío para mantener)' : 'Obligatoria para nuevos'; ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Foto</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <a href="admin_usuarios.php" class="btn btn-secondary w-50">Cancelar</a>
                                <button type="submit" class="btn btn-primary w-50 fw-bold"><?php echo $boton; ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>