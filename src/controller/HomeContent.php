<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class HomeContent
{
    public function list()
    {
        $query = "SELECT id, name,deadline FROM homepage_intro";
        $levels = (new Database())->select($query);
        $tr = "";

        if (sizeof($levels) > 0) {
            foreach ($levels as $level) {
                $tr .= "<tr>
<td>{$level['name']}</td>
<td>{$level['deadline']}</td>
<td>
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
    public function add()
    {
        header('Content-Type: application/json; charset=utf-8');

        // 1) Auth guard
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
            return;
        }

        // 2) Method guard
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }
        $name        = trim($_POST['name'] ?? '');
        $content= trim($_POST['content'] ?? '');
        $style = trim($_POST['style'] ?? '');
        $deadline = trim($_POST['deadline'] ?? date("Y-m-d H:i:s"));

        // Soft sanitize (DB safety comes from prepared statements)
        $name        = strip_tags($name);
        $content=htmlspecialchars($content);
        $style=htmlspecialchars($style);

        // 4) Validate
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

        // 5) DB
        $db = new Database();
        $con = $db->getCon(); // match your existing style

        $dupSql = "SELECT id FROM homepage_intro WHERE LOWER(name) = LOWER(?) LIMIT 1";
        if ($dup = $con->prepare($dupSql)) {
            $dup->bind_param('s', $name);
            $dup->execute();
            $dupRes = $dup->get_result();
            if ($dupRes && $dupRes->num_rows > 0) {
                http_response_code(409);
                echo json_encode(['status' => 'error', 'message' => 'A Value with this name already exists']);
                $dup->close();
                return;
            }
            $dup->close();
        }
        else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to check duplicates']);
            return;
        }
        // Insert
        $user_id=$auth->get_authenticated_user()->getId();
        $sql = "INSERT INTO homepage_intro (name, content,style,deadline,user_id) VALUES (?, ?, ?, ?,?)";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('ssssi', $name, $content,$style,$deadline,$user_id);
            if ($stmt->execute()) {
                echo json_encode([
                    'status'  => 'success',
                    'message' => 'Content added successfully',
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