<?php

require_once __DIR__ . '/admin.lib.php';

$config = loadAdminConfig();
cleanupExpiredAdminSecurityState();
header('X-Robots-Tag: noindex, nofollow', true);

$requestId = trim((string) ($_GET['request'] ?? ''));
$token = trim((string) ($_GET['token'] ?? ''));
$request = ($requestId !== '' && $token !== '') ? findAdminAccountRequestByStatusAccess($requestId, $token) : null;

$title = 'Request Status';
$message = 'Use the secure status link that was generated when you submitted your admin access request.';
$messageClass = 'alert-warning';
$statusBadgeClass = 'bg-secondary';
$nextStep = 'Submit a new request from the admin page if you still need access.';

if ($request !== null) {
    $status = (string) ($request['status'] ?? 'pending');
    $title = 'Admin Access Request Status';

    if ($status === 'pending') {
        $message = 'Your request is pending review by the approver.';
        $messageClass = 'alert-info';
        $statusBadgeClass = 'bg-primary';
        $nextStep = 'Please wait for the approver to review your request.';
    } elseif ($status === 'approved') {
        $message = 'Your request has been approved. You can now sign in to the admin page.';
        $messageClass = 'alert-success';
        $statusBadgeClass = 'bg-success';
        $nextStep = 'Use your approved email and password to sign in.';
    } elseif ($status === 'rejected') {
        $message = 'Your request was rejected.';
        $messageClass = 'alert-danger';
        $statusBadgeClass = 'bg-danger';
        $nextStep = 'If you believe this was a mistake, contact the website owner.';
    } elseif ($status === 'expired') {
        $message = 'Your request expired before it was approved or rejected.';
        $messageClass = 'alert-secondary';
        $statusBadgeClass = 'bg-secondary';
        $nextStep = 'Submit a fresh request if you still need admin access.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo adminEscape($title); ?> | NephSpace Elite Construction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <section class="py-5 bg-light admin-dashboard-section" style="min-height:100vh; display:flex; align-items:center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="project-detail-panel admin-panel-card">
                        <span class="section-subtitle">Requester View</span>
                        <h1 class="section-title mb-3"><?php echo adminEscape($title); ?></h1>
                        <div class="alert <?php echo adminEscape($messageClass); ?> mb-4"><?php echo adminEscape($message); ?></div>

                        <?php if ($request !== null): ?>
                            <div class="mb-4 d-flex flex-wrap align-items-center gap-3">
                                <span class="badge <?php echo adminEscape($statusBadgeClass); ?> fs-6 text-uppercase"><?php echo adminEscape((string) ($request['status'] ?? 'pending')); ?></span>
                                <span class="text-muted">Request ID: <?php echo adminEscape((string) ($request['id'] ?? '')); ?></span>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6"><strong>Name:</strong><br><?php echo adminEscape((string) ($request['name'] ?? '')); ?></div>
                                <div class="col-md-6"><strong>Email:</strong><br><?php echo adminEscape((string) ($request['email'] ?? '')); ?></div>
                                <div class="col-md-6"><strong>Submitted:</strong><br><?php echo adminEscape((string) ($request['created_at'] ?? '')); ?></div>
                                <div class="col-md-6"><strong>Expires:</strong><br><?php echo adminEscape((string) ($request['expires_at'] ?? '')); ?></div>
                                <?php if (!empty($request['handled_at'])): ?>
                                    <div class="col-md-6"><strong>Handled At:</strong><br><?php echo adminEscape((string) $request['handled_at']); ?></div>
                                <?php endif; ?>
                            </div>
                            <p class="section-description mb-4"><?php echo adminEscape($nextStep); ?></p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="admin.php" class="btn btn-outline-primary"><?php echo ($request['status'] ?? '') === 'approved' ? 'Go to Sign In' : 'Back to Admin'; ?></a>
                            </div>
                        <?php else: ?>
                            <p class="section-description mb-4">We could not verify this request status link. It may be invalid, incomplete, or no longer available.</p>
                            <a href="admin.php" class="btn btn-outline-primary">Back to Admin</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
