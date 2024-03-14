<?php
include_once "/var/www/html/models/usermodel.php";
class Crud_users extends SessionController {
    function __construct(){
        parent::__construct();
        error_log('DASHBOARD-> inicio de dashboard');

    }

    function render(){
        error_log('DASHBOARD-> CARGA EL INDEX DASHBOARD');
        $this->view->render('users/crud_users');
    }


    public function deleteUser(){
        if($this->existPOST(['id'])){
            $deleted = 0;
            $idUsuarioAEliminar = $this->getPOST('id');
            error_log('delete--> id: '. $idUsuarioAEliminar);
            $userModel = new UserModel();

        // Intenta eliminar el usuario con la función delete
        if ($userModel->delete($idUsuarioAEliminar, $deleted)) {
            echo "Usuario eliminado exitosamente.";
        } else {
            echo "Error al eliminar el usuario.";
            error_log("Error al eliminar el usuario con ID " . $idUsuarioAEliminar);
        }
            
        }
    }



    public function enabledUser(){
        if($this->existPOST(['id'])){
            $enabled = 1;
            $idUsuarioAHabilitar = $this->getPOST('id');
            error_log('dolete--> id: '. $idUsuarioAHabilitar);
            $userModel = new UserModel();

        // Intenta eliminar el usuario con la función delete
        if ($userModel->enabled($idUsuarioAHabilitar, $enabled)) {
            echo "Usuario habilitado exitosamente.";
        } else {
            echo "Error al habilitar el usuario.";
            error_log("Error al habilitar el usuario con ID " . $idUsuarioAHabilitar);
        }
            
        }
    }





    public function editUser(){
        if($this->existPOST(['id', 'nombre', 'name', 'surname','dni', 'gender', 'province', 'localidad', 'street',
        'bwStreet','bwStreetTwo', 'altura', 'cel','password', 'email', 'rol'])){
            $id = $_POST["id"];
            error_log($id);
            $username = $_POST["nombre"];
            error_log($username);
            $dni = $_POST["dni"];
            error_log($dni);
            // Verificar si el nuevo nombre de usuario ya existe en la base de datos
            $userModel = new UserModel();
            if ($userModel->usernameExists($id, $username,$dni)) {
                echo "El nombre de usuario ya está en uso. Por favor, elige otro nombre de usuario.";
                error_log("El nombre de usuario ya esta en uso");
            } else {
                // Resto de tus variables
                $name = ucwords(strtolower(($_POST["name"])));
                error_log($name);
                $surname = ucwords(strtolower(($_POST["surname"])));
                error_log($surname);
                $gender = $_POST["gender"];
                $province = strtolower($_POST["province"]);
                $localidad = strtolower($_POST["localidad"]);
                $street = strtolower($_POST["street"]);
                $bwStreet = strtolower($_POST["bwStreet"]);
                $bwStreetTwo = strtolower($_POST["bwStreetTwo"]);
                $altura = $_POST["altura"];
                $cel = $_POST["cel"];
                $password = $_POST["password"];
                $email = strtolower($_POST["email"]);
                $rol = $_POST["rol"];

            $userModel = new UserModel();
    if ($userModel->update($id, $username, $name, $surname, $dni, $gender, $province, $localidad, $street, 
    $bwStreet, $bwStreetTwo, $altura, $cel, $password ,$email, $rol)) {
        echo "Usuario actualizado exitosamente.";
    } else {
        echo "Error al actualizar el usuario.";
    }
}
}
}
    public function createUser(){
        if($this->existPOST(['username', 'name', 'surname','dni', 'gender', 'province', 'localidad', 'street',
        'bwStreet','bwStreetTwo', 'altura', 'cel', 'email', 'rol','password'])){
            $username = $_POST["username"];
            $name = ucwords(strtolower(($_POST["name"])));
            $surname = ucwords(strtolower(($_POST["surname"])));
            $dni = $_POST["dni"];
            $gender = $_POST["gender"];
            $province = strtolower($_POST["province"]);
            $localidad = strtolower($_POST["localidad"]);
            $street = strtolower($_POST["street"]);
            $bwStreet = strtolower($_POST["bwStreet"]);
            $bwStreetTwo = strtolower($_POST["bwStreetTwo"]);
            $altura = $_POST["altura"];
            $cel = $_POST["cel"];
            $email = strtolower($_POST["email"]);
            $rol = $_POST["rol"];
            $password = $_POST["password"];

            $userModel = new UserModel();
            if ($userModel->usernameExists(0, $username,$dni)) {
                echo "El nombre de usuario ya está en uso. Por favor, elige otro nombre de usuario.";
                error_log("El nombre de usuario ya esta en uso");
            } else{

            
            
                if ($userModel->createUser($username, $name, $surname, $dni, $gender, $province, $localidad, $street, 
                $bwStreet, $bwStreetTwo, $altura, $cel, $email, $rol, $password)) {
                    echo "Usuario creado exitosamente.";
                } else {
                    echo "Error al crear el usuario.";
                }
            }
        }
    }


}

?>