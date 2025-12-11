<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class HomeContent
{
    public function list()
    {
        Authentication::require_roles(['admin']);
        $query = "SELECT id, name,deadline FROM homepage_intro";
        $levels = (new Database())->select($query);
        $tr = "";

        if (sizeof($levels) > 0) {
            foreach ($levels as $level) {
                $tr .= "<tr>
<td>{$level['name']}</td>
<td>{$level['deadline']}</td>
<td>
<button class='btn btn-complete' onclick='editHome({$level['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteResource(\"homepage_intro\",{$level['id']})'>Delete <i class='bi bi-trash'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='3'>No Contents found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addHomeContent()">Add Homepage Content html</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Deadline</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>$tr</tbody>
    </table>
</div>
HTML;

        MainLayout::render($content,"","Homepage Contents");
    }

    public function get($params)
    {
        $id=intval($params[0]);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode(["data"=>(new Database())->select("select * from homepage_intro where id='{$id}'")[0]]);
    }
    public function add()
    {
        header('Content-Type: application/json; charset=utf-8');

        Authentication::require_role('admin');

        // 2) Method guard
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $id       = intval($_POST['id'] ?? 0); // for update
        $name     = trim($_POST['name'] ?? '');
        $content  = trim($_POST['content'] ?? '');
        $style    = trim($_POST['style'] ?? '');
        $deadline = trim($_POST['deadline'] ?? date("Y-m-d H:i:s"));

        // Sanitize
        $name     = strip_tags($name);
        $content  = htmlspecialchars($content);
        $style    = htmlspecialchars($style);

        // Validate
        if ($name === '') {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'Name is required']);
            return;
        }
        if (mb_strlen($name) > 100) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'Name must be at most 100 characters']);
            return;
        }

        // DB
        $db  = new Database();
        $con = $db->getCon();

        $user_id    = Authentication::user()->getId();
        $updated_by = $user_id;

        if ($id > 0) {
            // --- UPDATE ---
            $sql = "UPDATE homepage_intro 
                SET name = ?, content = ?, style = ?, deadline = ?, updated_by = ?, updated_at = NOW()
                WHERE id = ?";
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param('ssssii', $name, $content, $style, $deadline, $updated_by, $id);
                if ($stmt->execute()) {
                    echo json_encode([
                        'status'  => 'success',
                        'message' => 'Content updated successfully',
                        'id'      => $id
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update']);
                }
                $stmt->close();
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare update']);
            }
        } else {
            // --- INSERT ---
            $sql = "INSERT INTO homepage_intro (name, content, style, deadline, user_id, updated_by) 
                VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param('ssssii', $name, $content, $style, $deadline, $user_id, $updated_by);
                if ($stmt->execute()) {
                    echo json_encode([
                        'status'  => 'success',
                        'message' => 'Content add
                        ed successfully',
                        'id'      => $stmt->insert_id
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add']);
                }
                $stmt->close();
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare insert']);
            }
        }
    }

}