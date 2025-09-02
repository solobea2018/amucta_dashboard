<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class program
{
    public function list()
    {
        $query="select * from program";
        $programs=(new Database())->select($query);
        $tr="";
        if (sizeof($programs)>0){
            foreach ($programs as $program) {
                $tr.="<tr>
<td>{$program['short_name']}</td>
<td>{$program['name']}</td>
<td>
<button class='btn btn-primary'>Delete <i class='bi bi-eye'></i></button>
<button class='btn btn-danger' onclick='deleteResource(\"program\",{$program['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-complete'>Edit <i class='bi bi-pencil'></i></button>
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
        $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
        $content = htmlspecialchars(trim($_POST['content']), ENT_QUOTES, 'UTF-8');
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

}