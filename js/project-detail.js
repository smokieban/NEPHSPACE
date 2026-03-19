(function() {
    const portfolio = Array.isArray(window.projectPortfolio) ? window.projectPortfolio : [];
    const params = new URLSearchParams(window.location.search);
    const slug = params.get('project');
    const allowedBackPages = new Set(['projects.html', 'ongoing-projects.html']);

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function setText(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    }

    const project = portfolio.find((entry) => entry.slug === slug);
    const fallbackPage = project?.page || 'projects.html';
    const requestedBackPage = params.get('from');
    const backPage = allowedBackPages.has(requestedBackPage) ? requestedBackPage : fallbackPage;
    const backLabel = backPage === 'ongoing-projects.html' ? 'Back to Ongoing Projects' : 'Back to Completed Projects';
    const breadcrumbParentLabel = backPage === 'ongoing-projects.html' ? 'Ongoing Projects' : 'Completed Projects';
    const projectContent = document.getElementById('projectContent');
    const projectNotFound = document.getElementById('projectNotFound');
    const backLink = document.getElementById('projectBackLink');
    const backLinkText = backLink?.querySelector('span');
    const breadcrumbParentLink = document.getElementById('projectBreadcrumbParentLink');
    const notFoundLink = projectNotFound?.querySelector('a');

    if (backLink) {
        backLink.href = backPage;
        backLink.setAttribute('aria-label', backLabel);
        backLink.setAttribute('title', backLabel);
        if (backLinkText) {
            backLinkText.textContent = backLabel;
        } else {
            backLink.textContent = backLabel;
        }
        backLink.addEventListener('click', function(event) {
            if (!document.referrer) {
                return;
            }

            try {
                const referrer = new URL(document.referrer);
                if (referrer.origin === window.location.origin && referrer.pathname.endsWith(backPage)) {
                    event.preventDefault();
                    window.history.back();
                }
            } catch (error) {
                // Ignore malformed referrers and fall back to the href.
            }
        });
    }

    if (breadcrumbParentLink) {
        breadcrumbParentLink.href = backPage;
    }

    setText('projectBreadcrumbParent', breadcrumbParentLabel);

    if (notFoundLink) {
        notFoundLink.href = backPage;
        notFoundLink.textContent = backLabel;
    }

    if (!project) {
        if (projectContent) {
            projectContent.hidden = true;
        }
        if (projectNotFound) {
            projectNotFound.hidden = false;
        }
        document.title = 'Project Not Found | NephSpace Elite Construction';
        return;
    }

    document.title = `${project.title} | NephSpace Elite Construction`;
    document.getElementById('pageDescriptionMeta')?.setAttribute('content', project.description);
    document.getElementById('pageCanonicalLink')?.setAttribute('href', `https://nephspace.co.ke/project-detail.html?project=${encodeURIComponent(project.slug)}`);

    setText('projectPageTitle', project.title);
    setText('projectPageLead', `${project.statusType === 'ongoing' ? 'Ongoing' : 'Completed'} project in ${project.category}`);
    setText('projectBreadcrumbCurrent', project.title);
    setText('projectCategory', project.category);
    setText('projectStatusBadge', project.statusType === 'ongoing' ? 'Ongoing Project' : 'Completed Project');
    setText('projectDetailTitle', project.title);
    setText('projectDescription', project.description);

    const metaMarkup = project.meta.map((entry) => `
        <div class="project-detail-meta-item">
            <span class="project-detail-meta-label">${escapeHtml(entry.label)}</span>
            <span class="project-detail-meta-value">${escapeHtml(entry.value)}</span>
        </div>
    `).join('');

    const galleryMarkup = project.images.slice(0, 3).map((image, index) => `
        <figure class="project-gallery-item mb-0">
            <img src="${escapeHtml(image)}" alt="${escapeHtml(project.title)} showcase image ${index + 1}" loading="lazy">
        </figure>
    `).join('');

    document.getElementById('projectMeta').innerHTML = metaMarkup;
    document.getElementById('projectGallery').innerHTML = galleryMarkup;
}());