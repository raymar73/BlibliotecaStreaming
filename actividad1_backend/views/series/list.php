<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Series</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php if (isset($_SESSION['success_message'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = <?= json_encode($_SESSION['success_message'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
            showSuccessModal(successMessage);

            setTimeout(function () {
                window.location.href = "../routes/router.php?path=series/list";
            }, 3000);
        });
    </script>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>


    <?php if (isset($_SESSION['error_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const errorMessage = <?= json_encode($_SESSION['error_message'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
                showErrorModal(errorMessage);
            });
        </script>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <div class="container mt-5">
        <h1>Listado de Series</h1>
        <a href="../routes/router.php?path=series/create" class="btn btn-success mb-3">Nueva Serie</a>
        <a href="../public/index.html" class="btn btn-success mb-3">Inicio</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Plataforma</th>
                    <th>Director</th>
                    <th>Actores</th>
                    <th>Audios</th>
                    <th>Subtítulos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Configuración de la paginación
                $rowsPerPage = 10; // Número de filas por página
                $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
                $startIndex = ($currentPage - 1) * $rowsPerPage; // Índice de inicio
                if ($series instanceof Series) {
                    $series = [$series]; // Convierte el objeto único en un array.
                }
                // Dividir las series en partes
                $totalSeries = count($series);
                $totalPages = ceil($totalSeries / $rowsPerPage);

                // Mostrar las series de la página actual
                $currentSeries = array_slice($series, $startIndex, $rowsPerPage);

                if (!empty($currentSeries)): 
                    $rowNumber = $startIndex + 1; ?>
                    <?php foreach ($currentSeries as $serie): ?>
                        <tr>
                            <td><?= $rowNumber++ ?></td>
                            <td><?= $serie->getTitle() ?></td>
                            <td><?= $serie->getPlatformName() ?></td>
                            <td><?= $serie->getDirectorName() ?></td>
                            <td>
                                <?php if ($serie->getActors()): ?>
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach (explode(', ', $serie->getActors()) as $actor): ?>
                                            <li><?= $actor ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <em>Sin actores registrados</em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($serie->getAudioLanguages()): ?>
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach (explode(', ', $serie->getAudioLanguages()) as $audio): ?>
                                            <li><?= $audio ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <em>Sin audios registrados</em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($serie->getSubtitleLanguages()): ?>
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach (explode(', ', $serie->getSubtitleLanguages()) as $subtitle): ?>
                                            <li><?= $subtitle ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <em>Sin subtítulos registrados</em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="../routes/router.php?path=series/edit&id=<?= $serie->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                        data-entity-name="<?= $serie->getTitle() ?>"
                                        data-delete-url="../routes/router.php?path=series/delete&id=<?= $serie->getId() ?>">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No hay series registradas.</td>
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
                            <a class="page-link" href="router.php?path=series/list&page=<?= $currentPage - 1 ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="router.php?path=series/list&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="router.php?path=series/list&page=<?= $currentPage + 1 ?>">Siguiente</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

<?php include '../public/assets/modals/deleteModal.php'; ?>
<?php include '../public/assets/modals/successModal.php'; ?>
<?php include '../public/assets/modals/errorModal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/assets/scripts/deleteModal.js"></script>
<script src="../public/assets/scripts/statusModal.js"></script>
</body>
</html>
