<?php
include_once "/var/www/html/models/productsmodel.php";
$productsModel = new ProductsModel();

$products = $productsModel->getAll();
function cmp($a, $b) {
    return $a->getId() - $b->getId();
}
usort($products, "cmp");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Productos</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-6">Productos</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Producto</th>
                    <th>Stock</th>
                    <th></th>
                    <th>Precio</th>
                    <th>Proveedor</th>
                    <th>Alerta stock</th>
                    <th>Eliminar Producto</th>
                    <th>Editar Producto</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product->getId(); ?></td>
                    <td><?php echo $product->getItemName(); ?></td>
                    <td><?php echo $product->getStock(); ?></td>
                    <td><?php echo $product->getNameType(); ?></td>
                    <td><?php echo $product->getPrice(); ?></td>
                    <td><?php echo $product->getRazonSocial(); ?></td>
                    <td><?php if(($product->getStock()) < ($product->getStockAlert())){echo 'CONSIDERE COMPRAR MÁS';} else{echo 'Va bien';}?></td>
                    <td>
                        <form id="deleteForm" action='<?php echo constant('URL'); ?>crud_products/deleteProduct' method="POST">
                            <input type="hidden" name="id" value="<?php echo $product->getId(); ?>">
                            <button class="btn btn-danger" type="button" name="eliminar" onclick="confirmDelete('<?php echo $product->getId(); ?>')">Eliminar</button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-edit" onclick="openEditModal(<?php echo $product->getId(); ?>)">Editar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div id="edit-form-container" style="display: none;"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

function openEditModal(productId) {
    // Realiza una solicitud AJAX para obtener el formulario de edición
    $.ajax({
        url: "views/products/edit_products.php", 
        type: "GET",
        data: { id: productId }, 
        success: function(response) {
            $("#edit-form-container").html(response).slideDown();
            $("#editarUsuarioModal").modal("show");
        },
        error: function() {
            alert("Error al cargar el formulario de edición.");
        }
    });
}

</script>
<script>

function confirmDelete(productId) {
    Swal.fire({
        title: '¿Estás seguro de que deseas eliminar este producto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo constant('URL') ?>crud_products/deleteProduct", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    var row = document.getElementById("userRow" + productId);
                    if (row) {
                        row.remove();
                    }
                    CargarContenido('views/products/crud_products.php', 'content-wrapper');
                } else {
                    console.error('Error en la solicitud: ' + xhr.status);
                }
            };
            xhr.send("id=" + productId);
        }
    });
}

</script>
