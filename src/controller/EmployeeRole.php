<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class EmployeeRole
{
    public function list()
    {
        Authentication::require_roles(['admin','hro']);
        $query = "SELECT * FROM role_group order by name";
        $attachments = (new Database())->select($query);
        $tr = "";

        if (sizeof($attachments) > 0) {
            $table = "attachments";
            foreach ($attachments as $att) {
                $tr .= "<tr>
<td>{$att['name']}</td>
<td>{$att['created_at']}</td>
<td>
<button class='btn btn-complete' onclick='editRole({$att['id']})'>Edit <i class='bi bi-pencil'></i></button>
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
        <button class="btn btn-complete" onclick="addRole()">Add Role</button>
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
        Authentication::require_roles(['admin','hro']);

        // Sanitize inputs
        $id   = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $user_id = Authentication::user()->getId();

        if ($name === '') {
            echo json_encode(['status' => 'error', 'message' => 'Role name is required']);
            return;
        }

        // Prevent XSS
        $name = htmlspecialchars($name);

        $db = new Database();

        if ($id > 0) {
            // 🔹 Update by ID
            $exists = $db->fetch("SELECT id FROM role_group WHERE id = ?", [$id]);
            if (!$exists) {
                echo json_encode(['status' => 'error', 'message' => 'Role not found']);
                return;
            }

            $updated = $db->update('role_group', [
                'name' => $name,
                'user_id' => $user_id,
                'updated_at' => date("Y-m-d H:i:s")
            ], ["id"=>$id]);

            if ($updated) {
                echo json_encode(['status' => 'success', 'message' => 'Role updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No changes made or update failed']);
            }

        } else {
            // 🔹 Insert new (or update if name exists)
            $exists = $db->fetch("SELECT id FROM role_group WHERE name = ?", [$name]);

            if ($exists) {
                // Update instead of duplicate
                $updated = $db->update('role_group', [
                    'name' => $name,
                    'user_id' => $user_id,
                    'updated_at' => date("Y-m-d H:i:s")
                ], ["id"=>$exists['id']]);

                if ($updated) {
                    echo json_encode(['status' => 'success', 'message' => 'Role updated successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No changes made or update failed']);
                }
            } else {
                // Insert new
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
    public function roles()
    {
        header("Content-Type: application/json");
        $db=new Database();
        $fcts=$db->select("select id,name from role_group order by name");
        if (sizeof($fcts)>0){
            echo json_encode(["status"=>"success","data"=>$fcts]);
        }else{
            echo json_encode(['status'=>"error","message"=>"No data found"]);
        }
    }
}