<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Actores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Listado de Actores</h1>

        <!-- Mostrar mensaje de éxito si existe -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?> <!-- Limpiar el mensaje después de mostrarlo -->
        <?php endif; ?>

        <!-- Mostrar mensaje de error si existe -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?> <!-- Limpiar el mensaje después de mostrarlo -->
        <?php endif; ?>

        <a href="../routes/router.php?path=actors/create" class="btn btn-success mb-3">Nuevo Actor</a>
        <a href="../public/index.html" class="btn btn-success mb-3">Inicio</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Nacionalidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Configuración de la paginación
                $rowsPerPage = 10;
                $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                $startIndex = ($currentPage - 1) * $rowsPerPage;
                $totalActors = count($listActors);
                $totalPages = ceil($totalActors / $rowsPerPage);
                $currentActors = array_slice($listActors, $startIndex, $rowsPerPage);

                if (!empty($listActors)):
                    $rowNumber = $startIndex + 1;
                    foreach ($currentActors as $actor): ?>
                        <tr>
                            <td><?= $rowNumber++ ?></td>
                            <td><?= $actor->getNombres() ?></td>
                            <td><?= $actor->getApellidos() ?></td>
                            <td><?= $actor->getFechaNacimiento() ?></td>
                            <td><?= $actor->getNacionalidad() ?></td>
                            <td>
                                <a href="../routes/router.php?path=actors/edit&id=<?= $actor->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-entity-type="el actor"
                                    data-entity-name="<?= $actor->getNombres() . ' ' . $actor->getApellidos() ?>"
                                    data-delete-url="../routes/router.php?path=actors/delete&id=<?= $actor->getId() ?>">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay actores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="router.php?path=actors/list&page=<?= $currentPage - 1 ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="router.php?path=actors/list&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="router.php?path=actors/list&page=<?= $currentPage + 1 ?>">Siguiente</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <?php include("../public/assets/modals/deleteModal.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/scripts/deleteModal.js"></script>
</body>

</html>
