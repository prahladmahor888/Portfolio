<?php
/**
 * AJAX Contact Form Handler
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Message.php';

// Ensure it's an AJAX request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method');
}

// Get and sanitize input
$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$subject = sanitizeInput($_POST['subject'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');

// Validate inputs
if (empty($name) || empty($email) || empty($message)) {
    jsonResponse(false, 'Please fill in all required fields');
}

if (!isValidEmail($email)) {
    jsonResponse(false, 'Please provide a valid email address');
}

// Save to database
$messageModel = new Message();
$data = [
    'name' => $name,
    'email' => $email,
    'subject' => $subject,
    'message' => $message,
    'ip_address' => getClientIp()
];

if ($messageModel->create($data)) {
    // Optional: Send email notification
    // You can implement email sending here using PHP mail() or PHPMailer
    
    jsonResponse(true, 'Thank you! Your message has been sent successfully.');
} else {
    jsonResponse(false, 'Failed to send message. Please try again.');
}
