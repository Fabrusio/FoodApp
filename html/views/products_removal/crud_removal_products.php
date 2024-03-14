<?php
include_once "/var/www/html/models/insproductsmodel.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$insProductsModel = new InsProductsModel();

$reasons = $insProductsModel->getAllReasons();

function cmp($a, $b) {
    return $a->getIdRemoval() - $b->getIdRemoval();
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
        <h1 class="mt-6">Razones por las que remover los productos</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Razón</th>
                    <th>Eliminar</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($reasons as $reason): ?>
                <tr>
                    <td><?php echo $reason->getIdRemoval(); ?></td>
                    <td><?php echo $reason->getRemovalReason(); ?></td>
                    <td>
                        <form id="deleteForm<?php echo $reason->getIdRemoval(); ?>" action='<?php echo constant('URL'); ?>crud_insproducts/deleteReason' method="POST">
                            <input type="hidden" name="id" value="<?php echo $reason->getIdRemoval(); ?>">
                            <button id="eliminarBtn" class="btn btn-danger" type="button" name="eliminar" onclick="confirmDelete('<?php echo $reason->getIdRemoval(); ?>')">Eliminar</button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-edit" onclick="openEditModal(<?php echo $reason->getIdRemoval(); ?>)">Editar</button>
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
        title: '¿Estás seguro de que deseas eliminar la razón?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo constant('URL') ?>crud_insproducts/deleteReason", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    var row = document.getElementById("userRow" + typeId);
                    if (row) {
                        row.remove();
                    }
                    CargarContenido('views/products_removal/crud_removal_products.php', 'content-wrapper');
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
        $.ajax({
            url: "views/products_removal/edit_removal_products.php", // Ruta al archivo de edición de usuario
            type: "GET",
            data: { id: typeId }, 
            success: function(response) {
                $("#edit-form-container").html(response).slideDown();
                $("#editarTypeModal").modal("show");
            },
            error: function() {
                alert("Error al cargar el formulario de edición.");
            }
        });
    }

</script>