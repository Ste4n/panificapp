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
    <style>
        .produccion-container {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .form-group input {
            width: 100px;
            /* Ajusta el ancho según sea necesario */
            display: inline-block;
        }

        .datos-ingresados {
            margin-top: 20px;
        }

        .produccion-table {
            display: flex;
            justify-content: space-between;
        }

        .produccion-form {
            flex: 1;
        }

        .tabla-datos {
            flex: 1;
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <!-- Encabezado de la página -->
    <header>
        <!-- Barra de navegación -->
        <nav>
            <a href="adminDash.php">Perfil</a>
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
            <h1>Producción</h1>
        </section>

        <!-- Efecto de onda -->
        <div class="wave" style="height: 150px; overflow: hidden;">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #ffffff;"></path>
            </svg>
        </div>
    </header>

    <!-- Sección para mostrar el perfil y los datos -->
    <div class="container mt-5">
        <div class="row produccion-table">
            <div class="produccion-form">
                <h2 class="my-4">Producción</h2>
                <div class="produccion-container">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="fecha">Fecha:</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="form-group">
                            <label for="materia_prima">Costo Materia Prima:</label>
                            <input type="text" class="form-control" id="materia_prima" name="materia_prima" required>
                        </div>
                        <div class="form-group">
                            <label for="sueldo">Sueldo Personal:</label>
                            <input type="text" class="form-control" id="sueldo" name="sueldo" required>
                        </div>
                        <div class="form-group">
                            <label for="otros_costos">Otros Costos:</label>
                            <input type="text" class="form-control" id="otros_costos" name="otros_costos" required>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_pan">Cantidad Pan (kg):</label>
                            <input type="text" class="form-control" id="cantidad_pan" name="cantidad_pan" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Calcular</button>
                    </form>

                    <?php
                    // Verificar si se han enviado datos por POST
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Obtener y limpiar los datos del formulario
                        $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
                        $materia_prima = isset($_POST['materia_prima']) ? floatval($_POST['materia_prima']) : 0;
                        $sueldo = isset($_POST['sueldo']) ? floatval($_POST['sueldo']) : 0;
                        $otros_costos = isset($_POST['otros_costos']) ? floatval($_POST['otros_costos']) : 0;
                        $cantidad_pan = isset($_POST['cantidad_pan']) ? floatval($_POST['cantidad_pan']) : 0;

                        // Validar que los valores sean numéricos y mayores a cero
                        if ($materia_prima > 0 && $sueldo > 0 && $otros_costos > 0 && $cantidad_pan > 0) {
                            // Calcular costo por kg de pan
                            $costo_total = ($materia_prima + $sueldo + $otros_costos) / $cantidad_pan;

                            // Insertar datos en la tabla
                            $stmt = $conexion->prepare("INSERT INTO costos_pan (fecha, materia_prima, sueldo, otros_costos, cantidad_pan, costo_total) VALUES (?, ?, ?, ?, ?, ?)");
                            if ($stmt->execute([$fecha, $materia_prima, $sueldo, $otros_costos, $cantidad_pan, $costo_total])) {
                                echo '<div class="mt-4 alert alert-success" role="alert">Datos guardados exitosamente.</div>';
                            } else {
                                echo '<div class="mt-4 alert alert-danger" role="alert">Error al guardar los datos.</div>';
                            }
                        } else {
                            echo '<div class="mt-4 alert alert-danger" role="alert">Por favor ingresa valores numéricos válidos y mayores a cero.</div>';
                        }
                    }
                    ?>
                    <br>
                    <h2 class="my-4">Producción por fecha</h2>
                    <?php
                    // Obtener todos los registros de la tabla
                    $stmt = $conexion->query("SELECT * FROM costos_pan");

                    if ($stmt->rowCount() > 0) {
                        echo '<table class="table">';
                        echo '<thead><tr><th>ID</th><th>Fecha</th><th>Materia Prima</th><th>Sueldo</th><th>Otros Costos</th><th>Cantidad Pan (kg)</th><th>Costo Total</th></tr></thead>';
                        echo '<tbody>';

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . $row['fecha'] . '</td>';
                            echo '<td>' . $row['materia_prima'] . '</td>';
                            echo '<td>' . $row['sueldo'] . '</td>';
                            echo '<td>' . $row['otros_costos'] . '</td>';
                            echo '<td>' . $row['cantidad_pan'] . '</td>';
                            echo '<td>' . $row['costo_total'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody></table>';
                    } else {
                        echo '<div class="mt-4 alert alert-info" role="alert">No hay registros en la tabla.</div>';
                    }
                    ?>

                </div>
            </div>

            <!--
            <div class="tabla-datos">
                <h2 class="my-4">Producción por fecha</h2>
                <div class="datos-ingresados">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Costo Materia Prima</th>
                                <th>Sueldo Personal</th>
                                <th>Otros Costos</th>
                                <th>Cantidad Pan (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $materia_prima > 0 && $sueldo > 0 && $otros_costos > 0 && $cantidad_pan > 0): ?>
                                <tr>
                                    <td>$<?= number_format($materia_prima, 2) ?></td>
                                    <td>$<?= number_format($materia_prima, 2) ?></td>
                                    <td>$<?= number_format($sueldo, 2) ?></td>
                                    <td>$<?= number_format($otros_costos, 2) ?></td>
                                    <td><?= number_format($cantidad_pan, 2) ?> kg</td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No hay datos ingresados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            -->

        </div>
    </div>
    <br>

    <!-- Enlace a Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- Funcionalidad adicional -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

<!-- Pie de página -->
<footer class="text-center text-white p-3" style="background-color: orange;">
    <div class="text-center">
        © Copyright 2024 | PanificApp
    </div>
</footer>

</html>