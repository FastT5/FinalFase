<?php
session_start();
include 'conexion.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) { header("Location: index.php"); exit(); }

$id = ""; $nombre = ""; $tipo = ""; $costo = ""; $desc = ""; $foto_actual = "";
$titulo = "Crear Servicio";

// MODO EDICIÓN: CARGAR DATOS
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $titulo = "Editar Servicio";
    $row = $conn->query("SELECT * FROM Servicios WHERE ID = $id")->fetch_assoc();
    $nombre = $row['NombreServicio']; $tipo = $row['TipoServicio']; 
    $costo = $row['Costo']; $desc = $row['Descripcion'];
    $foto_actual = $row['Foto']; // Guardamos el nombre de la foto actual
}

// GUARDAR CAMBIOS (POST)
if (isset($_POST['guardar'])) {
    $nom = $_POST['nombre']; 
    $tip = $_POST['tipo']; 
    $cos = $_POST['costo']; 
    $des = $_POST['descripcion']; 
    $id_edit = $_POST['id'];
    
    // LOGICA DE IMAGEN
    $nombre_foto = $foto_actual; // Por defecto mantenemos la vieja
    
    if (!empty($_FILES['foto']['name'])) {
        $nombre_foto = $_FILES['foto']['name'];
        $temp = $_FILES['foto']['tmp_name'];
        move_uploaded_file($temp, "img/" . $nombre_foto);
    } else {
        // Si es nuevo y no subió foto, asignamos una por defecto según el tipo
        if ($id_edit == "") {
            if($tip == 'Odontología Estética') $nombre_foto = "estetica.jpg";
            elseif($tip == 'Especialidades') $nombre_foto = "especialidades.jpg";
            else $nombre_foto = "general.jpg";
        }
    }

    if ($id_edit != "") {
        // UPDATE
        $sql = "UPDATE Servicios SET NombreServicio='$nom', TipoServicio='$tip', Costo='$cos', Descripcion='$des', Foto='$nombre_foto' WHERE ID=$id_edit";
    } else {
        // INSERT
        $sql = "INSERT INTO Servicios (NombreServicio, TipoServicio, Costo, Descripcion, Foto, Estatus) VALUES ('$nom', '$tip', '$cos', '$des', '$nombre_foto', 'Habilitada')";
    }
    
    if ($conn->query($sql)) header("Location: admin_servicios.php");
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
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white"><h4><?php echo $titulo; ?></h4></div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="guardar" value="1">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipo</label>
                                    <select name="tipo" class="form-select">
                                        <option value="Odontología General" <?php if($tipo=="Odontología General") echo "selected"; ?>>Odontología General</option>
                                        <option value="Odontología Estética" <?php if($tipo=="Odontología Estética") echo "selected"; ?>>Odontología Estética</option>
                                        <option value="Especialidades" <?php if($tipo=="Especialidades") echo "selected"; ?>>Especialidades</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Costo ($)</label>
                                <input type="number" step="0.01" name="costo" class="form-control" value="<?php echo $costo; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3"><?php echo $desc; ?></textarea>
                            </div>

                            <div class="mb-3 p-3 bg-light border rounded">
                                <label class="form-label fw-bold">Imagen del Servicio</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <?php if($foto_actual != ""): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Imagen actual:</small><br>
                                        <img src="img/<?php echo $foto_actual; ?>" width="100" class="rounded border">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="admin_servicios.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-success fw-bold">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>