
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showSuccessModal("<?= $_SESSION['success_message']; ?>");
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
        <h1>Editar Serie</h1>
        <form action="" method="POST" id="editSeriesForm">
            <!-- Título -->
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control" id="name" name="title" value="<?= htmlspecialchars($seriesInfo['series']['titulo']) ?>" required>
            </div>

            <!-- Plataforma -->
            <div class="mb-3">
                <label for="platform_id" class="form-label">Plataforma</label>
                <select class="form-control" id="platform_id" name="platform_id" required>
                    <option value="">Seleccionar Plataforma</option>
                    <?php foreach ($platforms as $platform): ?>
                        <option value="<?= $platform->getId(); ?>" <?= $platform->getId() == $seriesInfo['series']['plataforma_id'] ? 'selected' : '' ?>>
                            <?= $platform->getName(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Director -->
            <div class="mb-3">
                <label for="director_id" class="form-label">Director</label>
                <select class="form-control" id="director_id" name="director_id" required>
                    <option value="">Seleccionar Director</option>
                    <?php foreach ($directors as $director): ?>
                        <option value="<?= $director->getId(); ?>" <?= $director->getId() == $seriesInfo['series']['director_id'] ? 'selected' : '' ?>>
                        <?= $director->getName() . ' ' . $director->getlast_name(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Actores -->
            <div class="mb-3">
                <label for="actor-select" class="form-label">Actores</label>
                <select class="form-control" id="actor-select">
                    <option value="">Seleccionar Actor</option>
                    <?php foreach ($actors as $actor): ?>
                        <?php if (!in_array($actor->getId(), array_column($seriesInfo['actors'], 'id'))): // Excluir actores ya seleccionados ?>
                            <option value="<?= $actor->getId(); ?>"><?= $actor->getNombres() . ' ' . $actor->getApellidos(); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <div id="selected-actors" class="selected-items">
                    <?php foreach ($seriesInfo['actors'] as $actor): ?>
                        <div class="selected-item" data-value="<?= $actor['id'] ?>">
                            <?= $actor['nombre_completo'] ?>
                            <span class="remove-btn">&times;</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="actors" name="actors[]" value='<?= json_encode(array_column($seriesInfo['actors'], 'id')); ?>' required>
            </div>


            <!-- Idiomas de Audio -->
            <div class="mb-3">
                <label for="audio-language-select" class="form-label">Idiomas de Audio</label>
                <select class="form-control" id="audio-language-select">
                    <option value="">Seleccionar Idioma</option>
                    <?php foreach ($languages as $language): ?>
                        <?php if (!in_array($language->getId(), array_column($seriesInfo['audio_languages'], 'id'))): // Excluir idiomas ya seleccionados ?>
                            <option value="<?= $language->getId(); ?>"><?= $language->getName(); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <div id="selected-audio-languages" class="selected-items">
                    <?php foreach ($seriesInfo['audio_languages'] as $audio): ?>
                        <div class="selected-item" data-value="<?= $audio['id'] ?>">
                            <?= $audio['nombre']; ?>
                            <span class="remove-btn">&times;</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="languages_audio" name="languages_audio[]" value='<?= json_encode(array_column($seriesInfo['audio_languages'], 'id')); ?>'>
            </div>

            <!-- Idiomas de Subtítulos -->
            <div class="mb-3">
                <label for="subtitle-language-select" class="form-label">Idiomas de Subtítulos</label>
                <select class="form-control" id="subtitle-language-select">
                    <option value="">Seleccionar Idioma</option>
                    <?php foreach ($languages as $language): ?>
                        <?php if (!in_array($language->getId(), array_column($seriesInfo['subtitle_languages'], 'id'))): // Excluir idiomas ya seleccionados ?>
                            <option value="<?= $language->getId(); ?>"><?= $language->getName(); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <div id="selected-subtitle-languages" class="selected-items">
                    <?php foreach ($seriesInfo['subtitle_languages'] as $subtitle): ?>
                        <div class="selected-item" data-value="<?= $subtitle['id'] ?>">
                            <?= $subtitle['nombre']; ?>
                            <span class="remove-btn">&times;</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="languages_subtitles" name="languages_subtitles[]" value='<?= json_encode(array_column($seriesInfo['subtitle_languages'], 'id')); ?>'>
            </div>
            
            <button 
                type="button" 
                class="btn btn-primary"
                data-bs-toggle="modal" 
                data-bs-target="#updateModal" 
                data-entity-type="la listas de Series." 
                data-entity-name="<?= htmlspecialchars($seriesInfo['series']['titulo']) ?>">
                Actualizar</button>
            <a href="../routes/router.php?path=series/list" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        function setupMultiSelect(selectId, containerId, hiddenInputId) {
            const select = document.getElementById(selectId);
            const container = document.getElementById(containerId);
            const hiddenInput = document.getElementById(hiddenInputId);

            if (!select || !container || !hiddenInput) {
                console.error(`No se encontraron los elementos con IDs: ${selectId}, ${containerId}, ${hiddenInputId}`);
                return;
            }

            // Función para crear un nuevo elemento seleccionado
            function createSelectedItem(value, text) {
                const item = document.createElement("div");
                item.className = "selected-item";
                item.dataset.value = value;
                item.innerHTML = `
                    <span class="item-text">${text}</span>
                    <span class="remove-btn">&times;</span>
                `;

                // Agregar evento para eliminar
                item.querySelector(".remove-btn").addEventListener("click", function () {
                    container.removeChild(item);
                    const option = document.createElement("option");
                    option.value = value;
                    option.textContent = text;
                    select.appendChild(option);
                    updateHiddenInput(container, hiddenInput);
                });

                return item;
            }

            // Inicializar elementos ya preseleccionados
            Array.from(container.children).forEach(item => {
                const value = item.dataset.value;
                const text = item.textContent.trim().replace('×', ''); // Asegurarnos de remover el botón "×" del texto

                // Añadir evento para eliminar
                const removeBtn = item.querySelector(".remove-btn");
                if (removeBtn) {
                    removeBtn.addEventListener("click", function () {
                        container.removeChild(item);
                        const option = document.createElement("option");
                        option.value = value;
                        option.textContent = text;
                        select.appendChild(option);
                        updateHiddenInput(container, hiddenInput);
                    });
                }
            });

            // Agregar evento para seleccionar nuevos elementos
            select.addEventListener("change", function () {
                const selectedValue = select.value;
                const selectedText = select.options[select.selectedIndex].text;

                if (selectedValue) {
                    const newItem = createSelectedItem(selectedValue, selectedText);

                    container.appendChild(newItem);
                    select.querySelector(`option[value="${selectedValue}"]`).remove();
                    select.value = "";
                    updateHiddenInput(container, hiddenInput);
                }
            });
        }

        function updateHiddenInput(container, hiddenInput) {
            const values = Array.from(container.querySelectorAll(".selected-item"))
                .map(item => item.dataset.value);
            hiddenInput.value = JSON.stringify(values);
        }

        document.addEventListener("DOMContentLoaded", function () {
            setupMultiSelect("actor-select", "selected-actors", "actors");
            setupMultiSelect("audio-language-select", "selected-audio-languages", "languages_audio");
            setupMultiSelect("subtitle-language-select", "selected-subtitle-languages", "languages_subtitles");
        });

    </script>

    <!-- Incluir el modal de confirmación -->
    <?php include '../public/assets/modals/updateModal.php'; ?>
    <?php include '../public/assets/modals/successModal.php'; ?>
    <?php include '../public/assets/modals/errorModal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/scripts/updateModal.js"></script>
    <script src="../public/assets/scripts/statusModal.js"></script>
</body>
</html>
