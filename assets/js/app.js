document.querySelectorAll('[data-confirm]').forEach((element) => {
    element.addEventListener('click', (event) => {
        if (!confirm(element.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

// Highlight the active sidebar navigation item based on path
document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname.toLowerCase();
    document.querySelectorAll('.app-sidebar a').forEach((link) => {
        try {
            const linkPath = new URL(link.href).pathname.toLowerCase();
            // Check if current path matches, or if link matches a prefix folder path
            if (currentPath === linkPath || (linkPath !== '/' && currentPath.includes(linkPath))) {
                link.classList.add('active');
            }
        } catch (e) {
            // Ignore invalid URLs
        }
    });
});
