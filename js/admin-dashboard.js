(function () {
    const articleForm = document.getElementById('adminArticleForm');
    const articleList = document.getElementById('adminArticleList');
    const articleMessage = document.getElementById('adminArticleMessage');
    const articleResetButton = document.getElementById('adminArticleReset');
    const testimonialList = document.getElementById('adminTestimonialList');

    if (!articleForm || !articleList || !testimonialList) {
        return;
    }

    const fields = {
        originalSlug: document.getElementById('articleOriginalSlug'),
        csrf: document.getElementById('adminCsrfToken'),
        title: document.getElementById('articleTitleInput'),
        category: document.getElementById('articleCategoryInput'),
        topic: document.getElementById('articleTopicInput'),
        date: document.getElementById('articleDateInput'),
        readTime: document.getElementById('articleReadTimeInput'),
        image: document.getElementById('articleImageInput'),
        excerpt: document.getElementById('articleExcerptInput'),
        body: document.getElementById('articleBodyInput')
    };

    let articleCache = [];
    let testimonialCache = [];

    articleForm.addEventListener('submit', saveArticle);
    articleResetButton.addEventListener('click', resetArticleForm);
    fields.body.addEventListener('paste', handleRichArticlePaste);

    loadArticles();
    loadTestimonials();

    async function loadArticles() {
        articleList.innerHTML = loadingMarkup('Loading articles...');
        try {
            const response = await fetch('articles.php', { headers: { 'Accept': 'application/json' } });
            const payload = await response.json();
            if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load articles.');
            articleCache = Array.isArray(payload.articles) ? payload.articles : [];
            renderArticles();
        } catch (error) {
            articleList.innerHTML = emptyMarkup(error.message || 'Unable to load articles.');
        }
    }

    async function loadTestimonials() {
        testimonialList.innerHTML = loadingMarkup('Loading testimonials...');
        try {
            const response = await fetch('testimonials.php', { headers: { 'Accept': 'application/json' } });
            const payload = await response.json();
            if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to load testimonials.');
            testimonialCache = Array.isArray(payload.testimonials) ? payload.testimonials : [];
            renderTestimonials();
        } catch (error) {
            testimonialList.innerHTML = emptyMarkup(error.message || 'Unable to load testimonials.');
        }
    }

    async function saveArticle(event) {
        event.preventDefault();
        const formData = new FormData(articleForm);
        formData.set('action', 'save');
        formData.set('rawBody', fields.body.value || '');
        if (fields.csrf && fields.csrf.value) formData.set('csrf_token', fields.csrf.value);

        const submitButton = articleForm.querySelector('button[type="submit"]');
        const originalHtml = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="btn-text">Saving...</span><span class="btn-icon"><i class="fas fa-spinner fa-spin"></i></span>';

        try {
            const response = await fetch('articles.php', { method: 'POST', body: formData });
            const payload = await response.json();
            if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to save the article.');
            articleCache = Array.isArray(payload.articles) ? payload.articles : articleCache;
            renderArticles();
            resetArticleForm();
            showMessage(articleMessage, payload.message || 'Article saved successfully.', 'success');
        } catch (error) {
            showMessage(articleMessage, error.message || 'Unable to save the article.', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalHtml;
        }
    }

    function renderArticles() {
        if (articleCache.length === 0) {
            articleList.innerHTML = emptyMarkup('No articles available yet.');
            return;
        }

        articleList.innerHTML = articleCache.map(article => `
            <div class="admin-list-card">
                <div class="admin-list-card-body">
                    <span class="project-category">${escapeHtml(article.category)}</span>
                    <h4>${escapeHtml(article.title)}</h4>
                    <p>${escapeHtml(article.excerpt)}</p>
                    <div class="article-feed-meta"><span><i class="fas fa-calendar-alt me-2"></i>${escapeHtml(formatDate(article.date))}</span><span><i class="fas fa-book-open me-2"></i>${escapeHtml(article.readTime || '5 min read')}</span></div>
                </div>
                <div class="admin-list-card-actions">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-action="edit-article" data-slug="${escapeHtml(article.slug)}">Edit</button>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-action="delete-article" data-slug="${escapeHtml(article.slug)}">Delete</button>
                </div>
            </div>
        `).join('');

        articleList.querySelectorAll('[data-action="edit-article"]').forEach(button => {
            button.addEventListener('click', () => populateArticleForm(button.getAttribute('data-slug')));
        });
        articleList.querySelectorAll('[data-action="delete-article"]').forEach(button => {
            button.addEventListener('click', () => deleteArticle(button.getAttribute('data-slug')));
        });
    }

    function renderTestimonials() {
        if (testimonialCache.length === 0) {
            testimonialList.innerHTML = emptyMarkup('No testimonials available to manage.');
            return;
        }

        testimonialList.innerHTML = testimonialCache.map(testimonial => `
            <div class="admin-list-card">
                <div class="admin-list-card-body">
                    <h4>${escapeHtml(testimonial.name || 'Anonymous Client')}</h4>
                    <p class="admin-list-card-role">${escapeHtml(testimonial.role || 'Client')}</p>
                    <p>${escapeHtml(testimonial.message || '')}</p>
                </div>
                <div class="admin-list-card-actions">
                    <button type="button" class="btn btn-outline-danger btn-sm" data-action="delete-testimonial" data-id="${escapeHtml(testimonial.id)}">Delete</button>
                </div>
            </div>
        `).join('');

        testimonialList.querySelectorAll('[data-action="delete-testimonial"]').forEach(button => {
            button.addEventListener('click', () => deleteTestimonial(button.getAttribute('data-id')));
        });
    }

    function populateArticleForm(slug) {
        const article = articleCache.find(entry => entry.slug === slug);
        if (!article) return;
        fields.originalSlug.value = article.slug || '';
        fields.title.value = article.title || '';
        fields.category.value = article.category || '';
        fields.topic.value = article.topic || '';
        fields.date.value = article.date || '';
        fields.readTime.value = article.readTime || '';
        fields.image.value = article.image || '';
        fields.excerpt.value = article.excerpt || '';
        fields.body.value = getEditableArticleBody(article);
        showMessage(articleMessage, `Editing “${article.title}”. Save to update it.`, 'success');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function deleteArticle(slug) {
        if (!slug || !window.confirm('Delete this article? This action cannot be undone.')) return;
        const formData = new FormData();
        formData.set('action', 'delete');
        formData.set('slug', slug);
        if (fields.csrf && fields.csrf.value) formData.set('csrf_token', fields.csrf.value);

        try {
            const response = await fetch('articles.php', { method: 'POST', body: formData });
            const payload = await response.json();
            if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to delete article.');
            articleCache = articleCache.filter(article => article.slug !== slug);
            renderArticles();
            showMessage(articleMessage, payload.message || 'Article deleted successfully.', 'success');
            if (fields.originalSlug.value === slug) resetArticleForm();
        } catch (error) {
            showMessage(articleMessage, error.message || 'Unable to delete article.', 'error');
        }
    }

    async function deleteTestimonial(id) {
        if (!id || !window.confirm('Delete this testimonial from the website?')) return;
        const formData = new FormData();
        formData.set('action', 'delete');
        formData.set('id', id);
        if (fields.csrf && fields.csrf.value) formData.set('csrf_token', fields.csrf.value);

        try {
            const response = await fetch('testimonials.php', { method: 'POST', body: formData });
            const payload = await response.json();
            if (!response.ok || !payload.success) throw new Error(payload.message || 'Unable to delete testimonial.');
            testimonialCache = testimonialCache.filter(testimonial => testimonial.id !== id);
            renderTestimonials();
        } catch (error) {
            window.alert(error.message || 'Unable to delete testimonial.');
        }
    }

    function resetArticleForm() {
        articleForm.reset();
        fields.originalSlug.value = '';
        fields.readTime.value = '5 min read';
        articleMessage.className = 'mt-3';
        articleMessage.textContent = '';
    }

    function showMessage(element, message, type) {
        const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
        element.className = 'mt-3';
        element.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show mb-0" role="alert">
                ${escapeHtml(message)}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
    }

    function loadingMarkup(message) {
        return `<div class="testimonial-empty-state">${escapeHtml(message)}</div>`;
    }

    function emptyMarkup(message) {
        return `<div class="testimonial-empty-state">${escapeHtml(message)}</div>`;
    }

    function formatDate(value) {
        const parsed = new Date(value);
        return Number.isNaN(parsed.getTime()) ? (value || 'Undated') : parsed.toLocaleDateString('en-KE', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    function getEditableArticleBody(article) {
        const rawBody = normalizeEditorText(article.rawBody || '');
        if (rawBody !== '') {
            return rawBody;
        }

        return htmlToEditorBody(article.body || '');
    }

    function htmlToEditorBody(html) {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html || '';
        const blocks = [];

        Array.from(wrapper.childNodes).forEach(node => appendEditorBlocks(node, blocks));

        return normalizeEditorText(blocks.join('\n\n'));
    }

    function handleRichArticlePaste(event) {
        const html = event.clipboardData?.getData('text/html') || '';
        if (!html || !/<\/?[a-z][\s\S]*>/i.test(html)) {
            return;
        }

        const converted = htmlToEditorBody(html);
        if (!converted) {
            return;
        }

        event.preventDefault();
        insertTextAtCursor(fields.body, converted);
    }

    function appendEditorBlocks(node, blocks) {
        if (!node) {
            return;
        }

        if (node.nodeType === Node.TEXT_NODE) {
            const text = normalizeInlineEditorText(node.textContent || '');
            if (text) {
                blocks.push(text);
            }
            return;
        }

        if (node.nodeType !== Node.ELEMENT_NODE) {
            return;
        }

        const tag = node.tagName.toUpperCase();
        if (tag === 'H3' || tag === 'P') {
            const text = elementToEditorText(node);
            if (text) {
                blocks.push(text);
            }
            return;
        }

        if (tag === 'UL' || tag === 'OL') {
            const items = Array.from(node.children)
                .filter(child => child.tagName && child.tagName.toUpperCase() === 'LI')
                .map((item, index) => `${tag === 'OL' ? `${index + 1}.` : '•'} ${elementToEditorText(item)}`.trim())
                .filter(Boolean)
                .join('\n');
            if (items) {
                blocks.push(items);
            }
            return;
        }

        Array.from(node.childNodes).forEach(child => appendEditorBlocks(child, blocks));
    }

    function elementToEditorText(element) {
        return normalizeInlineEditorText(Array.from(element.childNodes).map(nodeToEditorText).join(''));
    }

    function nodeToEditorText(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            return node.textContent || '';
        }

        if (node.nodeType !== Node.ELEMENT_NODE) {
            return '';
        }

        const tag = node.tagName.toUpperCase();
        const content = Array.from(node.childNodes).map(nodeToEditorText).join('');
        const style = String(node.getAttribute('style') || '').toLowerCase();

        if (tag === 'BR') {
            return '\n';
        }

        if (tag === 'STRONG' || tag === 'B') {
            return `**${normalizeInlineEditorText(content)}**`;
        }

        if (tag === 'EM' || tag === 'I') {
            return `*${normalizeInlineEditorText(content)}*`;
        }

        if (/font-weight\s*:\s*(bold|[5-9]00)/.test(style)) {
            return `**${normalizeInlineEditorText(content)}**`;
        }

        if (/font-style\s*:\s*italic/.test(style)) {
            return `*${normalizeInlineEditorText(content)}*`;
        }

        if (tag === 'A') {
            const linkText = normalizeInlineEditorText(content);
            return linkText || normalizeInlineEditorText(node.getAttribute('href') || '');
        }

        return content;
    }

    function normalizeInlineEditorText(value) {
        return String(value || '')
            .replace(/\u00a0/g, ' ')
            .replace(/[\t\f\v\r ]+/g, ' ')
            .replace(/ *\n */g, '\n')
            .replace(/\n{3,}/g, '\n\n')
            .trim();
    }

    function insertTextAtCursor(textarea, text) {
        const start = typeof textarea.selectionStart === 'number' ? textarea.selectionStart : textarea.value.length;
        const end = typeof textarea.selectionEnd === 'number' ? textarea.selectionEnd : textarea.value.length;
        const prefix = start > 0 && !/\n\s*$/.test(textarea.value.slice(0, start)) ? '\n\n' : '';
        const suffix = end < textarea.value.length && !/^\s*\n/.test(textarea.value.slice(end)) ? '\n\n' : '';
        const insertion = `${prefix}${normalizeEditorText(text)}${suffix}`;
        textarea.setRangeText(insertion, start, end, 'end');
        textarea.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function normalizeEditorText(value) {
        return String(value || '')
            .replace(/\r\n?/g, '\n')
            .replace(/\n{3,}/g, '\n\n')
            .trim();
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