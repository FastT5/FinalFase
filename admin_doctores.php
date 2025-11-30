<?php
session_start();
include 'conexion.php';

// Seguridad
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) { header("Location: index.php"); exit(); }

// VARIABLES INICIALES (Vacías para modo "Crear")
$id_edit = "";
$nombre = "";
$correo = "";
$especialidad = "";
$foto_actual = "";
$titulo_form = "Registrar Nuevo Doctor";
$btn_texto = "AGREGAR AL EQUIPO";
$btn_color = "btn-success";

// ---------------------------------------------------------
// 1. LÓGICA DE CARGAR DATOS PARA EDITAR (Cuando das clic al Lápiz)
// ---------------------------------------------------------
if (isset($_GET['editar'])) {
    $id_edit = $_GET['editar'];
    $titulo_form = "Editar Doctor";
    $btn_texto = "GUARDAR CAMBIOS";
    $btn_color = "btn-warning"; // Cambia color para que sepas que editas

    $sql = "SELECT * FROM Usuarios WHERE ID = $id_edit";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    $nombre = $row['Nombre'];
    $correo = $row['Correo'];
    $especialidad = $row['Especialidad'];
    $foto_actual = $row['Foto'];
}

// ---------------------------------------------------------
// 2. LÓGICA DE GUARDAR (CREAR O ACTUALIZAR)
// ---------------------------------------------------------
if (isset($_POST['guardar'])) {
    $id = $_POST['id_edit']; // ID oculto
    $nom = $_POST['nombre'];
    $cor = $_POST['correo'];
    $pass = $_POST['pass'];
    $esp = $_POST['especialidad'];
    
    // Foto
    $foto = ($id != "") ? $_POST['foto_actual'] : "doctor1.jpg"; // Mantener o Default
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "img/" . $foto);
    }

    if ($id != "") {
        // --- ACTUALIZAR ---
        $sql_pass = (!empty($pass)) ? ", Contrasena='$pass'" : ""; // Solo cambia pass si escribió una
        $sql = "UPDATE Usuarios SET Nombre='$nom', Correo='$cor', Especialidad='$esp', Foto='$foto' $sql_pass WHERE ID=$id";
    } else {
        // --- CREAR ---
        $pass = !empty($pass) ? $pass : "doctor123";
        $sql = "INSERT INTO Usuarios (Nombre, Correo, Contrasena, RolID, Especialidad, Foto) VALUES ('$nom', '$cor', '$pass', 2, '$esp', '$foto')";
    }

    if ($conn->query($sql)) {
        header("Location: admin_doctores.php"); // Limpiar formulario
    }
}

// ---------------------------------------------------------
// 3. ELIMINAR
// ---------------------------------------------------------
if (isset($_GET['borrar'])) {
    $conn->query("DELETE FROM Usuarios WHERE ID=".$_GET['borrar']);
    header("Location: admin_doctores.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Doctores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <a href="admin.php" class="btn btn-secondary mb-4">Volver</a>
        
        <div class="row">
            
            <div class="col-md-4 mb-4">
                <div class="card shadow p-4 sticky-top" style="top: 20px;">
                    <h5 class="mb-3 fw-bold <?php echo ($id_edit != "") ? 'text-warning' : 'text-success'; ?>">
                        <?php echo $titulo_form; ?>
                    </h5>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="guardar" value="1">
                        <input type="hidden" name="id_edit" value="<?php echo $id_edit; ?>">
                        <input type="hidden" name="foto_actual" value="<?php echo $foto_actual; ?>">
                        
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Correo</label>
                            <input type="email" name="correo" class="form-control" value="<?php echo $correo; ?>" required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Contraseña</label>
                            <input type="password" name="pass" class="form-control" placeholder="<?php echo ($id_edit != "") ? '(Dejar vacío para no cambiar)' : ''; ?>" <?php echo ($id_edit == "") ? 'required' : ''; ?>>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Especialidad</label>
                            <input type="text" name="especialidad" class="form-control" value="<?php echo $especialidad; ?>" placeholder="Ej. Ortodoncia" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Foto de Perfil</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <?php if($foto_actual != ""): ?>
                                <small class="text-muted">Actual: <?php echo $foto_actual; ?></small>
                            <?php endif; ?>
                        </div>

                        <button class="btn <?php echo $btn_color; ?> w-100 fw-bold"><?php echo $btn_texto; ?></button>
                        
                        <?php if($id_edit != ""): ?>
                            <a href="admin_doctores.php" class="btn btn-outline-secondary w-100 mt-2">Cancelar Edición</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h5 class="m-0 fw-bold">Equipo Médico Actual</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Foto</th>
                                    <th>Nombre</th>
                                    <th>Especialidad</th>
                                    <th class="text-end pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT * FROM Usuarios WHERE RolID=2");
                                if ($res->num_rows > 0) {
                                    while($doc = $res->fetch_assoc()) {
                                        $imgDoc = !empty($doc['Foto']) ? $doc['Foto'] : 'doctor1.jpg';
                                        
                                        // Resaltar la fila si se está editando
                                        $clase_fila = ($doc['ID'] == $id_edit) ? "table-warning" : "";

                                        echo "<tr class='$clase_fila'>
                                            <td class='ps-4'>
                                                <img src='img/$imgDoc' class='rounded-circle border' width='50' height='50' style='object-fit:cover'>
                                            </td>
                                            <td class='fw-bold'>Dr. ".$doc['Nombre']."</td>
                                            <td class='text-muted'>".$doc['Especialidad']."</td>
                                            <td class='text-end pe-4'>
                                                
                                                <a href='admin_doctores.php?editar=".$doc['ID']."' 
                                                   class='btn btn-outline-primary btn-sm me-1' 
                                                   title='Editar'>
                                                   <i class='bi bi-pencil-square'></i>
                                                </a>

                                                <a href='admin_doctores.php?borrar=".$doc['ID']."' 
                                                   class='btn btn-outline-danger btn-sm' 
                                                   onclick='return confirm(\"¿Eliminar a este doctor?\")' 
                                                   title='Eliminar'>
                                                   <i class='bi bi-trash'></i>
                                                </a>

                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center py-4'>No hay doctores registrados.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>