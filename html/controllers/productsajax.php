<?php
include_once 'products.php';

class ProductsAjax  {
 


    /*===================================================================
        LISTAR NOMBRE DE PRODUCTOS PARA INPUT DE AUTOCOMPLETADO
    ====================================================================*/
    public function ajaxListarNombreProductos() {
        error_log('Ajax::ajaxListarNombreProductos');

        if (isset($_POST["accion"]) && $_POST["accion"] == 6) {
            // TRAER LISTADO DE PRODUCTOS PARA EL AUTOCOMPLETADO
            $products = new ProductsAjax();
            $products->ajaxListarNombreProductos();
        }
      
    }
}

?>












?>