<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path as necessary

function sendPaymentEmail($email, $fullName, $amountNaira, $paymentReference, $duration, $expirationDate) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'contact@sparklabhub.com';                // SMTP username
        $mail->Password   = 'Contactmail@101';                       // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption
        $mail->Port       = 587; // Use port 587 for TLS

        // Recipients
        $mail->setFrom('contact@sparklabhub.com', 'Spark Lab Hub');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Payment Confirmation for Your Coworking Space Subscription';
        $mail->Body = "
            <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 20px;
                            background-color: #f7f7f7;
                        }
                        .container {
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 5px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        .header {
                            background-color: #014D87; /* Header background color */
                            color: white;
                            padding: 15px 20px;
                            text-align: center;
                            border-radius: 5px 5px 0 0;
                        }
                        h1 {
                            color: #ffffff; /* White text for header */
                            margin: 0;
                        }
                        p {
                            color: #555555;
                        }
                        ul {
                            list-style-type: none;
                            padding: 0;
                        }
                        li {
                            margin: 5px 0;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #888888;
                        }
                        .logo {
                            width: 100px;
                            margin-bottom: 10px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <img src='https://sparklabhub.com/wp-content/uploads/2025/03/logo.png' alt='Spark Lab Hub Logo' class='logo' />
                            <h1>Welcome to Spark Lab Hub!</h1>
                            <p>Your coworking space subscription has been confirmed.</p>
                        </div>
                        <p>Dear $fullName,</p>
                        <p>Thank you for your payment of NGN $amountNaira for your coworking space subscription. Your subscription details are as follows:</p>
                        <ul>
                            <li><strong>Payment Reference:</strong> $paymentReference</li>
                            <li><strong>Subscription Duration:</strong> $duration</li>
                            <li><strong>Expiration Date:</strong> $expirationDate</li>
                        </ul>
                        <p>We appreciate your support and look forward to seeing you at Spark Lab Hub!</p>
                        <p class='footer'>Best Regards,<br> Spark Lab Hub Team</p>
                    </div>
                </body>
            </html>
        ";

        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        // Log the error message
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false; // Error sending email
    }
}
?>