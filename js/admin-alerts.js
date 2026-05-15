(function () {
    const AUTO_DISMISS_MS = 5000;
    const FADE_DURATION_MS = 200;
    const initializedAlerts = new WeakSet();

    function dismissAlert(alertElement) {
        if (!(alertElement instanceof HTMLElement)) return;
        if (alertElement.dataset.closing === 'true') return;

        alertElement.dataset.closing = 'true';
        const timeoutId = alertElement.dataset.autoDismissId;
        if (timeoutId) {
            window.clearTimeout(Number(timeoutId));
        }

        alertElement.classList.remove('show');
        window.setTimeout(() => {
            if (alertElement.parentNode) {
                alertElement.remove();
            }
        }, FADE_DURATION_MS);
    }

    function registerAlert(alertElement) {
        if (!(alertElement instanceof HTMLElement)) return;
        if (!alertElement.classList.contains('alert')) return;
        if (!alertElement.classList.contains('alert-dismissible')) return;
        if (initializedAlerts.has(alertElement)) return;

        initializedAlerts.add(alertElement);
        const timeoutId = window.setTimeout(() => dismissAlert(alertElement), AUTO_DISMISS_MS);
        alertElement.dataset.autoDismissId = String(timeoutId);
    }

    function scanAlerts(root) {
        if (!(root instanceof HTMLElement) && root !== document) return;
        const scope = root === document ? document : root;

        if (scope instanceof HTMLElement && scope.matches('.alert.alert-dismissible')) {
            registerAlert(scope);
        }

        scope.querySelectorAll('.alert.alert-dismissible').forEach(registerAlert);
    }

    function initAdminAlerts() {
        document.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof Element)) return;

            const closeButton = target.closest('.alert .btn-close');
            if (!closeButton) return;

            const alertElement = closeButton.closest('.alert');
            dismissAlert(alertElement);
        });

        scanAlerts(document);

        if (!(document.body instanceof HTMLElement)) return;
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node instanceof HTMLElement) {
                        scanAlerts(node);
                    }
                });
            });
        });

        observer.observe(document.body, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminAlerts);
    } else {
        initAdminAlerts();
    }
}());
