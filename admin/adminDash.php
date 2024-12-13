<?php

// confirmar sesion

session_start();

include ('../php/conexion_be.php');

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

$user = $_SESSION['usuario'];

$sql = "SELECT id, nombre, email, direccion, telefono, usuario, id_rol, password FROM usuarios WHERE usuario='$user'";
$resultado = $conexion->query($sql);

while ($data = $resultado->fetch(PDO::FETCH_ASSOC)) {

    $id = $data['id'];
    $nombre = $data['nombre'];
    $email = $data['email'];
    $direccion = $data['direccion'];
    $telefono = $data['telefono'];
    $usuario = $data['usuario'];
    $password = $data['password'];
    $id_rol = $data['id_rol'];
}

if ($id_rol != 1) {
    echo '
    <script> 
    alert("Inicia sesión como administrador")
    window.location = "../acceso.php";
    </script>
    ';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Meta etiquetas para la codificación de caracteres y la compatibilidad con dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Título de la página -->
    <title>Admin Dashboard</title>

    <!-- Icono de la pestaña -->
    <link rel="shortcut icon" href="../img/PUlogow18.png" type="image/x-icon">

    <!-- Enlace a la hoja de estilos personalizada -->
    <link rel="stylesheet" href="../css/estilos.css">

    <!-- Conexiones anticipadas para mejorar el rendimiento de carga de fuentes de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Enlace a las fuentes de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">

    <!-- Enlace a Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <!-- Encabezado de la página -->
    <header>
        <!-- Barra de navegación -->
        <nav>
            <a href="produccion.php">Producción</a>
            <a href="adminPedidos.php">Pedidos</a>
            <a href="clientes.php">Clientes</a>
            <a href="agregarProducto.php">Agregar Producto</a>
            <a href="inventario.php">Inventario</a>
            <a href="horarios.php">Horarios</a>
            <a href="../php/cerrar_sesion.php">Cerrar Session</a>
        </nav>

        <!-- Sección de encabezado con texto e imagen -->
        <section class="textos-header">
            <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
            <h1>Administrador</h1>
        </section>

        <!-- Efecto de onda -->
        <div class="wave" style="height: 150px; overflow: hidden;">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>

        <!-- Estilos personalizados -->
        <style>

        </style>
    </header>

    <!-- Sección para mostrar el perfil y los datos -->
    <div class="container mt-5">
        <div class="row">
            <!-- Perfil del Administrador -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="../img/admin_avatar.png" alt="Admin Avatar" class="rounded-circle" width="150">
                        <h3 class="mt-3"><?= $nombre ?></h3>
                        <p><?= $email ?></p>
                        <p><?= $direccion ?></p>
                        <p><?= $telefono ?></p>
                    </div>
                </div>
            </div>

            <!-- Datos de la página -->
            <div class="col-md-8">
                <h2>Bienvenido: <?= $nombre ?></h2>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Clientes</h5>
                                <p class="card-text">11</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Pedidos</h5>
                                <p class="card-text">22</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Pedidos Aceptados</h5>
                                <p class="card-text">14</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Pedidos Rechazados</h5>
                                <p class="card-text">5</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Servicios</h5>
                                <p class="card-text">20</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Ventas Hoy</h5>
                                <p class="card-text">15</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Ventas Ayer</h5>
                                <p class="card-text">20</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Ventas Últimos 7 días</h5>
                                <p class="card-text">120</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Ventas del Año</h5>
                                <p class="card-text">2190</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">

            </div>
        </div>
    </div>
    <br>
    <!-- Enlace a Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- // Aquí puedes añadir funcionalidad para el buscador y los botones de editar/eliminar.   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

</body>

<!-- Pie de página -->
<footer class="text-center text-white p-3" style="background-color: orange;">
    <div class="text-center">
        © Copyright 2024 | PanificApp
    </div>
</footer>

</html>