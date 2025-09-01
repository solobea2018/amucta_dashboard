<?php


namespace Solobea\Dashboard\controller;


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
<button class='btn btn-danger' onclick='deleteDepartment({$dept['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewDepartment({$dept['id']})'>View <i class='bi bi-eye'></i></button>
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

}