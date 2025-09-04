<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Department
{
    public function list()
    {
        $query = "SELECT d.*, f.name as faculty_name 
              FROM department d 
              LEFT JOIN faculty f ON d.faculty_id = f.id";
        $departments = (new Database())->select($query);
        $tr = "";

        if (sizeof($departments) > 0) {
            foreach ($departments as $dept) {
                $facultyName = $dept['faculty_name'] ?? 'N/A';
                $tr .= "<tr>
<td>{$dept['name']}</td>
<td>{$facultyName}</td>
<td>{$dept['description']}</td>
<td>
<button class='btn btn-complete' onclick='editDepartment({$dept['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteResource(\"department\",{$dept['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewDepartment(\"department\",{$dept['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='4'>No departments found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addDepartment()">Add Department</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Faculty</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        $tr
    </table>
</div>
HTML;

        MainLayout::render($content);
    }
    public function add()
    {
        $auth = new Authentication();

        // Ensure user is logged in and is admin
        if (!$auth->is_admin()) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Not authorized to perform this action.'
            ]);
            return;
        }

        // Sanitize inputs
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $faculty_id = isset($_POST['faculty_id']) ? intval($_POST['faculty_id']) : null;
        $user_id = $auth->get_authenticated_user()->getId();

        if ($name === '' || !$faculty_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Department name and faculty are required.'
            ]);
            return;
        }

        // Allow basic HTML tags in description but strip scripts
        /*$allowed_tags = '<p><br><b><i><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6>';
        $description = strip_tags($description, $allowed_tags);*/

        $description=htmlspecialchars($description);

        $db = new Database();

        // Check for duplicate department in the same faculty
        $exists = $db->fetch("SELECT id FROM department WHERE name = ? AND faculty_id = ?", [$name, $faculty_id]);
        if ($exists) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Department with this name already exists in the selected faculty.'
            ]);
            return;
        }

        // Insert into database
        $inserted = $db->insert("department", [
            'name' => $name,
            'description' => $description,
            'faculty_id' => $faculty_id,
            'user_id' => $user_id,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        if ($inserted) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Department added successfully!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add department. Please try again.'
            ]);
        }
    }

    public function get_simple()
    {
        $db=new Database();
        $fcts=$db->select("select id,name from department");
        if (sizeof($fcts)>0){
            echo json_encode(["status"=>"success","data"=>$fcts]);
        }else{
            echo json_encode(['status'=>"error","message"=>"No data found"]);
        }
    }
    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("select * from department"));
    }

}