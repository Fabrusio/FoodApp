<?php
include_once "../../models/productsmodel.php"; 
include_once "../../models/providermodel.php"; 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];
    $productsModel = new ProductsModel();

    $products = $productsModel->get($userId);
    $types = $productsModel->getAllTypes();

    if ($products) {
        $existingUsernames = json_encode($productsModel->getAllNames());
        $providerModel = new ProviderModel();
        $providers = $providerModel->getAll();
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
                <form method='POST' action='<?php echo constant('URL') ?>crud_products/editProducts'>
                            <input type='hidden' name='id' value='<?php echo $userId; ?>'>
                        <div class="mb-3">
                            <label for="productName" class="form-label">Producto:</label>
                            <input required type='text' class='form-control' name='productName' id="productName" value='<?php echo $products->getItemName(); ?>'>
                            <span id="username-error" style="color: red;"></span>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type='text' maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='stock' value='<?php echo $products->getStock(); ?>'>
                        </div>
                        <div class="mb-3">
                        <label for="productType" class="form-label">Tipo:</label>
                        <select class="form-select form-control" name="productType" aria-label="Default select example">
                            <?php
                            foreach ($types as $type) {
                                $selected = ($type->getIdType() == $products->getIdType()) ? 'selected' : '';
                                echo '<option value="' . $type->getIdType() . '" ' . $selected . '>' . $type->getNameType() . '</option>';
                            }
                            ?>
                        </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Precio:</label>
                            <input type='text' maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='price' value='<?php echo $products->getPrice(); ?>'>
                        </div>
                        <div class="mb-3">
                        <label for="providerName" class="form-label">Proveedor:</label>
                        <select class="form-select form-control" name="providerName" aria-label="Default select example">
                            <?php
                            foreach ($providers as $provider) {
                                $selected = ($provider->getId() == $products->getIdProvider()) ? 'selected' : '';
                                echo '<option value="' . $provider->getId() . '" ' . $selected . '>' . $provider->getRazonSocial() . '</option>';
                            }
                            ?>
                        </select>
                        </div>
                        <div class="mb-3">
                            <label for="stockAlert" class="form-label">Alerta stock:</label>
                            <input type='text' maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='stockAlert' value='<?php echo $products->getStockAlert(); ?>'>
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
            usernameError.innerText = 'El nombre de usuario ya está en uso.';
        } else {
            // Envía el formulario si el nombre de usuario no está en uso
            this.submit();
        }
    });
</script>
