<?php
include_once "/var/www/html/models/insproductsmodel.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$insProductsModel = new InsProductsModel();

$reasons = $insProductsModel->getAllReasons();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir nueva razón</title>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Añadir nueva razón al dar de baja</h2>
        <form id="miFormulario">
            <div class="mb-3">
                <label for="reason" class="form-label">Nombre del Tipo:</label>
                <input type="text" maxlength="255" class="form-control" name="reason" id="reason" required>
                <span id="name-error" style="color: red;"></span>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<script>
    var existingReasons = <?php 
    echo json_encode($insProductsModel->getReasonsDetails()); 
    ?>;
    document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    var name = document.getElementById('reason').value;
    var nameError = document.getElementById('name-error');

    if (existingReasons.includes(name)) {
        nameError.innerText = 'Esa razón ya existe.';
     
        Swal.fire({
            icon: 'error',
            title: 'LA RAZÓN YA EXISTE',
            text: 'Por favor ingrese otra'
        });
    } else {
        const formData = new FormData(this);

        const xhr = new XMLHttpRequest();

        xhr.open('POST', 'crud_insproducts/createReason', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Formulario enviado con éxito'
                }).then(() => {
                    document.getElementById('miFormulario').reset();
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
