(async function () {
    const articles = await loadArticles();
    const params = new URLSearchParams(window.location.search);
    const slug = params.get('article');
    const article = articles.find(entry => entry.slug === slug);
    const content = document.getElementById('articleContent');
    const notFound = document.getElementById('articleNotFound');
    const recentLinks = document.getElementById('articleDetailRecentLinks');
    function formatDate(value) { return new Date(value).toLocaleDateString('en-KE', { year: 'numeric', month: 'long', day: 'numeric' }); }
    if (recentLinks) recentLinks.innerHTML = articles.slice(0, 5).map(entry => `<a href="article-detail.html?article=${encodeURIComponent(entry.slug)}" class="article-recent-link"><span class="article-recent-link-title">${escapeHtml(entry.title)}</span><span class="article-recent-link-date">${escapeHtml(formatDate(entry.date))}</span></a>`).join('');
    if (!article) { if (content) content.hidden = true; if (notFound) notFound.hidden = false; return; }
    document.title = `${article.title} | NephSpace Elite Construction`;
    document.getElementById('articleDescriptionMeta')?.setAttribute('content', article.excerpt);
    document.getElementById('articleCanonicalLink')?.setAttribute('href', `https://nephspaceelite.com/article-detail.html?article=${encodeURIComponent(article.slug)}`);
    document.querySelector('meta[property="og:title"]')?.setAttribute('content', `${article.title} | NephSpace Elite Construction`);
    document.querySelector('meta[property="og:description"]')?.setAttribute('content', article.excerpt);
    document.querySelector('meta[property="og:url"]')?.setAttribute('content', `https://nephspaceelite.com/article-detail.html?article=${encodeURIComponent(article.slug)}`);
    document.getElementById('articleOgImage')?.setAttribute('content', `https://nephspaceelite.com/${article.image}`);
    document.querySelector('meta[name="twitter:title"]')?.setAttribute('content', `${article.title} | NephSpace Elite Construction`);
    document.querySelector('meta[name="twitter:description"]')?.setAttribute('content', article.excerpt);
    document.getElementById('articlePageTitle').textContent = article.title;
    document.getElementById('articlePageLead').textContent = `${article.category} insight from the NephSpace article library.`;
    document.getElementById('articleBreadcrumbCurrent').textContent = article.title;
    document.getElementById('articleHeroImage').src = article.image;
    document.getElementById('articleHeroImage').alt = article.title;
    document.getElementById('articleCategory').textContent = article.category;
    document.getElementById('articleDate').textContent = formatDate(article.date);
    document.getElementById('articleReadTime').textContent = article.readTime;
    document.getElementById('articleTitle').textContent = article.title;
    document.getElementById('articleExcerpt').textContent = article.excerpt;
    document.getElementById('articleBody').innerHTML = renderArticleBody(article.body);

    if (typeof AOS !== 'undefined') {
        AOS.refreshHard();
    }

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

    function renderArticleBody(bodyHtml) {
        return `<div class="article-rich-body">${bodyHtml || ''}</div>`;
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