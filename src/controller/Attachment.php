<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Attachment
{
    public function list()
    {
        $query = "SELECT * FROM attachments";
        $attachments = (new Database())->select($query);
        $tr = "";

        if (sizeof($attachments) > 0) {
            foreach ($attachments as $att) {
                $tr .= "<tr>
<td>{$att['name']}</td>
<td>{$att['file_url']}</td>
<td>{$att['type']}</td>
<td>{$att['related_to']}</td>
<td>
<button class='btn btn-complete' onclick='editAttachment({$att['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteAttachment({$att['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewAttachment({$att['id']})'>View <i class='bi bi-eye'></i></button>
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
        $tr
    </table>
</div>
HTML;

        MainLayout::render($content);
    }
    public function add()
    {
        $auth = new Authentication();

        // Only admin can add attachments
        if (!$auth->is_admin()) {
            echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
            return;
        }

        // Sanitize inputs
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        $related_to = isset($_POST['related_to']) ? trim($_POST['related_to']) : '';
        $user_id = $auth->get_authenticated_user()->getId();

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
            'category' => $type,
            'url' => $file_path,
            'related_to' => $related_to,
            'user_id' => $user_id,
            'created_up' => date("Y-m-d H:i:s")
        ]);

        if ($inserted) {
            echo json_encode(['status'=>'success','message'=>'Attachment added successfully']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Failed to add attachment']);
        }
    }

}