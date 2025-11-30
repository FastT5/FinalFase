<?php
session_start();
include 'conexion.php';

$mensaje = "";

// ---------------------------------------------------------
// LÓGICA DE LOGIN Y REGISTRO (PHP)
// ---------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- REGISTRO ---
    if (isset($_POST['accion']) && $_POST['accion'] == 'registro') {
        $nombre = $_POST['nuevo_usuario'];
        $email = $_POST['nuevo_email'];
        $pass = $_POST['nueva_password'];
        $rol = 3; // Paciente por defecto

        // Validar correo
        $check = "SELECT * FROM Usuarios WHERE Correo = '$email'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            $mensaje = "<div class='alert alert-danger'>El correo ya está registrado.</div>";
        } else {
            $sql = "INSERT INTO Usuarios (Nombre, Correo, Contrasena, RolID) VALUES ('$nombre', '$email', '$pass', $rol)";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('¡Registro exitoso! Por favor inicia sesión.'); window.location.href='login.php';</script>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }
    }

    // --- LOGIN ---
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
            
            // REDIRECCIÓN SEGÚN ROL
            if($_SESSION['rol'] == 1) {
                header("Location: admin.php");        // Admin
            } elseif ($_SESSION['rol'] == 2) {
                header("Location: panel_doctor.php"); // Doctor
            } else {
                header("Location: index.php");        // Paciente
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
    <style>
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">DENTALIFE 🦷</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">HOME</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 85vh;">
        
        <?php if($mensaje != "") echo $mensaje; ?>

        <div class="row w-100 justify-content-center">
            
            <div class="col-md-8 col-lg-6 col-xl-5 shadow-lg rounded bg-white overflow-hidden p-0">
                
                <div class="p-5" id="loginForm">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">BIENVENIDO</h2>
                        <p class="text-muted small">Ingresa a tu cuenta DentaLife</p>
                    </div>
                    
                    <form action="login.php" method="POST">
                        <input type="hidden" name="accion" value="login">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Correo Electrónico</label>
                            <input type="email" class="form-control form-control-lg bg-light fs-6" name="usuario" placeholder="ejemplo@correo.com" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-secondary">Contraseña</label>
                            <input type="password" class="form-control form-control-lg bg-light fs-6" name="password" placeholder="********" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold mb-3">ENTRAR</button>
                    </form>
                    
                    <div class="text-center">
                        <small class="text-muted">¿No tienes cuenta? <a href="#" onclick="mostrarRegistro()" class="text-primary fw-bold text-decoration-none">Crea una aquí</a></small>
                    </div>
                </div>

                <div class="p-5 bg-light" id="registroForm" style="display: none;">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-success">CREAR CUENTA</h2>
                        <p class="text-muted small">Únete a nuestra comunidad</p>
                    </div>

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
                        
                        <button type="submit" class="btn btn-success w-100 btn-lg fw-bold mb-3">REGISTRARSE</button>
                    </form>
                    
                    <div class="text-center">
                        <small class="text-muted">¿Ya tienes cuenta? <a href="#" onclick="mostrarLogin()" class="text-success fw-bold text-decoration-none">Inicia sesión</a></small>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="m-0 small">&copy; 2025 DentaLife - Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>