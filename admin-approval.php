<?php

require_once __DIR__ . '/admin.lib.php';

$config = loadAdminConfig();
cleanupExpiredAdminSecurityState();
header('X-Robots-Tag: noindex, nofollow', true);

$type = trim((string) ($_REQUEST['type'] ?? ''));
$requestId = trim((string) ($_REQUEST['request'] ?? ''));
$token = trim((string) ($_REQUEST['token'] ?? ''));
$decision = trim((string) ($_POST['decision'] ?? ''));

$title = 'Approval Review';
$message = 'Open this page using the secure link that was emailed to the approver.';
$messageClass = 'alert-warning';

if ($type !== '' && $requestId !== '' && $token !== '') {
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($decision, array('approve', 'reject'), true)) {
            if ($type === 'account_request') {
                $request = processAdminAccountRequestDecision($requestId, $token, $decision, $config);
                $title = $decision === 'approve' ? 'Account Request Approved' : 'Account Request Rejected';
                $message = $decision === 'approve'
                    ? 'The admin account request for ' . (string) ($request['email'] ?? '') . ' has been approved.'
                    : 'The admin account request for ' . (string) ($request['email'] ?? '') . ' has been rejected.';
                $messageClass = $decision === 'approve' ? 'alert-success' : 'alert-secondary';
            } elseif ($type === 'password_reset') {
                $request = processAdminPasswordResetRequestDecision($requestId, $token, $decision, $config);
                $title = $decision === 'approve' ? 'Password Reset Approved' : 'Password Reset Rejected';
                $message = $decision === 'approve'
                    ? 'The password reset request for ' . (string) ($request['email'] ?? '') . ' has been approved and a reset link has been sent.'
                    : 'The password reset request for ' . (string) ($request['email'] ?? '') . ' has been rejected.';
                $messageClass = $decision === 'approve' ? 'alert-success' : 'alert-secondary';
            } else {
                throw new RuntimeException('Unsupported approval request type.');
            }
        } else {
            if ($type === 'account_request') {
                $title = 'Review Admin Access Request';
                $message = 'Use the buttons below to approve or reject this admin access request.';
                $messageClass = 'alert-info';
            } elseif ($type === 'password_reset') {
                $title = 'Review Password Reset Request';
                $message = 'Use the buttons below to approve or reject this password reset request.';
                $messageClass = 'alert-info';
            } else {
                throw new RuntimeException('Unsupported approval request type.');
            }
        }
    } catch (RuntimeException $exception) {
        $title = 'Approval Link Error';
        $message = $exception->getMessage();
        $messageClass = 'alert-danger';
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
                        <span class="section-subtitle">Protected Approval Review</span>
                        <h1 class="section-title mb-3"><?php echo adminEscape($title); ?></h1>
                        <div class="alert <?php echo adminEscape($messageClass); ?> alert-dismissible fade show mb-4" role="alert"><?php echo adminEscape($message); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
                        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' && in_array($type, array('account_request', 'password_reset'), true) && $requestId !== '' && $token !== ''): ?>
                            <form method="POST" class="d-flex flex-wrap gap-3">
                                <input type="hidden" name="type" value="<?php echo adminEscape($type); ?>">
                                <input type="hidden" name="request" value="<?php echo adminEscape($requestId); ?>">
                                <input type="hidden" name="token" value="<?php echo adminEscape($token); ?>">
                                <button type="submit" name="decision" value="approve" class="btn btn-success">Approve</button>
                                <button type="submit" name="decision" value="reject" class="btn btn-outline-danger">Reject</button>
                            </form>
                        <?php endif; ?>
                        <div class="mt-4"><a href="admin.php" class="btn btn-outline-primary">Back to Admin</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="js/admin-alerts.js"></script>
</body>
</html>