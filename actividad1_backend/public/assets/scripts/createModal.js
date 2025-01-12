document.addEventListener('DOMContentLoaded', function () {
    const createModal = document.getElementById('createModal');
    const nameInput = document.getElementById('name');
    const createEntityNameElement = document.getElementById('createEntityName');
    const showCreateModalButton = document.getElementById('showCreateModal');

    // Antes de mostrar el modal, actualiza el nombre dinámicamente
    showCreateModalButton.addEventListener('click', function () {
        const entityName = nameInput.value.trim();
        if (entityName) {
            showCreateModalButton.setAttribute('data-entity-name', entityName);
            createEntityNameElement.textContent = entityName;
        } else {
            createEntityNameElement.textContent = "una nueva plataforma";
        }
    });

    // Confirmar y enviar el formulario A PLATAFORMA
    const confirmCreateButton = document.getElementById('confirmCreate');
    confirmCreateButton.addEventListener('click', function () {
        const form = document.getElementById('createPlatformForm');
        form.submit(); // Enviar el formulario
    });
       // Confirmar y enviar el formulario A iDIOMAS
       const confirmCreateButtonLanguage = document.getElementById('confirmCreate');
       confirmCreateButtonLanguage.addEventListener('click', function () {
           const form = document.getElementById('createLanguageForm');
           form.submit(); // Enviar el formulario
       });
       // Confirmar y enviar el formulario A Directores
       const confirmCreateButtonDirector = document.getElementById('confirmCreate');
       confirmCreateButtonDirector.addEventListener('click', function () {
           const form = document.getElementById('createDirectorForm');
           form.submit(); // Enviar el formulario
       });
       // Confirmar y enviar el formulario A Series
       const confirmCreateButtonSeries = document.getElementById('confirmCreate');
       confirmCreateButtonSeries.addEventListener('click', function () {
           const form = document.getElementById('createSeriesForm');
           form.submit(); // Enviar el formulario
       });
           // Confirmar y enviar el Actores
           const confirmCreateButtonActores = document.getElementById('confirmCreate');
           confirmCreateButtonActores.addEventListener('click', function () {
               const form = document.getElementById('createModalActor');
               form.submit(); // Enviar el formulario
           });
   

    

    // Mostrar mensaje de éxito o error
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');

    if (successMessage) {
        showSuccessModal(successMessage.textContent);
        setTimeout(() => {
            window.location.href = "../routes/router.php?path=platforms/list";
            // window.location.href = "../routes/router.php?path=languages/list";
        }, 3000);
    }

    if (errorMessage) {
        showErrorModal(errorMessage.textContent);
    }
});
