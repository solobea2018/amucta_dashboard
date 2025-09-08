<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class EmployeeRole
{
    public function list()
    {
        $query = "SELECT * FROM role_group";
        $attachments = (new Database())->select($query);
        $tr = "";

        if (sizeof($attachments) > 0) {
            $table = "attachments";
            foreach ($attachments as $att) {
                $tr .= "<tr>
<td>{$att['name']}</td>
<td>{$att['created_at']}</td>
<td>
<button class='btn btn-complete' onclick='editAttachment({$att['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteResource(\"role_group\",{$att['id']})'>Delete <i class='bi bi-trash'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='3'>No attachments found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addRole()">Add Attachment</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>             
                <th>Date</th>
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
        $auth = new Authentication();

        // Only admin can add attachments
        if (!$auth->is_admin()) {
            echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
            return;
        }

        // Sanitize inputs
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $user_id = $auth->get_authenticated_user()->getId();

        if ($name === '') {
            echo json_encode(['status' => 'error', 'message' => 'Role name is required']);
            return;
        }

        // Insert into database
        $db = new Database();
        $inserted = $db->insert('role_group', [
            'name' => $name,
            'user_id' => $user_id,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        if ($inserted) {
            echo json_encode(['status' => 'success', 'message' => 'Role added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add Role']);
        }
    }

    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("select * from role_group"));
    }

}