<?php

include_once 'auth.php';
include_once 'file.php';
include_once 'profesor.php';
include_once 'materia.php';

class Asignacion{
    public $id;
    public $legajoProfesor;
    public $idMateria;
    public $turno;


    public function __construct($legajo, $materia, $turno)
    {
        $this->legajoProfesor = $legajo;
        $this->idMateria = $materia;
        $this->turno = $turno;
    }


    public function guardarAsignacion($token)
    {
        $token = Auth::validarToken($token);
        if ($token) {
            if(!Profesor::seEncuentra($this->legajoProfesor)) {
                return 'El legajo no se encuentra.';
            }
            if(!Materia::findById($this->idMateria)){
                return 'La materia no se encuentra.';
            }
            if($this->seEncuentra()){
                return 'La asignación ya se encuentra.';
            }
            File::guardar('materias-profesores.xxx', $this);
            return 'Asignación exitosa';
        }
        return 'token inválido';
    }

    private function seEncuentra()
    {
        $asignacion = File::leer('materias-profesores.xxx');
        if ($asignacion) {
            foreach ($asignacion as $key) {
                if ($key->legajoProfesor == $this->legajoProfesor && $key->idMateria == $this->idMateria && $this->turno == $key->turno) {
                    return true;
                }
            }
        }
        $this->setId($asignacion);
        return false;
    }

    private function setId($asignacion)
    {
        if ($asignacion) {
            $this->id = end($asignacion)->id + 1;
        } else {
            $this->id = 1;
        }
    }


    public static function mostrarAsignacion($token){
        $token = Auth::validarToken($token);
        $profesores = File::leer('profesores.xxx')??null;
        $asignaciones = File::leer('materias-profesores.xxx')??null;
        $materias = File::leer('materias.xxx')??null;
        $retorno = '';
        if($token){
            if($profesores && $asignaciones && $materias){
                foreach($asignaciones as $asignacion){
                    foreach($profesores as $profesor){
                        if($asignacion->legajoProfesor == $profesor->legajo){
    
                            $retorno = $retorno.'Nombre del profesor: '.$profesor->nombre.' - Materias: ';
    
                            foreach($materias as $materia){
                                if($asignacion->idMateria == $materia->id){
                                    $retorno = $retorno.$materia->materia.' ';
                                }
                            }
                            $retorno = $retorno.' - Turno: '.$asignacion->turno.PHP_EOL;
                        }
                    }
                }
                return $retorno;
            }
            else{
                return 'Error al abrir los archivos';
            }
        }
        else{
            return 'Token inválido';
        }
    }
}