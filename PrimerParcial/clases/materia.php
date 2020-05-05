<?php
include_once 'auth.php';
include_once 'file.php';

class Materia
{

    public $id;
    public $materia;
    public $cuatrimestre;


    function __construct($materia, $cuatrimestre)
    {
        $this->materia = $materia;
        $this->cuatrimestre = $cuatrimestre;
    }

    public function guardarProducto($token)
    {
        $token = Auth::validarToken($token);
        if ($token) {
            if ($this->seEncuentra()) {
                return 'La materia ya se encuentra';
            }
            File::guardar('materias.xxx', $this);
            return 'Materia guardada';
        }
        return 'token inválido';
    }

    private function seEncuentra()
    {
        $materias = File::leer('materias.xxx');
        if ($materias) {
            foreach ($materias as $key) {
                if ($key->materia == $this->materia) {
                    return true;
                }
            }
        }
        $this->setId($materias);
        return false;
    }

    private function setId($materias)
    {
        if ($materias) {
            $this->id = end($materias)->id + 1;
        } else {
            $this->id = 1;
        }
    }

    public static function findById($id){
        $materias = File::leer('materias.xxx');
        if($materias){
            foreach($materias as $value){
                if($value->id == $id){
                    return $value;
                }
            }
        }
        return false;
    }


    public static function mostrarMaterias($token)
    {
        $token = Auth::validarToken($token);
        if ($token) {
            $materias = File::leer('materias.xxx') ?? null;
            if ($materias) {
                return Materia::stringMaterias($materias, $token);
            }
        }
        return 'Token inválido';
    }

    private static function stringMaterias($materias)
    {
        $retorno = '';
        foreach ($materias as $key) {
            $retorno = $retorno . 'ID: ' . $key->id . PHP_EOL;
            $retorno = $retorno . 'Materia: ' . $key->materia . PHP_EOL;
            $retorno = $retorno . 'Cuatrimestre: ' . $key->cuatrimestre . PHP_EOL .PHP_EOL;
            $retorno = $retorno . '-----------------------' . PHP_EOL . PHP_EOL;
        }
        return $retorno;
    }

}