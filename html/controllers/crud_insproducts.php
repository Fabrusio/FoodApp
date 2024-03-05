<?php
include_once "/var/www/html/models/insproductsmodel.php";  
include_once "/var/www/html/models/productsmodel.php"; 

class Crud_insproducts extends SessionController {
    function __construct(){
        parent::__construct();
        error_log('DASHBOARD-> inicio de dashboard');

    }

    function render(){
        error_log('DASHBOARD-> CARGA EL INDEX DASHBOARD');
        $this->view->render('products_insert/crud_inserted_products');
    }

    public function insertProduct(){
        if($this->existPOST(['productName', 'quantity', 'price','providerName', 'expirationDate', 'batchNumber', 'purchaseDate'])){
            $productName = strtolower($_POST["productName"]);
            $quantity = $_POST["quantity"];
            $price = $_POST["price"];
            $providerName = $_POST["providerName"];
            $expirationDate = $_POST["expirationDate"];
            $batchNumber = strtoupper($_POST["batchNumber"]);
            $purchaseDate = $_POST["purchaseDate"];
            error_log('LLEGÓ AL CREATE');

            $insproductsModel = new InsProductsModel();

            if($insproductsModel->batchNumberExists(0, $batchNumber)){
                echo "Ya existe ese número de lote.";
                error_log('CONTROLADOR::INSERTARPRODUCTOS-> Existe el número de lote.');
            } else{
                if ($insproductsModel->insertProduct($productName, $quantity, $price, $providerName, $expirationDate, $batchNumber, $purchaseDate)) {
                    echo "Producto ingresado exitosamente.";
                } else {
                    echo "Error al crear el producto.";
                }
            }
        }
    }

    public function editInsertedProducts(){
    if($this->existPOST(['id', 'productName', 'quantity', 'price','providerName', 'expirationDate', 'batchNumber', 'purchaseDate'])){
            $id = $_POST["id"];
            $batchNumber = strtoupper($_POST["batchNumber"]);
            error_log('ACÁ ESTOY. SI ESTOY LLAMA AL CONTROLADOR');

            $insproductsModel = new InsProductsModel();
            $insProducts = $insproductsModel->get($id);

            if ($insproductsModel->batchNumberExists($id, $batchNumber)) {
                echo "Ya existe ese producto y/o número de lote.";
                error_log('CONTROLADOR::INSERTEDPRODUCTOS-> EL NÚMERO DE LOTE YA EXISTE, TE TIRA ERROR');
            } else {
                $productName = $_POST["productName"];
                error_log('CONTROLADOR::INSERTEDPRODUCTOS-> PRODUCTO: ' . $productName);
                $quantity = $_POST["quantity"];
                $price = $_POST["price"];
                $provider = $_POST["providerName"];
                $expirationDate = $_POST["expirationDate"];
                $purchaseDate = $_POST["purchaseDate"];

                $previousQuantity = $insProducts->getQuantity();
                error_log('CONTROLADOR::INSERTEDPRODUCTOS-> CANTIDAD ANTERIOR: ' . $previousQuantity);

                
                // Actualizar el producto en la tabla insert_products
                if ($insproductsModel->update($id, $batchNumber, $productName, $quantity, $price, $provider, $purchaseDate, $expirationDate)) {
                    error_log('CONTROLADOR::INSERTEDPRODUCTOS-> SE ENVÍA');

                    // Calcular la diferencia de cantidad
                    $quantityDifference = $quantity - $previousQuantity;

                    // Actualizar la cantidad del producto en la tabla de productos
                    $productsModel = new ProductsModel();
                    if ($quantityDifference != 0) {
                        $productId = $insProducts->getIdItemName();
                        $productsModel->updateProductQuantity($productId, $quantityDifference);
                    }
                } else {
                    echo "Error al actualizar el Producto.";
                    error_log('CONTROLADOR::INSERTEDPRODUCTOS-> HAY ALGÚN ERROR AL ACTUALIZAR EL PRODUCTO');
                }
            }
        }
    }


}

?>