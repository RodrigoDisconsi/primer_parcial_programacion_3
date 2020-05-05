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

}