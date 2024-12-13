<?php
session_start();
include('../php/conexion_be.php');

// Verifica si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION["usuario"])) {
    echo '<script>alert("Inicia sesión"); window.location = "../acceso.php";</script>';
    session_destroy();
    die();
}

$user = $_SESSION['usuario'];
$sql = "SELECT id_rol FROM usuarios WHERE usuario='$user'";
$resultado = $conexion->query($sql);
$data = $resultado->fetch(PDO::FETCH_ASSOC);

if ($data['id_rol'] != 1) {
    echo '<script>alert("Inicia sesión como administrador"); window.location = "../acceso.php";</script>';
    session_destroy();
    die();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['guardar_producto'])) {
        // Guardar producto
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $imagen = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $ruta_imagen = "../uploads/" . $imagen;

        move_uploaded_file($imagen_tmp, $ruta_imagen);

        $sql_insert = "INSERT INTO mis_productos (nombre, descripcion, imagen, precio) 
                       VALUES ('$nombre', '$descripcion', '$ruta_imagen', '$precio')";
        $conexion->exec($sql_insert);
        $producto_id = $conexion->lastInsertId();

        // Guardar receta
        $sql_receta = "INSERT INTO recetas (producto_id, harina_blanca, harina_integral, sal, mejorador, manteca, levadura, agua, aceite_vegetal) 
                       VALUES ('$producto_id', :harina_blanca, :harina_integral, :sal, :mejorador, :manteca, :levadura, :agua, :aceite_vegetal)";
        $stmt = $conexion->prepare($sql_receta);
        $stmt->execute([
            ':harina_blanca' => $_POST['harina_blanca'],
            ':harina_integral' => $_POST['harina_integral'],
            ':sal' => $_POST['sal'],
            ':mejorador' => $_POST['mejorador'],
            ':manteca' => $_POST['manteca'],
            ':levadura' => $_POST['levadura'],
            ':agua' => $_POST['agua'],
            ':aceite_vegetal' => $_POST['aceite_vegetal']
        ]);

        header("Location: agregarProducto.php");
        exit();
    } elseif (isset($_POST['editar_receta'])) {
        // Editar receta
        $producto_id = $_POST['producto_id'];
        $sql_update_receta = "UPDATE recetas SET harina_blanca = :harina_blanca, harina_integral = :harina_integral, sal = :sal, mejorador = :mejorador, manteca = :manteca, levadura = :levadura, agua = :agua, aceite_vegetal = :aceite_vegetal WHERE producto_id = :producto_id";
        $stmt = $conexion->prepare($sql_update_receta);
        $stmt->execute([
            ':harina_blanca' => $_POST['harina_blanca'],
            ':harina_integral' => $_POST['harina_integral'],
            ':sal' => $_POST['sal'],
            ':mejorador' => $_POST['mejorador'],
            ':manteca' => $_POST['manteca'],
            ':levadura' => $_POST['levadura'],
            ':agua' => $_POST['agua'],
            ':aceite_vegetal' => $_POST['aceite_vegetal'],
            ':producto_id' => $producto_id
        ]);

        header("Location: agregarProducto.php");
        exit();
    } elseif (isset($_POST['eliminar_producto'])) {
        // Eliminar producto
        $id = $_POST['id'];
        $sql_delete = "DELETE FROM mis_productos WHERE id = '$id'";
        $conexion->exec($sql_delete);
        header("Location: agregarProducto.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <nav>
        <a href="adminDash.php">Perfil</a>
        <a href="adminPedidos.php">Pedidos</a>
        <a href="clientes.php">Clientes</a>
        <a href="agregarProducto.php">Agregar Prod</a>
        <a href="inventario.php">Inventario</a>
        <a href="horarios.php">Horarios</a>
        <a href="../php/cerrar_sesion.php">Cerrar Sesión</a>
    </nav>
    <section class="textos-header">
        <img src="../img/Logo_PU-removebg-preview.png" alt="" class="imagen-titulo">
        <h1>Productos</h1>
    </section>
</header>
<div class="container mt-5">
    <!-- Formulario de ingreso de productos -->
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <h3>Agregar Producto</h3>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen</label>
                    <input type="file" class="form-control" id="imagen" name="imagen">
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio" required>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Agregar Receta</h3>
                <div class="mb-3">
                    <label for="harina_blanca" class="form-label">Harina Blanca (g)</label>
                    <input type="number" class="form-control" id="harina_blanca" name="harina_blanca" required>
                </div>
                <div class="mb-3">
                    <label for="harina_integral" class="form-label">Harina Integral (g)</label>
                    <input type="number" class="form-control" id="harina_integral" name="harina_integral" required>
                </div>
                <div class="mb-3">
                    <label for="sal" class="form-label">Sal (g)</label>
                    <input type="number" class="form-control" id="sal" name="sal" required>
                </div>
                <div class="mb-3">
                    <label for="mejorador" class="form-label">Mejorador (g)</label>
                    <input type="number" class="form-control" id="mejorador" name="mejorador" required>
                </div>
                <div class="mb-3">
                    <label for="manteca" class="form-label">Manteca (g)</label>
                    <input type="number" class="form-control" id="manteca" name="manteca" required>
                </div>
                <div class="mb-3">
                    <label for="levadura" class="form-label">Levadura (g)</label>
                    <input type="number" class="form-control" id="levadura" name="levadura" required>
                </div>
                <div class="mb-3">
                    <label for="agua" class="form-label">Agua (ml)</label>
                    <input type="number" class="form-control" id="agua" name="agua" required>
                </div>
                <div class="mb-3">
                    <label for="aceite_vegetal" class="form-label">Aceite Vegetal (ml)</label>
                    <input type="number" class="form-control" id="aceite_vegetal" name="aceite_vegetal" required>
                </div>
                <button type="submit" name="guardar_producto" class="btn btn-success">Guardar Producto y Receta</button>
            </div>
        </div>
    </form>
    <hr>
    <!-- Productos Guardados -->
    <div>
        <h3>Productos Guardados</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Receta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_select = "SELECT * FROM mis_productos";
                $result = $conexion->query($sql_select);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>" . $row['descripcion'] . "</td>";
                    echo "<td><img src='" . $row['imagen'] . "' alt='Imagen del producto' width='100'></td>";
                    echo "<td>" . $row['precio'] . "</td>";
                    echo "<td><a href='editarReceta.php?producto_id=" . $row['id'] . "' class='btn btn-info btn-sm'>Editar Receta</a></td>";
                    echo "<td>
                            <form method='POST' style='display:inline-block;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' name='eliminar_producto' class='btn btn-danger btn-sm'>Eliminar</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
