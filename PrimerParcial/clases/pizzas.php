<?php
include_once 'auth.php';
include_once 'file.php';

class Pizza{
    
    public $id;
    public $tipo;
    public $sabor;
    public $precio;
    public $stock;
    public $foto;


    function __construct($tipo, $sabor, $precio, $stock, $foto){
        $this->tipo = $tipo;
        $this->sabor = $sabor;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->foto = $foto;
    }

    public function guardarProducto($token){
        $token = Auth::validarToken($token);
        if($token){
            if($token->tipo == 'encargado')
            {
                
                if($this->seEncuentra()){
                    return 'La combinación ya se encuentra';
                }
                if($this->guardarImagenProducto($this->foto['tmp_name'], $this->foto['name'])){
                    File::guardar('pizzas.xxx', $this);
                    return 'Pizza guardada';
                }
                else{
                    return 'Error al guardar la imagén';
                }
            }
        }
        return 'token inválido';
    }

    private function seEncuentra(){
        $pizzas = File::leer('pizzas.xxx');
        if($pizzas){
            foreach ($pizzas as $key) {
                if($key->tipo == $this->tipo && $this->sabor == $key->sabor){
                    return true;
                }
            }
        }
        $this->setId($pizzas);
        return false;
    }

    private function setId($pizzas){
        if($pizzas){
            $this->id = end($pizzas)->id + 1;
        }
        else{
            $this->id = 1;
        }
    }

    public function guardarImagenProducto($path, $nombre){
        $folder = "imagenes/";
        return move_uploaded_file($path, $folder.time().'-'.$nombre);
    }

    public static function leerPizzas($token){
        $token = Auth::validarToken($token);
        if($token){
            $pizzas = File::leer('pizzas.xxx')??null;
            if($pizzas){
                return Pizza::stringProdcutos($pizzas, $token);
            }
        }
        return 'Token inválido';
    }

    private static function stringProdcutos($productos, $token){
        $retorno = '';
        foreach ($productos as $key) {
            $retorno = $retorno.'Tipo: '.$key->tipo.PHP_EOL;
            $retorno = $retorno.'Sabor: '.$key->sabor.PHP_EOL;
            $retorno = $retorno.'Precio: '.$key->precio.PHP_EOL;
            if($token->tipo == 'encargado'){
                $retorno = $retorno.'Stock: '.$key->stock.PHP_EOL.PHP_EOL;
            }
            $retorno = $retorno.'-----------------------'.PHP_EOL.PHP_EOL;
        }
        return $retorno;
    }


    public static function venderProcuto($tipo, $sabor){
        $retorno = -1;
        $pizzas = File::leer('pizzas.xxx');
        if($pizzas){
            // for ($i=0; $i < count($pizzas) ; $i++) { 
            //     if($pizzas[$i]->tipo == $tipo && $pizzas[$i]->sabor == $sabor){
            //        if($pizzas[$i]->stock > 0){
            //             $pizzas[$i]->stock = $pizzas[$i]->stock - 1;
            //             $retorno = 1 * $pizzas[$i]->precio;
            //             File::guardar('pizzas.xxx', $pizzas, true);
            //         }
            //         else{
            //             $retorno = 0;
            //         }
            //     }
            // }
            foreach ($pizzas as $key) {
                if($key->tipo == $tipo && $key->sabor == $sabor){
                    if($key->stock > 0){
                        $key->stock = $key->stock - 1;
                        $retorno = 1 * $key->precio;
                        File::guardar('pizzas.xxx', $pizzas, true);
                        return $retorno;
                    }   
                    else{
                        return 0;
                    }
                }
            }
        }
        return $retorno;
    }
}