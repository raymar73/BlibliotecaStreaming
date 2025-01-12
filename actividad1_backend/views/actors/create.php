<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Actor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body >
    <?php
    // Mensajes de depuración para confirmar si los mensajes están presentes
    if (isset($_SESSION['success_message'])) {
        error_log("Mensaje recibido en la vista: " . $_SESSION['success_message']);
    } else {
        error_log("No se recibió el mensaje de éxito en la vista.");
    }
    ?>

    <!-- Mostrar el mensaje de éxito -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mostrar el modal con el mensaje de éxito
                showSuccessModal("<?= $_SESSION['success_message']; ?>");

                // Redirigir después de 3 segundos
                setTimeout(function() {
                    window.location.href = "../routes/router.php?path=actors/list";
                }, 3000);
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- Mostrar el mensaje de error si existe -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mostrar el modal con el mensaje de error
                showErrorModal("<?= $_SESSION['error_message']; ?>");
            });
        </script>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="container mt-5">
        <h1>Crear Actor</h1>

        <!-- Formulario de creación del actor -->
        <form action="" method="POST" id="createModalActor">
            <div class="mb-3">
                <label for="firstName" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="firstName" name="name" required>
            </div>

            <div class="mb-3">
                <label for="lastName" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="lastName" name="apellido" required>
            </div>

            <div class="mb-3">
                <label for="birthDate" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="birthDate" name="birthDate" required>
            </div>

            <div class="mb-3">
                <label for="nationality" class="form-label">Nacionalidad</label>
                <input type="text" class="form-control" id="nationality" name="nationality" required>
            </div>

            <button
                type="submit"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#AcreateModal"
                id="AshowModal">
                Guardar
            </button>
            <a href="../routes/router.php?path=actors/list" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <!-- Incluir los modales -->
    <?php include '../public/assets/modals/createModal.php'; ?>
    <?php include '../public/assets/modals/successModal.php'; ?>
    <?php include '../public/assets/modals/errorModal.php'; ?>

    <!-- Incluir los scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/scripts/createModal.js"></script>
    <script src="../public/assets/scripts/statusModal.js"></script>

</body>

</html>