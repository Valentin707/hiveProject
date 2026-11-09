import '../css/applications.css';

document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        const applicationName = form.dataset.applicationName || 'this application';

        if (! window.confirm(`Delete ${applicationName} from the registry?`)) {
            event.preventDefault();
        }
    });
});
