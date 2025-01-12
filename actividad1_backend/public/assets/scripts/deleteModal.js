// Manejo del modal para eliminación
const deleteModal = document.getElementById('deleteModal');
deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const entityType = button.getAttribute('data-entity-type'); // Tipo de entidad (ej: "plataforma", "idioma", etc.)
    const entityName = button.getAttribute('data-entity-name'); // Nombre de la entidad
    const deleteUrl = button.getAttribute('data-delete-url');  // URL para realizar la eliminación

    // Actualizar el contenido del modal
    const entityNameElement = document.getElementById('entityName');
    const deleteLink = document.getElementById('deleteLink');

    entityNameElement.textContent = `${entityType} "${entityName}"`;
    deleteLink.href = deleteUrl;
});
