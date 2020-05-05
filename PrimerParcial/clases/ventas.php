<?php
include_once 'pizzas.php';

class Venta{

    public $id;
    public $tipo;
    public $sabor;
    public $email;
    public $monto;
    public $fecha;

    public function __construct($tipo, $sabor)
    {
        $this->tipo = $tipo;
        $this->sabor = $sabor;
    }

    private function setId(){
        $ventas = File::leer('ventas.xxx')??array();
        if($ventas){
            $this->id = end($ventas)->id +1;
        }
        else{
            $this->id = 1;
        }
    }

    public function realizarVenta($token){
        $token = Auth::validarToken($token);
        if($token && $token->tipo == 'cliente'){            
            $monto = Pizza::venderProcuto($this->tipo, $this->sabor);
            if($monto > 0){
                $this->monto = $monto;
                $this->setId();
                $this->email = $token->email;
                $this->fecha = time();
                File::guardar('ventas.xxx', $this);
                return 'Venta realizada.';
            }
            else if($monto == -1){
                return 'No se encuentra la pizza';
            }
            else{
                return 'No hay suficiente stock';
            }
        }
        else{
            return 'Usuario incorrecto';
        }
    }


    // public static function mostrarVentas($token){
    //     $token = Auth::validarToken($token);
    //     $ventas = File::leer('ventas.xxx');
    //     $montoTotal = 0;
    //     $retorno = '';
    //     foreach ($ventas as $key) {
    //         if($token->tipo === 'encargado'){
    //             $montoTotal += $key->monto;
    //         }  
    //         else if($token->tipo === 'cliente'){
    //             if($key->email == $token->email){
    //                 $retorno = $retorno.Venta::stringVentasUsuario($key);
    //             }
    //         }
    //     }
    //     if($token->tipo === 'encargado'){
    //         $retorno = 'Cantidad de ventas: '.count($ventas).' - '.'Monto total: $'.$montoTotal;
    //     }
    //     return $retorno;
    // }

    public static function mostrarVentas($token){
        $token = Auth::validarToken($token);
        $ventas = File::leer('ventas.xxx');
        $montoTotal = 0;
        $retorno = '';
        if($token){
            if($ventas){
                if($token->tipo == 'encargado'){
                    $retorno = Venta::stringVentasEncargado($ventas);
                }
                else{
                    $retorno = Venta::stringVentasUsuario($ventas, $token->email);
                }
            }
            else{
                $retorno = 'Error al abrir el archivo de ventas';
            }
        }
        else{
            $retorno = 'Token inválido.';
        }
        return $retorno;
    }

    private static function stringVentasUsuario($ventas, $email){
        $retorno = '';
        foreach ($ventas as $key) {
            if($email == $key->email){
                $retorno = $retorno.'Tipo: '.$key->tipo.PHP_EOL;
                $retorno = $retorno.'Sabor: '.$key->sabor.PHP_EOL;
                $retorno = $retorno.'Monto: '.$key->monto.PHP_EOL;
                $retorno = $retorno.'Fecha: '.$key->fecha.PHP_EOL.PHP_EOL;
                $retorno = $retorno.'-----------------------'.PHP_EOL.PHP_EOL;
            }
        }
        return $retorno;
    }

    private static function stringVentasEncargado($ventas){
        $montoTotal = 0;
        foreach ($ventas as $key) {
            $montoTotal += $key->monto;
        }
        return 'Cantidad de ventas: '.count($ventas).' - Monto total: $'.$montoTotal;
    }

}