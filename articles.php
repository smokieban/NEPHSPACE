<?php

header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', '0');
error_reporting(E_ALL);
ob_start();

require_once __DIR__ . '/articles.lib.php';
require_once __DIR__ . '/admin.lib.php';

$adminConfig = loadAdminConfig();
startAdminSession($adminConfig['session_name']);

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

    if (!isAdminConfigured($adminConfig)) {
        recordAdminSecurityEvent('article_write_attempt_without_admin_configuration', array('action' => $action), $adminConfig);
        sendArticlesJsonResponse(array('success' => false, 'message' => 'Admin access is not configured yet.'), 503);
    }

    if (!isAdminAuthenticated()) {
        recordAdminSecurityEvent('unauthenticated_article_write_attempt', array('action' => $action), $adminConfig);
        sendArticlesJsonResponse(array('success' => false, 'message' => 'Authentication required.'), 401);
    }

    if (!validateAdminCsrfToken($_POST['csrf_token'] ?? '')) {
        recordAdminSecurityEvent('invalid_article_write_csrf_token', array('action' => $action), $adminConfig);
        sendArticlesJsonResponse(array('success' => false, 'message' => 'Your session has expired. Please sign in again.'), 403);
    }

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