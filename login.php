<?php
session_start();
include 'conexion.php';

$mensaje = "";

// PROCESAR FORMULARIOS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- LÓGICA DE REGISTRO (Crea Pacientes por defecto) ---
    if (isset($_POST['accion']) && $_POST['accion'] == 'registro') {
        $nombre = $_POST['nuevo_usuario'];
        $email = $_POST['nuevo_email'];
        $pass = $_POST['nueva_password'];
        $rol = 3; // Paciente

        $check = "SELECT * FROM Usuarios WHERE Correo = '$email'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            $mensaje = "<div class='alert alert-danger'>Ese correo ya está registrado.</div>";
        } else {
            $sql = "INSERT INTO Usuarios (Nombre, Correo, Contrasena, RolID) VALUES ('$nombre', '$email', '$pass', $rol)";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('¡Registro exitoso! Por favor inicia sesión.'); window.location.href='login.php';</script>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al registrar: " . $conn->error . "</div>";
            }
        }
    }

    // --- LÓGICA DE LOGIN (Redirección Inteligente) ---
    if (isset($_POST['accion']) && $_POST['accion'] == 'login') {
        $correo = $_POST['usuario']; 
        $pass = $_POST['password'];

        $sql = "SELECT * FROM Usuarios WHERE Correo = '$correo' AND Contrasena = '$pass'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $fila = $result->fetch_assoc();
            $_SESSION['usuario_id'] = $fila['ID'];
            $_SESSION['nombre'] = $fila['Nombre'];
            $_SESSION['rol'] = $fila['RolID'];
            
            // --- AQUÍ ESTÁ EL CAMBIO CLAVE ---
            if($_SESSION['rol'] == 1) {
                header("Location: admin.php");        // Admin -> Panel Admin
            } elseif ($_SESSION['rol'] == 2) {
                header("Location: panel_doctor.php"); // Doctor -> Panel Doctor (NUEVO)
            } else {
                header("Location: index.php");        // Paciente -> Home
            }
            exit();
        } else {
            $mensaje = "<div class='alert alert-danger'>Correo o contraseña incorrectos.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">DENTALIFE 🦷</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
        
        <?php if($mensaje != "") echo $mensaje; ?>

        <div class="row w-100 shadow-lg rounded overflow-hidden">

            <div class="col-md-6 bg-white p-5" id="loginForm">
                <h2 class="fw-bold text-center mb-4">LOGIN</h2>
                <form action="login.php" method="POST">
                    <input type="hidden" name="accion" value="login">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="usuario" placeholder="ejemplo@correo.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">ENTRAR</button>
                </form>
                <p class="mt-3 text-center">¿No tienes cuenta? <a href="#" onclick="mostrarRegistro()">Crea una</a></p>
            </div>

            <div class="col-md-6 bg-light p-5 border-start" id="registroForm" style="display: none;">
                <h2 class="fw-bold text-center mb-4">REGISTRARSE</h2>
                <form action="login.php" method="POST">
                    <input type="hidden" name="accion" value="registro">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="nuevo_usuario" placeholder="Nombre Completo" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="nuevo_email" placeholder="Correo Electrónico" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="nueva_password" placeholder="Contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100">REGISTRARSE</button>
                </form>
                <p class="mt-3 text-center">¿Ya tienes cuenta? <a href="#" onclick="mostrarLogin()">Inicia sesión</a></p>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 DentaLife - Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>