<?php


namespace Solobea\Dashboard\utils;


use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    public static function sendEmail($to, $subject, $bodyContent, $altBody = '', $attachments = []): bool
    {
        $mail = new PHPMailer(true);
        $org_name=Config::$ORG['name'];

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = Config::$SMTP['server']; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = Config::$SMTP['username']; // Replace with your email address
            $mail->Password = Config::$SMTP['password'];         // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //$mail->Port = 465;
            $mail->Port = Config::$SMTP['port'];

            // Email sender and recipient
            try {
                $mail->setFrom(Config::$SMTP['username'], $org_name);
            } catch (Exception $e) {
                throw new \Exception($e->getMessage());
                //ErrorReporter::report("Mailer setFrom failed: ",$mail->ErrorInfo,"/mail");
            } // Replace with your "From" address
            $mail->addAddress($to);

            // Add attachments (optional)
            foreach ($attachments as $filePath => $fileName) {
                $mail->addAttachment($filePath, $fileName);
            }

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $bodyContent;
            $mail->AltBody = $altBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error for debugging
            echo $mail->ErrorInfo."\n";
            echo $e->getMessage();
            ErrorReporter::report("Mailer Error: ",$mail->ErrorInfo,"/mail");
            return false;
        }
    }

    function emailTemplate($content, $templateType = 'default'): string
    {
        $org_name=Config::$ORG['name'];
        $title = match ($templateType) {
            'password_reset' => 'Password Reset Request',
            'account_activation' => 'Activate Your Account',
            'marketing' => 'Check Out Our Latest Offers!',
            'reminder' => 'Don’t Miss Out on New Jobs!',
            'reply' => 'We’ve Responded to Your Query',
            default => 'Notification Job Application',
        };

        return "
    <html lang='en'>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            .container {
                max-width: 600px;
                margin: auto;
                background: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                padding: 10px;
                background: #3E7A92;
                color: #ffffff;
                border-radius: 8px 8px 0 0;
            }
            .footer {
                text-align: center;
                padding: 10px;
                font-size: 12px;
                color: #777;
            }
            .content {
                margin: 20px 0;
                font-size: 16px;
                line-height: 1.6;
            }
            a.button {
                display: inline-block;
                padding: 10px 20px;
                margin: 20px 0;
                background: #007bff;
                color: #ffffff;
                text-decoration: none;
                border-radius: 4px;
            }
            a.button:hover {
                background: #0056b3;
            }
        </style>
        <title>E</title>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>$title</h1>
            </div>
            <div class='content'>
                $content
            </div>
            <div class='footer'>
                &copy; " . date('Y') . " {$org_name}. All rights reserved.
            </div>
        </div>
    </body>
    </html>
    ";
    }

}