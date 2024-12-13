<?php
session_start();

// Información de la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "papp_db";

// Crear conexión PDO
try {
    $conexion = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

$usuario = $_POST['usuario'];
$password = $_POST['password'];

// Consulta usando PDO
$validar_login = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario AND password=:password");
$validar_login->bindParam(':usuario', $usuario);
$validar_login->bindParam(':password', $password);
$validar_login->execute();

if ($validar_login->rowCount() > 0) {
    $_SESSION['usuario'] = $usuario;
    $filas = $validar_login->fetch(PDO::FETCH_ASSOC);
    if ($filas['id_rol'] == 1) {
        header("location:../admin/adminDash.php");
    } elseif ($filas['id_rol'] == 2) {
        header("location:../cliente/perfilUsuario.php");
    }
} else {
    echo '
    <script> 
    alert("El usuario no existe");
    window.location = "../acceso.php";
    </script>
    ';
    exit();
}