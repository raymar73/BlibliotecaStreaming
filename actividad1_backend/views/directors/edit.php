<!-- edit.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Director</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
<!-- seccion de ventanas de exito o errores -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showSuccessModal("<?= $_SESSION['success_message']; ?>");

                // Redirigir después de 3 segundos
                setTimeout(function () {
                    window.location.href = "../routes/router.php?path=directors/list";
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


<!-- formulario -->
    <div class="container mt-5">
        <h1>Editar Director</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" id="editDirectorForm">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?= htmlspecialchars($director->getName()) ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    value="<?= htmlspecialchars($director->getlast_name()) ?>" required>
            </div>
            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Fecha Nacimiento</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                    value="<?= htmlspecialchars($director->getdate_of_birth()) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nationality" class="form-label">Nacionalidad</label>
                <input type="text" class="form-control" id="nationality" name="nationality"
                    value="<?= htmlspecialchars($director->getnationality()) ?>" required>
            </div>
            <button 
                type="button" 
                class="btn btn-primary"
                data-bs-toggle="modal" 
                data-bs-target="#updateModal" 
                data-entity-type="la listas de Directores." 
                data-entity-name="<?= $director->getName() ?>">
                Actualizar</button>
            <a href="../routes/router.php?path=directors/list" class="btn btn-secondary">Cancelar</a>
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