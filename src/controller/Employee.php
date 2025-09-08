<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\utils\Resource;
use Solobea\Dashboard\view\MainLayout;

class Employee
{
    public function list()
    {
        $query = "SELECT * FROM employee order by name";
        $employees = (new Database())->select($query);
        $tr = "";

        if (sizeof($employees) > 0) {
            foreach ($employees as $emp) {
                $tr .= "<tr>
<td>{$emp['name']}</td>
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

                if ($employee && count($employee) > 0) {
                    // Return JSON response
                    header('Content-Type: application/json');
                    echo json_encode([
                        "status" => "success",
                        "data" => $employee[0]
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
        $title        = intval($_POST['role_name'] ?? 0);
        $unit_id        = intval($_POST['unit'] ?? 0);
        $department_id  = intval($_POST['department_id'] ?? 0);
        $start_date     = htmlspecialchars(trim($_POST['start_date'] ?? ''), ENT_QUOTES, 'UTF-8');
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