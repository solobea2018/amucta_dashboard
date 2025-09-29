<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;
use Solobea\Helpers\data\Sanitizer;
use Solobea\Helpers\visitor\VisitorData;

class Program
{
    public function list()
    {
        $query="select * from program order by level_id,department_id";
        $programs=(new Database())->select($query);
        $tr="";
        if (sizeof($programs)>0){
            foreach ($programs as $program) {
                $tr.="<tr>
<td>{$program['short_name']}</td>
<td>{$program['name']}</td>
<td>
<button class='btn btn-primary'>Active<i class='bi bi-eye'></i></button>
<button class='btn btn-danger' onclick='deleteResource(\"program\",{$program['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-complete' onclick='editProgram({$program['id']})'>Edit <i class='bi bi-pencil'></i></button>
</td>
</tr>";
            }
        }else{
            $tr = "<tr><td colspan='3'>No programs found</td></tr>";
        }
        $content=<<<content
<div class="flex flex-col">
    <div class="w-full"> <button class="btn btn-complete" onclick="addProgram()">Add</button> </div>
<table class="solobea-table">
<thead>
    <td>Short name</td>
    <td>Full Name</td>
    <td>Actions</td>
</thead>
<tbody>$tr</tbody>
</table>
<div>
content;

        MainLayout::render($content);
    }
    public function add()
    {
        // Required fields check
        $required = [
            'name', 'short_name', 'intakes', 'duration',
            'capacity', 'accreditation_year',
            'faculty_id', 'department_id', 'level_id',
            'description', 'content', 'user_id'
        ];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                http_response_code(400);
                echo "Required field missing: $field";
                exit();
            }
        }

        // Sanitize inputs
        $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
        $short_name = htmlspecialchars(trim($_POST['short_name']), ENT_QUOTES, 'UTF-8');
        $intakes = (int) $_POST['intakes'];
        $duration = htmlspecialchars(trim($_POST['duration']), ENT_QUOTES, 'UTF-8');
        $capacity = (float) $_POST['capacity'];
        $accreditation_year = htmlspecialchars(trim($_POST['accreditation_year']), ENT_QUOTES, 'UTF-8');
        $faculty_id = (int) $_POST['faculty_id'];
        $department_id = (int) $_POST['department_id'];
        $level_id = (int) $_POST['level_id'];
        $description = htmlspecialchars(trim($_POST['description']??""));
        $content = htmlspecialchars(trim($_POST['content']??""));
        $fees = htmlspecialchars(trim($_POST['fees']??""));
        $requirements = htmlspecialchars(trim($_POST['requirements']??""));
        $user_id = (int) $_POST['user_id'];

        $db = new Database();

        // Prevent duplicates (optional: check by name + faculty)
        if ($db->find_program_by_name($name, $faculty_id)) {
            http_response_code(409);
            echo "Program already exists in this faculty";
            exit();
        }

        // Create new Program object
        $program = new \Solobea\Dashboard\model\Program();
        $program->setName($name);
        $program->setShortName($short_name);
        $program->setIntakes($intakes);
        $program->setDuration($duration);
        $program->setCapacity($capacity);
        $program->setAccreditationYear($accreditation_year);
        $program->setFacultyId($faculty_id);
        $program->setDepartmentId($department_id);
        $program->setLevelId($level_id);
        $program->setDescription($description);
        $program->setContent($content);
        $program->setFees($fees);
        $program->setRequirements($requirements);
        $program->setCreatedBy($user_id);
        $program->setCreatedAt(date('Y-m-d H:i:s'));

        // Save program in database
        if ($db->save_program($program)) {
            http_response_code(200);
            echo "Program created successfully";
        } else {
            http_response_code(500);
            echo "Failed to create program";
        }
    }
    public function update()
    {
        $auth=new Authentication();
        if (!$auth->is_authenticated()){
            http_response_code(403);
            echo "Not authorized";
            exit();
        }
        $db = new Database();

        // Required fields
        $required = [
            'id', 'name', 'short_name', 'intakes', 'duration',
            'capacity', 'accreditation_year',
            'faculty_id', 'department_id', 'level_id',
            'description', 'content'
        ];

        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                http_response_code(400);
                echo "Required field missing: $field";
                exit();
            }
        }

        // Sanitize inputs
        $id = (int) $_POST['id'];
        $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
        $short_name = htmlspecialchars(trim($_POST['short_name']), ENT_QUOTES, 'UTF-8');
        $intakes = (int) $_POST['intakes'];
        $duration = htmlspecialchars(trim($_POST['duration']), ENT_QUOTES, 'UTF-8');
        $capacity = (float) $_POST['capacity'];
        $accreditation_year = htmlspecialchars(trim($_POST['accreditation_year']), ENT_QUOTES, 'UTF-8');
        $faculty_id = (int) $_POST['faculty_id'];
        $department_id = (int) $_POST['department_id'];
        $level_id = (int) $_POST['level_id'];
        $description = htmlspecialchars(trim($_POST['description'] ?? ""));
        $content = htmlspecialchars(trim($_POST['content'] ?? ""));
        $fees = htmlspecialchars(trim($_POST['fees'] ?? ""));
        $requirements = htmlspecialchars(trim($_POST['requirements'] ?? ""));
        $user_id = $auth->get_authenticated_user()->getId();


        // Update program object
        $program = new \Solobea\Dashboard\model\Program();
        $program->setId($id);
        $program->setName($name);
        $program->setShortName($short_name);
        $program->setIntakes($intakes);
        $program->setDuration($duration);
        $program->setCapacity($capacity);
        $program->setAccreditationYear($accreditation_year);
        $program->setFacultyId($faculty_id);
        $program->setDepartmentId($department_id);
        $program->setLevelId($level_id);
        $program->setDescription($description);
        $program->setContent($content);
        $program->setFees($fees);
        $program->setRequirements($requirements);
        $program->setUpdatedBy($user_id);
        $program->setUpdatedAt(date('Y-m-d H:i:s'));

        // Save changes
        if ($db->update_program($program)) {
            http_response_code(200);
            echo "Program updated successfully";
        } else {
            http_response_code(500);
            echo "Failed to update program";
        }
    }


    public static function getCount()
    {
        $db=new Database();
        return $db->selectOne("Select count(*) as count from program")['count'];
    }
    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("select * from program"));
    }
    public function data($params)
    {
        header('Content-Type: application/json');
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = new Database();
                $employee = $db->select("SELECT * from program where id= {$id} LIMIT 1");
                $lvs = $db->select("SELECT id,name FROM level");
                $deps = $db->select("SELECT id,name FROM department");
                $faculty = $db->select("SELECT id,name FROM faculty");

                if ($employee && count($employee) > 0) {
                    // Return JSON response
                    $employee[0]['description'] = Sanitizer::clean_for_json($employee[0]['description']);
                    $employee[0]['content'] = Sanitizer::clean_for_json($employee[0]['content']);
                    $employee[0]['fees'] = Sanitizer::clean_for_json($employee[0]['fees']);
                    $employee[0]['entry_requirements'] = Sanitizer::clean_for_json($employee[0]['entry_requirements']);

                    echo json_encode([
                        "status" => "success",
                        "data" => $employee[0],
                        "levels" => $lvs,
                        "departments"=>$deps,
                        "faculties"=>$faculty
                    ]);
                } else {
                    // Employee not found
                    echo json_encode([
                        "status" => "error",
                        "message" => "Programs not found"
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