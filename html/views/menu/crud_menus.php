<?php
include_once "/var/www/html/models/menumodel.php";
require_once "/var/www/html/models/menumodel.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$menuModel = new MenuModel();

$menus = $menuModel->getAll();

// Define una función de comparación para ordenar los menus por ID
function cmp($a, $b) {
    return $a->getId() - $b->getId();
}

usort($menus, "cmp");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Menus</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
        <h1 class="mt-6">Menus</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Menu</th>
                    <th>Detalle</th>
                    <th>Precio</th>
                    <th>Categoria</th>
                    <th>Eliminar Menu</th>
                    <th>Editar Menu</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($menus as $menu): ?>
                <tr>
                    <td><?php echo $menu->getId(); ?></td>
                    <td><?php echo $menu->getName(); ?></td>
                    <td><?php echo $menu->getDetails(); ?></td>
                    <td><?php echo $menu->getPrice(); ?></td>
                    <td><?php echo $menu->getCatName(); ?></td>
                    <td>
                        <form id="deleteForm<?php echo $menu->getId(); ?>" action='<?php echo constant('URL'); ?>crud_menus/deleteMenu' method="POST">
                            <input type="hidden" name="id" value="<?php echo $menu->getId(); ?>">
                            <button id="eliminarBtn" class="btn btn-danger" type="button" name="eliminar" onclick="confirmDelete('<?php echo $menu->getId(); ?>')">Eliminar</button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-edit" onclick="openEditModal(<?php echo $menu->getId(); ?>)">Editar</button>
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
function confirmDelete(menuId) {
    Swal.fire({
        title: '¿Estás seguro de que deseas eliminar este menu?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo constant('URL') ?>crud_menus/deleteMenu", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    var row = document.getElementById("userRow" + menuId);
                    if (row) {
                        row.remove();
                    }
                    CargarContenido('views/menu/crud_menus.php', 'content-wrapper');
                } else {
                    console.error('Error en la solicitud: ' + xhr.status);
                }
            };
            xhr.send("id=" + menuId);
        }
    });
}

</script>

<script>

    function openEditModal(menuId) {
        // Realiza una solicitud AJAX para obtener el formulario de edición
        $.ajax({
            url: "views/menu/edit_menu.php", // Ruta al archivo de edición de usuario
            type: "GET",
            data: { id: menuId }, // Envía el ID del usuario
            success: function(response) {
                // Muestra el formulario de edición en el contenedor
                $("#edit-form-container").html(response).slideDown();
                // Abre el modal
                $("#editarMenuModal").modal("show");
            },
            error: function() {
                alert("Error al cargar el formulario de edición.");
            }
        });
    }

</script>