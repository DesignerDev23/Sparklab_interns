<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path if you're using a manual install

function sendWelcomeEmail($to, $fullName) {
    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.hostinger.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'contact@sparklabhub.com';                // SMTP username
        $mail->Password   = 'Contactmail@101';                       // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption
        $mail->Port       = 465;                                   // TCP port to connect to (use 587 for TLS)

        // Recipients
        $mail->setFrom('contact@sparklabhub.com', 'Spark Lab Hub');
        $mail->addAddress($to, $fullName);                         // Add a recipient

        // Content
        $mail->isHTML(true);                                       // Set email format to HTML
        $mail->Subject = 'Welcome to Spark Lab Hub!';

        $mail->Body    = "
        <html>
        <head>
            <title>Welcome to Spark Lab Hub!</title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
                body { font-family: 'Montserrat', sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
                .header { text-align: center; padding-bottom: 20px; }
                .header img { max-width: 150px; }
                h1 { color: #014D87; font-size: 18px; margin-bottom: 10px; }
                p { font-size: 14px; color: #555; line-height: 1.6; }
                .footer { margin-top: 20px; font-size: 14px; color: #999; text-align: center; }
                .button { display: inline-block; padding: 12px 25px; background: #014D87; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 700; }
                .button:hover { background: #012e5b; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='https://sparklabhub.com/wp-content/uploads/2024/01/Asset_8-removebg-preview.png' alt='Spark Lab Hub Logo' />
                </div>
                <h1>Welcome to Spark Lab Hub, $fullName!</h1>
                <p>We're thrilled to have you join our community of innovators and creators. Thank you for signing up!</p>
                <p>If you have any questions or need assistance, please don't hesitate to reach out to us at <a href='mailto:contact@sparklabhub.com'>contact@sparklabhub.com</a>.</p>
                <p><a href='https://sparklabhub.com' class='button'>Explore Our Platform</a></p>
                <div class='footer'>Best Regards,<br>The Spark Lab Hub Team</div>
            </div>
        </body>
        </html>
        ";

        $mail->send();
        return true; // Successfully sent
    } catch (Exception $e) {
        // Log the error to a file
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}\n", 3, 'email_error_log.txt');
        return false; // Failed to send
    }
}