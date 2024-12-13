<?php
include '../php/conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orden_id = $_POST['orden_id'];
    $estado_pago = $_POST['estado_pago'];

    $sql = "UPDATE orden SET estado_pago = :estado_pago WHERE id = :orden_id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['estado_pago' => $estado_pago, 'orden_id' => $orden_id]);

    if ($stmt->rowCount() > 0) {
        echo '
        <script> 
        alert("Estado de pago actualizado correctamente");
        window.location = "adminPedidos.php";
        </script>
        ';
    } else {
        echo "Error actualizando el estado de pago del pedido";
    }
}