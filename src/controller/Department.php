<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Department
{
    public function list()
    {
        Authentication::require_roles(['admin','hod']);
        $query = "SELECT d.*, f.name as faculty_name 
              FROM department d 
              LEFT JOIN faculty f ON d.faculty_id = f.id order by category,name";
        $departments = (new Database())->select($query);
        $tr = "";

        if (sizeof($departments) > 0) {
            foreach ($departments as $dept) {
                $facultyName = $dept['faculty_name'] ?? 'N/A';
                $tr .= "<tr>
<td>{$dept['name']} <span class='text-blue-500'><b>({$dept['category']})</b></span></td>
<td>{$facultyName}</td>
<td>
<button class='btn btn-complete' onclick='editDepartment({$dept['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteResource(\"department\",{$dept['id']})'>Delete <i class='bi bi-trash'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='4'>No departments found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addDepartment()">Add Department/Unit</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Faculty</th>              
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
        Authentication::require_roles(['admin','hod']);

        // Sanitize inputs
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        $faculty_id = isset($_POST['faculty_id']) ? intval($_POST['faculty_id']) : null;
        $user_id = Authentication::user()->getId();

        if ($name === '' || !$faculty_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Department name and faculty are required.'
            ]);
            return;
        }

        $description = htmlspecialchars($description);

        $db = new Database();

        if ($id > 0) {
            // 🔹 Update existing department
            $exists = $db->fetch("SELECT id FROM department WHERE id = ?", [$id]);
            if (!$exists) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Department not found.'
                ]);
                return;
            }

            $updated = $db->update("department", [
                'name' => $name,
                'description' => $description,
                'category' => $category,
                'faculty_id' => $faculty_id,
                'user_id' => $user_id,
                'updated_at' => date("Y-m-d H:i:s")
            ], ["id"=>$id]);

            if ($updated) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Department updated successfully!'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No changes made or update failed.'
                ]);
            }

        } else {
            // 🔹 Add new department
            $exists = $db->fetch("SELECT id FROM department WHERE name = ? AND faculty_id = ?", [$name, $faculty_id]);
            if ($exists) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Department with this name already exists in the selected faculty.'
                ]);
                return;
            }

            $inserted = $db->insert("department", [
                'name' => $name,
                'description' => $description,
                'category' => $category,
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
    }


    public function get_simple()
    {
        header("Content-Type: application/json");
        $db=new Database();
        $fcts=$db->select("select id,name from department where category='department' order by name");
        if (sizeof($fcts)>0){
            echo json_encode(["status"=>"success","data"=>$fcts]);
        }else{
            echo json_encode(['status'=>"error","message"=>"No data found"]);
        }
    }
    public function departments()
    {
        header("Content-Type: application/json");
        $db=new Database();
        $fcts=$db->select("select id,name from department where category='department' order by name");
        if (sizeof($fcts)>0){
            echo json_encode(["status"=>"success","data"=>$fcts]);
        }else{
            echo json_encode(['status'=>"error","message"=>"No data found"]);
        }
    }
    public function units()
    {
        header("Content-Type: application/json");
        $db=new Database();
        $fcts=$db->select("select id,name from department where category='unit' order by name");
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
        echo json_encode((new Database())->select("select * from department order by name"));
    }
    public function department($params)
    {
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = new Database();

                // Fetch employee by ID
                $employee = $db->select("SELECT * FROM department WHERE id = {$id} LIMIT 1");

                if ($employee && count($employee) > 0) {
                    // Return JSON response
                    header('Content-Type: application/json');
                    echo json_encode([
                        "status" => "success",
                        "data" => $employee[0],
                    ]);
                } else {
                    // Employee not found
                    echo json_encode([
                        "status" => "error",
                        "message" => "not found"
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

}