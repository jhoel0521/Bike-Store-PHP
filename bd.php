<?php 
$servidor = "localhost"; //se puede poner localhost o el puerto 127.0.0.1
$baseDeDatos = "bike_store";
$usuario = "root";
$contrasena = "";
try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDeDatos", $usuario, $contrasena);
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}

?>