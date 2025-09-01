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
<button class='btn btn-danger'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-complete'>Edit <i class='bi bi-pencil'></i></button>
</td>
</tr>";
            }
        }else{
            $tr="No programs found";
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
$tr
</table>
<div>
content;

        MainLayout::render($content);
    }
}