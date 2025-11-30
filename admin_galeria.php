<?php
session_start();
include 'conexion.php';

// SEGURIDAD: Solo Admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) { header("Location: index.php"); exit(); }

// LÓGICA PARA SUBIR IMÁGENES
if (isset($_POST['agregar'])) {
    $titulo = $_POST['titulo'];
    
    // Procesar Foto ANTES
    $nombreA = $_FILES['antes']['name'];
    $tempA = $_FILES['antes']['tmp_name'];
    $destinoA = "img/" . $nombreA;
    move_uploaded_file($tempA, $destinoA);

    // Procesar Foto DESPUÉS
    $nombreD = $_FILES['despues']['name'];
    $tempD = $_FILES['despues']['tmp_name'];
    $destinoD = "img/" . $nombreD;
    move_uploaded_file($tempD, $destinoD);

    // Guardar en Base de Datos
    $conn->query("INSERT INTO Galeria (Titulo, FotoAntes, FotoDespues, Fecha) VALUES ('$titulo', '$nombreA', '$nombreD', NOW())");
    header("Location: admin_galeria.php");
}

// ELIMINAR CASO
if (isset($_GET['borrar'])) {
    $conn->query("DELETE FROM Galeria WHERE ID=".$_GET['borrar']);
    header("Location: admin_galeria.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <a href="admin.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
        
        <div class="card shadow mb-4 p-4">
            <h4 class="mb-3 text-warning fw-bold"><i class="bi bi-images"></i> Subir Nuevo Caso de Éxito</h4>
            
            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="agregar" value="1">
                
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nombre del Paciente</label>
                    <input type="text" name="titulo" class="form-control" placeholder="Ej. Juan Pérez" required>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-bold">Foto Antes</label>
                    <input type="file" name="antes" class="form-control" accept="image/*" required>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-bold">Foto Después</label>
                    <input type="file" name="despues" class="form-control" accept="image/*" required>
                </div>
                
                <div class="col-12">
                    <button class="btn btn-warning w-100 fw-bold mt-2">PUBLICAR CASO</button>
                </div>
            </form>
        </div>

        <h4 class="mb-3">Casos Publicados</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            $res = $conn->query("SELECT * FROM Galeria ORDER BY ID DESC");
            if ($res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    echo '
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body text-center">
                                <h5 class="fw-bold mb-3">'.$row['Titulo'].'</h5>
                                <div class="d-flex justify-content-center gap-2 mb-3">
                                    <div style="width:48%">
                                        <img src="img/'.$row['FotoAntes'].'" class="img-fluid rounded border mb-1" style="height:100px; object-fit:cover; width:100%">
                                        <small class="text-muted d-block">Antes</small>
                                    </div>
                                    <div style="width:48%">
                                        <img src="img/'.$row['FotoDespues'].'" class="img-fluid rounded border mb-1" style="height:100px; object-fit:cover; width:100%">
                                        <small class="text-muted d-block">Después</small>
                                    </div>
                                </div>
                                <a href="admin_galeria.php?borrar='.$row['ID'].'" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm(\'¿Eliminar este caso?\')"><i class="bi bi-trash"></i> Eliminar</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-muted w-100 text-center">No hay casos subidos todavía.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>