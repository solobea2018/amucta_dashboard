<?php


namespace Solobea\Dashboard\utils;

class Notifier
{
    private array $adminEmails = ["daudmabula8@gmail.com"]; // Add admin emails here

    /**
     * Notify admin about a new order.
     *
     * @param string $message The notification message to send to admins.
     * @return void
     */

    public function notify_admin(string $message)
    {
        // Email subject
        $subject = "Admin Notification";

        // Email body
        $body = "Hello Admin,\nThere is an new notification on your website.\n\nDetails:\n{$message}\nPlease check the admin panel for more details.\n\nRegards,\nMwondoko.com Team";


        // Headers for the email
        $headers = [
            "From:Mwondoko Notifications <no-reply@mwondoko.com>", // Replace with your email
            "Reply-To: no-reply@mwondoko.com",
            "Content-Type: text/plain; charset=UTF-8"
        ];

        // Send email to all admins
        foreach ($this->adminEmails as $email) {
            mail($email, $subject, $body, implode("\r\n", $headers));
        }
    }
}