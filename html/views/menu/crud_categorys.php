<?php
include_once "/var/www/html/models/menumodel.php";
require_once "/var/www/html/models/menumodel.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$catModel = new MenuModel();

$categorys = $catModel->getAllCat();

// Define una función de comparación para ordenar las categorias por ID
function cmp($a, $b) {
    return $a->getId() - $b->getId();
}

usort($categorys, "cmp");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Categorias</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
        <h1 class="mt-6">Categorias</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Categoria</th>
                    <th>Eliminar Categoria</th>
                    <th>Editar Categorias</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($categorys as $cat): ?>
                <tr>
                    <td><?php echo $cat->getIdCat(); ?></td>
                    <td><?php echo $cat->getCatName(); ?></td>
                    <td>
                        <form id="deleteForm<?php echo $cat->getId(); ?>" action='<?php echo constant('URL'); ?>crud_menus/deleteCat' method="POST">
                            <input type="hidden" name="id" value="<?php echo $cat->getIdCat(); ?>">
                            <button id="eliminarBtn" class="btn btn-danger" type="button" name="eliminar" onclick="confirmDelete('<?php echo $cat->getIdCat(); ?>')">Eliminar</button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-edit" onclick="openEditModal(<?php echo $cat->getIdCat(); ?>)">Editar</button>
                    </td>
                </tr>
                
        <?php endforeach; ?>
    </tbody>
    </table>
    </div>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div id="edit-form-container" style="display: none;"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDelete(catId) {
    Swal.fire({
        title: '¿Estás seguro de que deseas eliminar esta categoria?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "crud_menus/deleteCat", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    var row = document.getElementById("userRow" + catId);
                    if (row) {
                        row.remove();
                    }
                    CargarContenido('views/menu/crud_categorys.php', 'content-wrapper');
                } else {
                    console.error('Error en la solicitud: ' + xhr.status);
                }
            };
            xhr.send("id=" + catId);
        }
    });
}

</script>

<script>

    function openEditModal(catId) {
        // Realiza una solicitud AJAX para obtener el formulario de edición
        $.ajax({
            url: "views/menu/edit_cat.php", // Ruta al archivo de edición de usuario
            type: "GET",
            data: { id: catId }, // Envía el ID del usuario
            success: function(response) {
                // Muestra el formulario de edición en el contenedor
                $("#edit-form-container").html(response).slideDown();
                // Abre el modal
                $("#editarCatModal").modal("show");
            },
            error: function() {
                alert("Error al cargar el formulario de edición.");
            }
        });
    }

</script>