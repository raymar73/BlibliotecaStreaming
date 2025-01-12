function showSuccessModal(message, redirectUrl = null) {
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const successMessageElement = document.getElementById('successMessageElement');
    successMessageElement.textContent = message;
    successModal.show();

    if (redirectUrl) {
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 3000); // Redirigir después de 3 segundos
    }
}

function showErrorModal(message) {
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const errorMessageElement = document.getElementById('errorMessageElement');
    errorMessageElement.textContent = message;
    errorModal.show();
}

// Asegurarse de que estas funciones estén disponibles en el ámbito global
window.showSuccessModal = showSuccessModal;
window.showErrorModal = showErrorModal;
