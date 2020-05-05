<?php
include_once 'vendor/autoload.php';
include_once 'clases/usuario.php';
include_once 'clases/materia.php';
include_once 'clases/profesor.php';
include_once 'clases/asignacion.php';

include_once 'clases/file.php';


use \Firebase\JWT\JWT;


$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "";
$headers = getallheaders();
$token = $headers['token']??null;
$respuesta;


switch($path){
    case '/usuario':

        if($method == 'POST'){
            $email = $_POST['email']??null;
            $clave = $_POST['clave']??null;

            if($email && $clave){
                $usuario = new Usuario($email, $clave);
                $respuesta = $usuario->signin();

            }
            else{
                $respuesta = 'Faltan parametros';
            }
        }
        else{
            $respuesta = 'Método no disponible';
        }
        echo $respuesta;


    break; 


    case '/login':
        if($method == 'POST'){
            $email = $_POST['email']??null;
            $clave = $_POST['clave']??null;
            
            if($email && $clave){
                $token = Usuario::login($email, $clave);
    
                if($token){
                    $respuesta = 'Su token es: '.$token;
                }
                else{
                    $respuesta = 'Verifique los datos';
                }
            }
            else{
                $respuesta = 'Faltan parametros';
            }
        }
        else{
            $respuesta = 'Método no disponible';
        }
        echo $respuesta;

    break;
    
    case '/materia':
        if($method == 'POST'){

            $materia = $_POST['nombre']??null;
            $cuatrimestre = $_POST['cuatrimestre']??null;

            if($materia && $cuatrimestre){

                $materia = new Materia($materia, $cuatrimestre);
        
                $respuesta = $materia->guardarProducto($token);
            }
            else{
                $respuesta = 'Faltan parametros';
            }
        }

        echo $respuesta;

    break;

    case '/profesor':
        if($method == 'POST'){

            $nombre = $_POST['nombre']??null;
            $legajo = $_POST['legajo']??null;
            $imagen = $_FILES['imagen']??null;

            if($nombre && $legajo && $imagen){

                $pizza = new Profesor($nombre, $legajo, $imagen);
        
                $respuesta = $pizza->guardarProfesor($token);
            }
            else{
                $respuesta = 'Faltan parametros';
            }
        }
        // else if ($method == 'GET'){
        //     $respuesta = Pizza::leerPizzas($token);
        // }

        echo $respuesta;

    break;

    case '/asignacion':
        if($method == 'POST'){

            $legajo = $_POST['legajo']??null;
            $id = $_POST['id']??null;
            $turno = $_POST['turno']??null;

            if($id && $legajo && $turno){

                if($turno === 'noche' || $turno === 'manana' || $turno === 'mañana'){
                    $asignacion = new Asignacion($legajo, $id, $turno);
                    $respuesta = $asignacion->guardarAsignacion($token);
                }
                else{
                    $respuesta = 'turno inválido';
                }
    
            }
            else{
                $respuesta = 'Faltan parametros';
            }
        }
        // else if ($method == 'GET'){
        //     $respuesta = Pizza::leerPizzas($token);
        // }

        echo $respuesta;

    break;

    default:

    break;
}



