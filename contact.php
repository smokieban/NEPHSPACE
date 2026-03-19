<?php
/**
 * NephSpace Elite Construction - Contact Form Handler
 * Building Excellence, Defining Spaces
 */

use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', '0');
error_reporting(E_ALL);
ob_start();

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    sendJsonResponse(array(
        'success' => false,
        'message' => 'Email service dependencies are missing. Please install Composer packages and try again.'
    ), 500);
}

require_once $autoloadPath;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(array(
        'success' => false,
        'message' => 'Invalid request method'
    ), 405);
}

try {
    $name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
    $email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : '';
    $service = isset($_POST['service']) ? trim(strip_tags($_POST['service'])) : '';
    $subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

    $errors = array();

    if ($name === '') {
        $errors[] = 'Name is required';
    }

    if ($email === '') {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if ($phone === '') {
        $errors[] = 'Phone number is required';
    }

    if ($subject === '') {
        $errors[] = 'Subject is required';
    }

    if ($message === '') {
        $errors[] = 'Message is required';
    }

    if (!empty($errors)) {
        sendJsonResponse(array(
            'success' => false,
            'message' => implode(', ', $errors)
        ), 422);
    }

    $config = loadMailConfig();
    validateMailConfig($config);

    $serviceLabel = $service !== '' ? ucwords(str_replace('-', ' ', $service)) : 'Not specified';
    $emailSubject = 'New Contact Form Submission: ' . $subject;
    $emailBody = buildAdminMessage($name, $email, $phone, $serviceLabel, $subject, $message);

    $mailer = createMailer($config);
    $mailer->setFrom($config['from_email'], $config['from_name']);
    $mailer->addAddress($config['recipient_email'], $config['recipient_name']);
    $mailer->addReplyTo($email, $name);
    $mailer->Subject = $emailSubject;
    $mailer->Body = $emailBody;
    $mailer->send();

    if (!empty($config['auto_reply_enabled'])) {
        sendAutoReply($config, $email, $name);
    }

    sendJsonResponse(array(
        'success' => true,
        'message' => 'Thank you for contacting us! We will get back to you soon.'
    ));
} catch (Throwable $exception) {
    error_log('Contact form handler exception: ' . $exception->getMessage());

    $message = 'Sorry, there was an error sending your message. Please try again later or contact us directly at nephspaceconstrustion1@gmail.com.';
    if (strpos($exception->getMessage(), 'SMTP configuration') !== false) {
        $message = 'Email service is not configured yet. Please contact us directly at nephspaceconstrustion1@gmail.com.';
    }

    sendJsonResponse(array(
        'success' => false,
        'message' => $message
    ), 500);
}

function sendAutoReply($config, $customerEmail, $customerName) {
    $mailer = createMailer($config);
    $mailer->setFrom($config['from_email'], $config['from_name']);
    $mailer->addAddress($customerEmail, $customerName);
    $mailer->addReplyTo($config['recipient_email'], $config['recipient_name']);
    $mailer->Subject = 'Thank you for contacting NephSpace Elite Construction';

    $message = "Dear {$customerName},\n\n";
    $message .= "Thank you for contacting NephSpace Elite Construction and Interiors Hub Ltd.\n\n";
    $message .= "We have received your message and will get back to you as soon as possible.\n\n";
    $message .= "In the meantime, feel free to explore our services:\n";
    $message .= "- Architecture\n";
    $message .= "- Quantity Surveying\n";
    $message .= "- Design and Build\n";
    $message .= "- Construction Material Supply\n";
    $message .= "- International Sourcing\n\n";
    $message .= "Building Excellence, Defining Spaces\n\n";
    $message .= "Best regards,\n";
    $message .= "NephSpace Elite Construction Team\n";
    $message .= "Email: {$config['recipient_email']}\n";
    $message .= "Phone: +254700903141\n";

    $mailer->Body = $message;

    try {
        $mailer->send();
    } catch (Throwable $exception) {
        error_log('Auto-reply delivery failed: ' . $exception->getMessage());
    }
}

function buildAdminMessage($name, $email, $phone, $serviceLabel, $subject, $message) {
    $body = "You have received a new message from the NephSpace website contact form.\n\n";
    $body .= "Here are the details:\n\n";
    $body .= "Name: {$name}\n";
    $body .= "Email: {$email}\n";
    $body .= "Phone: {$phone}\n";
    $body .= "Service Interested In: {$serviceLabel}\n";
    $body .= "Subject: {$subject}\n\n";
    $body .= "Message:\n{$message}\n";

    return $body;
}

function createMailer($config) {
    $mailer = new PHPMailer(true);
    $mailer->isSMTP();
    $mailer->Host = $config['host'];
    $mailer->SMTPAuth = true;
    $mailer->Username = $config['username'];
    $mailer->Password = $config['password'];
    $mailer->Port = (int) $config['port'];
    $mailer->CharSet = 'UTF-8';
    $mailer->isHTML(false);

    $encryption = strtolower((string) $config['encryption']);
    if ($encryption === 'tls') {
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } elseif ($encryption === 'ssl') {
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    }

    return $mailer;
}

function loadMailConfig() {
    $fileConfig = array();
    $configPath = __DIR__ . '/contact.config.php';

    if (file_exists($configPath)) {
        $loadedConfig = require $configPath;
        if (is_array($loadedConfig)) {
            $fileConfig = $loadedConfig;
        }
    }

    return array(
        'host' => getConfigValue($fileConfig, 'host', 'SMTP_HOST', ''),
        'port' => (int) getConfigValue($fileConfig, 'port', 'SMTP_PORT', 587),
        'encryption' => getConfigValue($fileConfig, 'encryption', 'SMTP_ENCRYPTION', 'tls'),
        'username' => getConfigValue($fileConfig, 'username', 'SMTP_USERNAME', ''),
        'password' => getConfigValue($fileConfig, 'password', 'SMTP_PASSWORD', ''),
        'from_email' => getConfigValue($fileConfig, 'from_email', 'SMTP_FROM_EMAIL', 'nephspaceconstrustion1@gmail.com'),
        'from_name' => getConfigValue($fileConfig, 'from_name', 'SMTP_FROM_NAME', 'NephSpace Elite Construction'),
        'recipient_email' => getConfigValue($fileConfig, 'recipient_email', 'SMTP_RECIPIENT_EMAIL', 'nephspaceconstrustion1@gmail.com'),
        'recipient_name' => getConfigValue($fileConfig, 'recipient_name', 'SMTP_RECIPIENT_NAME', 'NephSpace Elite Construction'),
        'auto_reply_enabled' => filter_var(getConfigValue($fileConfig, 'auto_reply_enabled', 'SMTP_AUTO_REPLY_ENABLED', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
    );
}

function getConfigValue($fileConfig, $key, $envKey, $default) {
    if (array_key_exists($key, $fileConfig) && $fileConfig[$key] !== '') {
        return $fileConfig[$key];
    }

    $envValue = getenv($envKey);
    if ($envValue !== false && $envValue !== '') {
        return $envValue;
    }

    return $default;
}

function validateMailConfig($config) {
    $requiredKeys = array('host', 'port', 'username', 'password', 'from_email', 'recipient_email');

    foreach ($requiredKeys as $key) {
        if (empty($config[$key])) {
            throw new RuntimeException('SMTP configuration is incomplete. Missing: ' . $key);
        }
    }
}

function sendJsonResponse($response, $statusCode = 200) {
    if (ob_get_length()) {
        ob_clean();
    }

    http_response_code($statusCode);
    echo json_encode($response);
    exit;
}

/**
 * Optional: Save contact form data to database
 */
function saveToDatabase($name, $email, $phone, $service, $subject, $message) {
    // Database configuration
    $servername = "localhost";
    $username = "your_db_username";
    $password = "your_db_password";
    $dbname = "your_db_name";

    try {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, phone, service, subject, message, submitted_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $name, $email, $phone, $service, $subject, $message);

        // Execute
        $stmt->execute();

        // Close connections
        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        // Log error (don't expose to user)
        error_log("Database error: " . $e->getMessage());
    }
}
