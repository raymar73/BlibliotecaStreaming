<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Serie</title>
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
        <h1>Crear Serie</h1>
        <form action="" method="POST" id="createSeriesForm">
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control" id="name" name="title" required>
            </div>

            <div class="mb-3">
                <label for="platform_id" class="form-label">Plataforma</label>
                <select class="form-control" id="platform_id" name="platform_id" required>
                    <option value="">Seleccionar Plataforma</option>
                    <?php if (!empty($platforms)): ?>
                        <?php foreach ($platforms as $platform): ?>
                            <option value="<?= $platform->getId(); ?>"><?= $platform->getName(); ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay plataformas disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="director_id" class="form-label">Director</label>
                <select class="form-control" id="director_id" name="director_id" required>
                    <option value="">Seleccionar Director</option>
                    <?php if (!empty($directors)): ?>
                        <?php foreach ($directors as $director): ?>
                            <option value="<?= $director->getId(); ?>">
                                <?= $director->getName() . ' ' . $director->getlast_name(); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay directores disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Actores -->
            <div class="mb-3">
                <label for="actor-select" class="form-label">Actores</label>
                <select class="form-control" id="actor-select">
                    <option value="">Seleccionar Actor</option>
                    <?php foreach ($actors as $actor): ?>
                        <option value="<?= $actor->getId() ?>">
                            <?= $actor->getNombres() . ' ' . $actor->getApellidos() ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="selected-actors" class="selected-items"></div>
                <input type="hidden" id="actors" name="actors[]" value="" required>
            </div>

            <!-- Idiomas de Audio -->
            <div class="mb-3">
                <label for="audio-language-select" class="form-label">Idiomas de Audio</label>
                <select class="form-control" id="audio-language-select">
                    <option value="">Seleccionar Idioma</option>
                    <?php foreach ($languages as $language): ?>
                        <option value="<?= $language->getId() ?>">
                            <?= $language->getName() ?> (<?= $language->getIsocode() ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="selected-audio-languages" class="selected-items"></div>
                <input type="hidden" id="languages_audio" name="languages_audio[]" value="">
            </div>

            <!-- Idiomas de Subtítulos -->
            <div class="mb-3">
                <label for="subtitle-language-select" class="form-label">Idiomas de Subtítulos</label>
                <select class="form-control" id="subtitle-language-select">
                    <option value="">Seleccionar Idioma</option>
                    <?php foreach ($languages as $language): ?>
                        <option value="<?= $language->getId() ?>">
                            <?= $language->getName() ?> (<?= $language->getIsocode() ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="selected-subtitle-languages" class="selected-items"></div>
                <input type="hidden" id="languages_subtitles" name="languages_subtitles[]" value="">
            </div>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"
                id="showCreateModal" data-entity-name="">
                Guardar</button>

            <a href="../routes/router.php?path=series/list" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        function setupMultiSelect(selectId, containerId, hiddenInputId) {
            const select = document.getElementById(selectId);
            const container = document.getElementById(containerId);
            const hiddenInput = document.getElementById(hiddenInputId);

            // Verifica que los elementos existen antes de continuar
            if (!select || !container || !hiddenInput) {
                console.error(`No se encontraron los elementos con IDs: ${selectId}, ${containerId}, ${hiddenInputId}`);
                return;
            }

            select.addEventListener("change", function () {
                const selectedValue = select.value;
                const selectedText = select.options[select.selectedIndex].text;

                if (selectedValue) {
                    // Crear etiqueta
                    const item = document.createElement("div");
                    item.className = "selected-item";
                    item.dataset.value = selectedValue;
                    item.innerHTML = `
                        ${selectedText}
                        <span class="remove-btn">&times;</span>
                    `;

                    // Agregar evento para eliminar
                    item.querySelector(".remove-btn").addEventListener("click", function () {
                        container.removeChild(item);
                        select.querySelector(`option[value="${selectedValue}"]`).style.display = "block";
                        updateHiddenInput(container, hiddenInput);
                    });

                    // Agregar al contenedor
                    container.appendChild(item);

                    // Ocultar opción seleccionada en el desplegable
                    select.querySelector(`option[value="${selectedValue}"]`).style.display = "none";

                    // Resetear el valor del desplegable
                    select.value = "";

                    // Actualizar el input oculto
                    updateHiddenInput(container, hiddenInput);
                }
            });
        }

        function updateHiddenInput(container, hiddenInput) {
            // Verifica que los elementos existen antes de continuar
            if (!container || !hiddenInput) {
                console.error(`No se encontraron los elementos necesarios para actualizar el input.`);
                return;
            }

            // Extraer valores como array plano
            const values = Array.from(container.querySelectorAll(".selected-item"))
                .map(item => parseInt(item.dataset.value, 10)); // Asegúrate de convertir a enteros
            hiddenInput.value = JSON.stringify(values); // Serializa como JSON
        }

        // Configurar múltiples selectores
        document.addEventListener("DOMContentLoaded", function () {
            setupMultiSelect("actor-select", "selected-actors", "actors");
            setupMultiSelect("audio-language-select", "selected-audio-languages", "languages_audio");
            setupMultiSelect("subtitle-language-select", "selected-subtitle-languages", "languages_subtitles");
        });

    </script>

    <!-- Incluir el modal de confirmación -->
    <?php include '../public/assets/modals/createModal.php'; ?>
    <?php include '../public/assets/modals/successModal.php'; ?>
    <?php include '../public/assets/modals/errorModal.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/scripts/createModal.js"></script>
    <script src="../public/assets/scripts/statusModal.js"></script>
</body>
</html>
