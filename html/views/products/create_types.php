<?php
include_once "/var/www/html/models/productsmodel.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$productsModel = new ProductsModel();

$types = $productsModel->getAllTypes();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Tipo</title>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Añadir tipo de producto</h2>
        <form id="miFormulario">
            <div class="mb-3">
                <label for="nameType" class="form-label">Nombre del Tipo:</label>
                <input type="text" maxlength="30" class="form-control" name="nameType" id="name" required>
                <span id="name-error" style="color: red;"></span>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script>

    var existingNames = <?php echo json_encode($productsModel->getAllTypeNames()); ?>;
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();
        console.log(existingNames);

        var name = document.getElementById('name').value;
        var nameError = document.getElementById('name-error');

        if (existingNames.includes(name)) {

            nameError.innerText = 'El nombre del tipo ya está en uso.';
         
            Swal.fire({
                    icon: 'error',
                    title: 'TIPO YA EXISTE',
                    text: 'Por favor ingrese otro tipo'
                }).then(() => {
          
                });
            

        } else {


            const formulario = document.getElementById('miFormulario');

            formulario.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(formulario);

            const xhr = new XMLHttpRequest();

            xhr.open('POST', 'crud_products/createType', true);

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