<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Events
{
    public function list()
    {
        Authentication::require_roles(['admin','pro','hro']);
        $query = "SELECT * FROM events order by id desc ";
        $events = (new Database())->select($query);
        $tr = "";

        if (sizeof($events) > 0) {
            foreach ($events as $event) {
                $tr .= "<tr>
<td>{$event['name']}</td>
<td>{$event['start_date']}</td>
<td>{$event['end_date']}</td>
<td>{$event['location']}</td>
<td>
<button class='btn btn-danger' onclick='deleteResource(\"events\",{$event['id']})'>Delete <i class='bi bi-trash'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='5'>No events found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addEvent()">Add Event</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>$tr</tbody>
    </table>
</div>
HTML;

        MainLayout::render($content);
    }
    public function add()
    {
        Authentication::require_roles(['admin','pro','hro']);

        // Sanitize inputs
        $name        = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'])) : '';
        $start_date  = $_POST['start_date'] ?? null;
        $end_date    = $_POST['end_date'] ?? null;
        $location    = isset($_POST['location']) ? trim($_POST['location']) : '';
        $category    = isset($_POST['category']) ? trim($_POST['category']) : '';
        $user_id     = Authentication::user()->getId();

        if ($name === '' || $start_date === null) {
            echo json_encode(['status'=>'error','message'=>'Event name and start date are required']);
            return;
        }

        // Handle feature image
        $feature_image_path = null;
        if (!empty($_FILES['feature_image']['name'])) {
            $img = $_FILES['feature_image'];
            if ($img['size'] > 1024*1024) {
                echo json_encode(['status'=>'error','message'=>'Feature image must be less than 1MB']);
                return;
            }
            $allowed = ['image/jpeg','image/png','image/webp'];
            if (!in_array($img['type'], $allowed)) {
                echo json_encode(['status'=>'error','message'=>'Invalid image type']);
                return;
            }

            $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
            $file_name = preg_replace("/[^a-zA-Z0-9]/", "_", $name) . "_" . time() . ".webp";
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/events/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $destination = $upload_dir . $file_name;

            // Convert to webp
            switch($img['type']){
                case 'image/jpeg':
                    $src = imagecreatefromjpeg($img['tmp_name']); break;
                case 'image/png':
                    $src = imagecreatefrompng($img['tmp_name']); break;
                case 'image/webp':
                    $src = imagecreatefromwebp($img['tmp_name']); break;
                default: $src = null;
            }
            if ($src) {
                if (!imageistruecolor($src)) {
                    $trueColor = imagecreatetruecolor(imagesx($src), imagesy($src));
                    imagecopy($trueColor, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
                    imagedestroy($src);
                    $src = $trueColor;
                }
                imagewebp($src, $destination, 50); // low quality
                imagedestroy($src);
                $feature_image_path = "/images/events/" . $file_name;
            }
        }

        // Handle attachment (PDF only, <1MB)
        $attachment_path = null;
        if (!empty($_FILES['attachment']['name'])) {
            $file = $_FILES['attachment'];
            if ($file['size'] > 1024*1024) {
                echo json_encode(['status'=>'error','message'=>'Attachment must be less than 1MB']);
                return;
            }
            if ($file['type'] !== 'application/pdf') {
                echo json_encode(['status'=>'error','message'=>'Attachment must be a PDF']);
                return;
            }

            $file_name = preg_replace("/[^a-zA-Z0-9]/", "_", $name) . "_" . time() . ".pdf";
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/files/events/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $destination = $upload_dir . $file_name;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $attachment_path = "/files/events/" . $file_name;
            }
        }

        // Insert into database
        $db = new Database();
        $inserted = $db->insert('events', [
            'name'          => $name,
            'description'   => $description,
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'location'      => $location,
            'feature_image' => $feature_image_path,
            'attachment'    => $attachment_path,
            'category'      => $category,
            'user_id'       => $user_id,
            'created_at'    => date("Y-m-d H:i:s")
        ]);

        if ($inserted) {
            echo json_encode(['status'=>'success','message'=>'Event added successfully']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Failed to add event']);
        }
    }
    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("select * from events order by id desc "));
    }
    public function all()
    {
        $events=(new Database())->select("select id, name, description, start_date, end_date, location, feature_image, attachment, category, created_at from events order by created_at desc");
        $events_list="";
        if (!empty($events)){
            foreach ($events as $prog) {
                $id=$prog['id'];
                $name=$prog['name'];
                $feature_image=$prog['feature_image']??'https://www.heslb.go.tz/assets/css/assets_22/images/new.svg';
                $date = date("F d, Y", strtotime($prog['start_date']));

                $expire  = $prog['end_date']??$date;
                $img     = "";
                if (strtotime(date("Y-m-d")) <= strtotime($expire)) {
                    $img = '<img src="https://www.heslb.go.tz/assets/images/new.gif" alt="new" class="event-new">';
                }
                $location =$prog['location']??"";
                $events_list.=<<<atta
            <li class="event-item">
                <div class="event-flex">
                    <img src="$feature_image" alt="event" class="event-img">
                    <div>
                        <a href="/events/detail/$id" class="event-title">{$name} - {$location}</a>
                        <div class="event-meta">
                            <i class="fa fa-clock-o"></i>
                            <span>{$date}</span>
                            $img
                        </div>
                    </div>
                </div>
                <hr class="event-divider">
            </li>
atta;
            }
        }
        $content=<<<pl
<div class="intro-col">
                <h3 class="intro-heading">News & Events</h3>
                <ul class="events-list">
                    $events_list                   
                </ul>
            </div>
pl;

        $head="<link rel='stylesheet' href='/css/home.css'>";
        MainLayout::render($content,$head,"Events");
    }
    public function detail($params)
    {
        if (!empty($params)) {
            $id = intval($params[0]);

            $event = (new Database())->select("
            SELECT id, name, description, start_date, end_date, location, feature_image, attachment, category, created_at
            FROM events 
            WHERE id='{$id}' 
            LIMIT 1
        ")[0];

            $desc = nl2br($event['description']);
            $featureImg = $event['feature_image'] ? "<img src='{$event['feature_image']}' alt='{$event['name']}' class='event-image'>" : "";
            $attachment = $event['attachment'] ? "<a href='{$event['attachment']}' class='btn btn-outline-primary mt-3' target='_blank'><i class='bi bi-paperclip'></i> Download Attachment</a>" : "";

            $content = <<<CONTENT
<div class="event-detail">
    <div class="event-header">
        {$featureImg}
        <h2 class="event-title">{$event['name']}</h2>
        <p class="event-category"><i class="bi bi-tags"></i> {$event['category']}</p>
    </div>
    <div class="event-body">
        <p class="event-info"><i class="bi bi-calendar-event"></i> 
            {$event['start_date']} to {$event['end_date']}
        </p>
        <p class="event-info"><i class="bi bi-geo-alt"></i> {$event['location']}</p>
        <div class="event-description">{$desc}</div>
        {$attachment}
    </div>
    <div class="event-footer text-muted">
        <small>Posted on {$event['created_at']}</small>
    </div>
</div>
CONTENT;

            $head = <<<HEAD
<style>
    .event-detail { max-width: 900px; margin: 2rem auto; padding: 1.5rem; background: #fff; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif; }
    .event-header { text-align: center; margin-bottom: 1.5rem; }
    .event-title { font-size: 2rem; font-weight: bold; margin: 1rem 0 0.5rem; }
    .event-category { font-size: 0.9rem; color: #6c757d; }
    .event-image { width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; }
    .event-body { margin-top: 1rem; line-height: 1.7; }
    .event-info { margin: 0.5rem 0; font-weight: 500; }
    .event-description { margin-top: 1rem; font-size: 1rem; color: #333; }
    .event-footer { text-align: right; margin-top: 2rem; font-size: 0.85rem; }
</style>
HEAD;

            MainLayout::render($content, $head, $event['name']);
        }
    }


}