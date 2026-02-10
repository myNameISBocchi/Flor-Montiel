<?php
namespace App\Helpers;
class Message{
    public static function stored(){
        return "Registro Guardado";
    }
    public static function updated(){
        return "Registro Actualizado";
    }
    public static function delete(){
        return "Registro Eliminado";
    }
    public static function findAll(){
        return "Registros Encontrados";
    }
    public static function findById(){
        return "Registro Encontrado";
    }
    public static function duplicate(){
        return "Registro Duplicado";
    }
    public static function errorServer(){
        return "Error del Servidor";
    }
    

}

?>