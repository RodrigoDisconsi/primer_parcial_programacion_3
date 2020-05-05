<?php

class File{

    public static $folder = './';

    public static function guardar($archivo, $datos, $actualizar = false){
        $archivo = File::$folder.$archivo;
        if(!$actualizar){
            $array = File::leer($archivo)??array();   //Por si el archivo es null 
            array_push($array, $datos);
            $datos = $array;
        }
        $file = fopen($archivo, 'w');
        $rta = fwrite($file, serialize($datos));
        fclose($file);
        return $rta;
    }

    public static function leer($archivo){
        $archivo = File::$folder.$archivo;
        if(file_exists($archivo) && filesize($archivo) > 0){ //Por si no existe o está vacío 
            $file = fopen($archivo, 'r');

            $arrayString = fgets($file);

            $retorno = unserialize($arrayString);

            fclose($file);

            return $retorno;
        }
        return null;
    }

    public static function guardarJSON($datos, $actualizar = false){
        $archivo = File::$folder.'productos.json';
        if(!$actualizar){
            $arrayJSON = File::leerJSON($archivo)??array();
            array_push($arrayJSON, $datos);
            $datos = $arrayJSON;       
        }
        $file = fopen($archivo, 'w+');
        $rta = fwrite($file, json_encode($datos));
        fclose($file);
        return $rta;

    }

    public static function leerJSON(){
        $archivo = './productos.json';
        if(file_exists($archivo) && filesize($archivo) > 0){
            $file = fopen($archivo, 'r');

            $arrayString = fread($file, filesize($archivo));

            $arrayJSON = json_decode($arrayString, true);

            fclose($file);

            return $arrayJSON;
        }
        return null;
    }
}