<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Events
{
    public function list()
    {
        $query = "SELECT * FROM events";
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
<button class='btn btn-complete' onclick='editEvent({$event['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteEvent({$event['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewEvent({$event['id']})'>View <i class='bi bi-eye'></i></button>
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
        $tr
    </table>
</div>
HTML;

        MainLayout::render($content);
    }
    public function add()
    {
        $auth = new Authentication();

        if (!$auth->is_admin()) {
            echo json_encode(['status'=>'error','message'=>'Not authorized']);
            return;
        }

        // Sanitize inputs
        $name        = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'])) : '';
        $start_date  = $_POST['start_date'] ?? null;
        $end_date    = $_POST['end_date'] ?? null;
        $location    = isset($_POST['location']) ? trim($_POST['location']) : '';
        $category    = isset($_POST['category']) ? trim($_POST['category']) : '';
        $user_id     = $auth->get_authenticated_user()->getId();

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

}