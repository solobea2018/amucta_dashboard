<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Contact
{
    public function index()
    {
        $content = <<<HTML
<div class="contact-page" style="max-width: 900px; margin: 50px auto; padding: 20px; border-radius: 12px; background: #f9f9f9; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h1 style="color: var(--amucta-blue); font-size: 2.2em; margin-bottom: 20px;">Contact Us</h1>
    
    <div class="contact-info" style="margin-bottom: 30px;">
        <h2 style="color: #1D4ED8; margin-bottom: 10px;">AMUCTA - Archbishop Mihayo University College of Tabora</h2>
        <p><strong>Address:</strong> P.O. Box 801, Tabora, Tanzania</p>
        <p><strong>Telephone:</strong> +255 734 966 674</p>
        <p><strong>Email:</strong> <a href="mailto:amucta@amucta.ac.tz">amucta@amucta.ac.tz</a></p>
        <p><strong>Website:</strong> <a href="https://amucta.ac.tz" target="_blank">https://amucta.ac.tz</a></p>
    </div>

    <div class="contact-form" style="margin-top: 20px;">
        <h3 style="color: var(--amucta-blue); margin-bottom: 15px;">Send us a message</h3>
        <form action="/contact/send" onsubmit="sendFormSweet(this,event)" method="post" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="text" name="name" placeholder="Your Name" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
            <input type="email" name="email" placeholder="Your Email" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
            <input type="text" name="subject" placeholder="Subject" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
            <textarea name="message" rows="6" placeholder="Your Message" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;"></textarea>
            <button type="submit" style="background: var(--amucta-blue); color: #fff; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Send Message</button>
        </form>
    </div>

    <div class="map" style="margin-top: 40px;">
        <h3 style="color: #1D4ED8; margin-bottom: 15px;">Our Location</h3>
        <iframe 
            src="https://www.google.com/maps?q=Archbishop+Mihayo+University+College+of+Tabora&output=embed" 
            width="100%" height="400" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy">
        </iframe>
    </div>
</div>
HTML;

        $head = ""; // optional extra <head> content
        $title = "Contact Us - AMUCTA";
        MainLayout::render($content, $head, $title);
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $full_name = htmlspecialchars(trim($_POST['name']));
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $subject = htmlspecialchars(trim($_POST['subject']));
            $message = htmlspecialchars(trim($_POST['message']));

            if (!$email) {
                http_response_code(400);
                echo "Invalid email address!";
                return;
            }
            $contact=new \Solobea\Dashboard\model\Contact();
            $contact->setEmail($email);
            $contact->setTitle($subject);
            $contact->setFullName($full_name);
            $contact->setMessage($message);

            // Insert into database
            $db = new Database();
            if ($db->save_contact($contact)) {
                echo "Message sent successfully!";
            } else {
                http_response_code(500);
                echo "Failed to send message. Please try again.";
            }
        } else {
            // Redirect to contact page if accessed directly
            header("Location: /contact");
            exit;
        }
    }

}