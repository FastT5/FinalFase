<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - DentaLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">DENTALIFE 🦷</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="servicios.php">SERVICIOS</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeria.php">GALERÍA</a></li>
                    <li class="nav-item"><a class="nav-link active" href="sobrenos.php">SOBRE NOSOTROS</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacto.php">CONTÁCTANOS</a></li>
                    
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1): ?>
                        <li class="nav-item"><a class="nav-link text-warning fw-bold" href="admin.php">ADMIN</a></li>
                    <?php endif; ?>
                </ul>

                <div class="ms-3">
                    <?php if (isset($_SESSION['nombre'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-light text-primary fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nombre']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-danger" href="logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-light text-primary fw-bold">Login <i class="bi bi-person-circle"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row text-center mb-5">
            <div class="col-md-4 mb-4">
                <div class="p-4 h-100 rounded-3 shadow-sm" style="background-color: #e3f2fd;">
                    <div class="bg-white rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                        <i class="bi bi-hourglass-split fs-2 text-primary"></i>
                    </div>
                    <h3 class="fw-bold">Historia</h3>
                    <p class="small text-muted text-start">
                        La clínica nació en el año 1970 con la visión de transformar la salud dental en nuestra comunidad. 
                        Lo que comenzó como un pequeño consultorio familiar, fundado por el Dr. abuelo Mendoza, ha evolucionado 
                        hasta convertirse en un centro de especialidades odontológicas de vanguardia. A lo largo de más de 50 años, 
                        hemos incorporado tecnología digital y las técnicas más avanzadas, manteniendo siempre el trato humano y 
                        cercano que nos caracteriza desde el primer día.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 h-100 rounded-3 shadow-sm text-white bg-primary">
                    <div class="bg-white rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                        <i class="bi bi-lightbulb fs-2 text-primary"></i>
                    </div>
                    <h3 class="fw-bold">Misión</h3>
                    <p class="small text-start">Nuestra misión es ofrecer servicios odontológicos de la más alta calidad, con un equipo profesional y tecnología de punta.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 h-100 rounded-3 shadow-sm" style="background-color: #e3f2fd;">
                    <div class="bg-white rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                        <i class="bi bi-eye fs-2 text-primary"></i>
                    </div>
                    <h3 class="fw-bold">Visión</h3>
                    <p class="small text-muted text-start">Ser la clínica dental líder en la región, reconocida por nuestra excelencia y trato humano.</p>
                </div>
            </div>
        </div>

        <h2 class="text-center fw-bold mb-5">Nuestro Equipo</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="row g-0 align-items-center">
                        <div class="col-4">
                            <img src="img/doctor1.jpg" class="img-fluid rounded-start h-100 object-fit-cover" alt="Dr.">
                        </div>
                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Carlos Mendoza</h5>
                                <p class="card-text text-primary small fw-bold">ODONTÓLOGO GENERAL</p>
                                <p class="card-text small text-muted">Director de la clínica. Especialista en diagnóstico.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="row g-0 align-items-center">
                        <div class="col-4">
                            <img src="img/doctora2.jpg" class="img-fluid rounded-start h-100 object-fit-cover" alt="Dra.">
                        </div>
                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Andrea López</h5>
                                <p class="card-text text-primary small fw-bold">ORTODONCISTA</p>
                                <p class="card-text small text-muted">Especialista en brackets y alineadores invisibles.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 DentaLife - Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>