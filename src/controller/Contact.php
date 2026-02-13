<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;
use Solobea\Helpers\data\Sanitizer;

class Contact
{
    public function index()
    {
        $contacts = [
            [
                "name" => "1. General",
                "position" => "General Contacts,",
                "org" => "Archbishop Mihayo University College of Tabora,",
                "box" => "P.O. Box 801,",
                "region" => "Tabora, Tanzania.",
                "tel" => "TEL: +255 26 2605355",
                "web" => "Website: www.amucta.ac.tz",
                "mail" => "Email: amucta@amucta.ac.tz"
            ],
            [
                "name" => "2. Principal",
                "position" => "The Principal,",
                "org" => "Archbishop Mihayo University College of Tabora,",
                "box" => "P.O. Box 801,",
                "region" => "Tabora, Tanzania.",
                "tel" => "TEL: +255 734 966 674",
                "web" => "Website: www.amucta.ac.tz",
                "mail" => "Email: principal@amucta.ac.tz"
            ],
            [
                "name" => "3. Public Relation Office",
                "position" => "Public Relation Officer,",
                "org" => "Archbishop Mihayo University College of Tabora,",
                "box" => "P.O. Box 801,",
                "region" => "Tabora, Tanzania.",
                "tel" => "TEL: +255 734 928 504",
                "web" => "Website: www.amucta.ac.tz",
                "mail" => "Email: pro@amucta.ac.tz"
            ],
            [
                "name" => "4. Admission Office",
                "position" => "Admission Officer,",
                "org" => "Archbishop Mihayo University College of Tabora,",
                "box" => "P.O. Box 801,",
                "region" => "Tabora, Tanzania.",
                "tel" => "TEL: +255 734 928 505",
                "web" => "Website: www.amucta.ac.tz",
                "mail" => "Email: admission@amucta.ac.tz"
            ],
            [
                "name" => "5. Webmaster",
                "position" => "Webmaster,",
                "org" => "Archbishop Mihayo University College of Tabora,",
                "box" => "P.O. Box 801,",
                "region" => "Tabora, Tanzania.",
                "tel" => "TEL: +255 734 966 674",
                "web" => "Website: www.amucta.ac.tz",
                "mail" => "Email: admin@amucta.ac.tz"
            ]
        ];

        // Build contact cards
        $contactCards = "";
        foreach ($contacts as $c) {
            $contactCards .= "
        <div class='contact-card' style='padding:15px; margin-bottom:15px; background:#fff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08);'>
            <h3 style='color:var(--amucta-blue); margin-bottom:8px;'>{$c['name']}</h3>
            <p><strong>{$c['position']}</strong> {$c['org']}</p>
            <p>{$c['box']} {$c['region']}</p>
            <p>{$c['tel']}</p>
            <p><a href='mailto:{$c['mail']}'>{$c['mail']}</a></p>
            <p><a href='https://{$c['web']}' target='_blank'>{$c['web']}</a></p>
        </div>";
        }

        $content = <<<HTML
<div class="contact-page" style="max-width: 1000px; margin: 50px auto; padding: 20px; border-radius: 12px; background: #f9f9f9; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h1 style="color: var(--amucta-blue); font-size: 2.2em; margin-bottom: 20px; text-align:center;">Contact Us</h1>

    <div class="contact-info" style="margin-bottom: 30px;">
        $contactCards
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

        $head = "";
        $title = "Contact Us - AMUCTA";
        MainLayout::render($content, $head, $title);
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize input
            $full_name = htmlspecialchars(trim($_POST['name']));
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $subject  = Sanitizer::sanitize(Sanitizer::clean_for_json($_POST['subject']));
            $message = Sanitizer::sanitize(Sanitizer::clean_for_json($_POST['message']));
            if (!Sanitizer::is_valid_message($message)){
                echo "Invalid message!";
                exit();
            }

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

    public function list($params=null)
    {
       Authentication::require_roles(['admin','admission','hro','pro']);
        if (isset($params) &&!empty($params)){
            if ($params[0]=="all"){
                $query = "SELECT id, full_name, email, message, read_status, create_date, title 
              FROM contacts ORDER BY create_date DESC";
            }else{
                $limit=intval($params[0]);
                $query = "SELECT id, full_name, email, message, read_status, create_date, title 
              FROM contacts ORDER BY create_date DESC limit {$limit}";
            }
        }else{
            $query = "SELECT id, full_name, email, message, read_status, create_date, title 
              FROM contacts ORDER BY create_date DESC limit 50";
        }
        $contacts = (new Database())->select($query);

        $items = "";

        if (sizeof($contacts) > 0) {
            foreach ($contacts as $contact) {
                $readStatus = $contact['read_status'] ? "Read" : "Unread";

                $items .= <<<HTML
<div class="contact-card" style="border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:15px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
    <h3 style="margin:0; font-size:18px; color:#1976d2;">{$contact['title']}</h3>
    <p style="margin:6px 0; font-size:15px; line-height:1.5; color:#333;">{$contact['message']}</p>
    
    <p style="margin:4px 0; font-size:13px; color:#555;">
        <strong>Name:</strong> {$contact['full_name']} <br>
        <strong>Email:</strong> <a href="mailto:{$contact['email']}" style="color:#1976d2; text-decoration:none;">{$contact['email']}</a>
    </p>
    
    <p style="margin:4px 0; font-size:13px; color:#999;">
        <strong>Status:</strong> {$readStatus}
    </p>

    <div style="margin-top:10px; display:flex; justify-content:space-between; align-items:center;">
        <small style="color:#777;">{$contact['create_date']}</small>
        <div>
            <button class="btn btn-primary" onclick="replyContact('{$contact['email']}', '{$contact['full_name']}')">Reply</button>
            <button class="btn btn-danger" onclick="deleteResource('contacts', {$contact['id']})">Delete</button>
        </div>
    </div>
<a href="/contact/list/all" class="text-blue-400">View All</a>
</div>
HTML;
            }
        } else {
            $items = "<p style='color:#777; font-style:italic;'>No messages found.</p>";
        }

        $content = <<<HTML
<div class="flex flex-col" style="max-width:800px; margin:0 auto;">
    <h2 style="margin-bottom:20px; color:#333;">Contact Messages</h2>
    $items
</div>

<script>
function replyContact(email, name) {
    window.location.href = "mailto:" + email + "?subject=Reply to your message&body=Hello " + name + ",";
}
</script>
HTML;

        MainLayout::render($content);
    }

    public function ai($params=null)
    {

        Authentication::require_roles(['admin','admission','hro','pro']);
        if (isset($params) && !empty($params)){
            if ($params[0]=="all"){
                $query = "SELECT id, name, message, reply, created_at 
              FROM ai_chats ORDER BY created_at DESC";
            }else{
                $limit=intval($params[0]);
                $query = "SELECT id, name, message, reply, created_at 
              FROM ai_chats ORDER BY created_at DESC limit {$limit}";
            }
        }else{
            $query = "SELECT id, name, message, reply, created_at 
              FROM ai_chats ORDER BY created_at DESC limit 50";
        }
        $chats = (new Database())->select($query);

        $items = "";

        if (sizeof($chats) > 0) {
            foreach ($chats as $chat) {
                $items .= <<<HTML
<div class="chat-card" style="border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:15px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.05);">
    <h4 style="margin:0; font-size:16px; color:#1976d2;">{$chat['name']}</h4>
    
    <p style="margin:6px 0; font-size:15px; color:#333;">
        <strong>User:</strong> {$chat['message']}
    </p>
HTML;

                if (!empty($chat['reply'])) {
                    $items .= <<<HTML
    <p style="margin:6px 0; font-size:15px; color:#555;">
        <strong>AI:</strong> {$chat['reply']}
    </p>
HTML;
                }

                $items .= <<<HTML
    <div style="margin-top:10px; display:flex; justify-content:space-between; align-items:center;">
        <small style="color:#777;">{$chat['created_at']}</small>
        <button class="btn btn-danger" onclick="deleteResource('ai_chats', {$chat['id']})">Delete</button>
    </div>
</div>
HTML;
            }
        } else {
            $items = "<p style='color:#777; font-style:italic;'>No chats found.</p>";
        }

        $content = <<<HTML
<div class="flex flex-col" style="max-width:800px; margin:0 auto;">
    <h2 style="margin-bottom:20px; color:#333;">AI Chat History</h2>
    $items
    <a href="/contact/ai/all" class="text-blue-400">View All</a>
</div>
HTML;

        MainLayout::render($content);
    }


}