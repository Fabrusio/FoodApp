<?php

class ErrorMessages{

    // ERROR_CONTROLLER_METHOD_ACTION
    const ERROR_ADMIN_NEWCATEGORY_EXISTS = "39e1d94aaae41a3ab7cdasafavvd2d6f5e3";
    const ERROR_LOGIN_AUTHENTICATE_EMPTY = "39e1ld64aeae41a3ab7dasafavvd2d7f8e3";//nuevo
    const ERROR_LOGIN_AUTHENTICATE_DATA = "49eJld64aeae41a3ab7dasafavvd2d7f8e3";//nuevo
    const ERROR_LOGIN_AUTHENTICATE = "49eJld64aeae41a37asdcasafavvd2d7f8e3";//nuevo
    const ERROR_LOGIN_AUTHENTICATE_STATE = "49eJld64aeae41a37asdcasa242434d2d7f8e3";//verifica si esta activo


    


    private $errorList = [];

    public function __construct() {
        $this->errorList = [
            ErrorMessages::ERROR_ADMIN_NEWCATEGORY_EXISTS=>'error',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY=>'Llena los campos de usuario y password',//nuevo
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA=>'Nombre de usuario y/o password incorrecto',//nuevo
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE=>'No se puede procesar la solicitud. Ingresa usuario y password',//nuevo
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE_STATE =>'Su usuario no esta activo, comunicate con su admin'//nuevo
        ];

    }
    public function get($hash){
        return $this->errorList[$hash];
    }

    public function existsKey($key){
        if(array_key_exists($key, $this->errorList)){
            return true;
        }else{
            return false;
        }
    }
}



?>