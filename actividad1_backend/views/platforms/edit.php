<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Plataforma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showSuccessModal("<?= $_SESSION['success_message']; ?>");

                // Redirigir después de 3 segundos
                setTimeout(function () {
                    window.location.href = "../routes/router.php?path=platforms/list";
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
    <div class="container mt-5">
        <h1>Editar Plataforma</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"> <?= $error ?> </div>
        <?php endif; ?>

        <form action="" method="POST" id="editPlatformForm">
            <input type="hidden" name="id" value="<?= $platform->getId() ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de la Plataforma</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $platform->getName() ?>" required>
            </div>
            <button 
                type="button" 
                class="btn btn-primary" 
                data-bs-toggle="modal" 
                data-bs-target="#updateModal" 
                data-entity-type="la plataforma" 
                data-entity-name="<?= $platform->getName() ?>">
                Actualizar
            </button>
            <a href="../routes/router.php?path=platforms/list" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

<!-- Incluir el modal de confirmación -->
<?php include '../public/assets/modals/updateModal.php'; ?>
<?php include '../public/assets/modals/successModal.php'; ?>
<?php include '../public/assets/modals/errorModal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/assets/scripts/updateModal.js"></script>
<script src="../public/assets/scripts/statusModal.js"></script>
</body>
</html>
