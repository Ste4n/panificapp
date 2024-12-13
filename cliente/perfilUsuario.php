<?php

// confirmar sesion

session_start();

if (!isset($_SESSION["usuario"])) {
    echo '
    <script> 
    alert("Inicia sesión")
    window.location = "../acceso.php";
    </script>
    ';
    session_destroy();
    die();
}

include "../php/conexion_be.php";

$user = $_SESSION['usuario'];

$sql = "SELECT id, nombre, email, direccion, telefono, usuario, password FROM usuarios WHERE usuario='$user'";
$resultado = $conexion->query($sql);

while ($data = $resultado->fetch(PDO::FETCH_ASSOC)) {

    $id = $data['id'];
    $nombre = $data['nombre'];
    $email = $data['email'];
    $direccion = $data['direccion'];
    $telefono = $data['telefono'];
    $usuario = $data['usuario'];
    $password = $data['password'];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Meta etiquetas -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página -->
    <title>Tu Perfil</title>
    <!-- Icono de la pestaña -->
    <link rel="shortcut icon" href="../img/PUlogow18.png" type="../image/x-icon">
    <!-- Hoja de estilos personalizada -->
    <link rel="stylesheet" href="../css/estilos.css">
    <!-- Fuentes de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <!-- Encabezado -->
    <header>
        <!-- Barra de navegación -->
        <nav>
            <a href="../carrito/index.php">Productos</a>
            <a href="tuspedidos.php">Historial</a>
            <a href="../carrito/VerCarta.php">Carrito</a>
            <a href="../php/cerrar_sesion.php">Cerrar sesión</a>
        </nav>
        <!-- Sección de título y subtítulo -->
        <section class="textos-header">
            <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
            <h1>Bienvenido <?php echo $nombre; ?><br> </h1>
        </section>
        <!-- Efecto de onda -->
        <div class="wave" style="height: 150px; overflow: hidden;">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
    </header>
    <div class="container mt-5">
        <!-- principal -->
        <h2>Su Información</h2>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><b>Nombre:</b> <?php echo $nombre; ?></p>
                <p class="card-text"><b>Correo:</b> <?php echo $email; ?></p>
                <p class="card-text"><b>Dirección:</b> <?php echo $direccion; ?></p>
                <p class="card-text"><b>Teléfono:</b> <?php echo $telefono; ?></p>
            </div>
        </div>
        <div class="row mt-5">

            <div class="col-md-4">
                <img src="../img/sotck4.png" class="img-fluid rounded" alt="Pan 2">
            </div>
            <div class="col-md-4">
                <img src="../img/sotck5.png" class="img-fluid rounded" alt="Pan 3">
            </div>
            <div class="col-md-4">
                <img src="../img/sotck1.png" class="img-fluid rounded" alt="Pan 3">
            </div>
        </div>
        <br>
    </div>


    <!-- Pie de página -->
    <footer class="text-center text-white" style="background-color: orange;">
        <div class="text-center p-3">
            © Copyright 2024 | PanificApp:
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>