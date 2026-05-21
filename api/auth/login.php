<?php

session_start();

header("Content-Type: application/json");

include("../../config/conexion.php");

$datos = json_decode(file_get_contents("php://input"), true);


// VALIDAR SI NO LLEGARON DATOS

if(!$datos){

    echo json_encode([

        "estado" => "error",
        "mensaje" => "No se recibieron datos"

    ]);

    exit();

}


$usuario = trim($datos["usuario"]);
$password = trim($datos["password"]);


$sql = $conexion->prepare(
    "SELECT * FROM usuarios WHERE usuario = ?"
);

$sql->bind_param("s", $usuario);

$sql->execute();

$resultado = $sql->get_result();


// VERIFICAR SI EXISTE

if($resultado->num_rows > 0){

    $usuarioDB = $resultado->fetch_assoc();

    // VERIFICAR PASSWORD

    if(password_verify($password, $usuarioDB["password"])){

        $_SESSION["usuario"] = $usuarioDB["usuario"];

        echo json_encode([

            "estado" => "ok",
            "mensaje" => "Login correcto"

        ]);

    }else{

        echo json_encode([

            "estado" => "error",
            "mensaje" => "Contraseña incorrecta"

        ]);

    }

}else{

    echo json_encode([

        "estado" => "error",
        "mensaje" => "Usuario no encontrado"

    ]);

}

?>