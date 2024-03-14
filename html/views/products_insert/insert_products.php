<?php
include_once "/var/www/html/models/productsmodel.php";
include_once "/var/www/html/models/providermodel.php";
include_once "/var/www/html/models/insproductsmodel.php";

$insProductsModel = new InsProductsModel();

$productsModel = new ProductsModel();
$products = $productsModel->getAll();
$insProducts = $insProductsModel->getAll(); 

$batchNumber = array();
foreach ($insProducts as $insProduct) {

    $batchNumber[] = $insProduct->getBatchNumber();
}

$providerModel = new ProviderModel();
$providers = $providerModel->getAll();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
</head>
    <body>
        <div class="container">
            <h2 class="text-center mb-4">Insertar productos</h2>
            <form method="post" action="<?php echo constant('URL') ?>crud_insproducts/insertProduct" id="miFormulario">
                <div class="mb-3">
                    <label for="batchNumber" class="form-label">Número de lote:</label>
                    <input type='text' maxlength="20" class='form-control' id='batchNumber' name='batchNumber' required>
                    <span id="batchNumber-error" style="color: red;"></span>
                </div>
                <div class="mb-3">
                <label for="productName" class="form-label">Producto:</label>
                    <select class="form-select form-control" name="productName" aria-label="Default select example">
                    <?php
                    foreach ($products as $product) {
                        echo '<option value="' . $product->getId() . '">' . $product->getItemName() . '</option>';
                    }
                    ?>
                </select>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="text" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" name="quantity" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Precio:</label>
                    <input type="text" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="providerName" class="form-label">Proveedor:</label>
                    <select class="form-select form-control" name="providerName" aria-label="Default select example">
                    <?php
                    foreach ($providers as $provider) {
                        echo '<option value="' . $provider->getId() . '">' . $provider->getRazonSocial() . '</option>';
                    }
                    ?>
                </select>
                </div>
                <div class="mb-3">
                        <label for="purchaseDate">Fecha de Compra:</label>
                        <input type="date" id="purchaseDate" name="purchaseDate" required>
                </div>
                <div class="mb-3">
                        <label for="expirationDate">Fecha de Vencimiento:</label>
                        <input type="date" id="expirationDate" name="expirationDate" required>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </body>
</html>

<script>
    var existingBatch = <?php echo json_encode($batchNumber); ?>;
    console.log(existingBatch);
    document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    var batch = document.getElementById('batchNumber').value.toUpperCase();
    var batchError = document.getElementById('batchNumber-error');

    if (existingBatch.includes(batch)) {
        batchError.innerText = 'El número de lote ya existe.';
        Swal.fire({
            icon: 'error',
            title: 'EL LOTE YA EXISTE',
            text: 'Por favor ingrese otro.'
        });
    } else {
        const formulario = document.getElementById('miFormulario');

        const formData = new FormData(formulario);

        const xhr = new XMLHttpRequest();

        xhr.open('POST', '<?php echo constant('URL') ?>crud_insproducts/insertProduct', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Formulario enviado con éxito'
                }).then(() => {
                    formulario.reset();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al enviar el formulario',
                });
            }
        };

        xhr.send(formData);
    }
});

</script> 