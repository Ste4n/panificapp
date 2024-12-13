<?php
include '../php/conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['estadop'])) {
        $id = $_POST['id'];
        $estadop = $_POST['estadop'];

        // Validación y sanitización de datos
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $estadop = filter_var($estadop, FILTER_SANITIZE_STRING);

        try {
            $stmt = $conexion->prepare("UPDATE horarios SET estadop = :estadop WHERE id = :id");

            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":estadop", $estadop, PDO::PARAM_STR);

            $stmt->execute();

            echo "
                <script>
                    alert('Estado actualizado correctamente');
                    window.location.href = '../admin/horarios.php';
                </script>
            ";

            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "
            <script>
                alert('¡Por favor rellena todos los campos!');
                window.location.href = '../admin/horarios.php';
            </script>
        ";
    }
}