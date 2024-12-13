<?php
session_start();
include('../php/conexion_be.php');

if (!isset($_SESSION["usuario"])) {
    echo '<script>alert("Inicia sesión"); window.location = "../acceso.php";</script>';
    session_destroy();
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM mis_productos WHERE id='$id'";
    $result = $conexion->query($sql);
    $producto = $result->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo '<script>alert("Producto no encontrado"); window.location = "agregarProducto.php";</script>';
        exit();
    }
} else {
    header("Location: agregarProducto.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_producto'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $ruta_imagen = "../uploads/" . $imagen;

    if ($imagen) {
        move_uploaded_file($imagen_tmp, $ruta_imagen);
        $sql_update = "UPDATE mis_productos SET nombre='$nombre', descripcion='$descripcion', imagen='$ruta_imagen', precio='$precio' WHERE id='$id'";
    } else {
        $sql_update = "UPDATE mis_productos SET nombre='$nombre', descripcion='$descripcion', precio='$precio' WHERE id='$id'";
    }

    $conexion->exec($sql_update);
    header("Location: agregarProducto.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <nav>
        <a href="adminDash.php">Perfil</a>
        <a href="adminPedidos.php">Pedidos</a>
        <a href="clientes.php">Clientes</a>
        <a href="agregarProducto.php">Agregar Producto</a>
        <a href="inventario.php">Inventario</a>
        <a href="horarios.php">Horarios</a>
        <a href="../php/cerrar_sesion.php">Cerrar Sesión</a>
    </nav>
    <section class="textos-header">
        <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
        <h1>Editar Producto</h1>
    </section>
    <div class="wave" style="height: 150px; overflow: hidden;">
        <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
            <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                style="stroke: none; fill: #ffffff;"></path>
        </svg>
    </div>
</header>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h4>Editar Producto</h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo $producto['descripcion']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Ingrese imagen</label>
                    <input class="form-control" type="file" id="imagen" name="imagen">
                    <img src="<?php echo $producto['imagen']; ?>" alt="Imagen del producto" width="100" class="mt-3">
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio de Compra</label>
                    <input type="number" class="form-control" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required>
                </div>
                <button type="submit" name="actualizar_producto" class="btn btn-success">Actualizar</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<footer class="text-center text-white p-3" style="background-color: orange;">
    <div class="text-center">
        © Copyright 2024 | PanificApp
    </div>
</footer>
</html>