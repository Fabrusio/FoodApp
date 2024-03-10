<?php
include_once "../../models/menumodel.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $menuId = $_GET['id'];
    $menuModel = new MenuModel();

    $menu = $menuModel->get($menuId);

    if ($menu) {
        $existingNames = json_encode($menuModel->getAllNames());
        $categorys = $menuModel->getAllCat();

?>




     <!-- Ventana modal -->
     <head>
     <link rel="stylesheet" href="../../public/css/styleEdit.css">
     </head>
    <div class="modal fade custom-modal" id="editarMenuModal"tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <!-- Encabezado de la ventana modal -->
                <div class="modal-header">
                    <h4 class="modal-title">Editar Menu</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Contenido del formulario -->
                <div class="modal-body">
                    <form id="miFormulario" method='post' action='<?php echo constant('URL') ?>crud_menus/editMenu'>
                        <input type='hidden' name='id' value='<?php echo $_GET['id']; ?>'>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de Menu:</label>
                            <input required type='text' maxlength="30" class='form-control' name='name' id="name" value='<?php echo $menu->getName(); ?>'>
                            <span id="name-error" style="color: red;"></span>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Categoria:</label>
                            <select class="form-select form-control" name="category" aria-label="Default select example">
                                <?php
                                foreach ($categorys as $cat) {
                                    $selected = ($cat->getIdCat() === $menu->getIdCat()) ? 'selected' : '';
                                    echo '<option value="' . $cat->getIdCat() . '" ' . $selected . '>' . $cat->getCatName() . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="detail" class="form-label">Detalle</label>
                            <input type='text' maxlength="50" class='form-control' name='detail' value='<?php echo $menu->getDetails(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Precio:</label>
                            <input type="text" maxlength="8"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='price' value='<?php echo $menu->getPrice(); ?>'>
                        </div>
                        <button type='submit' class='btn btn-primary'>Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

} else {
    echo 'menu no encontrado.';
}
} else {
echo 'ID de menu no válido.';
}
?>
<script>
    var existingNames = <?php echo $existingNames; ?>;
    var formMenuId = <?php echo $menu->getId(); ?>;
    error_log(formMenuId);
    error_log(existingNames);

    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var name = document.getElementById('name').value;
        var nameError = document.getElementById('name-error');

        var nameExists = existingNames.find(function(names) {
            return user.names === name;
        });

        if (nameExists && nameExists.id_menu !== formMenuId) {
            nameError.innerText = 'El nombre de menu ya está en uso por otro menu.';
        } else {
            this.submit();
        }
    });


</script>