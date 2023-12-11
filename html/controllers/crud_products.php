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
        if($this->existPOST(['productname', 'stock', 'productType', 'price','providerName', 'stockAlert', 'expirationDate', 'batchNumber', 'purchaseDate'])){
            $productName = strtolower($_POST["productname"]);
            $stock = $_POST["stock"];
            $productType = $_POST["productType"];
            $price = $_POST["price"];
            $providerName = $_POST["providerName"];
            $stockAlert = $_POST["stockAlert"];
            $expirationDate = $_POST["expirationDate"];
            $batchNumber = strtoupper($_POST["batchNumber"]);
            $purchaseDate = $_POST["purchaseDate"];

            $productsModel = new ProductsModel();
            if($productsModel->productNameExists(0, $productName) or $productsModel->batchNumberExists(0, $batchNumber)){
                echo "Ya existe ese producto y/o número de lote.";
                error_log('CONTROLADOR::PRODUCTOS-> Existe el producto o número de lote.');
            } else{
                if ($productsModel->createProduct($productName, $stock, $price, $providerName, $stockAlert, $productType, $expirationDate, $batchNumber, $purchaseDate)) {
                    echo "Producto ingresado exitosamente.";
                } else {
                    echo "Error al crear el producto.";
                }
            }
        }
    }

    public function editProducts(){
        if($this->existPOST(['id', 'productName', 'stock', 'productType', 'price','providerName', 'stockAlert', 'expirationDate', 'batchNumber', 'purchaseDate'])){
            $id = $_POST["id"];
            $name = strtolower($_POST["productName"]); 
            $batchNumber = strtoupper($_POST["batchNumber"]);
            error_log('ACÁ ESTOY. SI ESTOY LLAMA AL CONTROLADOR');

            $productsModel = new ProductsModel();
            if ($productsModel->productNameExists($id, $name) or $productsModel->batchNumberExists($id, $batchNumber)) {
                echo "Ya existe ese producto y/o número de lote.";
                error_log('CONTROLADOR::PRODUCTOS-> EL PRODUCTO O NÚMERO DE LOTE YA EXISTE, TE TIRA ERROR');
            } else {
                $productName = strtolower($_POST["productName"]);
                $stock = $_POST["stock"];
                $productType = $_POST["productType"];
                $price = $_POST["price"];
                $provider = $_POST["providerName"];
                $stockAlert = $_POST["stockAlert"];
                $expirationDate = $_POST["expirationDate"];
                $purchaseDate = $_POST["purchaseDate"];

                $productsModel = new ProductsModel();
                if ($productsModel->update($id, $productName, $stock, $price, $provider, $stockAlert, $productType, $expirationDate, $batchNumber, $purchaseDate)) {
                    error_log('CONTROLADOR::PRODUCTOS-> SE ENVÍA');
                } else {
                    echo "Error al actualizar el Producto.";
                    error_log('CONTROLADOR::PRODUCTOS-> HAY ALGÚN ERROR AL ACTUALIZAR EL PRODUCTO');
                }
            }
        }
    }

}//Cierra clase