<?php
include_once "/var/www/html/models/productsmodel.php";
include_once "/var/www/html/models/providermodel.php";

$productsModel = new ProductsModel();
$products = $productsModel->getAll();
$nameOfProduct = array();
foreach ($products as $product) {
    $nameOfProduct[] = $product->getItemName();
}

$providerModel = new ProviderModel();
$providers = $providerModel->getAll();

$types = $productsModel->getAllTypes();

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
            <h2 class="text-center mb-4">Adición de productos</h2>
            <form method="post" action="<?php echo constant('URL') ?>crud_products/createProduct" id="miFormulario">
                <div class="mb-3">
                    <label for="productname" class="form-label">Nombre del producto:</label>
                    <input type="text" class="form-control" name="productname" id="productname" maxlength="50" required>
                    <span id="productname-error" style="color: red;"></span>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock:</label>
                    <input type="text" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" name="stock" required>
                </div>
                <div class="mb-3">
                    <label for="productType" class="form-label">Tipo:</label>
                    <select class="form-select form-control" name="productType" aria-label="Default select example">
                    <?php
                    foreach ($types as $type) {
                        echo '<option value="' . $type->getIdType() . '">' . $type->getNameType() . '</option>';
                    }
                    ?>
                </select>
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
                    <label for="stockAlert" class="form-label">Alerta de stock:</label>
                    <input type="text" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" name="stockAlert" required>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </body>
</html>

<script>
    var existingNames = <?php echo json_encode($nameOfProduct); ?>;
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var product = document.getElementById('productname').value;
        var productError = document.getElementById('productname-error');

        if (existingNames.includes(product)) {
            productError.innerText = 'El producto ya existe.';
            Swal.fire({
                    icon: 'error',
                    title: 'EL PRODUCTO YA EXISTE',
                    text: 'Por favor ingrese uno nuevo.'
                }).then(() => {
          
                });
        } else {
            const formulario = document.getElementById('miFormulario');

            formulario.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(formulario);

            const xhr = new XMLHttpRequest();

            xhr.open('POST', '<?php echo constant('URL') ?>crud_products/createProduct', true);

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
            });

        }
    });
</script>