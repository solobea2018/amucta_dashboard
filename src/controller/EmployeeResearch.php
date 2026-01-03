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
        $query = "SELECT * FROM amucta_research ORDER BY year DESC, id DESC ";
        $researches = (new Database())->select($query);
        $tr = "";

        if (sizeof($researches) > 0) {
            foreach ($researches as $r) {
                $tr .= "<tr>
<td>{$r['title']}</td>
<td>{$r['publication_type']}</td>
<td>{$r['authors']}</td>
<td>
<button class='btn btn-danger hidden' onclick='deleteResource(\"amucta_research\",{$r['id']})'>Delete <i class='bi bi-trash'></i></button>
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
                <th>Title</th>               
                <th>Publication Type</th>               
                <th>Authors</th>
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

                $research = $db->select("SELECT * FROM amucta_research where id='{$id}' limit 1");

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
        $authors = htmlspecialchars($_POST['authors'] ?? "Anonymous");
        $title       = htmlspecialchars(trim($_POST['title'] ?? ''), ENT_QUOTES, 'UTF-8');
        $publication_type        = $_POST['publication_type'] ?? 'Journal Article'; // research, publication, project
        $abstract_text = htmlspecialchars(trim($_POST['abstract_text'] ?? ''), ENT_QUOTES, 'UTF-8');
        $publisher  = htmlspecialchars(trim($_POST['publisher'] ?? ''), ENT_QUOTES, 'UTF-8');
        $year    = htmlspecialchars(trim($_POST['year'] ?? ''), ENT_QUOTES, 'UTF-8');
        $link        = htmlspecialchars(trim($_POST['link'] ?? ''), ENT_QUOTES, 'UTF-8');
        $status = strtolower($_POST['status'] ?? 'complete');

        if (!$authors || !$title) {
            echo json_encode(['status' => "error", 'message' => "Authors and title are required"]);
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
            'authors' => $authors,
            'title'       => $title,
            'publication_type'        => $publication_type,
            'abstract_text' => $abstract_text,
            'year'  => $year ?: null,
            'file_path'    => $filePath ?: null,
            'link'        => $link ?: null,
            'publisher'   => $publisher,
            'status'      => $status,
            'user_id'     =>$user_id
        ];

        if ($db->insert("amucta_research", $data)) {
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
        echo json_encode((new Database())->select("SELECT * FROM amucta_research ORDER BY year DESC, id DESC"));
    }
}
