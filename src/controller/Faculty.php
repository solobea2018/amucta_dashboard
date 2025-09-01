<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Faculty
{
    public function list()
    {
        $query = "SELECT * FROM faculty";
        $faculties = (new Database())->select($query);
        $tr = "";

        if (sizeof($faculties) > 0) {
            foreach ($faculties as $faculty) {
                $tr .= "<tr>
<td>{$faculty['name']}</td>
<td>{$faculty['description']}</td>
<td>
<button class='btn btn-complete' onclick='editFaculty({$faculty['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteFaculty({$faculty['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewFaculty({$faculty['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='3'>No faculties found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addFaculty()">Add Faculty</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
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

        // Sanitize and validate inputs
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'])) : '';
        $user_id = $auth->get_authenticated_user()->getId();

        if ($name === '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Faculty name is required.'
            ]);
            return;
        }

        // Clean XSS
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

        $db = new Database();

        // Check duplicate faculty name
        $exists = $db->fetch("SELECT id FROM faculty WHERE name = ?", [$name]);
        if ($exists) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Faculty with this name already exists.'
            ]);
            return;
        }

        // Insert into database
        $inserted = $db->insert("faculty", [
            'name' => $name,
            'description' => $description,
            'user_id' => $user_id,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        if ($inserted) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Faculty added successfully!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add faculty. Please try again.'
            ]);
        }
    }

}