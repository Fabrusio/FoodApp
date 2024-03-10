<?php
include_once "../../models/productsmodel.php"; 
include_once "../../models/providermodel.php"; 
include_once "../../models/insproductsmodel.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];
    $insproductsModel = new InsProductsModel();

    $insProducts = $insproductsModel->get($userId);
    // $types = $insproductsModel->getAllTypes();
    $providerModel = new ProviderModel();
    $providers = $providerModel->getAll();

    if ($insProducts) {
        // $existingUsernames = json_encode($productsModel->getAllNames());
        
?>


     <!-- Ventana modal -->
     <head>
     <link rel="stylesheet" href="../../public/css/styleEdit.css">
     </head>
    <div class="modal fade custom-modal" id="editarUsuarioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <!-- Encabezado de la ventana modal -->
                <div class="modal-header">
                    <h4 class="modal-title">Editar Producto</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Contenido del formulario -->
                <div class="modal-body">
                <form method='POST' action='<?php echo constant('URL') ?>crud_insproducts/editInsertedProducts'>
                            <input type='hidden' name='id' value='<?php echo $userId; ?>'>
                        <div class="mb-3">
                            <label for="batchNumber" class="form-label">Número de lote:</label>
                            <input type='text' maxlength="20" class='form-control' name='batchNumber' value='<?php echo $insProducts->getBatchNumber(); ?>' required>
                        </div>
                        <div class="mb-3">
                            <!-- <label for="productName" class="form-label">Producto:</label> -->
                            <input required type='hidden' class='form-control' name='productName' id="productName" value='<?php echo $insProducts->getIdItemName(); ?>'>
                            <span id="username-error" style="color: red;"></span>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Cantidad</label>
                            <input type='text' maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='quantity' value='<?php echo $insProducts->getQuantity(); ?>' required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Precio:</label>
                            <input type='text' maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='price' value='<?php echo $insProducts->getPrice(); ?>' required>
                        </div>
                        <div class="mb-3">
                        <label for="providerName" class="form-label">Proveedor:</label>
                        <select class="form-select form-control" name="providerName" aria-label="Default select example">
                            <?php
                            foreach ($providers as $provider) {
                                $selected = ($provider->getId() == $insProducts->getIdProvider()) ? 'selected' : '';
                                echo '<option value="' . $provider->getId() . '" ' . $selected . '>' . $provider->getRazonSocial() . '</option>';
                            }
                            ?>
                        </select>
                        </div>
                        <div class="mb-3">
                            <label for="purchaseDate">Fecha de Compra:</label>
                            <input type="date" id="purchaseDate" class='form-control' name="purchaseDate" value='<?php echo $insProducts->getPurchaseDate(); ?>' required>
                        </div>
                        <div class="mb-3">
                            <label for="expirationDate">Fecha de Vencimiento:</label>
                            <input type="date" id="expirationDate" class='form-control' name="expirationDate" value='<?php echo $insProducts->getExpirationDate(); ?>' required>
                        </div>
                        <button type='submit' class='btn btn-primary'>Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

} else {
    echo 'Usuario no encontrado.';
}
} else {
echo 'ID de usuario no válido.';
}
?>


<script>
    var existingUsernames = <?php echo $existingUsernames; ?>;
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var username = document.getElementById('username').value;
        var usernameError = document.getElementById('username-error');

        if (existingUsernames.includes(username)) {
            usernameError.innerText = 'El producto ya existe.';
        } else {
            this.submit();
        }
    });
</script>
