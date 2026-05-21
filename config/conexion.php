<?php

$conexion = mysqli_connect(
    "localhost",
    "root",
    "",
    "inventario_api"
);

if(!$conexion){
    die("Error de conexión");
}

?>