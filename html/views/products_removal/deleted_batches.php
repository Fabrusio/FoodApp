<?php
include_once "/var/www/html/models/insproductsmodel.php";
$insproductsModel = new InsProductsModel();

$products = $insproductsModel->getAllDeletedBatches();
var_dump ($products);
// function cmp($a, $b) {
//     return $a->getId() - $b->getId();
// }
// usort($products, "cmp");
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
                    <td><?php echo $product->getNameType(); ?></td>
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

