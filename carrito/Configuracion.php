<?php

// Detalles de la base de datos

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'papp_db';

try {
    // Crear conexión y seleccionar la base de datos

    $db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
    
    // Establecer el modo de error de PDO a excepción

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("No hay conexión con la base de datos: " . $e->getMessage());
}