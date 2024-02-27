<?php
include_once "../../models/productsmodel.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $typeId = $_GET['id'];
    $productsModel = new ProductsModel();

    $type = $productsModel->getType($typeId);

    if ($type) {
        $existingNames = json_encode($productsModel->getAllTypeNames());

?>

     <head>
     <link rel="stylesheet" href="../../public/css/styleEdit.css">
     </head>
    <div class="modal fade custom-modal" id="editarTypeModal"tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Tipo</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
                <div class="modal-body">
                    <form id="miFormulario" method='post' action='<?php echo constant('URL') ?>crud_products/editType'>
                        <input type='hidden' name='id' value='<?php echo $_GET['id']; ?>'>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Tipo:</label>
                            <input required type='text' maxlength="30" class='form-control' name='name' id="name" value='<?php echo $type->getNameType(); ?>'>
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
    echo 'categoria no encontrada.';
    echo "$catId";

}
} else {
echo 'ID de categoria no válido.';
}
?>
<script>
    var existingNames = <?php echo $existingNames; ?>;
    var formTypeId = <?php echo $cat->getIdType(); ?>;
    error_log(formTypeId);
    error_log(existingNames);

    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var name = document.getElementById('name').value;
        var nameError = document.getElementById('name-error');

        var nameExists = existingNames.find(function(names) {
            return user.names === name;
        });

        if (nameExists && nameExists.id_type !== formTypeId) {
            nameError.innerText = 'El nombre del tipo ya está en uso.';
        } else {
            this.submit();
        }
    });


</script>