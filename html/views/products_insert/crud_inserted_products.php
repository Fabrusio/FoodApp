<?php
include_once "/var/www/html/models/insproductsmodel.php";
include_once "/var/www/html/models/productsmodel.php";
$insproductsModel = new InsProductsModel();
$productsModel = new ProductsModel();

$products = $insproductsModel->getAll();
$productsM = $productsModel->getAll();
function cmp($a, $b) {
    return $a->getId() - $b->getId();
}
usort($products, "cmp");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lotes ingresados</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-6">Lotes Ingresados</h1>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Número de lote</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th></th>
                    <th>Precio</th>
                    <th>Proveedor</th>
                    <th>Fecha de compra</th>
                    <th>Fecha de vencimiento</th>
                    <th>Eliminar Producto</th>
                    <th>Editar Producto</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <input type="hidden" name="id" value="<?php echo $product->getId(); ?>">
                    <td><?php echo $product->getBatchNumber(); ?></td>
                    <td><?php echo $product->getItemName(); ?></td>
                    <td><?php echo $product->getQuantity(); ?></td>
                    <td><?php foreach ($productsM as $productM) {
                        if ($product->getIdItemName() == $productM->getId()) {
                            echo $productM->getNameType();
                            break;
                        }
                    }
                    ?></td>
                    <td><?php echo $product->getPrice(); ?></td>
                    <td><?php echo $product->getRazonSocial(); ?></td>
                    <td><?php echo $product->getPurchaseDate(); ?></td>
                    <td><?php echo $product->getExpirationDate(); ?></td>
                    <td>
                    <button class="btn btn-danger" type="button" name="eliminar" onclick="openDeleteModal(<?php echo $product->getId(); ?>)">Eliminar</button>
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
        url: "views/products_insert/edit_inserted_products.php", 
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

<div id="delete-modal-container" style="display: none;"></div>
<script>
    function openDeleteModal(productId) {
        console.log("ID del producto:", productId);
        $.ajax({
            url: "views/products_insert/delete_inserted_products.php", 
            type: "GET",
            data: { id: productId }, 
            success: function(response) {
                $("#delete-modal-container").html(response).slideDown();
                $("#eliminarProductoModal").modal("show");
            },
            error: function() {
                alert("Error al cargar el modal de eliminación.");
            }
        });
    }
</script>

