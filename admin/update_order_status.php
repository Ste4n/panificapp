<?php
session_start();
include('../php/conexion_be.php');

// Validar si el usuario tiene permisos
if (!isset($_SESSION["usuario"])) {
    echo '<script>alert("Inicia sesión para continuar."); window.location = "../acceso.php";</script>';
    exit();
}

// Verificar si el método es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orden_id = $_POST['orden_id'];
    $nuevo_estado = $_POST['estado'];

    // Si el nuevo estado es "aceptado", realizar las validaciones y descuentos de inventario
    if ($nuevo_estado === 'aceptado') {
        // Calcular los insumos necesarios para esta orden
        $sql_insumos = "
        SELECT 
            FLOOR(recetas.harina_blanca * orden_articulos.cantidad) AS harina_blanca,
            FLOOR(recetas.harina_integral * orden_articulos.cantidad) AS harina_integral,
            FLOOR(recetas.sal * orden_articulos.cantidad) AS sal,
            FLOOR(recetas.mejorador * orden_articulos.cantidad) AS mejorador,
            FLOOR(recetas.manteca * orden_articulos.cantidad) AS manteca,
            FLOOR(recetas.levadura * orden_articulos.cantidad) AS levadura,
            FLOOR(recetas.agua * orden_articulos.cantidad) AS agua,
            FLOOR(recetas.aceite_vegetal * orden_articulos.cantidad) AS aceite,
            orden_articulos.orden_id
        FROM recetas
        INNER JOIN orden_articulos ON recetas.producto_id = orden_articulos.producto_id
        WHERE orden_articulos.orden_id = :orden_id
    ";

        $stmt_insumos = $conexion->prepare($sql_insumos);
        $stmt_insumos->execute(['orden_id' => $orden_id]);
        $insumos = $stmt_insumos->fetchAll(PDO::FETCH_ASSOC);

        // Verificar inventario
        $suficiente_inventario = true;
        $faltantes = []; // Para almacenar categorías que no sean suficientes

        foreach ($insumos as $insumo) {
            foreach ($insumo as $categoria_insumo => $cantidad_necesaria) {
                if ($categoria_insumo === 'orden_id' || $categoria_insumo === 'agua' || $cantidad_necesaria <= 0)
                    continue;

                // Consultar el inventario ordenado por fecha de vencimiento más cercana
                $stmt_check_inventario = $conexion->prepare("
                SELECT id, cantidad, fecha_vencimiento 
                FROM inventario 
                WHERE categoria = :categoria 
                ORDER BY fecha_vencimiento ASC
            ");
                $stmt_check_inventario->execute(['categoria' => $categoria_insumo]);
                $inventarios = $stmt_check_inventario->fetchAll(PDO::FETCH_ASSOC);

                $cantidad_restante = $cantidad_necesaria;

                foreach ($inventarios as $inventario) {
                    if ($cantidad_restante <= 0)
                        break;

                    if (floatval($inventario['cantidad']) >= $cantidad_restante) {
                        $cantidad_restante = 0;
                    } else {
                        $cantidad_restante -= floatval($inventario['cantidad']);
                    }
                }

                if ($cantidad_restante > 0) {
                    $suficiente_inventario = false;
                    $faltantes[] = $categoria_insumo;
                }
            }
        }

        // Si no hay suficiente inventario, notificar al administrador y detener el proceso
        if (!$suficiente_inventario) {
            echo '<script>alert("No hay suficientes insumos en el inventario para aceptar esta orden: ' . implode(', ', $faltantes) . '"); window.location = "adminPedidos.php";</script>';
            exit();
        }

        // Deducir las cantidades del inventario usando las categorías exactas y fecha de vencimiento más próxima
        foreach ($insumos as $insumo) {
            foreach ($insumo as $categoria_insumo => $cantidad_necesaria) {
                if ($categoria_insumo === 'orden_id' || $categoria_insumo === 'agua' || $cantidad_necesaria <= 0)
                    continue;

                $stmt_check_inventario = $conexion->prepare("
                SELECT id, cantidad 
                FROM inventario 
                WHERE categoria = :categoria 
                ORDER BY fecha_vencimiento ASC
            ");
                $stmt_check_inventario->execute(['categoria' => $categoria_insumo]);
                $inventarios = $stmt_check_inventario->fetchAll(PDO::FETCH_ASSOC);

                $cantidad_restante = $cantidad_necesaria;

                foreach ($inventarios as $inventario) {
                    if ($cantidad_restante <= 0)
                        break;

                    $cantidad_a_deducir = min($cantidad_restante, floatval($inventario['cantidad']));
                    $stmt_update_inventario = $conexion->prepare("
                    UPDATE inventario SET cantidad = cantidad - :cantidad WHERE id = :id
                ");
                    $stmt_update_inventario->execute([
                        'cantidad' => $cantidad_a_deducir,
                        'id' => $inventario['id']
                    ]);

                    $cantidad_restante -= $cantidad_a_deducir;
                }
            }
        }
    }

    // Actualizar el estado de la orden
    $stmt_update_estado = $conexion->prepare("
        UPDATE orden SET estado = :estado WHERE id = :orden_id
    ");
    $stmt_update_estado->execute([
        'estado' => $nuevo_estado,
        'orden_id' => $orden_id
    ]);

    echo '<script>alert("Estado de la orden actualizado correctamente."); window.location = "adminPedidos.php";</script>';
    exit();
}
