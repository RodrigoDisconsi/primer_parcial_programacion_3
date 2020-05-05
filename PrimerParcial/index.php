<?php
include_once 'vendor/autoload.php';
include_once 'clases/usuario.php';
include_once 'clases/pizzas.php';
include_once 'clases/ventas.php';

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
            $tipo = $_POST['tipo']??null;

            if($email && $clave && $tipo){
                $usuario = new Usuario($email, $clave, $tipo);
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
    
    case '/pizzas':
        if($method == 'POST'){

            $tipo = $_POST['tipo']??null;
            $sabor = $_POST['sabor']??null;
            $precio = $_POST['precio']??null;
            $stock = $_POST['stock']??null;
            $foto = $_FILES['foto']??null;

            if($tipo && $sabor && $precio && $stock && $foto){
                if($tipo == 'molde' || $tipo == 'piedra'){
                    if($sabor == 'jamón' || $sabor == 'napo' || $sabor == 'muzza'){
                        $pizza = new Pizza($tipo, $sabor, $precio, $stock, $foto);
        
                        $respuesta = $pizza->guardarProducto($token);
                    }
                    else{
                        $respuesta = 'Sabor inválido.';
                    }
                }
                else{
                    $respuesta = 'Tipo de pizza inválido.';
                }
            }
            else{
                $respuesta = 'Faltan parametros';
            }
        }
        else if ($method == 'GET'){
            $respuesta = Pizza::leerPizzas($token);
        }

        echo $respuesta;

    break;

    case '/ventas':
        if($method == 'POST'){
            $tipo = $_POST['tipo']??null;
            $sabor = $_POST['sabor']??null;
            if($tipo && $sabor){
                $venta = new Venta($tipo, $sabor);
                $respuesta = $venta->realizarVenta($token);
            }
        }
        else if($method == 'GET'){
            $respuesta = Venta::mostrarVentas($token);
        }

        echo $respuesta;

    break;

    default:

    break;
}



