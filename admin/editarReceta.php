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

// Obtener la receta del producto
if (isset($_GET['producto_id'])) {
    $producto_id = $_GET['producto_id'];

    $sql_receta = "SELECT * FROM recetas WHERE producto_id = :producto_id";
    $stmt = $conexion->prepare($sql_receta);
    $stmt->execute([':producto_id' => $producto_id]);
    $receta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$receta) {
        echo '<script>alert("No se encontró la receta para este producto."); window.location = "agregarProducto.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("ID del producto no proporcionado."); window.location = "agregarProducto.php";</script>';
    exit();
}

// Actualizar la receta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    echo '<script>alert("Receta actualizada correctamente."); window.location = "agregarProducto.php";</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Receta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Editar Receta del Producto</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="harina_blanca" class="form-label">Harina Blanca (g)</label>
            <input type="number" class="form-control" id="harina_blanca" name="harina_blanca" value="<?= $receta['harina_blanca'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="harina_integral" class="form-label">Harina Integral (g)</label>
            <input type="number" class="form-control" id="harina_integral" name="harina_integral" value="<?= $receta['harina_integral'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="sal" class="form-label">Sal (g)</label>
            <input type="number" class="form-control" id="sal" name="sal" value="<?= $receta['sal'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="mejorador" class="form-label">Mejorador (g)</label>
            <input type="number" class="form-control" id="mejorador" name="mejorador" value="<?= $receta['mejorador'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="manteca" class="form-label">Manteca (g)</label>
            <input type="number" class="form-control" id="manteca" name="manteca" value="<?= $receta['manteca'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="levadura" class="form-label">Levadura (g)</label>
            <input type="number" class="form-control" id="levadura" name="levadura" value="<?= $receta['levadura'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="agua" class="form-label">Agua (ml)</label>
            <input type="number" class="form-control" id="agua" name="agua" value="<?= $receta['agua'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="aceite_vegetal" class="form-label">Aceite Vegetal (ml)</label>
            <input type="number" class="form-control" id="aceite_vegetal" name="aceite_vegetal" value="<?= $receta['aceite_vegetal'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Receta</button>
        <a href="agregarProducto.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
