<?php

header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', '0');
error_reporting(E_ALL);
ob_start();

require_once __DIR__ . '/testimonials.lib.php';
require_once __DIR__ . '/admin.lib.php';

$adminConfig = loadAdminConfig();
startAdminSession($adminConfig['session_name']);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    sendTestimonialsJsonResponse(array(
        'success' => true,
        'testimonials' => getPublicTestimonials(),
    ));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendTestimonialsJsonResponse(array(
        'success' => false,
        'message' => 'Invalid request method.'
    ), 405);
}

try {
    $action = isset($_POST['action']) ? trim((string) $_POST['action']) : 'submit';

    if ($action === 'delete') {
        if (!isAdminConfigured($adminConfig)) {
            recordAdminSecurityEvent('testimonial_delete_attempt_without_admin_configuration', array('action' => $action), $adminConfig);
            sendTestimonialsJsonResponse(array('success' => false, 'message' => 'Admin access is not configured yet.'), 503);
        }

        if (!isAdminAuthenticated()) {
            recordAdminSecurityEvent('unauthenticated_testimonial_delete_attempt', array('action' => $action), $adminConfig);
            sendTestimonialsJsonResponse(array('success' => false, 'message' => 'Authentication required.'), 401);
        }

        if (!validateAdminCsrfToken($_POST['csrf_token'] ?? '')) {
            recordAdminSecurityEvent('invalid_testimonial_delete_csrf_token', array('action' => $action), $adminConfig);
            sendTestimonialsJsonResponse(array('success' => false, 'message' => 'Your session has expired. Please sign in again.'), 403);
        }

        $id = isset($_POST['id']) ? trim((string) $_POST['id']) : '';
        if ($id === '') {
            throw new RuntimeException('Testimonial id is required.');
        }

        if (!deleteTestimonialById($id)) {
            throw new RuntimeException('Testimonial could not be found.');
        }

        sendTestimonialsJsonResponse(array(
            'success' => true,
            'message' => 'Testimonial deleted successfully.'
        ));
    }

    if ($action !== 'submit') {
        throw new RuntimeException('Unsupported testimonial action.');
    }

    $honeypot = isset($_POST['website']) ? trim((string) $_POST['website']) : '';
    if ($honeypot !== '') {
        throw new RuntimeException('Spam submission detected.');
    }

    $name = isset($_POST['name']) ? trim(strip_tags((string) $_POST['name'])) : '';
    $role = isset($_POST['role']) ? trim(strip_tags((string) $_POST['role'])) : '';
    $message = isset($_POST['message']) ? trim(strip_tags((string) $_POST['message'])) : '';
    $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;

    $errors = array();

    if ($name === '') {
        $errors[] = 'Full name is required.';
    } elseif (mb_strlen($name) > 80) {
        $errors[] = 'Full name must be 80 characters or fewer.';
    }

    if ($role === '') {
        $errors[] = 'Role or company is required.';
    } elseif (mb_strlen($role) > 120) {
        $errors[] = 'Role or company must be 120 characters or fewer.';
    }

    if ($rating < 1 || $rating > 5) {
        $errors[] = 'Please choose a rating between 1 and 5.';
    }

    if ($message === '') {
        $errors[] = 'Review message is required.';
    } elseif (mb_strlen($message) < 20) {
        $errors[] = 'Review message must be at least 20 characters long.';
    } elseif (mb_strlen($message) > 600) {
        $errors[] = 'Review message must be 600 characters or fewer.';
    }

    if (!empty($errors)) {
        sendTestimonialsJsonResponse(array(
            'success' => false,
            'message' => implode(' ', $errors)
        ), 422);
    }

    addTestimonial($name, $role, $message, $rating);

    sendTestimonialsJsonResponse(array(
        'success' => true,
        'message' => 'Thank you for your review. It is now live on the website.'
    ));
} catch (Throwable $exception) {
    error_log('Testimonials handler exception: ' . $exception->getMessage());

    $statusCode = $exception instanceof RuntimeException ? 422 : 500;
    sendTestimonialsJsonResponse(array(
        'success' => false,
        'message' => $statusCode === 422
            ? $exception->getMessage()
            : 'Sorry, we could not process your review right now. Please try again later.'
    ), $statusCode);
}

function sendTestimonialsJsonResponse(array $response, int $statusCode = 200): void {
    if (ob_get_length()) {
        ob_clean();
    }

    http_response_code($statusCode);
    echo json_encode($response);
    exit;
}