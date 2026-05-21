<?php

include("config/conexion.php");

$usuario = "john";

$password = password_hash("12345", PASSWORD_DEFAULT);

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