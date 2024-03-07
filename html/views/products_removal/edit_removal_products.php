<?php
include_once "/var/www/html/models/insproductsmodel.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $reasonId = $_GET['id'];
    echo $reasonId;
    $insProductsModel = new InsProductsModel();

    $reason = $insProductsModel->getReason($reasonId);
    if ($reason) {
        $existingReason = json_encode($insProductsModel->getReasonsDetails());

?>

     <head>
     <link rel="stylesheet" href="../../public/css/styleEdit.css">
     </head>
    <div class="modal fade custom-modal" id="editarTypeModal"tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Razón</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
                <div class="modal-body">
                    <form id="miFormulario" method='post' action='<?php echo constant('URL') ?>crud_insproducts/editReason'>
                        <input type='hidden' name='id' value='<?php echo $_GET['id']; ?>'>
                        <div class="mb-3">
                            <label for="name" class="form-label">Razón a dar de baja:</label>
                            <input required type='text' maxlength="255" class='form-control' name='name' id="name" value='<?php echo $reason->getRemovalReason(); ?>'>
                            <span id="name-error" style="color: red;"></span>
                        </div>
                        <button type='submit' class='btn btn-primary'>Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

} else {
    echo 'Razón no encontrada.';
    echo $reasonId;
}
} else {
echo 'ID de razón no válido.';
}
?>

<script>
    var existingReason = <?php echo $existingNames; ?>;
    error_log(existingNames);

    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var name = document.getElementById('name').value;
        var nameError = document.getElementById('name-error');

        var nameExists = existingNames.find(function(names) {
            return user.names === name;
        });

        if (nameExists && nameExists.id_type !== formTypeId) {
            nameError.innerText = 'La razón ya existe.';
        } else {
            this.submit();
        }
    });


</script>