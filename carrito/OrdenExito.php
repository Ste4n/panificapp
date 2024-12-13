<?php
if (!isset($_REQUEST['id'])) {
    header("Location: index.php");
}

include 'Configuracion.php';

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    echo '
    <script> 
    alert("Inicia sesión")
    window.location = "../../acceso.php";
    </script>
    ';
    session_destroy();
    die();
}

$user = $_SESSION['usuario'];

$sql = "SELECT id, nombre, email, direccion, telefono, usuario, password FROM usuarios WHERE usuario='$user'";
$resultado = $db->query($sql);

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
    <title>Nuestros Productos</title>
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
    <style>
        .wave {
            height: 150px;
            overflow: hidden;
            position: relative;
        }

        .wave svg {
            height: 100%;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        footer {
            background-color: orange;
            color: white;
            padding: 10px 0;
            width: 100%;
            bottom: 0;
        }

        .galeria-port .imagen-port {
            position: relative;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .galeria-port .imagen-port img {
            width: 100%;
            height: auto;
            display: block;
        }

        .galeria-port .hover-galeria {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .galeria-port .hover-galeria img {
            width: 50px;
            height: 50px;
        }

        .galeria-port .hover-galeria p {
            margin-left: 10px;
            font-size: 1.2em;
        }

        .galeria-port .imagen-port:hover .hover-galeria {
            opacity: 1;
        }
    </style>
</head>

<body>
    <!-- Encabezado -->
    <header>
        <!-- Barra de navegación -->
        <nav>
            <a href="../cliente/tuspedidos.php">Historial</a>
            <a href="../cliente/perfilUsuario.php">Mi Perfil</a>
            <a href="../php/cerrar_sesion.php">Cerrar sesión</a>
        </nav>
        <!-- Sección de título y subtítulo -->
        <section class="textos-header">
            <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
            <h1>"Estado de orden"</h1>
        </section>
        <!-- Efecto de onda -->
        <div class="wave">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
    </header>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var enlaces = document.querySelectorAll('.imagen-port a');
            enlaces.forEach(function(enlace) {
                enlace.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevenir la acción por defecto del enlace
                    alert("Producto agregado");
                    window.location.href = enlace.href; // Continuar con la acción del enlace
                });
            });
        });
    </script>
</body>

<head>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Orden Completado</title>
    <meta charset="utf-8">
    <style>
        .container {
            padding: 20px;
        }

        p {
            color: #34a853;
            font-size: 18px;
        }

        .success-img {
            width: 100px;
            margin: 20px auto;
        }

        .order-details {
            margin: 20px 0;
        }
    </style>
</head>
<br>

<body>
    <div class="container">
        <div class="panel panel-default">


            <div class="panel-body text-center">
                <h1>Compra Exitosa</h1>
                <img src="../img/exito.png" alt="Éxito" class="success-img">
                <p>La Orden se ha enviado exitósamente. El ID de tu pedido es <?php echo $_GET['id']; ?></p>
                <div class="order-details">
                    <h3>Detalles del Cliente:</h3>
                    <p>Nombre: <?php echo htmlspecialchars($nombre); ?></p>
                    <p>Email: <?php echo htmlspecialchars($email); ?></p>
                    <p>Dirección: <?php echo htmlspecialchars($direccion); ?></p>
                    <p>Teléfono: <?php echo htmlspecialchars($telefono); ?></p>
                </div>
                <div class="footBtn">
                    <a href="index.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continuar Comprando</a>
                    <a href="../cliente/tuspedidos.php" class="btn btn-success">Ver Mis Pedidos</a>
                </div>
            </div>
        </div>
        <!-- Panel cierra -->
    </div>

    <!-- Pie de página -->
    <footer class="text-center text-white" style="background-color: orange;">
        <div class="text-center p-3">
            © Copyright 2024 | PanificApp
        </div>
    </footer>
</body>

</html>
