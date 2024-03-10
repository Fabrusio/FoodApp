<?php
include_once "/var/www/html/models/productsmodel.php";
require_once "/var/www/html/models/productsmodel.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$productsModel = new ProductsModel();

$types = $productsModel->getAllTypes();

function cmp($a, $b) {
    return $a->getIdType() - $b->getIdType();
}

// usort($menus, "cmp");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Tipos de Pesaje</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
        <h1 class="mt-6">Tipos de Pesaje</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Eliminar Tipo</th>
                    <th>Editar Tipo</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($types as $type): ?>
                <tr>
                    <td><?php echo $type->getIdType(); ?></td>
                    <td><?php echo $type->getNameType(); ?></td>
                    <td>
                        <form id="deleteForm<?php echo $type->getIdType(); ?>" action='<?php echo constant('URL'); ?>crud_products/deleteTypes' method="POST">
                            <input type="hidden" name="id" value="<?php echo $type->getIdType(); ?>">
                            <button id="eliminarBtn" class="btn btn-danger" type="button" name="eliminar" onclick="confirmDelete('<?php echo $type->getIdType(); ?>')">Eliminar</button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-edit" onclick="openEditModal(<?php echo $type->getIdType(); ?>)">Editar</button>
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
function confirmDelete(typeId) {
    Swal.fire({
        title: '¿Estás seguro de que deseas eliminar este tipo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo constant('URL') ?>crud_products/deleteType", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    var row = document.getElementById("userRow" + typeId);
                    if (row) {
                        row.remove();
                    }
                    CargarContenido('views/products/crud_types.php', 'content-wrapper');
                } else {
                    console.error('Error en la solicitud: ' + xhr.status);
                }
            };
            xhr.send("id=" + typeId);
        }
    });
}

</script>

<script>

    function openEditModal(typeId) {
        // Realiza una solicitud AJAX para obtener el formulario de edición
        $.ajax({
            url: "views/products/edit_types.php", // Ruta al archivo de edición de usuario
            type: "GET",
            data: { id: typeId }, // Envía el ID del usuario
            success: function(response) {
                // Muestra el formulario de edición en el contenedor
                $("#edit-form-container").html(response).slideDown();
                // Abre el modal
                $("#editarTypeModal").modal("show");
            },
            error: function() {
                alert("Error al cargar el formulario de edición.");
            }
        });
    }

</script>