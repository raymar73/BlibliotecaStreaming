<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Idiomas</title>
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

<!-- contenido pagina      -->
<div class="container mt-5">
    <h1>Listado de Idiomas</h1>
    <a href="../routes/router.php?path=languages/create" class="btn btn-success mb-3">Nuevo Idioma</a>
    <a href="../public/index.html" class="btn btn-success mb-3">Inicio</a>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Idioma</th>
            <th>IsoCode</th>
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
        $totalLanguages = count($language);
        $totalPages = ceil( $totalLanguages / $rowsPerPage);

        // Mostrar los idiomas de la página actual
        $currentLanguages = array_slice($language, $startIndex, $rowsPerPage);

        if (!empty($currentLanguages)): 
            $rowNumber = $startIndex + 1; ?>
            <?php foreach ($currentLanguages as $language): ?>
                <tr>
                    <td><?= $rowNumber++ ?></td>
                    <td><?= $language->getName() ?></td>
                    <td><?= $language->getIsocode() ?></td>
                    <td>
                        <a href="../routes/router.php?path=languages/edit&id=<?= $language->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                data-entity-type="El idioma" 
                                data-entity-name="<?= $language->getName() ?>" 
                                data-delete-url="../routes/router.php?path=languages/delete&id=<?= $language->getId() ?>">
                            Eliminar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No hay Idioma registradas.</td>
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
                        <a class="page-link" href="router.php?path=languages/list&page=<?= $currentPage - 1 ?>">Anterior</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="router.php?path=languages/list&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="router.php?path=languages/list&page=<?= $currentPage + 1 ?>">Siguiente</a>
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
