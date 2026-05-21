<?php

header("Content-Type: application/json");

include("conexion.php");

$sql = "SELECT * FROM productos";

$resultado = mysqli_query($conexion, $sql);

$productos = [];

while($fila = mysqli_fetch_assoc($resultado)){
    $productos[] = $fila;
}

echo json_encode($productos);

?>