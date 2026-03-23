<?php

function loadTestimonialsConfig() {
    $fileConfig = array();
    $configPath = __DIR__ . '/testimonials.config.php';

    if (file_exists($configPath)) {
        $loadedConfig = require $configPath;
        if (is_array($loadedConfig)) {
            $fileConfig = $loadedConfig;
        }
    }

    return array(
        'admin_password' => getTestimonialsConfigValue($fileConfig, 'admin_password', 'TESTIMONIAL_ADMIN_PASSWORD', ''),
        'session_name' => getTestimonialsConfigValue($fileConfig, 'session_name', 'TESTIMONIAL_SESSION_NAME', 'nephspace_testimonial_admin'),
    );
}

function getTestimonialsConfigValue($fileConfig, $key, $envKey, $default) {
    if (array_key_exists($key, $fileConfig) && $fileConfig[$key] !== '') {
        return $fileConfig[$key];
    }

    $envValue = getenv($envKey);
    if ($envValue !== false && $envValue !== '') {
        return $envValue;
    }

    return $default;
}

function getTestimonialsFilePath() {
    return __DIR__ . '/testimonials.json';
}

function readTestimonials() {
    $path = getTestimonialsFilePath();
    if (!file_exists($path)) {
        return array();
    }

    $decoded = json_decode((string) file_get_contents($path), true);
    if (isset($decoded['testimonials']) && is_array($decoded['testimonials'])) {
        $items = $decoded['testimonials'];
    } elseif (is_array($decoded)) {
        $items = $decoded;
    } else {
        $items = array();
    }

    $items = array_values(array_filter($items, 'is_array'));
    usort($items, function($left, $right) {
        return strcmp((string) ($right['created_at'] ?? ''), (string) ($left['created_at'] ?? ''));
    });

    return $items;
}

function saveTestimonials(array $testimonials) {
    $payload = json_encode(array('testimonials' => array_values($testimonials)), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($payload === false) {
        throw new RuntimeException('Unable to encode testimonials.');
    }

    if (file_put_contents(getTestimonialsFilePath(), $payload . PHP_EOL, LOCK_EX) === false) {
        throw new RuntimeException('Unable to save testimonials.');
    }
}

function addTestimonial($name, $role, $message, $rating) {
    $testimonials = readTestimonials();
    $testimonial = array(
        'id' => bin2hex(random_bytes(6)),
        'name' => $name,
        'role' => $role,
        'message' => $message,
        'rating' => (int) $rating,
        'created_at' => gmdate('c'),
    );

    array_unshift($testimonials, $testimonial);
    saveTestimonials($testimonials);

    return $testimonial;
}

function deleteTestimonialById($id) {
    $testimonials = readTestimonials();
    $remaining = array_values(array_filter($testimonials, function($testimonial) use ($id) {
        return (string) ($testimonial['id'] ?? '') !== (string) $id;
    }));

    if (count($remaining) === count($testimonials)) {
        return false;
    }

    saveTestimonials($remaining);
    return true;
}

function getPublicTestimonials($limit = 8) {
    $items = array_slice(readTestimonials(), 0, $limit);
    return array_map(function($testimonial) {
        return array(
            'id' => (string) ($testimonial['id'] ?? ''),
            'name' => (string) ($testimonial['name'] ?? ''),
            'role' => (string) ($testimonial['role'] ?? ''),
            'message' => (string) ($testimonial['message'] ?? ''),
            'rating' => (int) ($testimonial['rating'] ?? 5),
            'created_at' => (string) ($testimonial['created_at'] ?? ''),
        );
    }, $items);
}

function startTestimonialsSession($sessionName) {
    if (session_status() === PHP_SESSION_NONE) {
        session_name($sessionName);
        session_start();
    }
}

function isTestimonialsAdminConfigured($config) {
    return !empty($config['admin_password']) && $config['admin_password'] !== 'change-this-password';
}

function isTestimonialsAdminAuthenticated() {
    return !empty($_SESSION['testimonial_admin_authenticated']);
}

function ensureTestimonialsCsrfToken() {
    if (empty($_SESSION['testimonial_admin_csrf'])) {
        $_SESSION['testimonial_admin_csrf'] = bin2hex(random_bytes(16));
    }

    return $_SESSION['testimonial_admin_csrf'];
}

function validateTestimonialsCsrfToken($token) {
    return !empty($_SESSION['testimonial_admin_csrf'])
        && is_string($token)
        && hash_equals($_SESSION['testimonial_admin_csrf'], $token);
}