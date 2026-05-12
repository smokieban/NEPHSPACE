<?php

header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', '0');
error_reporting(E_ALL);
ob_start();

require_once __DIR__ . '/articles.lib.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    sendArticlesJsonResponse(array(
        'success' => true,
        'articles' => getPublicArticles(),
    ));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendArticlesJsonResponse(array('success' => false, 'message' => 'Invalid request method.'), 405);
}

try {
    $action = isset($_POST['action']) ? trim((string) $_POST['action']) : 'save';

    if ($action === 'delete') {
        $slug = isset($_POST['slug']) ? trim((string) $_POST['slug']) : '';
        if ($slug === '') {
            throw new RuntimeException('Article slug is required for deletion.');
        }

        if (!deleteArticleBySlug($slug)) {
            throw new RuntimeException('Article could not be found.');
        }

        sendArticlesJsonResponse(array('success' => true, 'message' => 'Article deleted successfully.'));
    }

    if ($action !== 'save') {
        throw new RuntimeException('Unsupported article action.');
    }

    $article = upsertArticle($_POST);
    sendArticlesJsonResponse(array(
        'success' => true,
        'message' => 'Article saved successfully.',
        'article' => $article,
        'articles' => getPublicArticles(),
    ));
} catch (Throwable $exception) {
    error_log('Articles handler exception: ' . $exception->getMessage());
    sendArticlesJsonResponse(array(
        'success' => false,
        'message' => $exception->getMessage() ?: 'Unable to process the article right now.'
    ), 422);
}

function sendArticlesJsonResponse(array $response, int $statusCode = 200): void {
    if (ob_get_length()) {
        ob_clean();
    }

    http_response_code($statusCode);
    echo json_encode($response);
    exit;
}