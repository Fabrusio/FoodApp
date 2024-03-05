<?php
include_once '/var/www/html/libs/imodel.php';
include_once '/var/www/html/libs/model.php';
require_once '/var/www/html/libs/imodel.php';
require_once '/var/www/html/libs/model.php';

class InsProductsModel extends Model{
    private $id;
    private $idItemName;
    private $itemName;
    private $quantity;
    private $price;
    private $idProvider;
    private $razonSocial;
    private $cuit;
    private $idType;
    private $nameType;
    private $purchaseDate;
    private $expirationDate;
    private $batchNumber;
    
    public function __construct() {
        parent::__construct();
        $this->id = 0;
        $this->idItemName = 0;
        $this->itemName = '';
        $this->quantity = 0;
        $this->price = 0.0;
        $this->idProvider = 0;
        $this->razonSocial = '';
        $this->cuit = '';
        $this->idType = 0;
        $this->nameType = 0;
        $this->purchaseDate = '';
        $this->expirationDate = '';
        $this->batchNumber = '';
    }

    public function insertProduct($idItemName, $quantity, $price, $providerId, $expirationDate, $batchNumber, $purchaseDate) {
        try {
            $query = $this->prepare('INSERT INTO insert_products (id_name_item, quantity, price, id_provedor, expiration_date, batch_number, purchase_date) VALUES (:name, :quantity, :price, :provider, :expirationDate, :batchNumber, :purchaseDate)');
            $query->execute([
                'name' => $idItemName,
                'quantity' => $quantity,
                'price' => $price,
                'provider' => $providerId,
                'expirationDate' => $expirationDate,
                'batchNumber' => $batchNumber,
                'purchaseDate' => $purchaseDate,
            ]);
    
            // Actualizar stock en la tabla products
            $updateQuery = $this->prepare('UPDATE products SET stock = stock + :quantity WHERE id_product = :id');
            $updateQuery->execute([
                'quantity' => $quantity,
                'id' => $idItemName,
            ]);
    
            return true;
        } catch (PDOException $e) {
            error_log('INSERTEDPRODUCTSMODEL::insertProduct-> PDOException ' . $e);
            return false;
        }
    }    

    public function getAll() {
        $items = [];
        try {
            $query = $this->query('SELECT ip.*, 
                p.name_iten AS item_name,
                pr.razon_social AS provedor_razon_social,
                pt.type_name
                FROM insert_products ip
                LEFT JOIN products p ON ip.id_name_item = p.id_product
                LEFT JOIN provedores pr ON ip.id_provedor = pr.id_provedor
                LEFT JOIN product_types pt ON ip.id_type = pt.id_type');
    
            while ($p = $query->fetch(PDO::FETCH_ASSOC)) {
                $item = new InsProductsModel();
                $item->setId($p['id_product']);
                $item->setIdItemName($p['id_name_item']);
                $item->setItemName($p['item_name']);
                $item->setQuantity($p['quantity']);
                $item->setPrice($p['price']);
                $item->setRazonSocial($p['provedor_razon_social']);
                $item->setNameType($p['type_name']);
                $item->setPurchaseDate($p['purchase_date']);
                $item->setExpirationDate($p['expiration_date']);
                $item->setBatchNumber($p['batch_number']);
    
                array_push($items, $item);
            }
    
            return $items;
        } catch (PDOException $e) {
            error_log('INSERTEDPRODUCTSMODEL::getAll-> PDOException ' . $e);
        }
    }
    

    public function batchNumberExists($id, $batchNumber) {
        try {
            $query = $this->prepare("SELECT COUNT(*) as count FROM insert_products WHERE batch_number = :batchNumber AND id_product != :id");
            $query->execute([':batchNumber' => $batchNumber, ':id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log('PRODUCTSMODEL::BatchNumberExists-> PDOException ' . $e);
            return false;
        }
    }
    
    public function get($id) {
        try {
            $query = $this->prepare('SELECT ip.*, 
                                     pr.razon_social AS razon_social,
                                     pt.type_name,
                                     p.name_iten
                                 FROM insert_products ip
                                 LEFT JOIN provedores pr ON ip.id_provedor = pr.id_provedor
                                 LEFT JOIN product_types pt ON ip.id_type = pt.id_type
                                 LEFT JOIN products p ON ip.id_name_item = p.id_product
                                 WHERE ip.id_product = :id;');
            $query->execute([
                'id' => $id,
            ]);
    
            $product = $query->fetch(PDO::FETCH_ASSOC);
            if ($product === false) {
                return null; 
            }
    
            $this->setId($product['id_product']);
            $this->setIdItemName($product['id_name_item']);
            $this->setItemName($product['name_iten']);
            $this->setQuantity($product['quantity']);
            $this->setPrice($product['price']);
            $this->setIdProvider($product['id_provedor']);
            $this->setRazonSocial($product['razon_social']);
            $this->setCuit($product['CUIT']);
            $this->setIdType($product['id_type']);
            $this->setNameType($product['type_name']);
            $this->setPurchaseDate($product['purchase_date']);
            $this->setExpirationDate($product['expiration_date']);
            $this->setBatchNumber($product['batch_number']);
            
            return $this;
        } catch(PDOException $e) {
            error_log('Error al obtener el producto: ' . $e->getMessage());
            return null;
        }
    }    

    public function update($id, $batchNumber, $idItemName, $quantity, $price, $idProvider, $purchaseDate, $expirationDate) {
        try {
            $query = $this->prepare("UPDATE insert_products SET batch_number = :batchNumber, id_name_item = :idItemName, quantity = :quantity, price = :price, id_provedor = :idProvider, purchase_date = :purchaseDate, expiration_date = :expirationDate WHERE id_product = :id");
    
            $query->execute([
                ':batchNumber' => $batchNumber,
                ':idItemName' => $idItemName,
                ':quantity' => $quantity,
                ':price' => $price,
                ':idProvider' => $idProvider,
                ':purchaseDate' => $purchaseDate,
                ':expirationDate' => $expirationDate,
                ':id' => $id
            ]);
    
            return true; 
        } catch (PDOException $e) {
            error_log('Error en update de InsProductsModel: ' . $e->getMessage());
            return false; 
        }
    }
    
    
    
    public function setId($id){             $this->id = $id;  }
    public function setIdItemName($idItemName){             $this->idItemName = $idItemName;  }
    public function setItemName($itemName){             $this->itemName = $itemName;  }
    public function setQuantity($quantity){             $this->quantity = $quantity;  }
    public function setPrice($price){             $this->price = $price;  }
    public function setIdProvider($idProvider){             $this->idProvider = $idProvider;  }
    public function setRazonSocial($razonSocial){             $this->razonSocial = $razonSocial;  }
    public function setCuit($cuit){             $this->cuit = $cuit;  }
    public function setIdType($idType){             $this->idType = $idType;  }
    public function setNameType($nameType){             $this->nameType = $nameType;  }
    public function setPurchaseDate($purchaseDate){             $this->purchaseDate = $purchaseDate;  }
    public function setExpirationDate($expirationDate){             $this->expirationDate = $expirationDate;  }
    public function setBatchNumber($batchNumber){             $this->batchNumber = $batchNumber;  }

    public function getId(){                return $this->id;}
    public function getIdItemName(){                return $this->idItemName;}
    public function getItemName(){                return $this->itemName;}
    public function getQuantity(){                return $this->quantity;}
    public function getPrice(){                return $this->price;}
    public function getIdProvider(){                return $this->idProvider;}
    public function getRazonSocial(){                return $this->razonSocial;}
    public function getCuit(){                return $this->cuit;}
    public function getIdType(){                return $this->idType;}
    public function getNameType(){                return $this->nameType;}
    public function getPurchaseDate(){                return $this->purchaseDate;}
    public function getExpirationDate(){                return $this->expirationDate;}
    public function getBatchNumber(){                return $this->batchNumber;}

}

?>