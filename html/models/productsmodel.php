<?php
include_once '/var/www/html/libs/imodel.php';
include_once '/var/www/html/libs/model.php';
require_once '/var/www/html/libs/imodel.php';
require_once '/var/www/html/libs/model.php';

class ProductsModel extends Model{
    private $id;
    private $itemName;
    private $stock;
    private $stockAlert;
    private $idType;
    private $nameType;
    

    public function __construct() {
        parent::__construct();
        $this->id = 0;
        $this->itemName = '';
        $this->stock = 0;
        $this->stockAlert = 0;
        $this->idType = 0;
        $this->nameType = 0;
        
    }


     public function getAll(){
        $items = [];
        try{
        $query = $this->query('SELECT products.*, product_types.id_type, product_types.type_name
                                    FROM products
                                    JOIN product_types ON products.id_type = product_types.id_type');
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new ProductsModel();
                $item->setId($p['id_product']);
                $item->setItemName($p['name_iten']);
                $item->setStock($p['stock']);
                $item->setStockAlert($p['alerta_stock']);
                $item->setIdType($p['id_type']);
                $item->setNameType($p['type_name']);
                

                array_push($items ,$item);
            }

            return $items;
        }catch(PDOException $e){
            error_log('PRODUCTSMODEL::getAll-> PDOException '.$e);
        }
     }

     public function getAllNames(){
        $names = [];
        try{
            $query = $this->query('SELECT name_iten FROM products');
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $names[] = $row['name_iten'];
            }
            return $names;
        }catch(PDOException $e){
            error_log('PRODUCTSMODEL::getAllNames-> PDOException '.$e);
            return [];
        }
    }

    public function getAllTypes(){
        $items = [];
        try{
            $query = $this->query('SELECT product_types.*
                                    FROM product_types');
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new ProductsModel();
                $item->setIdType($p['id_type']);
                $item->setNameType($p['type_name']);
                array_push($items ,$item);
            }

            return $items;
        }catch(PDOException $e){
            error_log('PRODUCTSMODEL::getAllTypes> PDOException '.$e);
        }
    }

    public function get($id){
        try{
            $query = $this->prepare('SELECT products.*, product_types.id_type, product_types.type_name
                                        FROM products
                                        JOIN product_types ON products.id_type = product_types.id_type
                                        WHERE products.id_product = :id;');
            $query->execute([
                'id' => $id,
            ]);

            $product = $query->fetch(PDO::FETCH_ASSOC);
            if ($product === false) {
                return null; // El producto no fue encontrado
            }

            $this->setItemName($product['name_iten']);
            $this->setStock($product['stock']);
            $this->setStockAlert($product['alerta_stock']);
            $this->setIdType($product['id_type']);
            $this->setNameType($product['type_name']);
            return $this;

        }catch(PDOException $e){
            error_log('PRODUCTSMODEL::getId-> PDOException '.$e);
            return null;
        }
    }

     public function delete($id){
        try {
            $query = $this->prepare('DELETE FROM products WHERE id_product = :id');
            $query->execute([
                'id' => $id,
            ]);
            return true;
        } catch(PDOException $e) {
            error_log('PRODUCTSMODEL::delete-> PDOException '.$e);
            return false;
        }
    }

    public function createProduct($productName, $stock, $stockAlert, $productType) {
        try {
            $query = $this->prepare('INSERT INTO products (name_iten, stock, alerta_stock, id_type) VALUES (:name, :stock, :stockAlert, :type)');
            $query->execute([
                'name' => $productName,
                'stock' => $stock,
                'stockAlert' => $stockAlert,
                'type' => $productType,

            ]);
    
            return true;
        } catch (PDOException $e) {
            error_log('PRODUCTSMODEL::createProduct-> PDOException ' . $e);
            return false;
        }
    }

    public function productNameExists($id, $name) {
        try {
            $query = $this->prepare("SELECT COUNT(*) as count FROM products WHERE name_iten = :name AND id_product != :id");
            $query->execute([':name' => $name, ':id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log('PRODUCTSMODEL::productNameExists-> PDOException ' . $e);
            return false;
        }
    }

    public function update($id, $productName, $stock, $stockAlert, $productType){
        try {
            $query = $this->prepare('UPDATE products 
            SET name_iten = :productName, stock = :stock,  
            alerta_stock = :stockAlert, id_type = (SELECT id_type FROM product_types WHERE id_type = :productType)
            WHERE id_product = :id');

            error_log("ABAJO EL ID QUE APARECE PERO EN UPDATE EN MODELO");
            error_log($id);

            $query->execute([
                'id' => $id,
                'productName' => $productName,
                'stock' => $stock,
                'stockAlert' => $stockAlert,
                'productType' => $productType,

            ]);
            error_log('PRODUCTSMODEL::UPDATE-> ACÁ ESTOY, SI APAREZCO LLEGA AL MODELO');
            return true;
        } catch(PDOException $e) {
            error_log('PRODUCTSMODEL::UPDATE-> ACÁ ESTOY, SI APAREZCO LLEGA AL MODELO PERO TIRA ERROR');
            return false;
        }
    }

    public function updateProductQuantity($productId, $quantityDifference) {
        try {
            $query = $this->prepare("UPDATE products SET stock = stock + :quantityDifference WHERE id_product = :productId");
            $query->execute([':quantityDifference' => $quantityDifference, ':productId' => $productId]);
            return true;
        } catch (PDOException $e) {
            error_log('PRODUCTSMODEL::updateProductQuantity-> PDOException ' . $e);
            return false;
        }
    }
    

    //         --------------------------------
    //              TIPO DE PESAJE, UNIDAD
    //         --------------------------------
    
    
    public function getAllTypeNames(){
        $names = [];
        try{
            $query = $this->query('SELECT type_name FROM product_types');
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $names[] = $row['type_name'];
            }
            return $names;
        }catch(PDOException $e){
            error_log('PRODUCTSMODEL::getAllTypeNames-> PDOException '.$e);
            return [];
        }
    }

    public function nameTypeExists($id, $name) {
        try {
            $query = $this->prepare("SELECT COUNT(*) as count FROM product_types WHERE (type_name = :name) AND id_type != :id");
            $query->execute([':name' => $name, ':id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log('Error: ' . $e);
            return false;  
        }
    }

    public function getType($id){
        try{
            $query = $this->prepare('SELECT product_types.*, product_types.type_name
                                        FROM product_types
                                        WHERE product_types.id_type= :id;');
            $query->execute([
                'id' => $id,
            ]);

            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user === false) {
                return null;
            }
            $this->setIdType($user['id_type']);	
            $this->setNameType($user['type_name']);

            return $this;
        }catch(PDOException $e){
            error_log('PRODUCTSMODEL::getIdType-> PDOException '.$e);
        }
    }

    public function createType($nameType) {
        try {
            $database = new Database();
            $pdo = $database->connect();
            $pdo->beginTransaction();

            $query = $pdo->prepare('INSERT INTO product_types(type_name) 
            VALUES (:nameType)');


            $query->execute([
                'nameType' => $nameType,
            ]);

            $pdo->commit();

            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('MENUMODEL::createType-> PDOException ' . $e);

            return false;
        }
    }

    public function deleteType($id){
        try{
            $query = $this->prepare('DELETE FROM product_types WHERE id_type = :id');
            $query->execute([
                'id' => $id,
            ]);
            return true;
        }catch(PDOException $e){
            error_log('PRODUCTSMODEL::deleteType-> PDOException '.$e);
            return false;
        }
    }

    public function updateType($id, $name){
        try{
            
            $query = $this->prepare('UPDATE product_types SET type_name = :name WHERE id_type = :id');
            
            $query->execute([
                'id' => $id,
                'name' => $name,
            ]);
            

            return true;
        }catch(PDOException $e){
            error_log('PRODUCTSMODEL::updateType-> PDOException '.$e);

            return false;
        }
    }



     public function setId($id){             $this->id = $id;  }
     public function setItemName($itemName){             $this->itemName = $itemName;  }
     public function setStock($stock){             $this->stock = $stock;  }
     public function setStockAlert($stockAlert){             $this->stockAlert = $stockAlert;  }
     public function setIdType($idType){             $this->idType = $idType;  }
     public function setNameType($nameType){             $this->nameType = $nameType;  }
     

     public function getId(){                return $this->id;}
     public function getItemName(){                return $this->itemName;}
     public function getStock(){                return $this->stock;}
     public function getStockAlert(){                return $this->stockAlert;}
     public function getIdType(){                return $this->idType;}
     public function getNameType(){                return $this->nameType;}
     

}

?>