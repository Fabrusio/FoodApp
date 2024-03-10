<?php
include_once '/var/www/html/libs/model.php';
require_once '/var/www/html/libs/model.php';

class MenuModel extends Model{

    private $id;
    private $name;
    private $details;
    private $price;
    private $idCat;
    private $catName; 

    public function __construct() {
       parent::__construct();
       $this->name = '';
       $this->details = '';
       $this->price = 0;
       $this->idCat = 0;
       $this->catName = '';

    }



    public function getAll(){
        $items = [];
        try{
            $query = $this->query('SELECT menu.*, categorias.categoria_name
                                    FROM menu
                                    JOIN categorias ON menu.id_categoria = categorias.id_categoria;');
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new MenuModel();
                $item->setId($p['id_menu']);
                $item->setName($p['name_menu']);
                $item->setDetails($p['detalle']);
                $item->setPrice($p['precio']);
                $item->setIdCat($p['id_categoria']);	
                $item->setCatName($p['categoria_name']);

                array_push($items ,$item);
            }

            return $items;
        }catch(PDOException $e){
            error_log('MENUMODEL::getAll-> PDOException '.$e);
        }
    }






    public function getAllCat(){
        $items = [];
        try{
            $query = $this->query('SELECT id_categoria, categoria_name FROM categorias');
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new MenuModel();
                $item->setIdCat($p['id_categoria']);   
                $item->setCatName($p['categoria_name']);
                array_push($items ,$item);
            }
            return $items;
        } catch(PDOException $e){
            error_log('MENUMODEL::getAllCat-> PDOException '.$e);
        }
    }
    



    public function getAllNames(){
        $names = [];
        try{
            $query = $this->query('SELECT name_menu FROM menu');
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $names[] = $row['name_menu'];
            }
            return $names;
        }catch(PDOException $e){
            error_log('MENUMODEL::getAllNames-> PDOException '.$e);
            return [];
        }
    }




    public function getAllCatNames(){
        $names = [];
        try{
            $query = $this->query('SELECT categoria_name FROM categorias');
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $names[] = $row['categoria_name'];
            }
            return $names;
        }catch(PDOException $e){
            error_log('MENUMODEL::getAllCatNames-> PDOException '.$e);
            return [];
        }
    }




    public function nameExists($id, $name) {
        try {
            $query = $this->prepare("SELECT COUNT(*) as count FROM menu WHERE (name_menu = :name) AND id_menu != :id");
            $query->execute([':name' => $name, ':id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log('Error: ' . $e);
            return false;  
        }
    }




    public function nameCatExists($id, $name) {
        try {
            $query = $this->prepare("SELECT COUNT(*) as count FROM categorias WHERE (categoria_name = :name) AND id_categoria != :id");
            $query->execute([':name' => $name, ':id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log('Error: ' . $e);
            return false;  
        }
    }





    public function get($id){
        try{
            $query = $this->prepare('SELECT menu.*, categorias.categoria_name
                                        FROM menu
                                        JOIN categorias ON menu.id_categoria = categorias.id_categoria
                                        WHERE menu.id_menu = :id;');
            $query->execute([
                'id' => $id,
            ]);

            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user === false) {
                return null; // El menu no fue encontrado
            }

            $this->setName($user['name_menu']);
            $this->setDetails($user['detalle']);
            $this->setPrice($user['precio']);	
            $this->setIdCat($user['id_categoria']);	
            $this->setCatName($user['categoria_name']);

            return $this;
        }catch(PDOException $e){
            error_log('MENUMODEL::getId-> PDOException '.$e);
        }
    }





    public function getCat($id){
        try{
            $query = $this->prepare('SELECT categorias.*, categorias.categoria_name
                                        FROM categorias
                                        WHERE categorias.id_categoria = :id;');
            $query->execute([
                'id' => $id,
            ]);

            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user === false) {
                return null; // La categoria no fue encontrada
            }
            $this->setIdCat($user['id_categoria']);	
            $this->setCatName($user['categoria_name']);

            return $this;
        }catch(PDOException $e){
            error_log('MENUMODEL::getIdCat-> PDOException '.$e);
        }
    }






    public function delete($id){
        try{
            $query = $this->prepare('DELETE FROM menu WHERE id_menu = :id');
            $query->execute([
                'id' => $id,
            ]);
            return true;
        }catch(PDOException $e){
            error_log('MENUMODEL::delete-> PDOException '.$e);
            return false;
        }
    }





    public function deleteCat($id){
        try{
            $query = $this->prepare('DELETE FROM categorias WHERE id_categoria = :id');
            $query->execute([
                'id' => $id,
            ]);
            return true;
        }catch(PDOException $e){
            error_log('MENUMODEL::deleteCat-> PDOException '.$e);
            return false;
        }
    }






    public function createMenu($name, $detail, $price, $category) {
        try {
            $database = new Database();
            $pdo = $database->connect();
            $pdo->beginTransaction();

            $query = $pdo->prepare('INSERT INTO menu(name_menu, detalle, precio, id_categoria) 
            VALUES (:name, :detail, :price, :category)');


            $query->execute([
                'name' => $name,
                'detail' => $detail,
                'price' => $price,
                'category' => $category,
            ]);

            // Confirmar la transacción
            $pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $pdo->rollBack();
            error_log('MENUMODEL::createMenu-> PDOException ' . $e);

            return false;
        }
    }






    public function getLastMenuId() {
        try {
            $query = $this->query('SELECT MAX(id_menu) as max_id FROM menu');
            $result = $query->fetch(PDO::FETCH_ASSOC);
    
            if ($result && isset($result['max_id'])) {
                return $result['max_id'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            // Manejar errores de base de datos según sea necesario
            error_log('Error al obtener el último ID de la tabla menu: ' . $e->getMessage());
            return null;
        }
    }
    







    public function createCat($nameCat) {
        try {
            $database = new Database();
            $pdo = $database->connect();
            $pdo->beginTransaction();

            $query = $pdo->prepare('INSERT INTO categorias(categoria_name) 
            VALUES (:nameCat)');


            $query->execute([
                'nameCat' => $nameCat,
            ]);

            // Confirmar la transacción
            $pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $pdo->rollBack();
            error_log('MENUMODEL::createCat-> PDOException ' . $e);

            return false;
        }
    }





    public function update($id, $name, $category, $detail, $price){
        try{
            
            $query = $this->prepare('UPDATE menu SET name_menu = :name, detalle = :detail,
                                    id_categoria = :category, precio = :price WHERE id_menu = :id');
            
            $query->execute([
                'id' => $id,
                'name' => $name,
                'category' => $category,
                'detail' => $detail,
                'price' => $price,
            ]);
            

            return true;
        }catch(PDOException $e){
            error_log('MENUMODEL::update-> PDOException '.$e);

            return false;
        }
    }






    public function updateCat($id, $name){
        try{
            
            $query = $this->prepare('UPDATE categorias SET categoria_name = :name WHERE id_categoria = :id');
            
            $query->execute([
                'id' => $id,
                'name' => $name,
            ]);
            

            return true;
        }catch(PDOException $e){
            error_log('MENUMODEL::updateCat-> PDOException '.$e);

            return false;
        }
    }


    public function insertMenuIngredient($menuId, $productoId, $cantidad) {

        try {
            $database = new Database();
            $pdo = $database->connect();
            $pdo->beginTransaction();

            $query = $pdo->prepare('INSERT INTO menu_ingredientes(id_menu, id_product, cantidad_requerida) 
            VALUES (:menuId, :productoId, :cantidad)');


            $query->execute([
                'menuId' => $menuId,
                'productoId' => $productoId,
                'cantidad' => $cantidad,
            ]);

            // Confirmar la transacción
            $pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $pdo->rollBack();
            error_log('MENUMODEL::createMenuIngredient-> PDOException ' . $e);

            return false;
        }
    }




    public function from($array){
        $this->id          = $array['id_menu'];
        $this->name        = $array['name_menu'];
        $this->details     = $array['detalle'];
        $this->price       = $array['precio'];
        $this->idCat       = $array['id_categoria'];
        $this->catName     = $array['categoria_name'];
    }


    public function setId($id){                   $this->id = $id;  }
    public function setName($name){               $this->name = $name; }
    public function setDetails($details){         $this->details = $details; }
    public function setPrice($price){             $this->price = $price; }
    public function setIdCat($idCat){             $this->idCat = $idCat; }
    public function setCatName($catName){         $this->catName = $catName; }

    public function getId(){                      return $this->id;}
    public function getName(){                    return $this->name;}
    public function getDetails(){                 return $this->details;}
    public function getPrice(){                   return $this->price;}
    public function getIdCat(){                   return $this->idCat;}
    public function getCatName(){                 return $this->catName;}

}
?>