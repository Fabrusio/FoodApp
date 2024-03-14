<?php
include_once "../../models/providermodel.php"; 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];
    $providerModel = new ProviderModel();

    $providers = $providerModel->get($userId);
    
    $provedores = $providerModel->getAll();
    $razonesSociales = array();
    foreach ($provedores as $provider) {
        $razonesSociales[] = $provider->getRazonSocial();
    }
    $CUIT = array();
    foreach ($provedores as $provider) {
        $CUIT[] = $provider->getCuit();
    }

    if ($providers) {

?>


     <!-- Ventana modal -->
     <head>
     <link rel="stylesheet" href="../../public/css/styleEdit.css">
     </head>
    <div class="modal fade custom-modal" id="editarUsuarioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <!-- Encabezado de la ventana modal -->
                <div class="modal-header">
                    <h4 class="modal-title">Editar Proveedor</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Contenido del formulario -->
                <div class="modal-body">
                <form method='POST' action='<?php echo constant('URL') ?>crud_providers/editProviders'>
                            <input type='hidden' name='id' value='<?php echo $userId; ?>'>
                        <div class="mb-3">
                            <label for="providerName" class="form-label">Razón Social:</label>
                            <input required type='text' maxlength="20" class='form-control' name='providerName' id="providerName" value='<?php echo $providers->getRazonSocial(); ?>'>
                            <span id="providerName-error" style="color: red;"></span>
                        </div>
                        <div class="mb-3">
                            <label for="CUIT" class="form-label">CUIT:</label>
                            <input type='text' maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='CUIT' id="CUIT" value='<?php echo $providers->getCuit(); ?>'>
                            <span id="CUIT-error" style="color: red;"></span>
                        </div>
                        <button type='submit' class='btn btn-primary'>Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

    } else {
        echo 'Proveedor no encontrado.';
    }
} else {
echo 'ID de proveedor no válido.';
}
?>

<script>
    var existingNames = <?php echo json_encode($razonesSociales); ?>;
    var existingCuit = <?php echo json_encode($CUIT); ?>;
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        var provider = document.getElementById('providerName').value;
        var providerError = document.getElementById('providerName-error');
        var cuit = document.getElementById('CUIT').value;
        var cuitError = document.getElementById('CUIT-error');

        if (existingNames.includes(provider)) {
            providerError.innerText = 'El proveedor ya está registrado.';
        } else if(existingCuit.includes(cuit)){
            cuitError.innerText = 'El CUIT ya está registrado';
        } else{
            this.submit();
        }

    });
</script>