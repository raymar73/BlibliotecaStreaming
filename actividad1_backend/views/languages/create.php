<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Idiomas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- seccion de ventana de exito o error -->
    <?php
    if (isset($_SESSION['success_message'])) {
        error_log("Mensaje recibido en la vista: " . $_SESSION['success_message']);
    } else {
        error_log("No se recibió el mensaje de éxito en la vista.");
    }
    ?>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showSuccessModal("<?= $_SESSION['success_message']; ?>");

                // Redirigir después de 3 segundos
                setTimeout(function () {
                    window.location.href = "../routes/router.php?path=languages/list";
                }, 3000);
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showErrorModal("<?= $_SESSION['error_message']; ?>");

                // No redirigir automáticamente, el usuario cierra el modal manualmente
            });
        </script>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- seccion de formulario -->
    <div class="container mt-5">
        <h1>Crear Idiomas</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"> <?= $error ?> </div>
        <?php endif; ?>
        <form action="" method="POST" id="createLanguageForm">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre del Idioma</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="isocode" class="form-label">Código ISO (2 letras)</label>
                <input type="text" class="form-control" id="isocode" name="isocode" maxlength="2" pattern="[A-Za-z]{2}"
                    title="El código ISO debe ser exactamente 2 letras (por ejemplo: ES, EN, FR)" required
                    style="text-transform: uppercase;">
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"
                id="showCreateModal" data-entity-name="">Guardar</button>
            <a href="../routes/router.php?path=languages/list" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
 
    <!-- Incluir el modal de confirmación -->
    <?php include '../public/assets/modals/createModal.php'; ?>
    <?php include '../public/assets/modals/successModal.php'; ?>
    <?php include '../public/assets/modals/errorModal.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/scripts/createModal.js"></script>
    <script src="../public/assets/scripts/statusModal.js"></script>
</body>

</html>