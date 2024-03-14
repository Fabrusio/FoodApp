<?php

class Login extends SessionController {
    function __construct(){
        parent::__construct();
        error_log('Login-> inicio de login');

    }

    function render(){
        error_log('Login-> CARGA EL INDEX LOGIN');
        $this->view->render('login/index');
    }


    function authenticate() { // nuevo
        if($this->existPOST(['username', 'password'])){
            $username = $this->getPOST('username');
            $password = $this->getPOST('password');

            error_log('Login::authenticate-> user' .$username);

            if($username == '' || empty($username) || $password == '' || empty($password)){
                $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY]);
                return;
            }

            $user = $this->model->login($username, $password);

            if($user != NULL){
                if($user == -1){
                    error_log('Login::authenticate-> NO ESTA ACTIVO EL USUARIO');
                    $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_STATE]);
                    return;

                }
                error_log('Login::authenticate-> manda a la funcion inicialice');
                $this->initialize($user);
                
            }else{
                error_log('Login::authenticate-> la variable user es null');
                $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA]);
                return;
            }
        }else{
            error_log('Login::authenticate-> la variable user es null');
            $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE]);
        }
        
    }
}

?>