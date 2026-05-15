<?php

require_once __DIR__ . '/admin.lib.php';

$config = loadAdminConfig();
header('X-Robots-Tag: noindex, nofollow', true);

$token = trim((string) ($_REQUEST['token'] ?? ''));
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        completeAdminPasswordReset($token, (string) ($_POST['password'] ?? ''), (string) ($_POST['password_confirmation'] ?? ''), $config);
        header('Location: admin.php?password_reset_complete=1');
        exit;
    } catch (RuntimeException $exception) {
        $errorMessage = $exception->getMessage();
    }
}

$resetToken = $token !== '' ? findValidAdminPasswordResetToken($token) : null;
if ($token === '' || $resetToken === null) {
    $errorMessage = $errorMessage !== '' ? $errorMessage : 'This password reset link is invalid or has expired.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Reset Admin Password | NephSpace Elite Construction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <section class="py-5 bg-light admin-dashboard-section" style="min-height:100vh; display:flex; align-items:center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="project-detail-panel admin-panel-card">
                        <span class="section-subtitle">Approved Reset</span>
                        <h1 class="section-title mb-3">Set a new admin password</h1>
                        <?php if ($errorMessage !== ''): ?><div class="alert alert-danger mb-4"><?php echo adminEscape($errorMessage); ?></div><?php endif; ?>
                        <?php if ($errorMessage === '' && $resetToken !== null): ?>
                            <p class="section-description mb-4">Enter a new password for the admin account. This secure reset link expires automatically.</p>
                            <form method="POST" class="admin-form">
                                <input type="hidden" name="token" value="<?php echo adminEscape($token); ?>">
                                <div class="modern-form-group"><input class="modern-form-control" type="password" id="resetPassword" name="password" placeholder=" " required><label class="modern-form-label" for="resetPassword">New Password</label><span class="modern-form-line"></span></div>
                                <div class="modern-form-group"><input class="modern-form-control" type="password" id="resetPasswordConfirm" name="password_confirmation" placeholder=" " required><label class="modern-form-label" for="resetPasswordConfirm">Confirm New Password</label><span class="modern-form-line"></span></div>
                                <div class="admin-form-actions"><button type="submit" class="modern-submit-btn"><span class="btn-text">Update Password</span><span class="btn-icon"><i class="fas fa-key"></i></span></button></div>
                            </form>
                        <?php else: ?>
                            <a href="admin.php" class="btn btn-outline-primary">Return to Admin</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>