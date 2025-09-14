<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\utils\Resource;
use Solobea\Dashboard\view\MainLayout;

class Employee
{
    public static function getCount()
    {
        $db=new Database();
        return $db->selectOne("Select count(*) as count from employee")['count'];
    }

    public function list()
    {
        $query = "SELECT * FROM employee order by name";
        $employees = (new Database())->select($query);
        $tr = "";

        if (sizeof($employees) > 0) {
            foreach ($employees as $emp) {
                $tr .= "<tr>
<td><a class='text-blue-500' href='/employee/profile/{$emp['id']}'> {$emp['name']}</a></td>
<td>{$emp['title']}</td>
<td>{$emp['phone']}</td>
<td><a class='btn btn-mark-read' href='/employee/active/{$emp['id']}'>" . ($emp['active'] ? 'Yes' : 'No') . "</a></td>
<td>
<button class='btn btn-complete' onclick='editEmployee({$emp['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-mark-read' onclick='addEmployeeRole({$emp['id']})'>Role<i class='bi bi-pencil'></i></button>
<button class='btn btn-danger hidden' onclick='deleteResourcet(\"employee\",{$emp['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewEmployee({$emp['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='5'>No employees found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addEmployee()">Add Employee</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Title</th>               
                <th>Phone</th>
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

    private function profStyle(): string
    {
        return <<<style
<style>
.profile-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px 30px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    font-family: "Segoe UI", Tahoma, sans-serif;
    color: #333;
}

.profile-container h2 {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #2c3e50;
}

.profile-container p {
    margin: 6px 0;
    font-size: 0.95rem;
    line-height: 1.5;
}

.profile-container strong {
    color: #555;
}

.profile-container img {
    display: block;
    margin-top: 10px;
    border-radius: 8px;
    max-width: 150px;
    height: auto;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.profile-container h3 {
    margin-top: 30px;
    margin-bottom: 12px;
    font-size: 1.3rem;
    color: #1e293b;
    border-left: 4px solid #2563eb;
    padding-left: 8px;
}

.profile-container .solobea-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 0.9rem;
}

.profile-container .solobea-table th,
.profile-container .solobea-table td {
    border: 1px solid #e5e7eb;
    padding: 8px 10px;
    text-align: left;
}

.profile-container .solobea-table th {
    background-color: #f8fafc;
    font-weight: 600;
    color: #374151;
}

.profile-container .solobea-table tr:nth-child(even) {
    background-color: #f9fafb;
}

.profile-container .solobea-table a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.profile-container .solobea-table a:hover {
    text-decoration: underline;
}

</style>
style;
    }
    public function profile($params)
    {
        if (!isset($params[0]) || empty($params[0])) {
            echo Resource::getErrorPage("Invalid Request: Employee ID required");
            return;
        }

        $employee_id = intval($params[0]);
        $db = new Database();

        // Fetch employee info
        $employee = $db->select("SELECT e.*, d.name as department_name
                             FROM employee e
                             LEFT JOIN department d ON e.department_id = d.id
                             WHERE e.id = {$employee_id} LIMIT 1");

        if (!$employee || count($employee) === 0) {
            echo Resource::getErrorPage("Employee not found");
            return;
        }

        $emp = $employee[0];

        // Fetch employee roles
        $roles = $db->select("SELECT r.role_name, r.start_date, r.end_date, d.name as department_name
                          FROM employee_role r
                          LEFT JOIN department d ON r.department_id = d.id                        
                          WHERE r.employee_id = {$employee_id} AND r.active = 1
                          ORDER BY r.start_date DESC");

        // Fetch employee researches, publications, projects
        $researches = $db->select("SELECT title, type, description, start_date, end_date, link, file_path, active
                               FROM employee_research
                               WHERE employee_id = {$employee_id}
                               ORDER BY start_date DESC");

        // Render HTML
        $rolesHtml = "";
        foreach ($roles as $role) {
            $rolesHtml .= "<tr>
            <td>{$role['role_name']}</td>
            <td>{$role['department_name']}</td>
            <td>{$role['start_date']}</td>
            <td>{$role['end_date']}</td>
        </tr>";
        }

        $researchHtml = "";
        foreach ($researches as $r) {
            $researchHtml .= "<tr>
            <td>{$r['title']}</td>
            <td>{$r['type']}</td>
            <td>{$r['description']}</td>
            <td>{$r['start_date']}</td>
            <td>{$r['end_date']}</td>
            <td>".($r['link'] ? "<a href='{$r['link']}' target='_blank'>Link</a>" : "-")."</td>
            <td>".($r['file_path'] ? "<a href='{$r['file_path']}' target='_blank'>File</a>" : "-")."</td>
            <td>".($r['active'] ? "Yes" : "No")."</td>
        </tr>";
        }
        $profile_tag=($emp['profile'] ? "<img src='{$emp['profile']}' width='100'>" : "No Image");

        $content = <<<HTML
<div class="profile-container">
    <h2>{$emp['name']}</h2>
    <div class="w-full flex flex-row items-center justify-center">$profile_tag</div>
    <p><strong>Title:</strong> {$emp['title']}</p>
    <p><strong>Email:</strong> {$emp['email']}</p>
    <p><strong>Phone:</strong> {$emp['phone']}</p>
    <p><strong>Qualification:</strong> {$emp['qualification']}</p>
    <p><strong>Entry Year:</strong> {$emp['entry_year']}</p>
    <p><strong>Department:</strong> {$emp['department_name']}</p>   

    <h3>Roles</h3>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Role Name</th>
                <th>Department</th>             
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            $rolesHtml
        </tbody>
    </table>

    <h3>Researches / Publications / Projects</h3>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Link</th>
                <th>File</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            $researchHtml
        </tbody>
    </table>
</div>
HTML;

        $head=$this->profStyle();
    MainLayout::render($content,$head);
}
    public function active($params)
    {
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            echo Resource::getErrorPage("Not authorized");
            return;
        }
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = new Database();

                // Get current active value
                $row = $db->select("SELECT active FROM employee WHERE id = {$id} LIMIT 1");
                if (!$row) {
                    echo Resource::getErrorPage("Employee not found");
                    return;
                }

                $current = intval($row[0]['active']);
                $new = $current === 1 ? 0 : 1;

                // Update with toggled value
                if ($db->update("employee", ["active" => $new], ["id" => $id])) {
                    echo Resource::getSuccessPage("Employee status updated successfully!");
                } else {
                    echo Resource::getErrorPage("Failed to update employee status");
                }
            } catch (\Exception $exception) {
                echo Resource::getErrorPage($exception->getMessage());
            }
        } else {
            echo Resource::getErrorPage("Invalid Request");
        }
    }

    public function get_employee($params)
    {
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = new Database();

                // Fetch employee by ID
                $employee = $db->select("SELECT * FROM employee WHERE id = {$id} LIMIT 1");
                $roles = $db->select("SELECT * FROM employee_role WHERE employee_id = {$id}");

                if ($employee && count($employee) > 0) {
                    // Return JSON response
                    header('Content-Type: application/json');
                    echo json_encode([
                        "status" => "success",
                        "data" => $employee[0],
                        "roles" =>$roles
                    ]);
                } else {
                    // Employee not found
                    echo json_encode([
                        "status" => "error",
                        "message" => "Employee not found"
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
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            echo json_encode(['status' => "error", 'message' => "Not Authorized"]);
            return;
        }

        // Sanitize inputs
        $name          = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $title         = htmlspecialchars(trim($_POST['title'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email         = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $phone         = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $qualification = htmlspecialchars(trim($_POST['qualification'] ?? ''), ENT_QUOTES, 'UTF-8');
        $entry_year    = intval($_POST['entry_year'] ?? 0);
        $active        = intval($_POST['active'] ?? 1);

        $imagePath = null;

        // Handle image upload securely
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];

            // 1. Check file size (max 1MB)
            if ($fileSize > 1024 * 1024) {
                echo json_encode(['status' => "error", 'message' => "Image size must be less than 1MB"]);
                return;
            }

            // 2. Validate image type
            $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $fileMime = mime_content_type($fileTmp);

            if (!in_array($fileMime, $allowedMime)) {
                echo json_encode(['status' => "error", 'message' => "Only JPG, PNG, GIF, or WEBP images are allowed"]);
                return;
            }

            // 3. Generate safe filename (prefix employee name + unique ID)
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($name));
            $uniqueId = uniqid();
            $fileName = $safeName . "_" . $uniqueId . ".webp";

            // 4. Destination folder
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/employees/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $targetFile = $uploadDir . $fileName;

            // 5. Convert to WebP (low quality)
            switch ($fileMime) {
                case 'image/jpeg':
                    $img = imagecreatefromjpeg($fileTmp);
                    break;
                case 'image/png':
                    $img = imagecreatefrompng($fileTmp);
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    break;
                case 'image/gif':
                    $img = imagecreatefromgif($fileTmp);
                    break;
                case 'image/webp':
                    $img = imagecreatefromwebp($fileTmp);
                    break;
                default:
                    $img = null;
            }

            if ($img) {
                if (!imageistruecolor($img)) {
                    $trueColor = imagecreatetruecolor(imagesx($img), imagesy($img));
                    imagecopy($trueColor, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
                    imagedestroy($img);
                    $img = $trueColor;
                }
                imagewebp($img, $targetFile, 40); // 40 = low quality
                imagedestroy($img);
                $imagePath = "/images/employees/" . $fileName; // relative path for DB
            } else {
                echo json_encode(['status' => "error", 'message' => "Failed to process image"]);
                return;
            }
        }

        // Insert into database
        $db = new Database();
        $user_id=(new Authentication())->get_authenticated_user()->getId();
        $employeeData = [
            'name'          => $name,
            'title'         => $title,
            'email'         => $email,
            'phone'         => $phone,
            'profile'         => $imagePath,
            'qualification' => $qualification,
            'entry_year'    => $entry_year,
            'user_id'    => $user_id,
            'active'        => $active
        ];

        if ($db->insert("employee", $employeeData)) {
            echo json_encode(['status' => "success", 'message' => "Employee added successfully"]);
        } else {
            echo json_encode(['status' => "error", 'message' => "Failed to add employee"]);
        }
    }
    public function add_role()
    {
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            echo json_encode(['status' => "error", 'message' => "Not Authorized"]);
            return;
        }

        // Sanitize inputs
        $employee_id    = intval($_POST['employee_id'] ?? 0);
        $role_id        = intval($_POST['role_title'] ?? 0);
        $unit_id        = intval($_POST['unit'] ?? 0);
        $department_id  = intval($_POST['department_id'] ?? 0);
        $start_date     = htmlspecialchars(trim($_POST['start_date'] ?? ''), ENT_QUOTES, 'UTF-8');
        $title     = htmlspecialchars(trim($_POST['role_name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $end_date       = htmlspecialchars(trim($_POST['end_date'] ?? ''), ENT_QUOTES, 'UTF-8');
        $active         = intval($_POST['active'] ?? 1);

        // Validate required fields
        if (!$employee_id || !$role_id || !$start_date) {
            echo json_encode(['status' => "error", 'message' => "Employee, Role, and Start Date are required"]);
            return;
        }

        // Prepare data for insertion
        $db = new Database();
        $user_id = $auth->get_authenticated_user()->getId();
        $roleData = [
            'employee_id'   => $employee_id,
            'role_name'    => $title,
            'role_group_id'       => $role_id,
            'unit_id'       => $unit_id ?: null,
            'department_id' => $department_id ?: null,
            'start_date'    => $start_date,
            'end_date'      => $end_date ?: null,
            'user_id'       => $user_id,
            'active'        => $active
        ];

        // Insert into employee_role table
        if ($db->insert("employee_role", $roleData)) {
            echo json_encode(['status' => "success", 'message' => "Role added successfully"]);
        } else {
            echo json_encode(['status' => "error", 'message' => "Failed to add role"]);
        }
    }

    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("select * from employee order by name"));
    }
}