<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Level
{
    public function list()
    {
        $query = "SELECT * FROM level";
        $levels = (new Database())->select($query);
        $tr = "";

        if (sizeof($levels) > 0) {
            foreach ($levels as $level) {
                $tr .= "<tr>
<td>{$level['name']}</td>
<td>{$level['description']}</td>
<td>
<button class='btn btn-complete' onclick='editLevel({$level['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteResource(\"level\",{$level['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewLevel({$level['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='3'>No levels found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addLevel()">Add Level</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
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

        // 3) Collect + sanitize inputs
        $id          = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Soft sanitize (DB safety comes from prepared statements)
        $name        = strip_tags($name);
        $description = strip_tags($description);

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

        // 6) Prevent duplicate names (case-insensitive)
        if ($id > 0) {
            $dupSql = "SELECT id FROM level WHERE LOWER(name) = LOWER(?) AND id <> ? LIMIT 1";
            if ($dup = $con->prepare($dupSql)) {
                $dup->bind_param('si', $name, $id);
                $dup->execute();
                $dupRes = $dup->get_result();
                if ($dupRes && $dupRes->num_rows > 0) {
                    http_response_code(409);
                    echo json_encode(['status' => 'error', 'message' => 'A level with this name already exists']);
                    $dup->close();
                    return;
                }
                $dup->close();
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to check duplicates']);
                return;
            }
        } else {
            $dupSql = "SELECT id FROM level WHERE LOWER(name) = LOWER(?) LIMIT 1";
            if ($dup = $con->prepare($dupSql)) {
                $dup->bind_param('s', $name);
                $dup->execute();
                $dupRes = $dup->get_result();
                if ($dupRes && $dupRes->num_rows > 0) {
                    http_response_code(409);
                    echo json_encode(['status' => 'error', 'message' => 'A level with this name already exists']);
                    $dup->close();
                    return;
                }
                $dup->close();
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to check duplicates']);
                return;
            }
        }

        // 7) Insert or Update
        if ($id > 0) {
            // Update
            $sql = "UPDATE level SET name = ?, description = ? WHERE id = ? LIMIT 1";
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param('ssi', $name, $description, $id);
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Level updated successfully', 'id' => $id]);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update level']);
                }
                $stmt->close();
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare update']);
            }
        } else {
            // Insert
            $user_id=$auth->get_authenticated_user()->getId();
            $sql = "INSERT INTO level (name, description,user_id) VALUES (?, ?, ?)";
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param('ssi', $name, $description,$user_id);
                if ($stmt->execute()) {
                    echo json_encode([
                        'status'  => 'success',
                        'message' => 'Level added successfully',
                        'id'      => $stmt->insert_id
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add level']);
                }
                $stmt->close();
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare insert']);
            }
        }
    }

}