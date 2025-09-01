<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Level
{
    public function list()
    {
        $query = "SELECT * FROM level";
        $levels = (new Database())->select($query);
        $tr = "";

        if (sizeof($levels) > 0) {
            foreach ($levels as $level) {
                $tr .= "<tr>
<td>{$level['name']}</td>
<td>{$level['description']}</td>
<td>
<button class='btn btn-complete' onclick='editLevel({$level['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteLevel({$level['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewLevel({$level['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='3'>No levels found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addLevel()">Add Level</button>
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