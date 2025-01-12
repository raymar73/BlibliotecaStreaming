document.addEventListener('DOMContentLoaded', function () {
    const updateModal = document.getElementById('updateModal');

    // Escuchar el evento "show.bs.modal" del modal
    updateModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Botón que activó el modal
        const entityType = button.getAttribute('data-entity-type'); // Tipo de entidad
        const entityName = button.getAttribute('data-entity-name'); // Nombre de la entidad

        // Actualizar el contenido del modal
        const updateEntityNameElement = document.getElementById('updateEntityName');
        const updateEntityTypeElement = document.getElementById('updateEntityType');

        updateEntityNameElement.textContent = entityName;
        updateEntityTypeElement.textContent = entityType;
    });

    // Confirmar y enviar el formulario de plataforma
    const confirmUpdateButton = document.getElementById('confirmUpdate');
    confirmUpdateButton.addEventListener('click', function () {
        const form = document.getElementById('editPlatformForm');
        form.submit(); // Enviar el formulario
    });
    // Confirmar y enviar el formulario de idiomas
    const confirmUpdateButtonLanguage = document.getElementById('confirmUpdate');
    confirmUpdateButtonLanguage.addEventListener('click', function () {
            const form = document.getElementById('editLanguageForm');
            form.submit(); // Enviar el formulario
    });

        // Confirmar y enviar el formulario de idiomas
    const confirmUpdateButtonDirector = document.getElementById('confirmUpdate');
    confirmUpdateButtonDirector.addEventListener('click', function () {
             const form = document.getElementById('editDirectorForm');
            form.submit(); // Enviar el formulario
    });


    // Confirmar y enviar el formulario de Actor
    const confirmUpdateButtonActor = document.getElementById('confirmUpdate');
    confirmUpdateButtonActor.addEventListener('click', function () {
        const form = document.getElementById('editActorForm');
        form.submit(); // Enviar el formulario
    });

    // Confirmar y enviar el formulario de Serie
    const confirmUpdateButtonSerie = document.getElementById('confirmUpdate');
    confirmUpdateButtonActor.addEventListener('click', function () {
        const form = document.getElementById('editSeriesForm');
        form.submit(); // Enviar el formulario
    });

});
