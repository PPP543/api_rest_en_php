<?php

$host = "localhost";
$usuario="root";
$password="";
$basededatos="apiphp";

$conexion = new mysqli($host, $usuario, $password, $basededatos);

if($conexion -> connect_error){
    die ("Conexión no establecida". $conexion->connect_error);
}

header("Content-Type: aplication/json");
$metodo =$_SERVER['REQUEST_METHOD'];
//Método para recuperar el id.
$path =isset($_SERVER ['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';
$buscarId=explode('/',$path);
$id= ($path!=='/') ? end ($buscarId): null;

print_r($metodo);


switch($metodo){
    //Select
    case 'GET':
    echo ("Consulta de registros -GET");
    consultaSelect($conexion, $id);
    break;
    //Insert
    case 'POST':
    echo ("Consulta de registros -POST");
    insertar($conexion);
    break;
    //Update
    case 'PUT';
    actualizar($conexion, $id);
    echo ("Consulta de registros -PUT");
    break;
    //Delete
    case 'DELETE':
    borrar($conexion, $id);
    echo ("Regitro borrado -DEETE");
    break;
    default:
    echo "Método no permirido";
    break;
}

function consultaSelect($conexion, $id) {
    $sql= ($id===null) ? "SELECT * FROM usuarios": "SELECT * FROM usuarios WHERE id_usuario =";
    $resultado = $conexion->query($sql);

    if($resultado){
        $datos= array();
        while ($fila=$resultado->fetch_assoc()){
            $datos[]=$fila;
        }
        echo json_encode($datos);
    }
    
}
function insertar($conexion) {
    $dato = json_decode(file_get_contents('php://input'),true);
    $nombre=$dato['nombre'];

    $sql= "INSERT INTO usuarios(nombre_usuario) VALUES ('$nombre')";
    $resultado = $conexion->query($sql);

    if($resultado){
        $dato['id'] = $conexion->insert_id;
        echo json_encode($dato);
    }else{
        echo json_encode(array('error'=>'Error al crear usuario'));
    }
}
function borrar($conexion, $id) {

    echo "El Usuario a eliminar es: ".$id;

    $sql= "DELETE FROM usuarios WHERE  id_usuario=$id";
    $resultado = $conexion->query($sql);

    if($resultado){
        echo json_encode(array('mensaje'=>'Usuario eliminado'));
    }else{
        echo json_encode(array('error'=>'Error al eliminar usuario'));
    }
  
}

function actualizar($conexion, $id) {

    $dato= json_decode(file_get_contents('php://input'),true);
    $nombre=$dato['nombre'];
    echo "El usuario a actualizar es: ".$id. " con el dato " .$nombre;

    $sql= "UPDATE usuarios SET nombre_usuario='$nombre' WHERE id_usuario=$id";
    $resultado = $conexion->query($sql);
    
    if($resultado){
        echo json_encode(array('mensaje'=>'Usuario actualizado'));
    }else{
        echo json_encode(array('error'=>'Error al actualizar usuario'));
    }
    
}
?>