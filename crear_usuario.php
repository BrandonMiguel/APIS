<?php

include("config/conexion.php");

$usuario = "director";

$password = password_hash("321", PASSWORD_DEFAULT);

$sql = $conexion->prepare(
    "INSERT INTO usuarios(usuario, password)
     VALUES(?, ?)"
);

$sql->bind_param("ss", $usuario, $password);

if($sql->execute()){

    echo "Usuario creado";

}else{

    echo "Error";
}

?>