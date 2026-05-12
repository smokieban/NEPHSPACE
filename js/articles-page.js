(async function () {
    const listing = document.getElementById('articlesListing');
    if (!listing) return;

    const articles = await loadArticles();
    listing.innerHTML = articles.map((article, index) => `
        <div class="col-lg-4 col-md-6" data-aos="fade-up"${index > 0 ? ` data-aos-delay="${Math.min((index % 3) * 100, 200)}"` : ''}>
            <article class="project-card article-list-card h-100">
                <div class="project-image article-list-image"><img src="${escapeHtml(article.image)}" alt="${escapeHtml(article.title)}" class="img-fluid"></div>
                <div class="project-info article-list-content">
                    <span class="project-category">${escapeHtml(article.category)}</span>
                    <div class="article-feed-meta"><span><i class="fas fa-calendar-alt me-2"></i>${escapeHtml(formatDate(article.date))}</span><span><i class="fas fa-book-open me-2"></i>${escapeHtml(article.readTime || '5 min read')}</span></div>
                    <h3>${escapeHtml(article.title)}</h3>
                    <p>${escapeHtml(article.excerpt)}</p>
                    <a href="article-detail.html?article=${encodeURIComponent(article.slug)}" class="btn btn-outline-primary">Read More</a>
                </div>
            </article>
        </div>`).join('') || `<div class="col-12"><div class="project-detail-panel"><h3 class="mb-3">No articles available.</h3><p class="mb-0">Use the admin page to publish your first article.</p></div></div>`;

    if (typeof AOS !== 'undefined') AOS.refreshHard();

    async function loadArticles() {
        try {
            const response = await fetch('articles.php', { headers: { 'Accept': 'application/json' } });
            const payload = await response.json();
            if (!response.ok || !payload.success || !Array.isArray(payload.articles)) {
                throw new Error(payload.message || 'Unable to load articles.');
            }
            return payload.articles;
        } catch (error) {
            return Array.isArray(window.articleLibrary) ? window.articleLibrary : [];
        }
    }

    function formatDate(value) {
        const parsed = new Date(value);
        return Number.isNaN(parsed.getTime()) ? value : parsed.toLocaleDateString('en-KE', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
}());