<?php

use PHPMailer\PHPMailer\PHPMailer;

function loadAdminConfig(): array {
    $fileConfig = array();
    $configPath = __DIR__ . '/admin.config.php';

    if (file_exists($configPath)) {
        $loadedConfig = require $configPath;
        if (is_array($loadedConfig)) {
            $fileConfig = $loadedConfig;
        }
    }

    return array(
        'enable_legacy_password_login' => getAdminBooleanConfigValue($fileConfig, 'enable_legacy_password_login', 'ADMIN_ENABLE_LEGACY_PASSWORD_LOGIN', false),
        'admin_password' => (string) getAdminConfigValue($fileConfig, 'admin_password', 'ADMIN_PASSWORD', ''),
        'admin_password_hash' => (string) getAdminConfigValue($fileConfig, 'admin_password_hash', 'ADMIN_PASSWORD_HASH', ''),
        'session_name' => (string) getAdminConfigValue($fileConfig, 'session_name', 'ADMIN_SESSION_NAME', 'nephspace_admin'),
        'alert_email' => (string) getAdminConfigValue($fileConfig, 'alert_email', 'ADMIN_ALERT_EMAIL', 'info@nephspaceelite.com'),
        'alert_name' => (string) getAdminConfigValue($fileConfig, 'alert_name', 'ADMIN_ALERT_NAME', 'NephSpace Elite Construction Admin'),
        'account_request_approver_email' => (string) getAdminConfigValue($fileConfig, 'account_request_approver_email', 'ADMIN_ACCOUNT_REQUEST_APPROVER_EMAIL', 'kamunyu003@gmail.com'),
        'account_request_approver_name' => (string) getAdminConfigValue($fileConfig, 'account_request_approver_name', 'ADMIN_ACCOUNT_REQUEST_APPROVER_NAME', 'NephSpace Admin Access Approver'),
        'password_reset_approver_email' => (string) getAdminConfigValue($fileConfig, 'password_reset_approver_email', 'ADMIN_PASSWORD_RESET_APPROVER_EMAIL', 'kamunyu003@gmail.com'),
        'password_reset_approver_name' => (string) getAdminConfigValue($fileConfig, 'password_reset_approver_name', 'ADMIN_PASSWORD_RESET_APPROVER_NAME', 'NephSpace Password Reset Approver'),
        'base_url' => (string) getAdminConfigValue($fileConfig, 'base_url', 'ADMIN_BASE_URL', ''),
    );
}

function getAdminConfigValue(array $fileConfig, string $key, string $envKey, mixed $default): mixed {
    if (array_key_exists($key, $fileConfig) && $fileConfig[$key] !== '') {
        return $fileConfig[$key];
    }

    $envValue = getenv($envKey);
    if ($envValue !== false && $envValue !== '') {
        return $envValue;
    }

    return $default;
}

function getAdminBooleanConfigValue(array $fileConfig, string $key, string $envKey, bool $default): bool {
    if (array_key_exists($key, $fileConfig)) {
        $normalizedValue = filter_var($fileConfig[$key], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($normalizedValue !== null) {
            return $normalizedValue;
        }
    }

    $envValue = getenv($envKey);
    if ($envValue !== false && $envValue !== '') {
        $normalizedValue = filter_var($envValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($normalizedValue !== null) {
            return $normalizedValue;
        }
    }

    return $default;
}

function ensureAdminStorageDirectoryExists(): void {
    $directory = __DIR__ . '/storage';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create admin storage directory.');
    }
}

function readAdminStorageItems(string $path, string $rootKey): array {
    if (!file_exists($path)) {
        return array();
    }

    $decoded = json_decode((string) file_get_contents($path), true);
    if (!is_array($decoded)) {
        return array();
    }

    $items = isset($decoded[$rootKey]) && is_array($decoded[$rootKey])
        ? $decoded[$rootKey]
        : $decoded;

    return array_values(array_filter($items, 'is_array'));
}

function saveAdminStorageItems(string $path, string $rootKey, array $items): void {
    ensureAdminStorageDirectoryExists();
    $payload = json_encode(array($rootKey => array_values($items)), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($payload === false) {
        throw new RuntimeException('Unable to encode admin storage payload.');
    }

    if (file_put_contents($path, $payload . PHP_EOL, LOCK_EX) === false) {
        throw new RuntimeException('Unable to save admin storage payload.');
    }
}

function getAdminAccountsFilePath(): string {
    return __DIR__ . '/storage/admin-accounts.json';
}

function getAdminAccountRequestsFilePath(): string {
    return __DIR__ . '/storage/admin-account-requests.json';
}

function getAdminPasswordResetRequestsFilePath(): string {
    return __DIR__ . '/storage/admin-password-reset-requests.json';
}

function getAdminPasswordResetTokensFilePath(): string {
    return __DIR__ . '/storage/admin-password-reset-tokens.json';
}

function readAdminAccounts(): array {
    $items = readAdminStorageItems(getAdminAccountsFilePath(), 'accounts');
    $accounts = array();

    foreach ($items as $item) {
        $email = normalizeAdminEmail((string) ($item['email'] ?? ''));
        $passwordHash = (string) ($item['password_hash'] ?? '');
        if ($email === '' || $passwordHash === '') {
            continue;
        }

        $accounts[] = array(
            'id' => (string) ($item['id'] ?? bin2hex(random_bytes(8))),
            'name' => trim((string) ($item['name'] ?? 'Admin User')),
            'email' => $email,
            'password_hash' => $passwordHash,
            'created_at' => (string) ($item['created_at'] ?? ''),
            'updated_at' => (string) ($item['updated_at'] ?? ''),
        );
    }

    return $accounts;
}

function saveAdminAccounts(array $accounts): void {
    saveAdminStorageItems(getAdminAccountsFilePath(), 'accounts', $accounts);
}

function readAdminAccountRequests(): array {
    return readAdminStorageItems(getAdminAccountRequestsFilePath(), 'requests');
}

function saveAdminAccountRequests(array $requests): void {
    saveAdminStorageItems(getAdminAccountRequestsFilePath(), 'requests', $requests);
}

function readAdminPasswordResetRequests(): array {
    return readAdminStorageItems(getAdminPasswordResetRequestsFilePath(), 'requests');
}

function saveAdminPasswordResetRequests(array $requests): void {
    saveAdminStorageItems(getAdminPasswordResetRequestsFilePath(), 'requests', $requests);
}

function readAdminPasswordResetTokens(): array {
    return readAdminStorageItems(getAdminPasswordResetTokensFilePath(), 'tokens');
}

function saveAdminPasswordResetTokens(array $tokens): void {
    saveAdminStorageItems(getAdminPasswordResetTokensFilePath(), 'tokens', $tokens);
}

function cleanupExpiredAdminSecurityState(): void {
    expirePendingAdminAccountRequests();
    expirePendingAdminPasswordResetRequests();
    purgeExpiredAdminPasswordResetTokens();
}

function expirePendingAdminAccountRequests(): void {
    $requests = readAdminAccountRequests();
    $changed = false;

    foreach ($requests as $index => $request) {
        if ((string) ($request['status'] ?? '') !== 'pending') {
            continue;
        }

        if (!isAdminIsoTimeExpired((string) ($request['expires_at'] ?? ''))) {
            continue;
        }

        $request['status'] = 'expired';
        $request['handled_at'] = getAdminNow();
        $request['handled_by'] = 'system_cleanup';
        $requests[$index] = $request;
        $changed = true;
    }

    if ($changed) {
        saveAdminAccountRequests($requests);
    }
}

function expirePendingAdminPasswordResetRequests(): void {
    $requests = readAdminPasswordResetRequests();
    $changed = false;

    foreach ($requests as $index => $request) {
        if ((string) ($request['status'] ?? '') !== 'pending') {
            continue;
        }

        if (!isAdminIsoTimeExpired((string) ($request['expires_at'] ?? ''))) {
            continue;
        }

        $request['status'] = 'expired';
        $request['handled_at'] = getAdminNow();
        $request['handled_by'] = 'system_cleanup';
        $requests[$index] = $request;
        $changed = true;
    }

    if ($changed) {
        saveAdminPasswordResetRequests($requests);
    }
}

function purgeExpiredAdminPasswordResetTokens(): void {
    $tokens = readAdminPasswordResetTokens();
    $activeTokens = array_values(array_filter($tokens, function ($token) {
        return empty($token['used_at']) && !isAdminIsoTimeExpired((string) ($token['expires_at'] ?? ''));
    }));

    if (count($activeTokens) !== count($tokens)) {
        saveAdminPasswordResetTokens($activeTokens);
    }
}

function adminAccountsExist(): bool {
    return !empty(readAdminAccounts());
}

function getAdminAuthMode(array $config): string {
    if (adminAccountsExist()) {
        return 'accounts';
    }

    if (!empty($config['enable_legacy_password_login']) && isAdminLegacyConfigured($config)) {
        return 'legacy';
    }

    return 'bootstrap';
}

function isAdminConfigured(array $config): bool {
    return getAdminAuthMode($config) !== 'bootstrap';
}

function isAdminLegacyConfigured(array $config): bool {
    return (!empty($config['admin_password_hash']))
        || (!empty($config['admin_password']) && $config['admin_password'] !== 'change-this-password');
}

function normalizeAdminEmail(string $email): string {
    return strtolower(trim($email));
}

function hashAdminToken(string $token): string {
    return hash('sha256', $token);
}

function generateAdminToken(): string {
    return bin2hex(random_bytes(24));
}

function getAdminNow(): string {
    return gmdate('c');
}

function isAdminIsoTimeExpired(string $isoTime): bool {
    $timestamp = strtotime($isoTime);
    if ($timestamp === false) {
        return true;
    }

    return $timestamp < time();
}

function getAdminRequestBaseUrl(): string {
    $host = trim((string) ($_SERVER['HTTP_HOST'] ?? ''));
    if ($host === '') {
        return '';
    }

    $scheme = isAdminHttpsRequest() ? 'https' : 'http';
    $directory = str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '')));
    $directory = $directory === '/' || $directory === '.' ? '' : '/' . trim($directory, '/');

    return $scheme . '://' . $host . $directory;
}

function getAdminBaseUrl(array $config): string {
    $configured = rtrim(trim((string) ($config['base_url'] ?? '')), '/');
    $requestBase = getAdminRequestBaseUrl();

    if ($requestBase === '') {
        return $configured;
    }

    if ($configured === '') {
        return $requestBase;
    }

    $configuredHost = strtolower((string) (parse_url($configured, PHP_URL_HOST) ?? ''));
    $requestHost = strtolower((string) (parse_url($requestBase, PHP_URL_HOST) ?? ''));

    if ($configuredHost !== '' && $requestHost !== '' && $configuredHost !== $requestHost) {
        return $requestBase;
    }

    return $configured;
}

function buildAdminUrl(array $config, string $path, array $query = array()): string {
    $base = getAdminBaseUrl($config);
    $target = $base !== ''
        ? $base . '/' . ltrim($path, '/')
        : ltrim($path, '/');

    if (!empty($query)) {
        $target .= '?' . http_build_query($query);
    }

    return $target;
}

function buildAdminAccountRequestStatusUrl(array $request, string $rawToken, array $config): string {
    return buildAdminUrl($config, 'admin-request-status.php', array(
        'request' => (string) ($request['id'] ?? ''),
        'token' => $rawToken,
    ));
}

function findAdminAccountRequestByStatusAccess(string $requestId, string $rawToken): ?array {
    $tokenHash = hashAdminToken($rawToken);

    foreach (readAdminAccountRequests() as $request) {
        if ((string) ($request['id'] ?? '') !== $requestId) {
            continue;
        }

        $storedHash = (string) ($request['status_token_hash'] ?? '');
        if ($storedHash === '' || !hash_equals($storedHash, $tokenHash)) {
            return null;
        }

        return $request;
    }

    return null;
}

function findAdminAccountByEmail(string $email): ?array {
    $normalizedEmail = normalizeAdminEmail($email);
    if ($normalizedEmail === '') {
        return null;
    }

    foreach (readAdminAccounts() as $account) {
        if ((string) ($account['email'] ?? '') === $normalizedEmail) {
            return $account;
        }
    }

    return null;
}

function findAdminAccountById(string $accountId): ?array {
    foreach (readAdminAccounts() as $account) {
        if ((string) ($account['id'] ?? '') === $accountId) {
            return $account;
        }
    }

    return null;
}

function authenticateAdminAccount(string $email, string $password): ?array {
    $account = findAdminAccountByEmail($email);
    if ($account === null) {
        return null;
    }

    return password_verify($password, (string) ($account['password_hash'] ?? ''))
        ? $account
        : null;
}

function createAdminAccountRequest(string $name, string $email, string $password, string $passwordConfirmation, array $config): array {
    cleanupExpiredAdminSecurityState();

    $authMode = getAdminAuthMode($config);
    if (!in_array($authMode, array('bootstrap', 'accounts'), true)) {
        throw new RuntimeException('Admin account requests are not available in the current authentication mode.');
    }

    $name = trim($name);
    $email = normalizeAdminEmail($email);
    $password = trim($password);
    $passwordConfirmation = trim($passwordConfirmation);

    if ($name === '' || mb_strlen($name) < 3) {
        throw new RuntimeException('Please provide a full name with at least 3 characters.');
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Please provide a valid email address.');
    }

    if (findAdminAccountByEmail($email) !== null) {
        throw new RuntimeException('An admin account with this email already exists. Please sign in instead.');
    }

    if (mb_strlen($password) < 8) {
        throw new RuntimeException('Your password must be at least 8 characters long.');
    }

    if ($password !== $passwordConfirmation) {
        throw new RuntimeException('The password confirmation does not match.');
    }

    $requests = readAdminAccountRequests();
    foreach ($requests as $request) {
        if ((string) ($request['status'] ?? '') === 'pending'
            && (string) ($request['email'] ?? '') === $email
            && !isAdminIsoTimeExpired((string) ($request['expires_at'] ?? ''))
        ) {
            throw new RuntimeException('An account request for this email is already pending approval.');
        }
    }

    $approvalToken = generateAdminToken();
    $statusToken = generateAdminToken();
    $request = array(
        'id' => bin2hex(random_bytes(8)),
        'name' => $name,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'status' => 'pending',
        'approval_token_hash' => hashAdminToken($approvalToken),
        'status_token_hash' => hashAdminToken($statusToken),
        'created_at' => getAdminNow(),
        'expires_at' => gmdate('c', time() + 60 * 60 * 48),
        'handled_at' => '',
        'handled_by' => '',
    );

    $requests[] = $request;
    saveAdminAccountRequests($requests);
    recordAdminSecurityEvent('admin_account_request_submitted', array('email' => $email, 'name' => $name), $config, false);

    try {
        sendAdminAccountRequestApprovalEmail($request, $approvalToken, $config);
    } catch (RuntimeException $exception) {
        $requests = array_values(array_filter($requests, function ($storedRequest) use ($request) {
            return (string) ($storedRequest['id'] ?? '') !== (string) ($request['id'] ?? '');
        }));
        saveAdminAccountRequests($requests);
        recordAdminSecurityEvent('admin_account_request_notification_failed', array('email' => $email, 'request_id' => (string) ($request['id'] ?? '')), $config, false);
        throw $exception;
    }

    $request['status_url'] = buildAdminAccountRequestStatusUrl($request, $statusToken, $config);
    return $request;
}

function processAdminAccountRequestDecision(string $requestId, string $token, string $decision, array $config): array {
    cleanupExpiredAdminSecurityState();

    $requests = readAdminAccountRequests();
    $updatedRequest = null;

    foreach ($requests as $index => $request) {
        if ((string) ($request['id'] ?? '') !== $requestId) {
            continue;
        }

        validatePendingApprovalRequest($request, $token);

        if ($decision === 'approve') {
            $existingAccounts = readAdminAccounts();
            foreach ($existingAccounts as $existingAccount) {
                if ((string) ($existingAccount['email'] ?? '') === normalizeAdminEmail((string) ($request['email'] ?? ''))) {
                    throw new RuntimeException('An admin account with this email already exists. This request can no longer be approved.');
                }
            }

            $timestamp = getAdminNow();
            $existingAccounts[] = array(
                'id' => bin2hex(random_bytes(8)),
                'name' => (string) ($request['name'] ?? 'Admin User'),
                'email' => normalizeAdminEmail((string) ($request['email'] ?? '')),
                'password_hash' => (string) ($request['password_hash'] ?? ''),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            );
            saveAdminAccounts($existingAccounts);
            $request['status'] = 'approved';
        } else {
            $request['status'] = 'rejected';
        }

        $request['handled_at'] = getAdminNow();
        $request['handled_by'] = (string) ($config['account_request_approver_email'] ?? '');
        $requests[$index] = $request;
        $updatedRequest = $request;
        break;
    }

    if ($updatedRequest === null) {
        throw new RuntimeException('This admin access request could not be found.');
    }

    saveAdminAccountRequests($requests);
    recordAdminSecurityEvent($decision === 'approve' ? 'admin_account_request_approved' : 'admin_account_request_rejected', array('email' => (string) ($updatedRequest['email'] ?? ''), 'request_id' => $requestId), $config, false);
    sendAdminAccountRequestDecisionEmail($updatedRequest, $decision, $config);
    return $updatedRequest;
}

function createAdminPasswordResetRequest(string $email, array $config): bool {
    cleanupExpiredAdminSecurityState();

    if (getAdminAuthMode($config) !== 'accounts') {
        return false;
    }

    $account = findAdminAccountByEmail($email);
    if ($account === null) {
        recordAdminSecurityEvent('unknown_admin_password_reset_request', array('email' => normalizeAdminEmail($email)), $config, false);
        return false;
    }

    $requests = readAdminPasswordResetRequests();
    $requests = array_values(array_filter($requests, function ($request) use ($account) {
        return !((string) ($request['status'] ?? '') === 'pending' && (string) ($request['email'] ?? '') === (string) $account['email'] && !isAdminIsoTimeExpired((string) ($request['expires_at'] ?? '')));
    }));

    $approvalToken = generateAdminToken();
    $request = array(
        'id' => bin2hex(random_bytes(8)),
        'account_id' => (string) ($account['id'] ?? ''),
        'email' => (string) ($account['email'] ?? ''),
        'status' => 'pending',
        'approval_token_hash' => hashAdminToken($approvalToken),
        'created_at' => getAdminNow(),
        'expires_at' => gmdate('c', time() + 60 * 60 * 24),
        'handled_at' => '',
        'handled_by' => '',
    );

    $requests[] = $request;
    saveAdminPasswordResetRequests($requests);
    recordAdminSecurityEvent('admin_password_reset_requested', array('email' => (string) $account['email']), $config, false);

    try {
        sendAdminPasswordResetApprovalEmail($request, $approvalToken, $config);
    } catch (RuntimeException $exception) {
        $requests = array_values(array_filter($requests, function ($storedRequest) use ($request) {
            return (string) ($storedRequest['id'] ?? '') !== (string) ($request['id'] ?? '');
        }));
        saveAdminPasswordResetRequests($requests);
        recordAdminSecurityEvent('admin_password_reset_notification_failed', array('email' => (string) $account['email'], 'request_id' => (string) ($request['id'] ?? '')), $config, false);
        throw $exception;
    }

    return true;
}

function processAdminPasswordResetRequestDecision(string $requestId, string $token, string $decision, array $config): array {
    cleanupExpiredAdminSecurityState();

    $requests = readAdminPasswordResetRequests();
    $updatedRequest = null;

    foreach ($requests as $index => $request) {
        if ((string) ($request['id'] ?? '') !== $requestId) {
            continue;
        }

        validatePendingApprovalRequest($request, $token);
        $request['handled_at'] = getAdminNow();
        $request['handled_by'] = (string) ($config['password_reset_approver_email'] ?? '');

        if ($decision === 'approve') {
            $request['status'] = 'approved';
            $account = findAdminAccountById((string) ($request['account_id'] ?? ''));
            if ($account === null) {
                throw new RuntimeException('The admin account for this password reset request no longer exists.');
            }

            $resetLink = createAdminPasswordResetToken((string) $account['id'], (string) $account['email'], $config);
            sendApprovedAdminPasswordResetEmail($account, $resetLink, $config);
        } else {
            $request['status'] = 'rejected';
            sendRejectedAdminPasswordResetEmail($request, $config);
        }

        $requests[$index] = $request;
        $updatedRequest = $request;
        break;
    }

    if ($updatedRequest === null) {
        throw new RuntimeException('This password reset approval request could not be found.');
    }

    saveAdminPasswordResetRequests($requests);
    recordAdminSecurityEvent($decision === 'approve' ? 'admin_password_reset_approved' : 'admin_password_reset_rejected', array('email' => (string) ($updatedRequest['email'] ?? ''), 'request_id' => $requestId), $config, false);
    return $updatedRequest;
}

function validatePendingApprovalRequest(array $request, string $token): void {
    if ((string) ($request['status'] ?? '') !== 'pending') {
        throw new RuntimeException('This request has already been processed.');
    }

    if (isAdminIsoTimeExpired((string) ($request['expires_at'] ?? ''))) {
        throw new RuntimeException('This approval link has expired.');
    }

    $storedHash = (string) ($request['approval_token_hash'] ?? '');
    if ($storedHash === '' || !hash_equals($storedHash, hashAdminToken($token))) {
        throw new RuntimeException('This approval link is invalid.');
    }
}

function createAdminPasswordResetToken(string $accountId, string $email, array $config): string {
    $rawToken = generateAdminToken();
    $tokens = array_values(array_filter(readAdminPasswordResetTokens(), function ($entry) use ($accountId) {
        return (string) ($entry['account_id'] ?? '') !== $accountId;
    }));

    $tokens[] = array(
        'id' => bin2hex(random_bytes(8)),
        'account_id' => $accountId,
        'email' => normalizeAdminEmail($email),
        'token_hash' => hashAdminToken($rawToken),
        'created_at' => getAdminNow(),
        'expires_at' => gmdate('c', time() + 60 * 60),
        'used_at' => '',
    );

    saveAdminPasswordResetTokens($tokens);
    return buildAdminUrl($config, 'admin-reset.php', array('token' => $rawToken));
}

function findValidAdminPasswordResetToken(string $rawToken): ?array {
    $tokenHash = hashAdminToken($rawToken);
    foreach (readAdminPasswordResetTokens() as $token) {
        if ((string) ($token['token_hash'] ?? '') !== $tokenHash) {
            continue;
        }

        if (!empty($token['used_at']) || isAdminIsoTimeExpired((string) ($token['expires_at'] ?? ''))) {
            return null;
        }

        return $token;
    }

    return null;
}

function completeAdminPasswordReset(string $rawToken, string $password, string $passwordConfirmation, array $config): array {
    $resetToken = findValidAdminPasswordResetToken($rawToken);
    if ($resetToken === null) {
        throw new RuntimeException('This password reset link is invalid or has expired.');
    }

    if (mb_strlen(trim($password)) < 8) {
        throw new RuntimeException('Your new password must be at least 8 characters long.');
    }

    if ($password !== $passwordConfirmation) {
        throw new RuntimeException('The password confirmation does not match.');
    }

    $accounts = readAdminAccounts();
    $updatedAccount = null;
    foreach ($accounts as $index => $account) {
        if ((string) ($account['id'] ?? '') !== (string) ($resetToken['account_id'] ?? '')) {
            continue;
        }

        $account['password_hash'] = password_hash(trim($password), PASSWORD_DEFAULT);
        $account['updated_at'] = getAdminNow();
        $accounts[$index] = $account;
        $updatedAccount = $account;
        break;
    }

    if ($updatedAccount === null) {
        throw new RuntimeException('The admin account for this reset link no longer exists.');
    }

    saveAdminAccounts($accounts);
    $tokens = array_values(array_filter(readAdminPasswordResetTokens(), function ($entry) use ($updatedAccount) {
        return (string) ($entry['account_id'] ?? '') !== (string) ($updatedAccount['id'] ?? '');
    }));
    saveAdminPasswordResetTokens($tokens);
    recordAdminSecurityEvent('admin_password_reset_completed', array('email' => (string) ($updatedAccount['email'] ?? '')), $config, false);
    return $updatedAccount;
}

function startAdminSession(string $sessionName): void {
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    session_name($sessionName);
    session_set_cookie_params(array('lifetime' => 0, 'path' => '/', 'secure' => isAdminHttpsRequest(), 'httponly' => true, 'samesite' => 'Lax'));
    session_start();
}

function isAdminHttpsRequest(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
}

function verifyAdminPassword(string $submittedPassword, array $config): bool {
    $passwordHash = (string) ($config['admin_password_hash'] ?? '');
    if ($passwordHash !== '') {
        return password_verify($submittedPassword, $passwordHash);
    }

    $plainPassword = (string) ($config['admin_password'] ?? '');
    return $plainPassword !== '' && hash_equals($plainPassword, $submittedPassword);
}

function isAdminAuthenticated(): bool {
    return !empty($_SESSION['nephspace_admin_authenticated']);
}

function setAdminAuthenticated(bool $value, array $user = array()): void {
    if ($value) {
        session_regenerate_id(true);
        $_SESSION['nephspace_admin_authenticated'] = true;
        $_SESSION['nephspace_admin_user'] = array('name' => (string) ($user['name'] ?? 'Admin User'), 'email' => (string) ($user['email'] ?? ''));
        return;
    }

    unset($_SESSION['nephspace_admin_authenticated'], $_SESSION['nephspace_admin_user'], $_SESSION['nephspace_admin_csrf']);
    session_regenerate_id(true);
}

function getAuthenticatedAdminUser(): array {
    return is_array($_SESSION['nephspace_admin_user'] ?? null) ? $_SESSION['nephspace_admin_user'] : array();
}

function ensureAdminCsrfToken(): string {
    if (empty($_SESSION['nephspace_admin_csrf'])) {
        $_SESSION['nephspace_admin_csrf'] = bin2hex(random_bytes(16));
    }

    return (string) $_SESSION['nephspace_admin_csrf'];
}

function validateAdminCsrfToken(mixed $token): bool {
    return !empty($_SESSION['nephspace_admin_csrf']) && is_string($token) && hash_equals((string) $_SESSION['nephspace_admin_csrf'], $token);
}

function recordAdminSecurityEvent(string $event, array $context = array(), ?array $config = null, bool $sendAlert = true): void {
    $config = $config ?? loadAdminConfig();
    $entry = array('timestamp' => getAdminNow(), 'event' => $event, 'method' => (string) ($_SERVER['REQUEST_METHOD'] ?? ''), 'uri' => (string) ($_SERVER['REQUEST_URI'] ?? ''), 'ip' => resolveAdminClientIp(), 'user_agent' => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500), 'referer' => mb_substr((string) ($_SERVER['HTTP_REFERER'] ?? ''), 0, 500), 'context' => $context);
    appendAdminSecurityLog($entry);
    if ($sendAlert) {
        sendAdminSecurityAlert($entry, $config);
    }
}

function resolveAdminClientIp(): string {
    foreach (array($_SERVER['HTTP_CF_CONNECTING_IP'] ?? '', $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '', $_SERVER['REMOTE_ADDR'] ?? '') as $candidate) {
        $value = trim((string) $candidate);
        if ($value === '') {
            continue;
        }
        if (str_contains($value, ',')) {
            $parts = explode(',', $value);
            $value = trim((string) ($parts[0] ?? ''));
        }
        if ($value !== '') {
            return $value;
        }
    }

    return 'unknown';
}

function appendAdminSecurityLog(array $entry): void {
    try {
        ensureAdminStorageDirectoryExists();
    } catch (Throwable $exception) {
        error_log('Unable to prepare admin security log directory: ' . $exception->getMessage());
        return;
    }

    $payload = json_encode($entry, JSON_UNESCAPED_SLASHES);
    if ($payload !== false) {
        file_put_contents(__DIR__ . '/storage/admin-security.log', $payload . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

function sendAdminSecurityAlert(array $entry, array $config): void {
    deliverAdminAlertEmail($config, 'NephSpace admin security alert: ' . str_replace('_', ' ', $entry['event']), buildAdminSecurityAlertBody($entry), (string) $config['alert_email'], (string) $config['alert_name']);
}

function sendAdminAccountRequestApprovalEmail(array $request, string $rawToken, array $config): void {
    $reviewUrl = buildAdminUrl($config, 'admin-approval.php', array('type' => 'account_request', 'request' => (string) ($request['id'] ?? ''), 'token' => $rawToken));
    deliverAdminAlertEmail($config, 'Admin access request requires approval', buildAdminAccountRequestApprovalBody($request, $reviewUrl), (string) $config['account_request_approver_email'], (string) $config['account_request_approver_name'], true, 'We could not send your approval email request. Please verify the SMTP settings and try again.');
}

function sendAdminAccountRequestDecisionEmail(array $request, string $decision, array $config): void {
    deliverAdminAlertEmail(
        $config,
        $decision === 'approve' ? 'Your admin access request was approved' : 'Your admin access request was rejected',
        buildAdminAccountRequestDecisionBody($request, $decision, $config),
        (string) ($request['email'] ?? ''),
        (string) ($request['name'] ?? 'Admin Requester')
    );
}

function sendAdminPasswordResetApprovalEmail(array $request, string $rawToken, array $config): void {
    $reviewUrl = buildAdminUrl($config, 'admin-approval.php', array('type' => 'password_reset', 'request' => (string) ($request['id'] ?? ''), 'token' => $rawToken));
    deliverAdminAlertEmail($config, 'Admin password reset request requires approval', buildAdminPasswordResetApprovalBody($request, $reviewUrl), (string) $config['password_reset_approver_email'], (string) $config['password_reset_approver_name'], true, 'We could not send the password reset approval email. Please verify the SMTP settings and try again.');
}

function sendApprovedAdminPasswordResetEmail(array $account, string $resetLink, array $config): void {
    deliverAdminAlertEmail($config, 'Your admin password reset was approved', buildApprovedAdminPasswordResetBody($account, $resetLink), (string) ($account['email'] ?? ''), (string) ($account['name'] ?? 'Admin User'));
}

function sendRejectedAdminPasswordResetEmail(array $request, array $config): void {
    deliverAdminAlertEmail(
        $config,
        'Your admin password reset request was rejected',
        buildRejectedAdminPasswordResetBody($request),
        (string) ($request['email'] ?? ''),
        'Admin User'
    );
}

function deliverAdminAlertEmail(array $config, string $subject, string $body, string $recipientEmail, string $recipientName, bool $throwOnFailure = false, string $publicFailureMessage = 'We could not send this email right now.'): void {
    $recipientEmail = trim($recipientEmail);
    if ($recipientEmail === '') {
        if ($throwOnFailure) {
            throw new RuntimeException($publicFailureMessage);
        }
        return;
    }

    $autoloadPath = __DIR__ . '/vendor/autoload.php';
    if (!file_exists($autoloadPath)) {
        error_log('Admin alert email skipped: Composer autoload missing.');
        if ($throwOnFailure) {
            throw new RuntimeException($publicFailureMessage);
        }
        return;
    }

    $transportConfig = loadAdminMailTransportConfig();
    if (!isAdminMailTransportConfigured($transportConfig)) {
        error_log('Admin alert email skipped: SMTP configuration incomplete.');
        if ($throwOnFailure) {
            throw new RuntimeException($publicFailureMessage);
        }
        return;
    }

    require_once $autoloadPath;

    try {
        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = (string) $transportConfig['host'];
        $mailer->SMTPAuth = true;
        $mailer->Username = (string) $transportConfig['username'];
        $mailer->Password = (string) $transportConfig['password'];
        $mailer->Port = (int) $transportConfig['port'];
        $mailer->CharSet = 'UTF-8';
        $mailer->isHTML(false);
        $encryption = strtolower((string) $transportConfig['encryption']);
        if ($encryption === 'tls') {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($encryption === 'ssl') {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }
        $mailer->setFrom((string) $transportConfig['from_email'], (string) $transportConfig['from_name']);
        $mailer->addAddress($recipientEmail, $recipientName);
        $mailer->Subject = $subject;
        $mailer->Body = $body;
        $mailer->send();
    } catch (Throwable $exception) {
        error_log('Admin alert email failed: ' . $exception->getMessage());
        if ($throwOnFailure) {
            throw new RuntimeException($publicFailureMessage, 0, $exception);
        }
    }
}

function loadAdminMailTransportConfig(): array {
    $fileConfig = array();
    $configPath = __DIR__ . '/contact.config.php';
    if (file_exists($configPath)) {
        $loadedConfig = require $configPath;
        if (is_array($loadedConfig)) {
            $fileConfig = $loadedConfig;
        }
    }

    return array('host' => (string) getAdminConfigValue($fileConfig, 'host', 'SMTP_HOST', ''), 'port' => (int) getAdminConfigValue($fileConfig, 'port', 'SMTP_PORT', 587), 'encryption' => (string) getAdminConfigValue($fileConfig, 'encryption', 'SMTP_ENCRYPTION', 'tls'), 'username' => (string) getAdminConfigValue($fileConfig, 'username', 'SMTP_USERNAME', ''), 'password' => (string) getAdminConfigValue($fileConfig, 'password', 'SMTP_PASSWORD', ''), 'from_email' => (string) getAdminConfigValue($fileConfig, 'from_email', 'SMTP_FROM_EMAIL', 'info@nephspaceelite.com'), 'from_name' => (string) getAdminConfigValue($fileConfig, 'from_name', 'SMTP_FROM_NAME', 'NephSpace Elite Construction'));
}

function isAdminMailTransportConfigured(array $config): bool {
    foreach (array('host', 'port', 'username', 'password', 'from_email') as $key) {
        if (empty($config[$key])) {
            return false;
        }
    }

    return true;
}

function buildAdminSecurityAlertBody(array $entry): string {
    return implode(PHP_EOL, array('An unauthorized admin-related access attempt was detected.', '', 'Timestamp: ' . (string) ($entry['timestamp'] ?? ''), 'Event: ' . (string) ($entry['event'] ?? ''), 'Method: ' . (string) ($entry['method'] ?? ''), 'URI: ' . (string) ($entry['uri'] ?? ''), 'IP Address: ' . (string) ($entry['ip'] ?? ''), 'Referer: ' . (string) ($entry['referer'] ?? ''), 'User Agent: ' . (string) ($entry['user_agent'] ?? ''), '', 'Context:', json_encode($entry['context'] ?? array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}'));
}

function buildAdminAccountRequestApprovalBody(array $request, string $reviewUrl): string {
    return implode(PHP_EOL, array('A new admin access request is pending approval.', '', 'Name: ' . (string) ($request['name'] ?? ''), 'Email: ' . (string) ($request['email'] ?? ''), 'Requested At: ' . (string) ($request['created_at'] ?? ''), 'Expires At: ' . (string) ($request['expires_at'] ?? ''), 'IP Address: ' . resolveAdminClientIp(), '', 'Review and approve/reject this request here:', $reviewUrl));
}

function buildAdminAccountRequestDecisionBody(array $request, string $decision, array $config): string {
    $baseLines = array(
        'Hello ' . (string) ($request['name'] ?? 'Requester') . ',',
        '',
        'Email: ' . (string) ($request['email'] ?? ''),
        'Request Submitted: ' . (string) ($request['created_at'] ?? ''),
    );

    if ($decision === 'approve') {
        $baseLines[] = '';
        $baseLines[] = 'Your admin access request has been approved.';
        $baseLines[] = 'You can now sign in using your email and password at:';
        $baseLines[] = buildAdminUrl($config, 'admin.php');
    } else {
        $baseLines[] = '';
        $baseLines[] = 'Your admin access request has been rejected.';
        $baseLines[] = 'If you believe this was in error, please contact the website owner.';
    }

    return implode(PHP_EOL, $baseLines);
}

function buildAdminPasswordResetApprovalBody(array $request, string $reviewUrl): string {
    return implode(PHP_EOL, array('An admin password reset request is pending approval.', '', 'Email: ' . (string) ($request['email'] ?? ''), 'Requested At: ' . (string) ($request['created_at'] ?? ''), 'Expires At: ' . (string) ($request['expires_at'] ?? ''), 'IP Address: ' . resolveAdminClientIp(), '', 'Review and approve/reject this request here:', $reviewUrl));
}

function buildApprovedAdminPasswordResetBody(array $account, string $resetLink): string {
    return implode(PHP_EOL, array('Your admin password reset request has been approved.', '', 'Hello ' . (string) ($account['name'] ?? 'Admin User') . ',', 'Use the secure link below to set a new password. This link expires in 1 hour.', '', $resetLink, '', 'If you did not request this reset, please contact the site administrator immediately.'));
}

function buildRejectedAdminPasswordResetBody(array $request): string {
    return implode(PHP_EOL, array(
        'Your admin password reset request has been rejected.',
        '',
        'Email: ' . (string) ($request['email'] ?? ''),
        'Requested At: ' . (string) ($request['created_at'] ?? ''),
        '',
        'If you still need access, please contact the website owner directly.',
    ));
}

function adminEscape(mixed $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}