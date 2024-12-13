<?php

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

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$usuario = $_POST['usuario'];
$password = $_POST['password'];


// Verificaciones
$verificar_usuario = $conexion->prepare("SELECT * FROM usuarios WHERE usuario=:usuario");
$verificar_usuario->bindParam(':usuario', $usuario);
$verificar_usuario->execute();

if ($verificar_usuario->rowCount() > 0) {
    echo '
    <script>
    alert("Este usuario ya está registrado, intenta con otro");
    window.location = "../registro.php";
    </script>
    ';
    exit();
}

$verificar_email = $conexion->prepare("SELECT * FROM usuarios WHERE email=:email");
$verificar_email->bindParam(':email', $email);
$verificar_email->execute();

if ($verificar_email->rowCount() > 0) {
    echo '
    <script>
    alert("Este email ya está registrado, intenta con otro");
    window.location = "../registro.php";
    </script>
    ';
    exit();
}

$verificar_telefono = $conexion->prepare("SELECT * FROM usuarios WHERE telefono=:telefono");
$verificar_telefono->bindParam(':telefono', $telefono);
$verificar_telefono->execute();

if ($verificar_telefono->rowCount() > 0) {
    echo '
    <script>
    alert("Este teléfono ya está registrado, intenta con otro");
    window.location = "../registro.php";
    </script>
    ';
    exit();
}


if (!preg_match('/^[0-9]{9}$/', $telefono)) {
    echo '
    <script>
    alert("Formato de teléfono inválido. Debe contener 9 dígitos");
    window.location = "../registro.php";
    </script>
    ';
    exit();
}

if (strlen($password) < 6) {
    echo '
    <script>
    alert("La contraseña debe tener al menos 6 caracteres");
    window.location = "../registro.php";
    </script>
    ';
    exit();
}


// Registro
$query_user_reg = "INSERT INTO usuarios (nombre, email, direccion, telefono, usuario, password) 
VALUES(:nombre, :email, :direccion, :telefono, :usuario, :password)";

$insertar_usuario = $conexion->prepare($query_user_reg);
$insertar_usuario->bindParam(':nombre', $nombre);
$insertar_usuario->bindParam(':email', $email);
$insertar_usuario->bindParam(':direccion', $direccion);
$insertar_usuario->bindParam(':telefono', $telefono);
$insertar_usuario->bindParam(':usuario', $usuario);
$insertar_usuario->bindParam(':password', $password);

if ($insertar_usuario->execute()) {
    echo '
    <script> 
    alert("Usuario registrado con éxito");
    window.location = "../acceso.php"; 
    </script>
    ';
} else {
    echo '
    <script> 
    alert("No se ha podido registrar el usuario");
    window.location = "../registro.php"; 
    </script>
    ';
}