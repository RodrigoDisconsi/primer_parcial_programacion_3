<?php
include_once 'file.php';
include_once 'auth.php';

class Usuario{

    public $id;
    public $email;
    public $clave;


    public function __construct($email, $clave){
        $this->email = $email;
        $this->clave = $clave;
    }
    

    private function seEncuentra(){
        $usuarios = File::leer('users.xxx')??null;
        if($usuarios){
            foreach ($usuarios as $key) {
                if($key->email === $this->email){
                    return true;
                }
            }
        }
        $this->setId($usuarios);
        return false;
    }

    private function setId($usuarios){
        if($usuarios){
            $this->id = end($usuarios)->id +1;
        }
        else{
            $this->id = 1;
        }
    }

    public function signin(){
        if(!$this->seEncuentra()){
            File::guardar('users.xxx', $this);
            return "Registrado correctamente.";
        }
        else{
            return "El email ya se encuentra.";
        }
    }

    public static function login($email, $clave){
        $usuarios = File::leer('users.xxx');
        if($usuarios){
            foreach($usuarios as $key){
                if($key->email === $email && $key->clave === $clave){
                    return Auth::generarToken($email, $key->id);
                }
            }
        }
        return false;
    }

    // public static function findById($id){
    //     $usuarios = File::leer('usuers.xxx');
    //     if($usuarios){
    //         foreach($usuarios as $value){
    //             if($value->id == $id){
    //                 return $value;
    //             }
    //         }
    //     }
    //     return false;
    // }

}
