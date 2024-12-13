<?php
include '../php/conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fecha'], $_POST['turno'], $_POST['cproducir'], $_POST['cagendada'], $_POST['estadop'])) {
        $fecha = $_POST['fecha'];
        $turno = $_POST['turno'];
        $cproducir = $_POST['cproducir'];
        $cagendada = $_POST['cagendada'];
        $estadop = $_POST['estadop'];

        // Validación y sanitización de datos
        $fecha = filter_var($fecha, FILTER_SANITIZE_STRING);
        $turno = filter_var($turno, FILTER_SANITIZE_STRING);
        $cproducir = filter_var($cproducir, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $cagendada = filter_var($cagendada, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $estadop = filter_var($estadop, FILTER_SANITIZE_STRING);

        try {
            $stmt = $conexion->prepare("INSERT INTO horarios (fecha, turno, cproducir, cagendada, estadop) VALUES (:fecha, :turno, :cproducir, :cagendada, :estadop)");

            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":turno", $turno, PDO::PARAM_STR);
            $stmt->bindParam(":cproducir", $cproducir, PDO::PARAM_STR);
            $stmt->bindParam(":cagendada", $cagendada, PDO::PARAM_STR);
            $stmt->bindParam(":estadop", $estadop, PDO::PARAM_STR);

            $stmt->execute();

            echo "
                <script>
                    alert('Horario Insertado Exitósamente!');
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