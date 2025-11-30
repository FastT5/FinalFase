<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo existe y tiene tus datos

$mensaje = "";

// PROCESAR FORMULARIOS
if ($_SERVER["REQ<?php
session_start();
include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // REGISTRO
    if (isset($_POST['accion']) && $_POST['accion'] == 'registro') {
        $nombre = $_POST['nuevo_usuario'];
        $email = $_POST['nuevo_email'];
        $pass = $_POST['nueva_password'];
        $rol = 3; // Paciente

        $check = "SELECT * FROM Usuarios WHERE Correo = '$email'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            $mensaje = "<div class='alert alert-danger'>Correo ya registrado.</div>";
        } else {
            $sql = "INSERT INTO Usuarios (Nombre, Correo, Contrasena, RolID) VALUES ('$nombre', '$email', '$pass', $rol)";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Registro exitoso. Inicia sesión.'); window.location.href='login.php';</script>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }
    }

    // LOGIN
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
            
            // REDIRECCIÓN POR ROL
            if($_SESSION['rol'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $mensaje = "<div class='alert alert-danger'>Datos incorrectos.</div>";
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
                    <div class="mb-3"><label>Correo</label><input type="email" class="form-control" name="usuario" required></div>
                    <div class="mb-3"><label>Contraseña</label><input type="password" class="form-control" name="password" required></div>
                    <button type="submit" class="btn btn-primary w-100">ENTRAR</button>
                </form>
                <p class="mt-3 text-center">¿No tienes cuenta? <a href="#" onclick="mostrarRegistro()">Crea una</a></p>
            </div>
            <div class="col-md-6 bg-light p-5 border-start" id="registroForm" style="display: none;">
                <h2 class="fw-bold text-center mb-4">REGISTRO</h2>
                <form action="login.php" method="POST">
                    <input type="hidden" name="accion" value="registro">
                    <div class="mb-3"><input type="text" class="form-control" name="nuevo_usuario" placeholder="Nombre" required></div>
                    <div class="mb-3"><input type="email" class="form-control" name="nuevo_email" placeholder="Correo" required></div>
                    <div class="mb-3"><input type="password" class="form-control" name="nueva_password" placeholder="Contraseña" required></div>
                    <button type="submit" class="btn btn-outline-primary w-100">REGISTRARSE</button>
                </form>
                <p class="mt-3 text-center">¿Ya tienes cuenta? <a href="#" onclick="mostrarLogin()">Inicia sesión</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>UEST_METHOD"] == "POST") {
    
    // ----------------------------
    // LÓGICA DE REGISTRO
    // ----------------------------
    if (isset($_POST['accion']) && $_POST['accion'] == 'registro') {
        $nombre = $_POST['nuevo_usuario']; // Nombre completo
        $email = $_POST['nuevo_email'];    // Correo
        $pass = $_POST['nueva_password'];  // Contraseña
        $rol = 3; // ID 3 = Paciente (según tu tabla Roles)

        // 1. Validar que no exista el correo
        $check = "SELECT * FROM Usuarios WHERE Correo = '$email'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            $mensaje = "<div class='alert alert-danger'>Ese correo ya está registrado.</div>";
        } else {
            // 2. Insertar nuevo usuario
            // Nota: No pedimos teléfono en el form, así que se guardará como NULL o vacío si la BD lo permite
            $sql = "INSERT INTO Usuarios (Nombre, Correo, Contrasena, RolID) VALUES ('$nombre', '$email', '$pass', $rol)";
            
            if ($conn->query($sql) === TRUE) {
                // Éxito: Mostramos alerta y recargamos para que inicien sesión
                echo "<script>alert('¡Registro exitoso! Por favor inicia sesión.'); window.location.href='login.php';</script>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al registrar: " . $conn->error . "</div>";
            }
        }
    }

    // ----------------------------
    // LÓGICA DE LOGIN
    // ----------------------------
    if (isset($_POST['accion']) && $_POST['accion'] == 'login') {
        $correo = $_POST['usuario']; // El input se llama 'usuario' pero contiene el correo
        $pass = $_POST['password'];

        // Consulta usando los nombres de columnas de tu NUEVA tabla
        $sql = "SELECT * FROM Usuarios WHERE Correo = '$correo' AND Contrasena = '$pass'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Login correcto: Guardamos datos en sesión
            $fila = $result->fetch_assoc();
            $_SESSION['usuario_id'] = $fila['ID'];
            $_SESSION['nombre'] = $fila['Nombre'];
            $_SESSION['rol'] = $fila['RolID'];
            
            // Redirigir al Home
            header("Location: index.php");
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
            <a class="navbar-brand fw-bold" href="index.html">DENTALIFE 🦷</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="servicios.html">SERVICIOS</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeria.html">GALERÍA</a></li>
                    <li class="nav-item"><a class="nav-link" href="sobrenos.html">SOBRE NOSOTROS</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacto.html">CONTÁCTANOS</a></li>
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
                    
                    <a href="#" class="d-block mb-3 text-muted small">¿Olvidaste la contraseña?</a>
                    <button type="submit" class="btn btn-primary w-100">LOGIN</button>
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