<?php

function getArticlesFilePath(): string {
    return __DIR__ . '/articles.json';
}

function getDefaultArticles(): array {
    $body = <<<'HTML'
<h3>Introduction</h3>
<p>In construction and engineering projects, failure rarely happens on-site—it happens in the planning stage.</p>
<p>Across Kenya and globally, many projects struggle with:</p>
<ul>
    <li>cost overruns</li>
    <li>delayed completion</li>
    <li>design rework</li>
    <li>procurement inefficiencies</li>
    <li>operational failures after handover</li>
</ul>
<p>The root cause is almost always the same: insufficient early-stage project definition and feasibility planning.</p>
<p>This is why Pre-FEED (Pre-Front End Engineering Design) is one of the most critical stages in modern construction project delivery.</p>
<p>At Nephspace Elite Construction and Interiors Hub Ltd, we specialize in structured early-stage project planning that ensures projects are technically viable, financially realistic, and strategically aligned before execution begins.</p>
<h3>What Is Pre-Feed In Construction?</h3>
<p>Pre-FEED (Pre-Front End Engineering Design) is the structured early-stage planning phase that evaluates a project before detailed engineering design begins.</p>
<p>It determines:</p>
<ul>
    <li>Is the project technically feasible?</li>
    <li>Is it financially viable?</li>
    <li>Is it operationally practical?</li>
    <li>Does it align with investor and client objectives?</li>
</ul>
<p><strong>In simple terms:</strong> Pre-FEED prevents expensive mistakes before they happen.</p>
<p>It connects: Concept Development → Feasibility → Detailed Engineering (FEED)</p>
<p>Without Pre-FEED, projects move forward based on assumptions rather than validated data.</p>
<h3>Why Pre-Feed Is Critical In Modern Construction Projects (Kenya Context)</h3>
<p>In Kenya’s growing construction and infrastructure sector, projects are becoming more complex due to:</p>
<ul>
    <li>rising material costs</li>
    <li>land and regulatory constraints</li>
    <li>tighter financing conditions</li>
    <li>increased sustainability requirements</li>
</ul>
<p>Without proper early-stage planning, projects often experience:</p>
<ul>
    <li>20%–50% cost overruns</li>
    <li>delayed approvals and permits</li>
    <li>redesign cycles during construction</li>
    <li>contractor disputes</li>
    <li>poor lifecycle performance</li>
</ul>
<p>Pre-FEED reduces uncertainty before capital is committed.</p>
<h3>Key Objectives Of Pre-Feed</h3>
<h3>1. Project Scope Definition (Prevent Scope Creep)</h3>
<p>Pre-FEED ensures that all stakeholders agree on:</p>
<ul>
    <li>project goals</li>
    <li>functional requirements</li>
    <li>technical specifications</li>
    <li>budget boundaries</li>
    <li>performance expectations</li>
</ul>
<p>This prevents uncontrolled changes during execution (scope creep), which is one of the biggest cost drivers in construction.</p>
<h3>2. Technical Feasibility Assessment</h3>
<p>Pre-FEED evaluates whether the project can realistically be built based on:</p>
<ul>
    <li>site conditions and geology</li>
    <li>utilities and infrastructure availability</li>
    <li>engineering constraints</li>
    <li>environmental and regulatory compliance</li>
    <li>logistics and access limitations</li>
</ul>
<p>This reduces the risk of designing impossible projects.</p>
<h3>3. Cost Planning &amp; Early Budget Accuracy</h3>
<p>One of the biggest benefits of Pre-FEED is early financial clarity.</p>
<p>It provides preliminary cost estimates based on:</p>
<ul>
    <li>material specifications</li>
    <li>labor and construction methods</li>
    <li>procurement strategy</li>
    <li>project timeline assumptions</li>
</ul>
<p>Investors gain a realistic financial roadmap before committing funds.</p>
<h3>4. Risk Identification &amp; Mitigation Planning</h3>
<p>Pre-FEED identifies risks such as:</p>
<ul>
    <li>design complexity risks</li>
    <li>procurement delays</li>
    <li>regulatory approvals</li>
    <li>inflation and price volatility</li>
    <li>site constraints</li>
</ul>
<p>This allows risk mitigation strategies to be built into the project from the beginning.</p>
<h3>5. Investment Decision Support</h3>
<p>Pre-FEED gives stakeholders clarity to decide:</p>
<ul>
    <li>proceed with the project</li>
    <li>revise scope or design</li>
    <li>pause or restructure investment</li>
    <li>reassess financial viability</li>
</ul>
<p>This prevents sunk-cost investment failures.</p>
<h3>Key Deliverables Of A Pre-Feed Study</h3>
<p>A professional Pre-FEED process typically produces:</p>
<ul>
    <li>feasibility study report</li>
    <li>concept design options</li>
    <li>preliminary cost estimates (CAPEX/OPEX)</li>
    <li>risk assessment matrix</li>
    <li>procurement strategy outline</li>
    <li>project execution roadmap</li>
    <li>timeline projection</li>
</ul>
<h3>What Happens When Pre-Feed Is Skipped?</h3>
<p>Projects that bypass Pre-FEED often experience:</p>
<ul>
    <li>unrealistic budgets</li>
    <li>major redesign during construction</li>
    <li>contractor disputes</li>
    <li>procurement inefficiencies</li>
    <li>delayed delivery timelines</li>
    <li>poor operational performance after completion</li>
</ul>
<p>In most cases, fixing these issues during construction costs 3–10 times more than fixing them at the planning stage.</p>
<h3>Pre-Feed And Project Success</h3>
<p>Pre-FEED is not just a technical step, it is a financial risk control mechanism.</p>
<p>It improves:</p>
<ul>
    <li>cost certainty</li>
    <li>construction efficiency</li>
    <li>design coordination</li>
    <li>procurement planning</li>
    <li>long-term asset performance</li>
</ul>
<h3>Why Professional Expertise Matters</h3>
<p>Effective Pre-FEED requires:</p>
<ul>
    <li>engineering knowledge</li>
    <li>construction experience</li>
    <li>cost estimation accuracy</li>
    <li>procurement strategy understanding</li>
    <li>risk analysis capability</li>
</ul>
<p>At this stage, poor decisions are not just technical mistakes, they become multi-million shilling project risks.</p>
<h3>Pre-Feed In Kenya’s Construction Industry</h3>
<p>With rapid urbanization in cities like Nairobi, Mombasa, and Kisumu, developers are increasingly adopting structured early-stage planning to:</p>
<ul>
    <li>reduce project failure rates</li>
    <li>improve investor confidence</li>
    <li>optimize capital deployment</li>
    <li>enhance sustainability outcomes</li>
</ul>
<p>Pre-FEED is becoming a standard requirement in serious development projects.</p>
<h3>Why Work With Nephspace Elite Construction And Interiors Hub Ltd</h3>
<p>We provide structured Pre-FEED and early-stage project planning services including:</p>
<ul>
    <li>feasibility studies</li>
    <li>concept development</li>
    <li>cost engineering</li>
    <li>procurement planning</li>
    <li>project risk analysis</li>
    <li>construction strategy development</li>
</ul>
<p>Our focus is to ensure:</p>
<ul>
    <li>reduced project risk</li>
    <li>improved cost efficiency</li>
    <li>predictable project delivery</li>
    <li>long-term asset value creation</li>
</ul>
<p><strong>Contact:</strong> <a href="mailto:info@nephspaceelite.com">info@nephspaceelite.com</a></p>
HTML;

    return array(array(
        'slug' => 'understanding-the-critical-role-of-early-stage-project-planning-in-construction',
        'topic' => 'pre-feed',
        'category' => 'Pre-FEED',
        'title' => 'Understanding The Critical Role Of Early-Stage Project Planning In Construction: What Is Pre-Feed And Why It Determines Project Success',
        'date' => '2026-05-11',
        'readTime' => '8 min read',
        'image' => 'assets/img/images/articles/Pre-feed.jpg',
        'excerpt' => 'In construction and engineering projects, failure rarely happens on-site. It usually begins in the planning stage. This article explains how Pre-FEED reduces cost overruns, delayed completion, design rework, procurement inefficiencies, and operational failure before execution begins.',
        'body' => $body,
        'created_at' => gmdate('c'),
        'updated_at' => gmdate('c'),
    ));
}

function readArticles(): array {
    $path = getArticlesFilePath();
    if (!file_exists($path)) {
        $defaults = getDefaultArticles();
        saveArticles($defaults);
        return $defaults;
    }

    $raw = (string) file_get_contents($path);
    $decoded = json_decode($raw, true);
    if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
        $defaults = getDefaultArticles();
        saveArticles($defaults);
        return $defaults;
    }

    if (isset($decoded['articles']) && is_array($decoded['articles'])) {
        $items = $decoded['articles'];
    } elseif (is_array($decoded)) {
        $items = $decoded;
    } else {
        $items = array();
    }

    $items = array_values(array_filter($items, 'is_array'));
    usort($items, function ($left, $right) {
        return strcmp((string) ($right['date'] ?? $right['updated_at'] ?? ''), (string) ($left['date'] ?? $left['updated_at'] ?? ''));
    });
    return $items;
}

function saveArticles(array $articles): void {
    $payload = json_encode(array('articles' => array_values($articles)), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($payload === false) {
        throw new RuntimeException('Unable to encode articles.');
    }

    if (file_put_contents(getArticlesFilePath(), $payload . PHP_EOL, LOCK_EX) === false) {
        throw new RuntimeException('Unable to save articles.');
    }
}

function getPublicArticles(): array {
    return array_map(function ($article) {
        return array(
            'slug' => (string) ($article['slug'] ?? ''),
            'topic' => (string) ($article['topic'] ?? ''),
            'category' => (string) ($article['category'] ?? ''),
            'title' => (string) ($article['title'] ?? ''),
            'date' => (string) ($article['date'] ?? ''),
            'readTime' => (string) ($article['readTime'] ?? ''),
            'image' => (string) ($article['image'] ?? ''),
            'excerpt' => (string) ($article['excerpt'] ?? ''),
            'body' => (string) ($article['body'] ?? ''),
            'rawBody' => (string) ($article['rawBody'] ?? ''),
            'created_at' => (string) ($article['created_at'] ?? ''),
            'updated_at' => (string) ($article['updated_at'] ?? ''),
        );
    }, readArticles());
}

function upsertArticle(array $input): array {
    $articles = readArticles();
    $title = trim((string) ($input['title'] ?? ''));
    $category = trim((string) ($input['category'] ?? ''));
    $topic = trim((string) ($input['topic'] ?? ''));
    $date = trim((string) ($input['date'] ?? ''));
    $readTime = trim((string) ($input['readTime'] ?? ''));
    $image = trim((string) ($input['image'] ?? ''));
    $excerpt = trim((string) ($input['excerpt'] ?? ''));
    $rawBody = normalizeArticleSourceBody((string) ($input['rawBody'] ?? $input['body'] ?? ''), $title);
    $body = normalizeArticleBody($rawBody, $title);
    $requestedSlug = trim((string) ($input['slug'] ?? ''));
    $originalSlug = trim((string) ($input['original_slug'] ?? ''));

    if ($title === '' || $category === '' || $date === '' || $body === '') {
        throw new RuntimeException('Title, category, date, and article content are required.');
    }

    $slug = makeUniqueArticleSlug($requestedSlug !== '' ? $requestedSlug : $title, $articles, $originalSlug);
    $timestamp = gmdate('c');
    $article = array(
        'slug' => $slug,
        'topic' => $topic !== '' ? slugify($topic) : slugify($category),
        'category' => $category,
        'title' => $title,
        'date' => $date,
        'readTime' => $readTime !== '' ? $readTime : estimateArticleReadTime($body),
        'image' => $image !== '' ? $image : 'assets/img/images/articles/Pre-feed.jpg',
        'excerpt' => $excerpt !== '' ? $excerpt : generateArticleExcerpt($body),
        'body' => $body,
        'rawBody' => $rawBody,
        'updated_at' => $timestamp,
    );

    $updated = false;
    foreach ($articles as &$existing) {
        if ((string) ($existing['slug'] ?? '') === $originalSlug && $originalSlug !== '') {
            $article['created_at'] = (string) ($existing['created_at'] ?? $timestamp);
            $existing = array_merge($existing, $article);
            $updated = true;
            break;
        }
    }
    unset($existing);

    if (!$updated) {
        $article['created_at'] = $timestamp;
        array_unshift($articles, $article);
    }

    usort($articles, function ($left, $right) {
        return strcmp((string) ($right['date'] ?? $right['updated_at'] ?? ''), (string) ($left['date'] ?? $left['updated_at'] ?? ''));
    });

    saveArticles($articles);
    return $article;
}

function deleteArticleBySlug(string $slug): bool {
    $articles = readArticles();
    $remaining = array_values(array_filter($articles, function ($article) use ($slug) {
        return (string) ($article['slug'] ?? '') !== (string) $slug;
    }));

    if (count($remaining) === count($articles)) {
        return false;
    }

    saveArticles($remaining);
    return true;
}

function makeUniqueArticleSlug(string $value, array $articles, string $currentSlug = ''): string {
    $base = slugify($value);
    $candidate = $base;
    $counter = 2;

    while (articleSlugExists($candidate, $articles, $currentSlug)) {
        $candidate = $base . '-' . $counter;
        $counter++;
    }

    return $candidate;
}

function articleSlugExists(string $slug, array $articles, string $currentSlug = ''): bool {
    foreach ($articles as $article) {
        $existingSlug = (string) ($article['slug'] ?? '');
        if ($existingSlug === $slug && $existingSlug !== $currentSlug) {
            return true;
        }
    }
    return false;
}

function slugify(string $value): string {
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    $value = trim((string) $value, '-');
    return $value !== '' ? $value : 'article';
}

function normalizeArticleSourceBody(string $body, string $articleTitle = ''): string {
    $body = trim($body);
    if ($body === '') {
        return '';
    }

    $body = preg_replace("/\r\n?|\n/", "\n", $body);
    $lines = preg_split('/\n/', (string) $body);
    $normalizedLines = array();
    $titleSlug = slugify($articleTitle);
    $titleRemoved = false;

    foreach ($lines as $line) {
        $cleanLine = rtrim((string) $line);
        if (!$titleRemoved && trim($cleanLine) !== '' && $titleSlug !== '' && slugify(trim($cleanLine)) === $titleSlug) {
            $titleRemoved = true;
            continue;
        }

        $normalizedLines[] = $cleanLine;
    }

    return trim(implode("\n", $normalizedLines));
}

function normalizeArticleBody(string $body, string $articleTitle = ''): string {
    $body = normalizeArticleSourceBody($body, $articleTitle);
    if ($body === '') {
        return '';
    }

    if (preg_match('/<\/?[a-z][\s\S]*>/i', $body)) {
        $body = preg_replace('/<(\/?)(?:b)(?=[\s>])/i', '<$1strong', $body);
        $body = preg_replace('/<(\/?)(?:i)(?=[\s>])/i', '<$1em', $body);
        return trim(strip_tags($body, '<p><br><h3><ul><ol><li><strong><em><a>'));
    }

    $lines = preg_split('/\R/', $body);
    $html = array();
    $paragraph = array();
    $listItems = array();
    $listTag = 'ul';

    $flushParagraph = function () use (&$paragraph, &$html) {
        if (empty($paragraph)) {
            return;
        }

        $text = trim(implode(' ', $paragraph));
        if ($text === '') {
            $paragraph = array();
            return;
        }

        if (preg_match('/^Contact\s*:\s*(.+)$/i', $text, $matches)) {
            $html[] = '<p><strong>Contact:</strong> ' . renderArticleInlineText(trim($matches[1])) . '</p>';
        } else {
            $html[] = '<p>' . renderArticleInlineText($text) . '</p>';
        }

        $paragraph = array();
    };

    $flushList = function () use (&$listItems, &$html, &$listTag) {
        if (empty($listItems)) {
            return;
        }

        $items = array_map(function ($item) {
            return '<li>' . renderArticleInlineText($item) . '</li>';
        }, $listItems);

        $html[] = '<' . $listTag . '>' . implode('', $items) . '</' . $listTag . '>';
        $listItems = array();
        $listTag = 'ul';
    };

    $totalLines = count($lines);
    for ($index = 0; $index < $totalLines; $index++) {
        $rawLine = $lines[$index];
        $line = trim((string) $rawLine);
        if ($line === '') {
            $flushParagraph();
            $flushList();
            continue;
        }

        if (preg_match('/^(?:##\s+)(.+)$/', $line, $matches)) {
            $flushParagraph();
            $flushList();
            $html[] = '<h3>' . renderArticleInlineText(trim($matches[1])) . '</h3>';
            continue;
        }

        if (isLikelyArticleHeading($line)) {
            $flushParagraph();
            $flushList();
            $html[] = '<h3>' . renderArticleInlineText($line) . '</h3>';
            continue;
        }

        if (preg_match('/^(?:[-*•])\s*(.+)$/u', $line, $matches)) {
            $flushParagraph();
            $listTag = 'ul';
            $listItems[] = trim($matches[1]);
            continue;
        }

        if (preg_match('/^([0-9]+)[\.)]\s+(.+)$/', $line, $matches) && !isLikelyArticleHeading($line)) {
            $flushParagraph();
            $listTag = 'ol';
            $listItems[] = trim($matches[2]);
            continue;
        }

        $flushList();
        $paragraph[] = $line;
    }

    $flushParagraph();
    $flushList();
    return implode('', $html);
}

function isLikelyArticleHeading(string $line): bool {
    $line = trim($line);
    if ($line === '') {
        return false;
    }

    if (preg_match('/^(?:[-*•])\s+/', $line)) {
        return false;
    }

    if (preg_match('/[:;,.!]$/u', $line)) {
        return false;
    }

    if (mb_strlen($line) > 140) {
        return false;
    }

    if (preg_match('/^[0-9]+[\.)]\s+.+$/', $line)) {
        return true;
    }

    if (preg_match('/^(Introduction|Overview|Conclusion|Summary|Recommendations|Next Steps)$/i', $line)) {
        return true;
    }

    $words = preg_split('/\s+/', $line);
    $validWords = 0;
    $titleLikeWords = 0;

    foreach ($words as $word) {
        $cleanWord = trim((string) $word, " \t\n\r\0\x0B\"'“”‘’,;()[]{}");
        if ($cleanWord === '') {
            continue;
        }

        $validWords++;
        if (preg_match('/^(and|or|of|the|in|on|for|to|a|an|with|by|at|is|as|from|what|why)$/i', $cleanWord)) {
            $titleLikeWords++;
            continue;
        }

        if (preg_match('/^[A-Z0-9]/', $cleanWord)) {
            $titleLikeWords++;
        }
    }

    if ($validWords === 0 || $validWords > 18) {
        return false;
    }

    return ($titleLikeWords / $validWords) >= 0.6;
}

function renderArticleInlineText(string $text): string {
    $text = htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $text);
    $text = preg_replace('/__(.+?)__/u', '<strong>$1</strong>', $text);
    $text = preg_replace('/(^|\s)\*(?!\s)([^*]+?)\*(?=\s|$)/u', '$1<em>$2</em>', $text);
    $text = preg_replace(
        '/([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,})/iu',
        '<a href="mailto:$1">$1</a>',
        $text
    );
    $text = preg_replace(
        '/((?:https?:\/\/|www\.)[^\s<]+)/iu',
        '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
        $text
    );
    return $text;
}

function generateArticleExcerpt(string $bodyHtml): string {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($bodyHtml)));
    if ($text === '') {
        return '';
    }

    if (mb_strlen($text) <= 220) {
        return $text;
    }

    return rtrim(mb_substr($text, 0, 217)) . '...';
}

function estimateArticleReadTime(string $bodyHtml): string {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($bodyHtml)));
    $wordCount = str_word_count($text);
    $minutes = max(1, (int) ceil($wordCount / 220));
    return $minutes . ' min read';
}