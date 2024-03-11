<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar mail al cliente</title>
</head>

<body>
    <div class="container">
        <h2 class="text-center mb-4">Enviar mail al cliente</h2>
        <form id="miFormulario" action="views/sales/enviar_correo.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="email" class="col-form-label p-0 m-0">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control form-control-sm"
                    placeholder="Ingrese correo electrónico del cliente" required>
            </div>
            <div class="mb-3">
                <label for="pdf">Adjuntar PDF:</label>
                <input type="file" id="pdf" name="pdf" accept=".pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_FILES['pdf'])) {
                $archivo = $_FILES['pdf'];
                if ($archivo['error'] === UPLOAD_ERR_OK) {
                    $ruta_destino = 'html/public/pdf/' . $archivo['name'];
                    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                        echo "<p>El archivo se ha subido correctamente.</p>";
                    } else {
                        echo "<p>Hubo un error al subir el archivo.</p>";
                    }
                } else {
                    echo "<p>Error al subir el archivo: " . $archivo['error'] . "</p>";
                }
            } else {
                echo "<p>No se han enviado archivos.</p>";
            }
        }
        ?>
</body>

</html>