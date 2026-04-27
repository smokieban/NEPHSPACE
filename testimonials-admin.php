<?php

require_once __DIR__ . '/testimonials.lib.php';

$config = loadTestimonialsConfig();
startTestimonialsSession($config['session_name']);

header('X-Robots-Tag: noindex, nofollow', true);

$errorMessage = '';
$successMessage = isset($_GET['logged_in'])
    ? 'Signed in successfully.'
    : (isset($_GET['logged_out']) ? 'You have been signed out.' : '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateTestimonialsCsrfToken($_POST['csrf_token'] ?? '')) {
        $errorMessage = 'Your session has expired. Please refresh the page and try again.';
    } else {
        $action = trim((string) ($_POST['action'] ?? ''));

        if ($action === 'login') {
            if (!isTestimonialsAdminConfigured($config)) {
                $errorMessage = 'Configure TESTIMONIAL_ADMIN_PASSWORD or testimonials.config.php before signing in.';
            } elseif (!hash_equals((string) $config['admin_password'], trim((string) ($_POST['password'] ?? '')))) {
                $errorMessage = 'Invalid admin password.';
            } else {
                $_SESSION['testimonial_admin_authenticated'] = true;
                header('Location: testimonials-admin.php?logged_in=1');
                exit;
            }
        }

        if ($action === 'logout') {
            unset($_SESSION['testimonial_admin_authenticated']);
            header('Location: testimonials-admin.php?logged_out=1');
            exit;
        }

        if ($action === 'delete') {
            if (!isTestimonialsAdminAuthenticated()) {
                $errorMessage = 'Please sign in before deleting a review.';
            } elseif (deleteTestimonialById(trim((string) ($_POST['id'] ?? '')))) {
                $successMessage = 'Testimonial deleted successfully.';
            } else {
                $errorMessage = 'The selected testimonial could not be found.';
            }
        }
    }
}

$csrfToken = ensureTestimonialsCsrfToken();
$isAuthenticated = isTestimonialsAdminAuthenticated();
$testimonials = readTestimonials();

function testimonialsAdminEscape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function testimonialsAdminDate($value) {
    $timestamp = strtotime((string) $value);
    return $timestamp ? date('M j, Y g:i A', $timestamp) : 'Unknown date';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Testimonials Admin | NephSpace Elite Construction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" sizes="any">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <span class="section-subtitle">Admin Console</span>
                <h1 class="h2 mb-2">Manage Testimonials</h1>
                <p class="mb-0 text-muted">Review submissions and remove testimonials that should no longer appear on the website.</p>
            </div>
            <a href="index.html" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Website</a>
        </div>

        <?php if ($successMessage !== ''): ?>
            <div class="alert alert-success"><?php echo testimonialsAdminEscape($successMessage); ?></div>
        <?php endif; ?>
        <?php if ($errorMessage !== ''): ?>
            <div class="alert alert-danger"><?php echo testimonialsAdminEscape($errorMessage); ?></div>
        <?php endif; ?>
        <?php if (!isTestimonialsAdminConfigured($config)): ?>
            <div class="alert alert-warning">Admin deletion is ready, but you still need to set <code>TESTIMONIAL_ADMIN_PASSWORD</code> or create <code>testimonials.config.php</code> from the example file.</div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <?php if (!$isAuthenticated): ?>
                    <form method="POST" class="row g-3 align-items-end">
                        <input type="hidden" name="action" value="login">
                        <input type="hidden" name="csrf_token" value="<?php echo testimonialsAdminEscape($csrfToken); ?>">
                        <div class="col-md-8">
                            <label for="adminPassword" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" id="adminPassword" name="password" required>
                        </div>
                        <div class="col-md-4 d-grid">
                            <button type="submit" class="btn btn-primary">Sign In</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h2 class="h5 mb-1">Signed in</h2>
                            <p class="mb-0 text-muted"><?php echo count($testimonials); ?> testimonial(s) currently stored.</p>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="action" value="logout">
                            <input type="hidden" name="csrf_token" value="<?php echo testimonialsAdminEscape($csrfToken); ?>">
                            <button type="submit" class="btn btn-outline-danger">Sign Out</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($isAuthenticated): ?>
            <div class="row g-4">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                    <div>
                                        <h3 class="h5 mb-1"><?php echo testimonialsAdminEscape($testimonial['name'] ?? 'Anonymous Client'); ?></h3>
                                        <p class="text-muted mb-0"><?php echo testimonialsAdminEscape($testimonial['role'] ?? 'Client'); ?></p>
                                    </div>
                                    <span class="badge text-bg-light"><?php echo str_repeat('★', (int) ($testimonial['rating'] ?? 5)); ?></span>
                                </div>
                                <p class="mb-3"><?php echo testimonialsAdminEscape($testimonial['message'] ?? ''); ?></p>
                                <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                                    <small class="text-muted">Submitted <?php echo testimonialsAdminEscape(testimonialsAdminDate($testimonial['created_at'] ?? '')); ?></small>
                                    <form method="POST" onsubmit="return confirm('Delete this testimonial?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="csrf_token" value="<?php echo testimonialsAdminEscape($csrfToken); ?>">
                                        <input type="hidden" name="id" value="<?php echo testimonialsAdminEscape($testimonial['id'] ?? ''); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (empty($testimonials)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 text-center text-muted">No testimonials have been submitted yet.</div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>