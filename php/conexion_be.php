<?php

//info base de datos

$server = "localhost";
$user = "root";
$pass = "";
$db = "papp_db";

//crear conexion

$conexion = new mysqli($server, $user, $pass, $db);

try {
    $conexion = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}