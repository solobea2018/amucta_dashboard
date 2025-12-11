<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\utils\Resource;
use Solobea\Dashboard\view\MainLayout;

class EmployeeResearch
{
    public function list()
    {
        Authentication::require_roles(['admin','dupr']);
        $query = "SELECT er.*, e.name AS employee_name 
                  FROM employee_research er
                  JOIN employee e ON e.id = er.employee_id order by e.name";
        $researches = (new Database())->select($query);
        $tr = "";

        if (sizeof($researches) > 0) {
            foreach ($researches as $r) {
                $tr .= "<tr>
<td>{$r['employee_name']}</td>
<td>{$r['title']}</td>
<td>{$r['type']}</td>
<td>{$r['start_date']}</td>
<td>{$r['end_date']}</td>
<td>
<button class='btn btn-complete' onclick='editResearch({$r['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger hidden' onclick='deleteResource(\"employee_research\",{$r['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewResearch({$r['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='7'>No research entries found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addResearch()">Add Research</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Title</th>               
                <th>Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>$tr</tbody>
    </table>
</div>
HTML;

        MainLayout::render($content);
    }

    public function get_research($params)
    {
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = new Database();

                $research = $db->select("SELECT r.*,e.name as employee_name FROM employee_research r left join employee e on e.id = r.employee_id WHERE r.id = {$id} LIMIT 1");

                if ($research && count($research) > 0) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        "status" => "success",
                        "data" => $research[0]
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Research entry not found"
                    ]);
                }
            } catch (\Exception $exception) {
                echo json_encode([
                    "status" => "error",
                    "message" => $exception->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid Request"
            ]);
        }
    }

    public function add()
    {
        Authentication::require_roles(['admin','dupr']);
        // Sanitize inputs
        $employee_id = intval($_POST['employee_id'] ?? 0);
        $title       = htmlspecialchars(trim($_POST['title'] ?? ''), ENT_QUOTES, 'UTF-8');
        $type        = $_POST['type'] ?? 'research'; // research, publication, project
        $description = htmlspecialchars(trim($_POST['description'] ?? ''), ENT_QUOTES, 'UTF-8');
        $start_date  = htmlspecialchars(trim($_POST['start_date'] ?? ''), ENT_QUOTES, 'UTF-8');
        $end_date    = htmlspecialchars(trim($_POST['end_date'] ?? ''), ENT_QUOTES, 'UTF-8');
        $link        = htmlspecialchars(trim($_POST['link'] ?? ''), ENT_QUOTES, 'UTF-8');
        $active      = intval($_POST['active'] ?? 1);

        if (!$employee_id || !$title) {
            echo json_encode(['status' => "error", 'message' => "Employee and title are required"]);
            return;
        }

        // Handle optional file upload
        $filePath = null;
        if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['file']['tmp_name'];
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($title));
            $uniqueId = uniqid();
            $fileName = $safeName . "_" . $uniqueId . "." . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/files/research/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmp, $targetFile)) {
                $filePath = "/files/research/" . $fileName;
            }
        }

        $db = new Database();
        $user_id = Authentication::user()->getId();
        $data = [
            'employee_id' => $employee_id,
            'title'       => $title,
            'type'        => $type,
            'description' => $description,
            'start_date'  => $start_date ?: null,
            'end_date'    => $end_date ?: null,
            'link'        => $link ?: null,
            'file_path'   => $filePath,
            'created_by'  => $user_id,
            'active'      => $active
        ];

        if ($db->insert("employee_research", $data)) {
            echo json_encode(['status' => "success", 'message' => "Research added successfully"]);
        } else {
            echo json_encode(['status' => "error", 'message' => "Failed to add research"]);
        }
    }

    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode((new Database())->select("SELECT er.*, e.name AS employee_name 
                                                   FROM employee_research er
                                                   JOIN employee e ON e.id = er.employee_id"));
    }
}
