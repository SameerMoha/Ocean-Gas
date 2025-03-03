<?php
session_start();
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = new mysqli('localhost', 'root', '', 'myapp_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['reset_request'])) {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            // Generate a reset token
            $token = bin2hex(random_bytes(50));

            // Store the token in the database
            $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
            $stmt->bind_param("ss", $token, $email);
            $stmt->execute();
            $stmt->close();

            // Create the reset link
            $reset_link = "http://localhost/Ocean%20-%20Copy/reset-password.php?token=$token";
            http://localhost/Ocean%20-%20Copy/forgot-password.php
            // Send email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // SMTP server (Gmail)
                $mail->SMTPAuth   = true;
                $mail->Username   = 'oceangas99@gmail.com'; // Your email
                $mail->Password   = 'oddwdctwyooigfhg'; // Your email password (or App Password)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Sender and recipient
                $mail->setFrom('oceangas99@gmail.com', 'MyApp Support');
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = "Password Reset Request";
                $mail->Body    = "Click the link below to reset your password: <br><a href='$reset_link'>$reset_link</a>";

                $mail->send();
                $success = "A password reset link has been sent to your email.";
            } catch (Exception $e) {
                $error = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $error = "Email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color: green;'>$success</p>"; ?>

    <form method="POST" action="">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit" name="reset_request">Send Reset Link</button>
    </form>
</body>
</html>
