<?php
include_once "/var/www/html/models/menumodel.php";
class Crud_menus extends SessionController {
    function __construct(){
        parent::__construct();
        error_log('DASHBOARD-> inicio de dashboard');

    }

    function render(){
        error_log('DASHBOARD-> CARGA EL INDEX DASHBOARD');
        $this->view->render('menu/crud_menus');
    }


    public function deleteMenu(){
        if($this->existPOST(['id'])){
            $id = $this->getPOST('id');


            $menuModel = new MenuModel();
        if ($menuModel->delete($id)) {
            error_log("Enviando menu a eliminar -----eliminando------ " . $id);
        } else {
            error_log("Error al eliminar el menu con ID " . $id);
        }
            
        }
    }


    public function createMenu(){
        if($this->existPOST(['name', 'detail', 'price','category','productosSeleccionados'])){
            error_log(print_r($_POST, true));

            $name = strtolower($_POST["name"]);
            $detail = strtolower($_POST["detail"]);
            $price = $_POST["price"];
            $category = $_POST["category"];
            error_log($name);
            error_log($name);error_log($name);error_log($name);error_log($name);error_log($name);error_log($name);error_log($name);error_log($name);
            $productosSeleccionados = $_POST["productosSeleccionados"];

            $menuModel = new MenuModel();
            if ($menuModel->nameExists(0, $name)) {
                echo "El nombre de menu ya está en uso. Por favor, elige otro nombre de menu.";
                error_log("El nombre de menu ya esta en uso");
            } else {
                
                if ($menuModel->createMenu($name, $detail, $price, $category)) {

                    $newMenuId = $menuModel->getLastMenuId();

                foreach ($productosSeleccionados as $producto) {

                    $productoId = $producto['id'];
                    $cantidad = $producto['cantidad'];
                    $cantidadN= (int)$cantidad;
                    error_log($cantidadN);

                    $menuModel->insertMenuIngredient($newMenuId, $productoId, $cantidadN);
                }

                    error_log("Menu esta siendo enviado ------------creando-----------.");
                } else {
                    error_log("Error al intentar enviar el menu.");
                }
            }
        }
    }




    public function editMenu(){
        if($this->existPOST(['id','name','category', 'detail', 'price'])){
            $id = $_POST["id"];
            error_log($id);
            $name = strtolower($_POST["name"]);
            error_log($name);
            // Verificar si el nuevo nombre de menu ya existe en la base de datos
            $menuModel = new MenuModel();
            if ($menuModel->nameExists($id, $name)) {
                echo "El nombre de menu ya está en uso. Por favor, elige otro nombre de menu.";
                error_log("El nombre de menu ya esta en uso");
            } else {
                    // Resto de tus variables
                    $category = $_POST["category"];
                    error_log($name);
                    $detail = strtolower($_POST["detail"]);
                    error_log($surname);
                    $price = $_POST["price"];

                $menuModel = new MenuModel();
                if ($menuModel->update($id, $name, $category, $detail, $price)) {
                    echo "menu actualizando.";
                } else {
                    echo "Error al actualizar el menu.";
                }
            }
        }
    }





////////////////////////// CATEGORIAS ////////////////////////
    public function createCat(){
        if($this->existPOST(['nameCat'])){

            $nameCat = strtolower($_POST["nameCat"]);

            $menuModel = new MenuModel();
            if ($menuModel->nameCatExists(0, $nameCat)) {
                echo "El nombre de la categoria ya está en uso. Por favor, elige otro nombre de categoria.";
                error_log("El nombre de categoria ya esta en uso");
            } else {

                if ($menuModel->createCat($nameCat)) {
                    error_log("Categoria se esta enviado ------------creando-----------.");
                } else {
                    error_log("Error al intentar enviar la categoria.");
                }
            }
        }
    }




    public function deleteCat(){
        if($this->existPOST(['id'])){
            $id = $this->getPOST('id');


            $menuModel = new MenuModel();
        if ($menuModel->deleteCat($id)) {
            error_log("Enviando categoria a eliminar -----eliminando------ " . $id);
        } else {
            error_log("Error al eliminar la categoria con ID " . $id);
        }
            
        }
    }




    public function editCat(){
        if($this->existPOST(['id','name'])){
            $id = $_POST["id"];
            error_log($id);
            $name = strtolower($_POST["name"]);
            error_log($name);
            // Verificar si el nuevo nombre de categoria ya existe en la base de datos
            $menuModel = new MenuModel();
            if ($menuModel->nameCatExists($id, $name)) {
                echo "El nombre la categoria ya está en uso. Por favor, elige otro nombre de categoria.";
                error_log("El nombre de categoria ya esta en uso");
            } else {

                $menuModel = new MenuModel();
                if ($menuModel->updateCat($id, $name)) {
                    echo "categoria actualizando.";
                } else {
                    echo "Error al actualizar la categoria.";
                }
            }
        }
    }







}

?>