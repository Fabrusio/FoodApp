<?php
include_once "../../models/menumodel.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $catId = $_GET['id'];
    $catModel = new MenuModel();

    $cat = $catModel->getCat($catId);

    if ($cat) {
        $existingNames = json_encode($catModel->getAllCatNames());

?>




     <!-- Ventana modal -->
     <head>
     <link rel="stylesheet" href="../../public/css/styleEdit.css">
     </head>
    <div class="modal fade custom-modal" id="editarCatModal"tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <!-- Encabezado de la ventana modal -->
                <div class="modal-header">
                    <h4 class="modal-title">Editar Categoria</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Contenido del formulario -->
                <div class="modal-body">
                    <form id="miFormulario" method='post' action='<?php echo constant('URL') ?>crud_menus/editCat'>
                        <input type='hidden' name='id' value='<?php echo $_GET['id']; ?>'>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de Categoria:</label>
                            <input required type='text' maxlength="30" class='form-control' name='name' id="name" value='<?php echo $cat->getCatName(); ?>'>
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
    var formCatId = <?php echo $cat->getIdCat(); ?>;
    error_log(formCatId);
    error_log(existingNames);

    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var name = document.getElementById('name').value;
        var nameError = document.getElementById('name-error');

        var nameExists = existingNames.find(function(names) {
            return user.names === name;
        });

        if (nameExists && nameExists.id_cat !== formCatId) {
            nameError.innerText = 'El nombre de categoria ya está en uso por otra categoria.';
        } else {
            this.submit();
        }
    });


</script>