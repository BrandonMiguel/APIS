<?php

header("Content-Type: application/json");

include("../../config/conexion.php");

$metodo = $_SERVER['REQUEST_METHOD'];


// =====================================
// GET
// =====================================

if($metodo == "GET"){

    $sql = "SELECT * FROM productos ORDER BY id DESC";

    $resultado = mysqli_query($conexion, $sql);

    $productos = [];

    while($fila = mysqli_fetch_assoc($resultado)){
        $productos[] = $fila;
    }

    echo json_encode($productos);

}



// =====================================
// POST
// =====================================

if($metodo == "POST"){

    $datos = json_decode(file_get_contents("php://input"), true);

    $nombre = trim($datos["nombre"]);
    $precio = trim($datos["precio"]);
    $cantidad = trim($datos["cantidad"]);


    // VALIDAR VACIOS

    if(empty($nombre) || empty($precio) || empty($cantidad)){

        echo json_encode([
            "estado" => "error",
            "mensaje" => "Todos los campos son obligatorios"
        ]);

        exit();
    }


    // VALIDAR DUPLICADOS

    $verificar = $conexion->prepare(
        "SELECT id FROM productos WHERE nombre = ?"
    );

    $verificar->bind_param("s", $nombre);

    $verificar->execute();

    $resultado = $verificar->get_result();

    if($resultado->num_rows > 0){

        echo json_encode([
            "estado" => "error",
            "mensaje" => "Ese producto ya existe"
        ]);

        exit();
    }


    // INSERTAR

    $sql = $conexion->prepare(
        "INSERT INTO productos(nombre, precio, cantidad)
         VALUES(?,?,?)"
    );

    $sql->bind_param(
        "sdi",
        $nombre,
        $precio,
        $cantidad
    );

    if($sql->execute()){

        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Producto agregado correctamente"
        ]);

    }else{

        echo json_encode([
            "estado" => "error",
            "mensaje" => "Error al agregar"
        ]);

    }

}



// =====================================
// PUT
// =====================================

if($metodo == "PUT"){

    $datos = json_decode(file_get_contents("php://input"), true);

    $id = $datos["id"];
    $nombre = trim($datos["nombre"]);
    $precio = trim($datos["precio"]);
    $cantidad = trim($datos["cantidad"]);


    $sql = $conexion->prepare(
        "UPDATE productos
         SET nombre = ?,
             precio = ?,
             cantidad = ?
         WHERE id = ?"
    );

    $sql->bind_param(
        "sdii",
        $nombre,
        $precio,
        $cantidad,
        $id
    );

    if($sql->execute()){

        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Producto actualizado"
        ]);

    }else{

        echo json_encode([
            "estado" => "error",
            "mensaje" => "Error al actualizar"
        ]);

    }

}



// =====================================
// DELETE
// =====================================

if($metodo == "DELETE"){

    $datos = json_decode(file_get_contents("php://input"), true);

    $id = $datos["id"];


    $sql = $conexion->prepare(
        "DELETE FROM productos WHERE id = ?"
    );

    $sql->bind_param("i", $id);

    if($sql->execute()){

        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Producto eliminado"
        ]);

    }else{

        echo json_encode([
            "estado" => "error",
            "mensaje" => "Error al eliminar"
        ]);

    }

}

?>