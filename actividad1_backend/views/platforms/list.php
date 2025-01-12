
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Plataformas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
        <h1>Listado de Plataformas</h1>
        <a href="../routes/router.php?path=platforms/create" class="btn btn-success mb-3">Nueva Plataforma</a>
        <a href="../public/index.html" class="btn btn-success mb-3">Inicio</a>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Configuración de la paginación
            $rowsPerPage = 10; // Número de filas por página
            $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
            $startIndex = ($currentPage - 1) * $rowsPerPage; // Índice de inicio

            // Dividir las plataformas en partes
            $totalPlatforms = count($platforms);
            $totalPages = ceil($totalPlatforms / $rowsPerPage);

            // Mostrar las plataformas de la página actual
            $currentPlatforms = array_slice($platforms, $startIndex, $rowsPerPage);

            if (!empty($currentPlatforms)): 
                $rowNumber = $startIndex + 1; ?>
                <?php foreach ($currentPlatforms as $platform): ?>
                    <tr>
                        <td><?= $rowNumber++ ?></td>
                        <td><?= $platform->getName() ?></td>
                        <td>
                            <a href="../routes/router.php?path=platforms/edit&id=<?= $platform->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                    data-entity-type="la plataforma" 
                                    data-entity-name="<?= $platform->getName() ?>" 
                                    data-delete-url="../routes/router.php?path=platforms/delete&id=<?= $platform->getId() ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No hay plataformas registradas.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="router.php?path=platforms/list&page=<?= $currentPage - 1 ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="router.php?path=platforms/list&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="router.php?path=platforms/list&page=<?= $currentPage + 1 ?>">Siguiente</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
<!-- Incluir el modal -->
<?php include '../public/assets/modals/deleteModal.php'; ?>
<?php include '../public/assets/modals/successModal.php'; ?>
<?php include '../public/assets/modals/errorModal.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/assets/scripts/deleteModal.js"></script>
<script src="../public/assets/scripts/statusModal.js"></script>
</body>
</html>
