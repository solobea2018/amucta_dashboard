<?php


namespace Solobea\Dashboard\controller;


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

}