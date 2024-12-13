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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pedido</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../img/PUlogow18.png" type="image/x-icon">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../css/estilos.css">
    <!-- Fuentes de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Estilos internos -->
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }

        .order-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px;
            border-radius: 5px;
            position: relative;
        }

        .order-card .order-details {
            margin-bottom: 15px;
        }

        .order-card .order-status {
            color: blue;
        }

        .order-card .payment-status {
            color: green;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <a href="adminDash.php">Perfil</a>
            <a href="produccion.php">Producción</a>
            <a href="clientes.php">Clientes</a>
            <a href="agregarProducto.php">Agregar Producto</a>
            <a href="inventario.php">Inventario</a>
            <a href="horarios.php">Horarios</a>
            <a href="../php/cerrar_sesion.php">Cerrar Sesión</a>
        </nav>
        <section class="textos-header">
            <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
            <h1>Administrador de pedidos</h1>
        </section>
    </header>

    <div class="container mt-5">
        <h2 class="text-center">Seguimiento de Pedidos</h2>
        <br>
        
        <!-- Caja que contiene el formulario de búsqueda y los resultados -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Gestión de Pedidos</h5>
        <!-- Formulario de Búsqueda -->
        <form method="GET" action="" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="cliente_nombre" class="form-control" placeholder="Buscar por Cliente" value="<?= htmlspecialchars($_GET['cliente_nombre'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="orden_id" class="form-control" placeholder="ID Pedido" value="<?= htmlspecialchars($_GET['orden_id'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($_GET['fecha'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="tipo_pan" class="form-control" placeholder="Tipo de Pan" value="<?= htmlspecialchars($_GET['tipo_pan'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <select name="estado" class="form-control">
                        <option value="">Todos los Estados</option>
                        <option value="pendiente" <?= (isset($_GET['estado']) && $_GET['estado'] === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                        <option value="aceptado" <?= (isset($_GET['estado']) && $_GET['estado'] === 'aceptado') ? 'selected' : '' ?>>Aceptado</option>
                        <option value="cancelado" <?= (isset($_GET['estado']) && $_GET['estado'] === 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
                        <option value="listo" <?= (isset($_GET['estado']) && $_GET['estado'] === 'listo') ? 'selected' : '' ?>>Listo</option>
                        <option value="entregado" <?= (isset($_GET['estado']) && $_GET['estado'] === 'entregado') ? 'selected' : '' ?>>Entregado</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="?" class="btn btn-secondary">Limpiar</a>
                    <button type="button" class="btn btn-warning" id="vaciar-pedidos">Vaciar Resultados</button>
                </div>
            </div>
        </form>

        <!-- Resultados de Pedidos -->
        <div class="row" id="resultados-pedidos">
            <?php
            // Construcción de la consulta SQL con filtros
            $sql = "SELECT orden.id as orden_id, 
                        orden.precio_total, 
                        orden.estado, 
                        orden.estado_pago, 
                        orden.fecha, 
                        orden.hora, 
                        usuarios.nombre as cliente_nombre, 
                        usuarios.direccion as cliente_direccion, 
                        usuarios.email as cliente_email 
                    FROM orden 
                    INNER JOIN usuarios ON orden.cliente_id = usuarios.id 
                    WHERE 1=1";

            // Aplicar filtros si están presentes
            $params = [];
            if (!empty($_GET['cliente_nombre'])) {
                $sql .= " AND usuarios.nombre LIKE :cliente_nombre";
                $params['cliente_nombre'] = "%" . $_GET['cliente_nombre'] . "%";
            }
            if (!empty($_GET['orden_id'])) {
                $sql .= " AND orden.id = :orden_id";
                $params['orden_id'] = $_GET['orden_id'];
            }
            if (!empty($_GET['fecha'])) {
                $sql .= " AND orden.fecha = :fecha";
                $params['fecha'] = $_GET['fecha'];
            }
            if (!empty($_GET['tipo_pan'])) {
                $sql .= " AND EXISTS (
                            SELECT 1 
                            FROM orden_articulos 
                            INNER JOIN mis_productos ON orden_articulos.producto_id = mis_productos.id 
                            WHERE orden_articulos.orden_id = orden.id 
                            AND mis_productos.nombre LIKE :tipo_pan
                          )";
                $params['tipo_pan'] = "%" . $_GET['tipo_pan'] . "%";
            }
            if (!empty($_GET['estado'])) {
                $sql .= " AND orden.estado = :estado";
                $params['estado'] = $_GET['estado'];
            }

            // Ejecutar la consulta con filtros
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar los pedidos si existen resultados
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    echo "<div class='col-md-6'>";
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-body'>";
                    echo "<h6 class='card-title'>N° Orden: " . $order["orden_id"] . "</h6>";
                    echo "<p>Cliente: " . $order["cliente_nombre"] . "</p>";
                    echo "<p>Fecha: " . $order["fecha"] . " Hora: " . $order["hora"] . "</p>";
                    echo "<p>Dirección despacho: " . $order["cliente_direccion"] . "</p>";
                    echo "<p>Estado: " . $order["estado"] . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='col-12 text-center'><p>No se encontraron pedidos con los criterios ingresados.</p></div>";
            }
            ?>
        </div>
    </div>
</div>

<script>
    // Vaciar los resultados al pulsar el botón "Vaciar Resultados"
    document.getElementById('vaciar-pedidos').addEventListener('click', function() {
        document.getElementById('resultados-pedidos').innerHTML = '';
    });
</script>


        <div class="row">

            <!-- Pedidos -->

            <?php

            $sql = "SELECT orden.id as orden_id, 
            orden.precio_total, 
            orden.estado, 
            orden.estado_pago, 
            orden.fecha, 
            orden.hora, 
            usuarios.nombre as cliente_nombre, 
            usuarios.direccion as cliente_direccion, 
            usuarios.email as cliente_email 
            FROM orden 
            INNER JOIN usuarios ON orden.cliente_id = usuarios.id";
            $stmt = $conexion->query($sql);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($orders as $order) {
                echo "<div class='col-md-6'>";
                echo "<div class='order-card'>";
                echo "<div class='order-actions'>";
                echo "</div>";
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

                $total_cantidad = 0;

                foreach ($articulos as $articulo) {
                    $total_cantidad += $articulo["cantidad"];
                    echo "<li>" . $articulo["nombre"] . ": $" . ($articulo["precio"] * $articulo["cantidad"]) . " (x" . $articulo["cantidad"] . ")</li>";
                }

                echo "</ul>";
                echo "<p>Cantidad Total de Productos: " . $total_cantidad . "</p>";
                echo "<p>IVA: $" . number_format($order["precio_total"] * 0.19, 2) . "</p>";
                echo "<p>Total: $" . $order["precio_total"] . "</p>";
                echo "<p>Estado: " . $order["estado"] . "</p>";
                echo "<p>Estado Pago: " . $order["estado_pago"] . "</p>";

                // Calcular insumos necesarios
                $orden_id = $order["orden_id"];
                $sql_insumos = "
                    SELECT 
                        recetas.harina_blanca * orden_articulos.cantidad AS total_harina_blanca,
                        recetas.harina_integral * orden_articulos.cantidad AS total_harina_integral,
                        recetas.sal * orden_articulos.cantidad AS total_sal,
                        recetas.mejorador * orden_articulos.cantidad AS total_mejorador,
                        recetas.manteca * orden_articulos.cantidad AS total_manteca,
                        recetas.levadura * orden_articulos.cantidad AS total_levadura,
                        recetas.agua * orden_articulos.cantidad AS total_agua,
                        recetas.aceite_vegetal * orden_articulos.cantidad AS total_aceite,
                        orden_articulos.orden_id
                    FROM recetas
                    INNER JOIN orden_articulos ON recetas.producto_id = orden_articulos.producto_id
                    WHERE orden_articulos.orden_id = :orden_id
                ";
                $stmt_insumos = $conexion->prepare($sql_insumos);
                $stmt_insumos->execute(['orden_id' => $orden_id]);
                $insumos = $stmt_insumos->fetchAll(PDO::FETCH_ASSOC);

                // Mostrar insumos necesarios
                echo "<p>Insumos necesarios:</p>";
                echo "<ul>";
                foreach ($insumos as $insumo) {
                    foreach ($insumo as $key => $value) {
                        if ($value > 0) {
                            echo "<li>" . ucfirst(str_replace('_', ' ', $key)) . ": " . number_format($value, 2) . " g/ml</li>";
                        }
                    }
                }
                echo "</ul>";

                // Formulario para actualizar el estado del pedido
            
                echo "<form method='POST' action='update_order_status.php'>";
                echo "<input type='hidden' name='orden_id' value='" . $order["orden_id"] . "'>";
                echo "<select class='form-control' name='estado' onchange='this.form.submit()'>";
                echo "<option value='pendiente'" . ($order["estado"] == "pendiente" ? " selected" : "") . ">Pendiente</option>";
                echo "<option value='aceptado'" . ($order["estado"] == "aceptado" ? " selected" : "") . ">Aceptado</option>";
                echo "<option value='cancelado'" . ($order["estado"] == "cancelado" ? " selected" : "") . ">Cancelado</option>";
                echo "<option value='listo'" . ($order["estado"] == "listo" ? " selected" : "") . ">Listo para Recoger</option>";
                echo "<option value='entregado'" . ($order["estado"] == "entregado" ? " selected" : "") . ">Entregado</option>";
                echo "</select>";
                echo "</form>";
                echo "<br>";

                // Formulario para actualizar el estado del pago
                echo "<form method='POST' action='update_payment_status.php'>";
                echo "<input type='hidden' name='orden_id' value='" . $order["orden_id"] . "'>";
                echo "<select class='form-control' name='estado_pago' onchange='this.form.submit()'>";
                echo "<option value='pendiente'" . ($order["estado_pago"] == "pendiente" ? " selected" : "") . ">Pendiente Pago</option>";
                echo "<option value='pagado'" . ($order["estado_pago"] == "pagado" ? " selected" : "") . ">Pagado</option>";
                echo "<option value='rechazado'" . ($order["estado_pago"] == "rechazado" ? " selected" : "") . ">Pago Rechazado</option>";
                echo "</select>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>

        </div>
    </div>

    <footer class="text-center text-white p-3" style="background-color: orange;">
        <div class="text-center">
            © Copyright 2024 | PanificApp
        </div>
    </footer>
</body>

</html>