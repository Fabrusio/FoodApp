<?php
include_once "../../models/insproductsmodel.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];
    $insproductsModel = new InsProductsModel();

    $insProducts = $insproductsModel->get($userId);
    $reasons = $insproductsModel->getAllReasons();

    if ($insProducts) {
        
?>
     <head>
     <link rel="stylesheet" href="../../public/css/styleEdit.css">
     </head>
    <div class="modal fade custom-modal" id="eliminarProductoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Producto</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <form method='POST' action='<?php echo constant('URL') ?>crud_insproducts/deleteBatch'>
                            <input type='hidden' name='id' value='<?php echo $userId; ?>'>
                        <div class="mb-3">
                            <input type='hidden' maxlength="20" class='form-control' name='batchNumber' value='<?php echo $insProducts->getBatchNumber(); ?>' required>
                        </div>
                        <div class="mb-3">
                            <input required type='hidden' class='form-control' name='productName' id="productName" value='<?php echo $insProducts->getIdItemName(); ?>'>
                            <span id="username-error" style="color: red;"></span>
                        </div>
                        <div class="mb-3">
                            <input type='hidden' maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='quantity' value='<?php echo $insProducts->getQuantity(); ?>' required>
                        </div>
                        <div class="mb-3">
                            <input type='hidden' maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='price' value='<?php echo $insProducts->getPrice(); ?>' required>
                        </div>
                        <div class="mb-3">
                        <input type='hidden' name='providerName' value='<?php echo $insProducts->getIdProvider(); ?>'>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" id="purchaseDate" class='form-control' name="purchaseDate" value='<?php echo $insProducts->getPurchaseDate(); ?>' required>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" id="expirationDate" class='form-control' name="expirationDate" value='<?php echo $insProducts->getExpirationDate(); ?>' required>
                        </div>
                        <div class="mb-3">
                        <label for="reason">Seleccione la razón de eliminación:</label>
                        <select class="form-select" name="reason" id="reason" required>
                            <?php foreach ($reasons as $reason): ?>
                                <option value="<?php echo $reason->getIdRemoval(); ?>"><?php echo $reason->getRemovalReason(); ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        <button type='submit' class='btn btn-primary'>Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php

} else {
    echo 'Producto no encontrado.';
}
} else {
echo 'ID del producto no válido.';
}
?>
                            
