<?php
include_once "/var/www/html/models/menumodel.php";
include_once "/var/www/html/models/productsmodel.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$menuModel = new MenuModel();
$productsModel = new ProductsModel();

$menus = $menuModel->getAll();
$categorys = $menuModel->getAllCat();
$products = $productsModel->getAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Menu</title>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Formulario de Menu</h2>
        <form id="miFormulario">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de Menu:</label>
                <input type="text" maxlength="30" class="form-control" name="name" id="name" required>
                <span id="name-error" style="color: red;"></span>
            </div>
            <div class="mb-3">
                <label for="detail" class="form-label">Detalle del Menu:</label>
                <input type="text" maxlength="50" class="form-control" name="detail" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Precio:</label>
                <input type="text" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" name="price" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Categoria:</label>
                <select class="form-select form-control" name="category" aria-label="Default select example">
                    <?php
                        foreach ($categorys as $cat) {
                            echo '<option value="' . $cat->getIdCat() . '">' . $cat->getCatName() . '</option>';
                        }
                    ?>
                </select>
            </div>
            <button type="button" class="btn btn-success" id="agregar_producto_btn">Añadir Producto</button> <br><br>
            <div id="productos_container"></div>

            <script>
                // Variable global para contar el número de selectores
                var contador = 0;
                    var productosSeleccionados = []; // Matriz para almacenar los selectores y la cantidad

                    document.getElementById('agregar_producto_btn').addEventListener('click', function() {
                        const productosContainer = document.getElementById('productos_container');
                        const nuevoProductoContainer = document.createElement('div');
                        nuevoProductoContainer.classList.add('producto-container'); // Añadir una clase para referencia fácil

                        contador++; // Incrementar el contador cuando se añade un nuevo select
                        actualizarContador(); // Actualizar el contador en el HTML
                        nuevoProductoContainer.innerHTML = `
                            <div class="mb-3">
                                <label for="product" class="form-label">Productos:</label>
                                <select class="form-select form-control" name="productos[]" aria-label="Default select example">
                                    <?php
                                        foreach ($products as $prod) {
                                            echo '<option value="' . $prod->getId() . '">' . $prod->getItemName() . '</option>';
                                        }
                                    ?>
                                </select>
                                <div class="mb-3">
                                    <label for="cant" class="form-label">Cantidad:</label>
                                    <input type="text" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control" name="cant" required>
                                </div>
                                <button type="button" class="btn btn-danger eliminar_producto_btn">Eliminar Producto</button>
                            </div>
                        `;

                        // Agregar el nuevo contenedor al contenedor de productos
                        productosContainer.appendChild(nuevoProductoContainer);

                        // Agregar evento para eliminar productos
                        nuevoProductoContainer.querySelector('.eliminar_producto_btn').addEventListener('click', function() {
                            // Obtener el índice del contenedor actual en la matriz
                            var indice = productosSeleccionados.indexOf(nuevoProductoContainer);
                            if (indice !== -1) {
                                // Eliminar el elemento del array si se encuentra
                                productosSeleccionados.splice(indice, 1);
                            }
                            productosContainer.removeChild(nuevoProductoContainer);
                            contador--; // Decrementar el contador cuando se elimina un select
                            actualizarContador(); // Actualizar el contador en el HTML
                        });
                        const nuevoProducto = {
                            contenedor: nuevoProductoContainer,
                            cantidad: 0 // La cantidad inicial se establece en 0
                        };
                            nuevoProductoContainer.querySelector('input[name="cant"]').addEventListener('input', function() {
                            // Actualizar la cantidad en el objeto productosSeleccionados cada vez que cambie el valor del campo de cantidad
                            nuevoProducto.cantidad = this.value;
                        });
                        productosSeleccionados.push(nuevoProducto);

                    });
                // Función para actualizar el contador en el HTML
                function actualizarContador() {
                    const contadorElement = document.getElementById('contador');
                    contadorElement.textContent = contador;
                }


            </script>
            <p>Número de selectores: <span id="contador">0</span></p>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script>
var existingNames = <?php echo json_encode($menuModel->getAllnames()); ?>;
const formulario = document.getElementById('miFormulario'); // Definir formulario fuera del evento

document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    var name = document.getElementById('name').value;
    var nameError = document.getElementById('name-error');

    if (existingNames.includes(name)) {
        nameError.innerText = 'El nombre del menú ya está en uso.';
        Swal.fire({
            icon: 'error',
            title: 'MENÚ YA EXISTE',
            text: 'Por favor ingrese otro menú'
        });
    } else {
        const formData = new FormData(formulario); // Crear formData aquí

        // Código para agregar productosSeleccionados al formData
        productosSeleccionados.forEach((producto, index) => {
            formData.append(`productosSeleccionados[${index}][id]`, producto.contenedor.querySelector('select').value);
            formData.append(`productosSeleccionados[${index}][cantidad]`, producto.cantidad);
        });

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo constant('URL') ?>crud_menus/createMenu', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Formulario enviado con éxito'
                }).then(() => {
                    formulario.reset();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al enviar el formulario',
                });
            }
        };

        xhr.send(formData);
    }
});

// Resto de tu código, incluyendo la función actualizarContador() y otros eventos, deberían permanecer igual

</script>

