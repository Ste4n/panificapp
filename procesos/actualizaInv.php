<?php

include ('../php/conexion_be.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['nombre'], $_POST['categoria'], $_POST['proveedor'], $_POST['precio'],$_POST['unidades'], $_POST['cantidad'], $_POST['fecha_ingreso'], $_POST['fecha_vencimiento'])) {
        $idP = $_POST['id'];
        $nombreP = $_POST['nombre'];
        $categoriaP = $_POST['categoria'];
        $proveedor = $_POST['proveedor'];
        $precio = $_POST['precio'];
        $unidades = $_POST['unidades'];
        $cantidad = $_POST['cantidad'];
        $fechaI = $_POST['fecha_ingreso'];
        $fechaV = $_POST['fecha_vencimiento'];

        
        // Validación y sanitización de datos
        $idProducto = filter_var($idProducto, FILTER_SANITIZE_NUMBER_INT);
        $nombreP = filter_var($nombreP, FILTER_SANITIZE_STRING);
        $categoriaP = filter_var($categoriaP, FILTER_SANITIZE_STRING);
        $proveedor = filter_var($proveedor, FILTER_SANITIZE_STRING);
        $precio = filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $unidades = filter_var($unidades, FILTER_SANITIZE_NUMBER_INT);
        $cantidad = filter_var($cantidad, FILTER_SANITIZE_NUMBER_INT);
        $fechaV = filter_var($fechaV, FILTER_SANITIZE_STRING);

        try {
            $stmt = $conexion->prepare("UPDATE inventario SET nombre = :nombre, categoria = :categoria, proveedor = :proveedor, precio = :precio,unidades = :unidades, cantidad = :cantidad, fecha_ingreso = :fecha_ingreso, fecha_vencimiento = :fecha_vencimiento WHERE id = :id");

            $stmt->bindParam(":id", $idP, PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $nombreP, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $categoriaP, PDO::PARAM_STR);
            $stmt->bindParam(":proveedor", $proveedor, PDO::PARAM_STR);
            $stmt->bindParam(":precio", $precio, PDO::PARAM_STR);
            $stmt->bindParam(":unidades", $unidades, PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_ingreso", $fechaI, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_vencimiento", $fechaV, PDO::PARAM_STR);

            $stmt->execute();

            echo "
                <script>
                    alert('Producto actualizado correctamente');
                    window.location.href = '../admin/inventario.php';
                </script>
            ";

            exit();
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    } else {
        echo "
            <script>
                alert('¡Por favor rellena todos los campos!');
                window.location.href = '../admin/inventario.php';
            </script>
        ";
    }
}