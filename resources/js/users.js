import '../css/users.css';

const userModal = document.querySelector('[data-user-modal]');
const openModalButton = document.querySelector('[data-open-user-modal]');

if (userModal instanceof HTMLDialogElement) {
    openModalButton?.addEventListener('click', () => userModal.showModal());

    userModal.querySelectorAll('[data-close-user-modal]').forEach((button) => {
        button.addEventListener('click', () => userModal.close());
    });

    userModal.addEventListener('click', (event) => {
        if (event.target === userModal) {
            userModal.close();
        }
    });

    if (userModal.dataset.openOnLoad === 'true') {
        userModal.showModal();
    }
}

document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        const userName = form.dataset.userName || 'this user';

        if (! window.confirm(`Delete ${userName} and revoke their connected OAuth sessions?`)) {
            event.preventDefault();
        }
    });
});
