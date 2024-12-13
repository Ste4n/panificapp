<?php

include ('../php/conexion_be.php');

if (isset($_GET['producto'])) {
    $idP = $_GET['producto'];

    try {
        $stmt = $conexion->prepare("DELETE FROM inventario WHERE id = :id");
        $stmt->bindParam('id', $idP, PDO::PARAM_STR);
        $stmt->execute();

        echo "
            <script>
                alert('Producto eliminado exitósamente!');
                window.location.href = '../admin/inventario.php';
            </script>
        ";
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}