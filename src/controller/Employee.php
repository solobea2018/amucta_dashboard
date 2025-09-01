<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Employee
{
    public function list()
    {
        $query = "SELECT * FROM employee";
        $employees = (new Database())->select($query);
        $tr = "";

        if (sizeof($employees) > 0) {
            foreach ($employees as $emp) {
                $tr .= "<tr>
<td>{$emp['name']}</td>
<td>{$emp['title']}</td>
<td>{$emp['email']}</td>
<td>{$emp['phone']}</td>
<td>{$emp['qualification']}</td>
<td>{$emp['entry_year']}</td>
<td>" . ($emp['active'] ? 'Yes' : 'No') . "</td>
<td>
<button class='btn btn-complete' onclick='editEmployee({$emp['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteEmployee({$emp['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewEmployee({$emp['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='8'>No employees found</td></tr>";
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
                <th>Email</th>
                <th>Phone</th>
                <th>Qualification</th>
                <th>Entry Year</th>
                <th>Active</th>
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