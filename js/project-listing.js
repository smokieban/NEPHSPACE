(function() {
    const portfolio = Array.isArray(window.projectPortfolio) ? window.projectPortfolio : [];
    const currentPage = window.location.pathname.split('/').pop() || 'projects.html';
    const projectsGrid = document.getElementById('projectsGrid');

    if (!projectsGrid || !portfolio.length) {
        return;
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function buildProjectCard(project, index) {
        const detailUrl = `project-detail.html?project=${encodeURIComponent(project.slug)}&from=${encodeURIComponent(project.page)}`;
        const coverImage = Array.isArray(project.images) && project.images.length ? project.images[0] : '';
        const delay = (index % 3) * 100;

        return `
            <div class="col-lg-4 col-md-6 project-item" data-category="${escapeHtml(project.filter)}" data-aos="fade-up"${delay ? ` data-aos-delay="${delay}"` : ''}>
                <div class="project-card project-card--compact">
                    <div class="project-image project-image--compact">
                        <img src="${escapeHtml(coverImage)}" alt="${escapeHtml(project.title)}">
                    </div>
                    <div class="project-details project-details--compact">
                        <span class="project-category">${escapeHtml(project.category)}</span>
                        <h5>${escapeHtml(project.title)}</h5>
                        <a href="${detailUrl}" class="btn btn-outline-primary btn-sm mt-auto" aria-label="View more details about ${escapeHtml(project.title)}">View More</a>
                    </div>
                </div>
            </div>
        `;
    }

    const pageProjects = portfolio.filter((entry) => entry.page === currentPage);

    if (!pageProjects.length) {
        return;
    }

    projectsGrid.innerHTML = pageProjects.map((project, index) => buildProjectCard(project, index)).join('');

    if (window.AOS && typeof window.AOS.refreshHard === 'function') {
        window.AOS.refreshHard();
    } else if (window.AOS && typeof window.AOS.refresh === 'function') {
        window.AOS.refresh();
    }
}());