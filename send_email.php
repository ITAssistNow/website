<?php
// Send Email Script for ITAssistNow Contact Form

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Required fields validation
$requiredFields = ['name', 'email', 'message'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Please fill in all required fields"]);
        exit;
    }
}

// Sanitize inputs
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$phone = isset($_POST['phone']) ? filter_var($_POST['phone'], FILTER_SANITIZE_STRING) : '';
$service = isset($_POST['service']) ? filter_var($_POST['service'], FILTER_SANITIZE_STRING) : 'Not specified';
$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
    exit;
}

// Basic spam protection - honeypot field
if (!empty($_POST['website'])) {
    // This is likely a bot - silently fail
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Message received']);
    exit;
}

// Email configuration
$to = 'support@itassistnow.com'; // Replace with your actual email
$subject = 'New Contact Request from ITAssistNow Website';
$headers = "From: ITAssistNow Website <noreply@itassistnow.com>\r\n";
$headers .= "Reply-To: $name <$email>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Email template
$emailBody = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0A2463; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .footer { padding: 10px; text-align: center; font-size: 12px; color: #777; }
        .label { font-weight: bold; color: #0A2463; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Contact Request</h2>
        </div>
        <div class='content'>
            <p><span class='label'>Name:</span> $name</p>
            <p><span class='label'>Email:</span> $email</p>
            <p><span class='label'>Phone:</span> " . ($phone ? $phone : 'Not provided') . "</p>
            <p><span class='label'>Service Needed:</span> $service</p>
            <p><span class='label'>Message:</span></p>
            <p>" . nl2br($message) . "</p>
        </div>
        <div class='footer'>
            <p>This email was sent from the contact form on ITAssistNow website</p>
        </div>
    </div>
</body>
</html>
";

// Send email
try {
    $mailSent = mail($to, $subject, $emailBody, $headers);
    
    if ($mailSent) {
        echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
    } else {
        error_log('Failed to send email for contact form submission');
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again later.']);
    }
} catch (Exception $e) {
    error_log('Email sending error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred. Please try again later.']);
}
?>