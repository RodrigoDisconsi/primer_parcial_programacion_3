<?php
include_once 'auth.php';
include_once 'file.php';

class Profesor
{

    public $nombre;
    public $legajo;
    public $imagen;


    function __construct($nombre, $legajo, $imagen)
    {
        $this->nombre = $nombre;
        $this->legajo = $legajo;
        $this->imagen = $imagen;
    }

    public function guardarProfesor($token)
    {
        $token = Auth::validarToken($token);
        if ($token) {
            if (Profesor::seEncuentra($this->legajo)) {
                return 'El legajo ya se encuentra';
            }
            if($this->guardarImagenProfesor($this->imagen['tmp_name'], $this->imagen['name'])){
                File::guardar('profesores.xxx', $this);
                return 'Profesor cargado exitosamente.';
            }
            else{
                return 'Error al guardan la imagen';
            }
        }
        return 'token inválido';
    }

    public static function seEncuentra($legajo)
    {
        $profesores = File::leer('profesores.xxx');
        if ($profesores) {
            foreach ($profesores as $key) {
                if ($key->legajo == $legajo) {
                    return true;
                }
            }
        }
        return false;
    }


    private function guardarImagenProfesor($path, $nombre)
    {
        $folder = "imagenes/";
        return move_uploaded_file($path, $folder.time().'-'.$nombre);
    }

    public static function mostrarProfesores($token)
    {
        $token = Auth::validarToken($token);
        if ($token) {
            $profesores = File::leer('profesores.xxx') ?? null;
            if ($profesores) {
                return Profesor::stringProfesores($profesores, $token);
            }
        }
        return 'Token inválido';
    }

    private static function stringProfesores($profesores)
    {
        $retorno = '';
        foreach ($profesores as $key) {
            $retorno = $retorno . 'Legajo: ' . $key->legajo . PHP_EOL;
            $retorno = $retorno . 'Nombre: ' . $key->nombre . PHP_EOL;
            $retorno = $retorno . 'Imagen: ' . $key->imagen['tmp_name'] . PHP_EOL . PHP_EOL;
            $retorno = $retorno . '-----------------------' . PHP_EOL . PHP_EOL;
        }
        return $retorno;
    }
}