<?php
include_once '/var/www/html/libs/imodel.php';
include_once '/var/www/html/libs/model.php';
require_once '/var/www/html/libs/imodel.php';
require_once '/var/www/html/libs/model.php';

class ProductsModel extends Model{
    private $id;
    private $itemName;
    private $stock;
    private $price;
    private $idProvider;
    private $stockAlert;
    private $razonSocial;
    private $cuit;
    private $idType;
    private $nameType;

    public function __construct() {
        parent::__construct();
        $this->id = 0;
        $this->itemName = '';
        $this->stock = 0;
        $this->price = 0.0;
        $this->idProvider = 0;
        $this->stockAlert = 0;
        $this->razonSocial = '';
        $this->cuit = '';
        $this->idType = 0;
        $this->nameType = 0;
    }


     public function getAll(){
        $items = [];
        try{
        $query = $this->query('SELECT products.*, provedores.id_provedor, provedores.razon_social, provedores.CUIT, product_types.id_type, product_types.type_name
                                    FROM products
                                    JOIN provedores ON products.id_provedor = provedores.id_provedor
                                    JOIN product_types ON products.id_type = product_types.id_type');
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new ProductsModel();
                $item->setId($p['id_product']);
                $item->setItemName($p['name_iten']);
                $item->setStock($p['stock']);
                $item->setPrice($p['precio_unitario']);
                $item->setIdProvider($p['id_provedor']);
                $item->setStockAlert($p['alerta_stock']);
                $item->setRazonSocial($p['razon_social']);
                $item->setCuit($p['CUIT']);
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
            $query = $this->prepare('SELECT products.*, provedores.id_provedor, provedores.razon_social, provedores.CUIT, product_types.id_type, product_types.type_name
                                        FROM products
                                        JOIN provedores ON products.id_provedor = provedores.id_provedor
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
            $this->setPrice($product['precio_unitario']);
            $this->setIdProvider($product['id_provedor']);
            $this->setRazonSocial($product['razon_social']);
            $this->setCuit($product['CUIT']);
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

    public function createProduct($productName, $stock, $price, $providerId, $stockAlert, $productType) {
        try {
            $query = $this->prepare('INSERT INTO products (name_iten, stock, precio_unitario, id_provedor, alerta_stock, id_type) VALUES (:name, :stock, :price, :provider, :stockAlert, :type)');
            $query->execute([
                'name' => $productName,
                'stock' => $stock,
                'price' => $price,
                'provider' => $providerId,
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
            // Modifica la consulta SQL para excluir el producto actual
            $query = $this->prepare("SELECT COUNT(*) as count FROM products WHERE name_iten = :name AND id_product != :id");
            $query->execute([':name' => $name, ':id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log('PRODUCTSMODEL::productNameExists-> PDOException ' . $e);
            return false;
        }
    }

    public function update($id, $productName, $stock, $price, $provider, $stockAlert, $productType){
        try {
            $query = $this->prepare('UPDATE products 
            SET name_iten = :productName, stock = :stock, precio_unitario = :price,
            id_provedor = (SELECT id_provedor FROM provedores WHERE id_provedor = :provider), 
            alerta_stock = :stockAlert, id_type = (SELECT id_type FROM product_types WHERE id_type = :productType)
            WHERE id_product = :id');
            error_log("ABAJO EL ID QUE APARECE PERO EN UPDATE EN MODELO");
            error_log($id);
            $query->execute([
                'id' => $id,
                'productName' => $productName,
                'stock' => $stock,
                'price' => $price,
                'provider' => $provider,
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
    



     public function setId($id){             $this->id = $id;  }
     public function setItemName($itemName){             $this->itemName = $itemName;  }
     public function setStock($stock){             $this->stock = $stock;  }
     public function setPrice($price){             $this->price = $price;  }
     public function setIdProvider($idProvider){             $this->idProvider = $idProvider;  }
     public function setStockAlert($stockAlert){             $this->stockAlert = $stockAlert;  }
     public function setRazonSocial($razonSocial){             $this->razonSocial = $razonSocial;  }
     public function setCuit($cuit){             $this->cuit = $cuit;  }
     public function setIdType($idType){             $this->idType = $idType;  }
     public function setNameType($nameType){             $this->nameType = $nameType;  }

     public function getId(){                return $this->id;}
     public function getItemName(){                return $this->itemName;}
     public function getStock(){                return $this->stock;}
     public function getPrice(){                return $this->price;}
     public function getIdProvider(){                return $this->idProvider;}
     public function getStockAlert(){                return $this->stockAlert;}
     public function getRazonSocial(){                return $this->razonSocial;}
     public function getCuit(){                return $this->cuit;}
     public function getIdType(){                return $this->idType;}
     public function getNameType(){                return $this->nameType;}

}//cierra Clase

?>