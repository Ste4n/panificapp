<?php

// Confirmar sesión
session_start();

include '../php/conexion_be.php';

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

// Obtener el ID del cliente basado en el nombre de usuario
$sql_cliente = "SELECT id FROM usuarios WHERE usuario = :user";
$stmt_cliente = $conexion->prepare($sql_cliente);
$stmt_cliente->execute(['user' => $user]);
$cliente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    echo "Cliente no encontrado";
    exit;
}

$cliente_id = $cliente['id'];

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $orden_id = $_POST['orden_id'];

    // Obtener el estado actual de la orden
    $sql_get_status = "SELECT estado FROM orden WHERE id = :orden_id";
    $stmt_get_status = $conexion->prepare($sql_get_status);
    $stmt_get_status->execute(['orden_id' => $orden_id]);
    $orden = $stmt_get_status->fetch(PDO::FETCH_ASSOC);

    if ($orden && $orden['estado'] === 'pendiente') {
        // Cambiar el estado de la orden a "cancelado"
        $sql_update_status = "UPDATE orden SET estado = 'cancelado' WHERE id = :orden_id";
        $stmt_update_status = $conexion->prepare($sql_update_status);
        $stmt_update_status->execute(['orden_id' => $orden_id]);

        echo '
        <script>
        alert("Pedido cancelado exitosamente");
        window.location = "tuspedidos.php";
        </script>
        ';
    } else {
        echo '
        <script>
        alert("No se puede cancelar esta orden porque no está en estado pendiente.");
        window.location = "tuspedidos.php";
        </script>
        ';
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Meta etiquetas -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página -->
    <title>Tus pedidos</title>
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
        .pedido-container {
            border: 2px solid #ddd;
            /* Define el borde */
            border-radius: 10px;
            /* Redondea las esquinas del borde */
            padding: 20px;
            /* Espacio interno */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Añade una sombra para mayor efecto */
            margin-bottom: 20px;
            /* Espacio entre pedidos */
        }
    </style>
</head>

<body>
    <!-- Encabezado -->
    <header>
        <!-- Barra de navegación -->
        <nav>
            <a href="perfilUsuario.php">Mi Perfil</a>
            <a href="../carrito/index.php">Productos</a>
            <a href="../carrito/VerCarta.php">Carrito</a>
            <a href="../php/cerrar_sesion.php">Cerrar sesión</a>
        </nav>
        <!-- Sección de título y subtítulo -->
        <section class="textos-header">
            <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
            <h1>Tus Pedidos</h1>
        </section>
        <!-- Efecto de onda -->
        <div class="wave" style="height: 150px; overflow: hidden;">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
    </header>

    <!-- Sección de pedidos -->
    <div class="container my-5">
        <div class="pedido-header">
            <h2>Mis Pedidos</h2>
            <br>
        </div>
        <div class="row">
            <!-- Mis Pedidos -->
            <?php

            $sql = "SELECT orden.id as orden_id, orden.precio_total, orden.estado, orden.fecha, orden.hora, usuarios.nombre as cliente_nombre, usuarios.direccion as cliente_direccion, usuarios.email as cliente_email 
            FROM orden 
            INNER JOIN usuarios ON orden.cliente_id = usuarios.id
            WHERE orden.cliente_id = :cliente_id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute(['cliente_id' => $cliente_id]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($orders as $order) {
                echo "<div class='col-md-6'>";
                echo "<div class='pedido-container'>";
                echo "<div class='order-details'>";
                echo "<p>N° orden " . $order["orden_id"] . " Cliente " . $order["cliente_nombre"] . " fecha " . $order["fecha"] . " hora " . $order["hora"] . "</p>";
                echo "<p>Dirección despacho: " . $order["cliente_direccion"] . "</p>";
                echo "<p>DETALLE:</p>";
                echo "<ul>";

                // Obtener detalles de los productos
                $orden_id = $order["orden_id"];
                $sql_articulos = "SELECT cantidad, nombre, precio FROM orden_articulos INNER JOIN mis_productos ON orden_articulos.producto_id = mis_productos.id WHERE orden_articulos.orden_id = :orden_id";
                $stmt_articulos = $conexion->prepare($sql_articulos);
                $stmt_articulos->execute(['orden_id' => $orden_id]);
                $articulos = $stmt_articulos->fetchAll(PDO::FETCH_ASSOC);

                foreach ($articulos as $articulo) {
                    echo "<li>" . $articulo["nombre"] . " (Cant: " . $articulo["cantidad"] . "): $" . ($articulo["precio"] * $articulo["cantidad"]) . "</li>";
                }

                echo "</ul>";
                echo "<p>IVA: $" . number_format($order["precio_total"] * 0.19, 2) . "</p>";
                echo "<p>Total: $" . $order["precio_total"] . " <span class='order-status'>Estado " . $order["estado"] . "</span></p>";
                echo "</div>";

                // Verificar el estado del pedido y mostrar el botón de cancelar solo si está en estado "pendiente"
                if ($order["estado"] === 'pendiente') {
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='orden_id' value='" . $order["orden_id"] . "'>";
                    echo "<button type='submit' name='cancel_order' class='btn btn-danger'>Cancelar Pedido</button>";
                    echo "</form>";
                } else {
                    echo "<button class='btn btn-secondary' disabled>Cancelar Pedido</button>";
                }

                echo "</div>";
                echo "</div>";
            }

            ?>
        </div>
    </div>
    <br>
    <!-- Pie de página -->
    <footer class="text-center text-white" style="background-color: orange;">
        <div class="text-center p-3">
            © Copyright 2024 | PanificApp
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>