<?php
include_once "../../models/usermodel.php"; // Asegúrate de que la ruta sea correcta

// Verifica si se ha pasado un ID de usuario válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];
    $userModel = new UserModel();

    // Obtén los datos del usuario por su ID
    $usuario = $userModel->get($userId);

    // Verifica si el usuario existe
    if ($usuario) {
        $existingUsernames = json_encode($userModel->getAllUsernames());

?>




     <!-- Ventana modal -->
     <head>
     <link rel="stylesheet" href="../../public/css/styleEdit.css">
     </head>
    <div class="modal fade custom-modal" id="editarUsuarioModal"tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <!-- Encabezado de la ventana modal -->
                <div class="modal-header">
                    <h4 class="modal-title">Editar Usuario</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Contenido del formulario -->
                <div class="modal-body">
                    <form id="miFormulario" method='post' action='http://localhost:8080/crud_users/editUser'>
                        <input type='hidden' name='id' value='<?php echo $usuario->getId(); ?>'>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de Usuario:</label>
                            <input required type='text' class='form-control' name='nombre' id="username" value='<?php echo $usuario->getUsername(); ?>'>
                            <span id="username-error" style="color: red;"></span>
                        </div>
                        <div class="mb-3">
                        <label for="rol" class="form-label">Rol:</label>
                        <select class="form-select" name="rol">
                            <option value="1"<?php if ($usuario->getRole() === 1) echo 'selected'; ?>>Admin</option>
                            <option value="2"<?php if ($usuario->getRole() === 2) echo 'selected'; ?>>Vendedor</option>
                            <option value="3"<?php if ($usuario->getRole() === 3) echo 'selected'; ?>>Cocinero</option>
                        </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type='text' class='form-control' name='name' value='<?php echo $usuario->getName(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Apellido:</label>
                            <input type='text' class='form-control' name='surname' value='<?php echo $usuario->getSurname(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="text" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class='form-control' name='dni' value='<?php echo $usuario->getDni(); ?>'>
                        </div>
                        <div class="mb-3">
                        <label for="gender" class="form-label">Género:</label>
                        <select class="form-select" name="gender">
                            <option value="1"<?php if ($usuario->getGender() === 1) echo 'selected'; ?>>Femenino</option>
                            <option value="2"<?php if ($usuario->getGender() === 2) echo 'selected'; ?>>Masculino</option>
                            <option value="3"<?php if ($usuario->getGender() === 3) echo 'selected'; ?>>Otro</option>
                        </select>
                        </div>
                        <div class="mb-3">
                            <label for="province" class="form-label">Provincia:</label>
                            <input type='text' class='form-control' name='province' value='<?php echo $usuario->getProvince(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="localidad" class="form-label">Localidad:</label>
                            <input type='text' class='form-control' name='localidad' value='<?php echo $usuario->getLocalidad(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="street" class="form-label">Calle:</label>
                            <input type='text' class='form-control' name='street' value='<?php echo $usuario->getStreet(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="bwStreet" class="form-label">Entre Calle 1:</label>
                            <input type='text' class='form-control' name='bwStreet' value='<?php echo $usuario->getBwStreet(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="bwStreetTwo" class="form-label">Entre Calle 2:</label>
                            <input type='text' class='form-control' name='bwStreetTwo' value='<?php echo $usuario->getBwStreetTwo(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="altura" class="form-label">Altura:</label>
                            <input type='text' class='form-control' name='altura' value='<?php echo $usuario->getAltura(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="cel" class="form-label">Celular:</label>
                            <input type='text' class='form-control' name='cel' value='<?php echo $usuario->getContactCel(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail:</label>
                            <input type='text' class='form-control' name='email' value='<?php echo $usuario->getContactEmail(); ?>'>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">Estado:</label>
                            <input type='text' class='form-control' name='state' value='<?php echo $usuario->getState(); ?>'>
                        </div>
                        <button type='submit' class='btn btn-primary'>Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

} else {
    echo 'Usuario no encontrado.';
}
} else {
echo 'ID de usuario no válido.';
}
?>
<script>
var existingUsernames = <?php echo $existingUsernames; ?>;
document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    var username = document.getElementById('username').value;
    var usernameError = document.getElementById('username-error');

    if (existingUsernames.includes(username)) {
        usernameError.innerText = 'El nombre de usuario ya está en uso.';
    } else {
        const formData = new FormData(this);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost:8080/crud_users/editUser', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status === 200) {
                // La solicitud se completó con éxito, maneja la respuesta aquí si es necesario
                console.log(xhr.responseText);
                
                // Cerrar el modal después de enviar los datos
                $('#editarUsuarioModal').modal('hide');

                // Recargar solo el contenido relevante de la página
                CargarContenido('views/users/crud_users.php', 'content-wrapper');
            } else {
                // Ocurrió un error durante la solicitud
                console.error('Error en la solicitud: ' + xhr.status);
            }
        };

        xhr.send(formData);
    }
});

</script>
