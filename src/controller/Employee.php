<?php


namespace Solobea\Dashboard\controller;


use JetBrains\PhpStorm\NoReturn;
use PhpOffice\PhpSpreadsheet\Calculation\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\utils\Resource;
use Solobea\Dashboard\view\Components;
use Solobea\Dashboard\view\MainLayout;
use Solobea\Helpers\data\Sanitizer;

class Employee
{
    public static function getCount()
    {
        $db=new Database();
        return $db->selectOne("Select count(*) as count from employee")['count'];
    }

    public function list()
    {
        $search = isset($_GET['q']) ? trim($_GET['q']) : "";

        $db=Database::get_instance();
        if ($search !== "") {
            $employees = $db->select_prepared(
                "SELECT * FROM employee
             WHERE name LIKE ?
                OR title LIKE ?
                OR phone LIKE ?
             ORDER BY name",
                [
                    "%$search%",
                    "%$search%",
                    "%$search%"
                ]
            );
        } else {
            $employees = $db->select(
                "SELECT * FROM employee where active=1 ORDER BY name limit 100"
            );
        }
        $tr = "";

        if (sizeof($employees) > 0) {
            foreach ($employees as $emp) {
                $staff_id=$emp['staff_id'];
                $id=$emp['id'];
                $prefix = $emp['prefix'];
                $tr .= "<tr>
<td><a class='text-blue-500' href='/employee/profile/{$emp['id']}'>{$prefix} {$emp['name']}</a></td>
<td>
<table class='table-borderless'><tr><td>Title: {$emp['title']}</td>
<td>Phone: {$emp['phone']}</td></tr>
<tr><td>Email: {$emp['email']}</td>
<td>Year:  {$emp['entry_year']}<br></td></tr>
</table>
Is active? <a class='btn btn-mark-read' href='/employee/active/{$emp['id']}'>" . ($emp['active'] ? 'Yes' : 'No') . "</a>
</td>
<td>
<button class='btn btn-complete' onclick='editEmployee({$emp['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-mark-read' onclick='addEmployeeRole({$emp['id']})'>Role<i class='bi bi-pencil'></i></button>
<button class='btn btn-primary' onclick='viewEmployee({$emp['id']})'>View <i class='bi bi-eye'></i></button>
<form class='' action='/employee/staff_id' onsubmit='sendFormSweet(this,event)'>
<input type='text' class='form-control' name='staff_id' value='{$staff_id}'>
<input type='text' name='prefix' class='form-control' value='{$prefix}'>
<input type='hidden' name='id' value='{$id}'>
<button type='submit' class='btn btn-amucta'>Update</button>
</form>
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
        <a href="/employee/export" class="btn btn-primary">Export Employees</a>
        <a href="/employee/import" class="btn btn-primary">Import Employees</a>
    </div>
    <form method="get" class="mb-4">
        <div class="flex gap-2">
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Search by name, title, or phone"
                   value="{$search}">
            <button class="btn btn-secondary">Search</button>
        </div>
    </form>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Detail</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>$tr</tbody>
    </table>
</div>
HTML;

        MainLayout::render($content);
    }

    public function staff_id()
    {
        Authentication::require_roles(['admin','hro','manager']);
        if (isset($_POST['id']) && isset($_POST['staff_id'])){
            $id=intval($_POST['id']);
            $staff=Sanitizer::sanitize($_POST['staff_id']);
            $prefix=Sanitizer::sanitize($_POST['prefix']);
            $db=Database::get_instance();
            if (!$db->exists('employee',['id'=>$id])){
                echo json_encode(['status'=>'error','message'=>'Employee not exists']);
            }else{
               if ($db->update('employee',['staff_id'=>$staff,'prefix'=>$prefix],['id'=>$id])){
                   echo json_encode(['status'=>'success','message'=>'Employee staffId updated successfully']);
               } else{
                   http_response_code(500);
                    echo json_encode(['status'=>'error','message'=>'Server error']);
               }
            }
        }else{
            echo json_encode(['status'=>'error','message'=>'Invalid data']);
        }
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
        $researches = $db->select("SELECT ar.title, ar.publication_type as type, year, link
                               FROM amucta_research ar 
join publication_assignments pa on ar.id = pa.publication_id
join employee e on e.id = pa.employee_id
                               WHERE e.id = {$employee_id}
                               ORDER BY year DESC");

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
            <td><table class='table-borderless'>
            <tr><td>Publication type: </td><td>{$r['type']}</td></tr>
            <tr><td>Title: </td><td>{$r['title']}</td></tr>
            <tr><td>Link: </td><td><a href='{$r['link']}' class='text-blue-400 break-link'>{$r['link']}</a></td></tr>
</table> </td>
            <td>{$r['year']}</td>
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
                <th>Detail</th>
                <th>Year</th>               
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

    public function data($params)
    {
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = new Database();

                // Fetch employee by ID
                $employee = $db->select("SELECT e.*,d.name as department_name FROM employee e left join department d on d.id = e.department_id WHERE e.id = {$id} LIMIT 1");
                $roles = $db->select("SELECT * FROM employee_role WHERE employee_id = {$id}");
                $deps = $db->select("SELECT id,name FROM department");
                $res = $db->select("SELECT id,title,created_at FROM employee_research where employee_id={$id}");

                if ($employee && count($employee) > 0) {
                    // Return JSON response
                    header('Content-Type: application/json');
                    echo json_encode([
                        "status" => "success",
                        "data" => $employee[0],
                        "roles" =>$roles,
                        "departments"=>$deps,
                        "researches"=>$res
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
    public function get_simple()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        $employees = (new Database())->select("select id,name from employee order by name");
        echo json_encode(['status'=>'success','employees'=>$employees]);
    }
    public function update()
    {
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            echo json_encode(['status' => "error", 'message' => "Not Authorized"]);
            return;
        }

        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status' => "error", 'message' => "Invalid employee ID"]);
            return;
        }

        // Sanitize inputs
        $name          = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $title         = htmlspecialchars(trim($_POST['title'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email         = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $phone         = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $branch        = htmlspecialchars(trim($_POST['branch'] ?? ''), ENT_QUOTES, 'UTF-8');
        $qualification = htmlspecialchars(trim($_POST['qualification'] ?? ''), ENT_QUOTES, 'UTF-8');
        $department_id = intval($_POST['department_id'] ?? 0);

        $db = new Database();

        // Fetch current employee (to keep old files if no new upload)
        $current = $db->fetch("SELECT profile, cv_url FROM employee WHERE id = ?", [$id]);
        if (!$current) {
            echo json_encode(['status' => "error", 'message' => "Employee not found"]);
            return;
        }

        $profilePath = $current['profile'];
        $cvPath      = $current['cv_url'];

        // Handle new profile upload
        if (!empty($_FILES['profile']['name']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['profile']['tmp_name'];
            $fileMime = mime_content_type($fileTmp);
            $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

            if (in_array($fileMime, $allowedMime)) {
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($name));
                $uniqueId = uniqid();
                $fileName = $safeName . "_" . $uniqueId . ".webp";

                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/employees/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $targetFile = $uploadDir . $fileName;

                switch ($fileMime) {
                    case 'image/jpeg': $img = imagecreatefromjpeg($fileTmp); break;
                    case 'image/png': $img = imagecreatefrompng($fileTmp);
                        imagepalettetotruecolor($img);
                        imagealphablending($img, true);
                        imagesavealpha($img, true);
                        break;
                    case 'image/gif':  $img = imagecreatefromgif($fileTmp); break;
                    case 'image/webp': $img = imagecreatefromwebp($fileTmp); break;
                    default: $img = null;
                }

                if ($img) {
                    if (!imageistruecolor($img)) {
                        $trueColor = imagecreatetruecolor(imagesx($img), imagesy($img));
                        imagecopy($trueColor, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
                        imagedestroy($img);
                        $img = $trueColor;
                    }
                    imagewebp($img, $targetFile, 40);
                    imagedestroy($img);

                    $profilePath = "/images/employees/" . $fileName;
                }
            }
        }

        // Handle CV upload (PDF or DOCX assumed)
        if (!empty($_FILES['cv']['name']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
            $cvTmp  = $_FILES['cv']['tmp_name'];
            $cvMime = mime_content_type($cvTmp);
            $allowedCv = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

            if (in_array($cvMime, $allowedCv)) {
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($name));
                $uniqueId = uniqid();
                $ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
                $cvName = $safeName . "_" . $uniqueId . "." . $ext;

                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/files/cv/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $targetCv = $uploadDir . $cvName;
                if (move_uploaded_file($cvTmp, $targetCv)) {
                    $cvPath = "/files/cv/" . $cvName;
                }
            }
        }

        // Prepare update data
        $employeeData = [
            'name'          => $name,
            'title'         => $title,
            'email'         => $email,
            'phone'         => $phone,
            'branch'        => $branch,
            'qualification' => $qualification,
            'department_id' => $department_id,
            'profile'       => $profilePath,
            'cv_url'            => $cvPath,
            'updated_at'    => date("Y-m-d H:i:s")
        ];

        // Run update
        $updated = $db->update("employee", $employeeData, ["id"=>$id]);

        if ($updated) {
            echo json_encode(['status' => "success", 'message' => "Employee updated successfully"]);
        } else {
            echo json_encode(['status' => "error", 'message' => "No changes made or update failed"]);
        }
    }

    public function import()
    {
        Authentication::require_roles(['admin','hro','manager']);
        $db = Database::get_instance();

        if (isset($_FILES['template']) && $_FILES['template']['error'] == 0) {

            $file = $_FILES['template']['tmp_name'];

            try {

                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // remove header
                unset($rows[0]);

                foreach ($rows as $row) {

                    list(
                        $staff_id,
                        $prefix,
                        $name,
                        $title,
                        $email,
                        $phone,
                        $qualification,
                        $entry_year,
                        $branch,
                        $gender
                        ) = $row;

                    if (empty($staff_id) || empty($name)) {
                        continue;
                    }

                    if ($db->exists('employee',['staff_id'=>$staff_id])) {

                        // Update
                        $db->update('employee',
                        ['prefix'=>$prefix,'gender'=>$gender,'title'=>$title,'email'=>$email,'phone'=>$phone,'highest_qualification'=>$qualification,'entry_year'=>$entry_year,'branch'=>$branch],['staff_id'=>$staff_id]);

                    } else {

                        // Insert
                        $db->insert('employee',[
                            'email'=>$email,
                            'prefix'=>$prefix,
                            'title'=>$title,
                            'phone'=>$phone,
                            'highest_qualification'=>$qualification,
                            'entry_year'=>$entry_year,
                            'branch'=>$branch,
                            'gender'=>$gender
                        ]);

                    }
                }

                echo "<script>Swal.fire('Success','Employees imported successfully','success')</script>";

            } catch (Exception $e) {

                echo "<script>Swal.fire('Error','".$e->getMessage()."','error')</script>";

            }
            exit();
        }

        $content = <<<CONTENT
<div>
<div>
    <a href="/employee/template" class="btn btn-blue">Download Template</a>
</div>

<form method="post" enctype="multipart/form-data" onsubmit="sendFormSweet(this,event)" action="/employee/import">

<div class="form-group">
<label>Excel file only</label>
<input type="file" class="form-control" name="template" accept=".xlsx,.xls" required>
</div>

<div class="form-group">
<button class="btn btn-primary">Import</button>
</div>

</form>
</div>
CONTENT;

        $title = "Import employee";
        $head = "";
        MainLayout::render($content,$head,$title);
    }

    #[NoReturn] public function template()
    {
        Authentication::require_roles(['admin','hro','manager']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Template headers
        $headers = [
            'staff_id',
            'prefix',
            'name',
            'title',
            'email',
            'phone',
            'highest_qualification',
            'entry_year',
            'branch',
            'gender'
        ];

        // Write header row
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col.'1', $header);
            $sheet->getStyle($col.'1')->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Example row
        $sheet->setCellValue('A2', 'STF001');
        $sheet->setCellValue('B2', 'Mr.');
        $sheet->setCellValue('C2', 'John Doe');
        $sheet->setCellValue('D2', 'Lecturer');
        $sheet->setCellValue('E2', 'john@example.com');
        $sheet->setCellValue('F2', '0712345678');
        $sheet->setCellValue('G2', 'PhD');
        $sheet->setCellValue('H2', '2024');
        $sheet->setCellValue('I2', '');
        $sheet->setCellValue('J2', 'M');

        // File name
        $filename = "employee_import_template.xlsx";

        // Download headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    #[NoReturn]
    public function export()
    {
        $db = Database::get_instance();

        $query = "SELECT id, name,gender,prefix,highest_qualification, title, email, phone, qualification, entry_year, department_id, branch, staff_id 
              FROM employee 
              WHERE active=1";

        $employees = $db->select($query);

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header row
        $sheet->setCellValue('A1', 'Staff ID');
        $sheet->setCellValue('B1', 'Prefix');
        $sheet->setCellValue('C1', 'Name');
        $sheet->setCellValue('D1', 'Title');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'Phone');
        $sheet->setCellValue('G1', 'Qualification');
        $sheet->setCellValue('H1', 'Entry Year');
        $sheet->setCellValue('I1', 'Branch');
        $sheet->setCellValue('J1', 'Gender');

        // Fill data
        $row = 2;
        foreach ($employees as $emp) {
            $sheet->setCellValue('A'.$row, $emp['staff_id']);
            $sheet->setCellValue('B'.$row, $emp['prefix']);
            $sheet->setCellValue('C'.$row, $emp['name']);
            $sheet->setCellValue('D'.$row, $emp['title']);
            $sheet->setCellValue('E'.$row, $emp['email']);
            $sheet->setCellValue('F'.$row, $emp['phone']);
            $sheet->setCellValue('G'.$row, $emp['highest_qualification']);
            $sheet->setCellValue('H'.$row, $emp['entry_year']);
            $sheet->setCellValue('I'.$row, $emp['branch']);
            $sheet->setCellValue('J'.$row, $emp['gender']);
            $row++;
        }

        // Auto size columns
        foreach(range('A','K') as $col){
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // File name
        $filename = "employees_" . date('Ymd_His') . ".xlsx";

        // Headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Write file
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}