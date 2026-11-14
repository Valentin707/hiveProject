import '../css/applications.css';

document.querySelectorAll('[data-open-application-modal]').forEach((button) => {
    button.addEventListener('click', () => {
        const modal = document.getElementById(button.dataset.openApplicationModal);

        if (modal instanceof HTMLDialogElement) {
            modal.showModal();
            modal.querySelector('input:not([type="hidden"]), select')?.focus();
        }
    });
});

document.querySelectorAll('[data-close-application-modal]').forEach((button) => {
    button.addEventListener('click', () => {
        button.closest('dialog')?.close();
    });
});

document.querySelectorAll('[data-application-modal][data-open-on-load="true"]').forEach((modal) => {
    if (modal instanceof HTMLDialogElement) {
        modal.showModal();
        modal.querySelector('input:not([type="hidden"]), select')?.focus();
    }
});

document.querySelectorAll('[data-application-modal]').forEach((modal) => {
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.close();
        }
    });
});

document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        const applicationName = form.dataset.applicationName || 'this application';

        if (! window.confirm(`Delete ${applicationName} from the registry?`)) {
            event.preventDefault();
        }
    });
});
