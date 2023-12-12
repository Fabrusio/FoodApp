<?php
include_once "/var/www/html/models/productsmodel.php";

class Crud_products extends SessionController {
    function __construct(){
        parent::__construct();
        error_log('DASHBOARD-> inicio de dashboard');

    }

    function render(){
        error_log('DASHBOARD-> CARGA EL INDEX DASHBOARD');
        $this->view->render('products/crud_products');
    }

    public function deleteProduct(){
        if($this->existPOST(['id'])){
            $id = $_POST['id'];
            $productsModel = new ProductsModel();

            if($productsModel->delete($id)){
                echo "Producto eliminado exitosamente.";
            }else{
                echo "Error al eliminar el producto.";
            }
        }
    }

    public function createProduct(){
        if($this->existPOST(['productname', 'stock', 'productType', 'price','providerName', 'stockAlert'])){
            $productName = strtolower($_POST["productname"]);
            $stock = $_POST["stock"];
            $productType = $_POST["productType"];
            $price = $_POST["price"];
            $providerName = $_POST["providerName"];
            $stockAlert = $_POST["stockAlert"];

            $productsModel = new ProductsModel();
            if($productsModel->productNameExists(0, $productName)){
                echo "Ya existe ese producto.";
            } else{
                if ($productsModel->createProduct($productName, $stock, $price, $providerName, $stockAlert, $productType)) {
                    echo "Producto ingresado exitosamente.";
                } else {
                    echo "Error al crear el producto.";
                }
            }
        }
    }

    public function editProducts(){
        if($this->existPOST(['id', 'productName', 'stock', 'productType', 'price','providerName', 'stockAlert'])){
            $id = $_POST["id"];
            $name = strtolower($_POST["productName"]); 
            error_log('ACÁ ESTOY. SI ESTOY LLAMA AL CONTROLADOR');

            // Verificar si el nuevo nombre de usuario ya existe en la base de datos
            $productsModel = new ProductsModel();
            if ($productsModel->productNameExists($id, $name)) {
                echo "Ya existe ese producto.";
                error_log('CONTROLADOR::PRODUCTOS-> EL USUARIO YA EXISTE TE TIRA ERROR');
            } else {
                $productName = strtolower($_POST["productName"]);
                $stock = $_POST["stock"];
                $productType = $_POST["productType"];
                $price = $_POST["price"];
                $provider = $_POST["providerName"];
                $stockAlert = $_POST["stockAlert"];

                $productsModel = new ProductsModel();
                if ($productsModel->update($id, $productName, $stock, $price, $provider, $stockAlert, $productType)) {
                    error_log('CONTROLADOR::PRODUCTOS-> SE ENVÍA');
                } else {
                    echo "Error al actualizar el Producto.";
                    error_log('CONTROLADOR::PRODUCTOS-> HAY ALGÚN ERROR AL ACTUALIZAR EL PRODUCTO');
                }
            }
        }
    }

}//Cierra clase