<?php
include_once "/var/www/html/models/insproductsmodel.php";
include_once "/var/www/html/models/productsmodel.php";
$insproductsModel = new InsProductsModel();
$productsModel = new ProductsModel();

$products = $insproductsModel->getAllDeletedBatches();
$productsM = $productsModel->getAll();

function cmp($a, $b) {
    return $a->getId() - $b->getId();
}
usort($products, "cmp");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lotes eliminados</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-6">Lotes eliminados</h1>
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
                    <th>Razón de baja</th>
                    <th>Fecha de baja</th>
                    <th>Eliminar registro</th>
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
                    ?>
                    </td>
                    <td><?php echo $product->getPrice(); ?></td>
                    <td><?php echo $product->getRazonSocial(); ?></td>
                    <td><?php echo $product->getPurchaseDate(); ?></td>
                    <td><?php echo $product->getExpirationDate(); ?></td>
                    <td><?php echo $product->getRemovalReason(); ?></td>
                    <td><?php echo $product->getDeletedDate(); ?></td>
                    <td>
                        <form id="deleteForm" action='<?php echo constant('URL'); ?>crud_insproducts/deleteDBatch' method="POST">
                            <input type="hidden" name="id" value="<?php echo $product->getId(); ?>">
                            <button class="btn btn-danger" type="button" name="eliminar" onclick="confirmDelete('<?php echo $product->getId(); ?>')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

<script>

function confirmDelete(productId) {
    Swal.fire({
        title: '¿Estás seguro de que deseas eliminar este registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo constant('URL') ?>crud_insproducts/deleteDBatch", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    var row = document.getElementById("userRow" + productId);
                    if (row) {
                        row.remove();
                    }
                    CargarContenido('views/products_removal/deleted_batches.php', 'content-wrapper');
                } else {
                    console.error('Error en la solicitud: ' + xhr.status);
                }
            };
            xhr.send("id=" + productId);
        }
    });
}

</script>