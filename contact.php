<?php
/**
 * NephSpace Elite Construction - Contact Form Handler
 * Building Excellence, Defining Spaces
 */

// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize and validate input data
    $name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
    $email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : '';
    $service = isset($_POST['service']) ? trim(strip_tags($_POST['service'])) : '';
    $subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

    // Validation
    $errors = array();

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    }

    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }

    if (empty($message)) {
        $errors[] = 'Message is required';
    }

    // If there are validation errors
    if (!empty($errors)) {
        $response['message'] = implode(', ', $errors);
        echo json_encode($response);
        exit;
    }

    // Email configuration
    $to = 'info@nephspace.co.ke'; // Change this to your email address
    $email_subject = 'New Contact Form Submission: ' . $subject;

    // Create email body
    $email_body = "You have received a new message from the NephSpace website contact form.\n\n";
    $email_body .= "Here are the details:\n\n";
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Phone: $phone\n";
    $email_body .= "Service Interested In: " . ($service ? $service : 'Not specified') . "\n";
    $email_body .= "Subject: $subject\n\n";
    $email_body .= "Message:\n$message\n";

    // Email headers
    $headers = "From: noreply@nephspace.co.ke\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send email
    if (mail($to, $email_subject, $email_body, $headers)) {
        $response['success'] = true;
        $response['message'] = 'Thank you for contacting us! We will get back to you soon.';

        // Optional: Save to database
        // saveToDatabase($name, $email, $phone, $service, $subject, $message);

        // Optional: Send auto-reply to customer
        sendAutoReply($email, $name);

    } else {
        $response['message'] = 'Sorry, there was an error sending your message. Please try again later or contact us directly.';
    }

} else {
    $response['message'] = 'Invalid request method';
}

// Return JSON response
echo json_encode($response);

/**
 * Send auto-reply email to customer
 */
function sendAutoReply($customer_email, $customer_name) {
    $subject = 'Thank you for contacting NephSpace Elite Construction';

    $message = "Dear $customer_name,\n\n";
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
    $message .= "Email: info@nephspace.co.ke\n";
    $message .= "Phone: +254 700 000 000\n";

    $headers = "From: info@nephspace.co.ke\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    mail($customer_email, $subject, $message, $headers);
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
?>
