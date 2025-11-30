<?php
session_start();
include 'conexion.php';

$mensaje = "";

// ---------------------------------------------------------
// LÓGICA DE LOGIN Y REGISTRO
// ---------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- REGISTRO (Crea Paciente - Rol 3) ---
    if (isset($_POST['accion']) && $_POST['accion'] == 'registro') {
        $nombre = $_POST['nuevo_usuario'];
        $email = $_POST['nuevo_email'];
        $pass = $_POST['nueva_password'];
        $rol = 3; // Rol de Paciente por defecto

        // 1. Validar si ya existe el correo
        $check = "SELECT * FROM Usuarios WHERE Correo = '$email'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            $mensaje = "<div class='alert alert-danger text-center'>Ese correo ya está registrado.</div>";
        } else {
            // 2. Insertar nuevo usuario
            $sql = "INSERT INTO Usuarios (Nombre, Correo, Contrasena, RolID) VALUES ('$nombre', '$email', '$pass', $rol)";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('¡Registro exitoso! Por favor inicia sesión.'); window.location.href='login.php';</script>";
            } else {
                $mensaje = "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
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
            $mensaje = "<div class='alert alert-danger text-center'>Correo o contraseña incorrectos.</div>";
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
        body { background-color: #f0f2f5; } /* Fondo suave */
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
        
        <div class="w-100 d-flex justify-content-center">
            <div class="col-md-6">
                <?php if($mensaje != "") echo $mensaje; ?>
            </div>
        </div>

        <div class="row w-100 justify-content-center">
            <div class="col-md-8 col-lg-5 col-xl-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    
                    <div class="card-body p-5" id="loginForm">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary">Bienvenido</h2>
                            <p class="text-muted small">Ingresa a tu cuenta DentaLife</p>
                        </div>
                        
                        <form action="login.php" method="POST">
                            <input type="hidden" name="accion" value="login">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-lg bg-light fs-6" name="usuario" placeholder="nombre@correo.com" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary">Contraseña</label>
                                <input type="password" class="form-control form-control-lg bg-light fs-6" name="password" placeholder="********" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold mb-3 shadow-sm">ENTRAR</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p class="small text-muted mb-0">¿No tienes cuenta?</p>
                            <a href="#" onclick="mostrarRegistro()" class="text-primary fw-bold text-decoration-none">Crear una cuenta nueva</a>
                        </div>
                    </div>

                    <div class="card-body p-5 bg-light" id="registroForm" style="display: none;">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-success">Registrarse</h2>
                            <p class="text-muted small">Únete a nuestra familia dental</p>
                        </div>

                        <form action="login.php" method="POST">
                            <input type="hidden" name="accion" value="registro">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">Nombre Completo</label>
                                <input type="text" class="form-control" name="nuevo_usuario" placeholder="Ej. Juan Pérez" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">Correo Electrónico</label>
                                <input type="email" class="form-control" name="nuevo_email" placeholder="nombre@correo.com" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary">Contraseña</label>
                                <input type="password" class="form-control" name="nueva_password" placeholder="Crea una contraseña" required>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 btn-lg fw-bold mb-3 shadow-sm">REGISTRARSE</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p class="small text-muted mb-0">¿Ya tienes cuenta?</p>
                            <a href="#" onclick="mostrarLogin()" class="text-success fw-bold text-decoration-none">Iniciar Sesión</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="m-0 small">&copy; 2025 DentaLife - Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function mostrarRegistro() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registroForm').style.display = 'block';
        }

        function mostrarLogin() {
            document.getElementById('registroForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        }
    </script>
</body>
</html>