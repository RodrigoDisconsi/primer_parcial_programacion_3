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

}