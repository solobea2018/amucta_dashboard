<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Attachment
{
    public function list()
    {
        Authentication::require_roles(['admin','pro','hro']);
        $query = "SELECT * FROM attachments order by id desc ,created_at desc ";
        $attachments = (new Database())->select($query);
        $tr = "";

        if (sizeof($attachments) > 0) {
            $table="attachments";
            foreach ($attachments as $att) {
                $tr .= "<tr>
<td>{$att['name']}</td>
<td>{$att['file_url']}</td>
<td>{$att['type']}</td>
<td>{$att['related_to']}</td>
<td>
<button class='btn btn-danger' onclick='deleteResource(\"attachments\",{$att['id']})'>Delete <i class='bi bi-trash'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='5'>No attachments found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addAttachment()">Add Attachment</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>File URL</th>
                <th>Type</th>
                <th>Related To</th>
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
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        $related_to = isset($_POST['related_to']) ? trim($_POST['related_to']) : '';
        $user_id = Authentication::user()->getId();

        if ($name === '') {
            echo json_encode(['status'=>'error','message'=>'Attachment name is required']);
            return;
        }

        // Handle file upload
        $file_path = null;
        if (!empty($_FILES['file_url']['name'])) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/attachments/files/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $tmp_name = $_FILES['file_url']['tmp_name'];
            $ext = strtolower(pathinfo($_FILES['file_url']['name'], PATHINFO_EXTENSION));

            // Only allow PDFs
            if ($ext !== 'pdf') {
                echo json_encode(['status'=>'error','message'=>'Only PDF files are allowed']);
                return;
            }

            // Max size 5MB
            if ($_FILES['file_url']['size'] > 5*1024*1024) {
                echo json_encode(['status'=>'error','message'=>'File must be less than 5MB']);
                return;
            }

            // Generate unique file name to avoid collisions
            $safe_name = preg_replace('/\s+/', '_', strtolower($name));
            $file_name = $safe_name . '_' . time() . '.pdf';
            $target_file = $uploadDir . $file_name;

            if (!move_uploaded_file($tmp_name, $target_file)) {
                echo json_encode(['status'=>'error','message'=>'Failed to upload file']);
                return;
            }

            $file_path = "/attachments/files/" . $file_name;
        }

        // Insert into database
        $db = new Database();
        $inserted = $db->insert('attachments', [
            'name' => $name,
            'type' => $type,
            'file_url' => $file_path,
            'related_to' => $related_to,
            'user_id' => $user_id,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        if ($inserted) {
            echo json_encode(['status'=>'success','message'=>'Attachment added successfully']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Failed to add attachment']);
        }
    }
    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("select * from attachments order by id desc ,created_at desc "));
    }

    public function all()
    {
        $attachments=(new Database())->select("select id, name, file_url, type, created_at from attachments order by created_at desc");
        $attachments_list="";
        if (!empty($attachments)){
            foreach ($attachments as $prog) {
                $name=$prog['name'];
                $date = date("F d, Y", strtotime($prog['created_at']));
                $attachments_list.=<<<atta
            <li class="attachment-item">
                <h3 class="attachment-title">{$name}</h3>
                <div class="attachment-meta">
                    <a href="{$prog['file_url']}" class="download-link">📁 Download</a>
                    <span>|</span>
                    <span class="date">📅 {$date}</span>
                </div>
            </li>
atta;
            }
        }
        $content=<<<pl
<div class="">
        <h2 class="section-title">Download Center</h2>
        <ul class="attachments-list">
            $attachments_list
        </ul>       
    </div>
pl;

        $head="<link rel='stylesheet' href='/css/home.css'>";
        MainLayout::render($content,$head,"Attachments");
    }

}