<?php

include '../php/conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'], $_POST['categoria'], $_POST['proveedor'], $_POST['precio'], $_POST['unidades'], $_POST['cantidad'], $_POST['fecha_ingreso'], $_POST['fecha_vencimiento'])) {
        $nombreP = $_POST['nombre'];
        $categoriaP = $_POST['categoria'];
        $proveedor = $_POST['proveedor'];
        $precio = $_POST['precio'];
        $unidades = $_POST['unidades'];
        $cantidad = $_POST['cantidad'];
        $fechaI = date("Y-m-d H:i:s");
        $fechaV = $_POST['fecha_vencimiento'];


        try {
            $stmt = $conexion->prepare("INSERT INTO inventario (nombre, categoria, proveedor, precio, unidades, cantidad, fecha_ingreso, fecha_vencimiento) VALUES (:nombre, :categoria, :proveedor, :precio, :unidades, :cantidad, :fecha_ingreso, :fecha_vencimiento)");

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
                    alert('Producto Agregado Exitósamente!');
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