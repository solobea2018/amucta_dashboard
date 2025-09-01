<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Gallery
{
    public function list()
    {
        $query = "SELECT * FROM images";
        $images = (new Database())->select($query);
        $tr = "";

        if (sizeof($images) > 0) {
            foreach ($images as $img) {
                $tr .= "<tr>
<td>{$img['name']}</td>
<td>{$img['category']}</td>
<td><img src='{$img['url']}' alt='{$img['name']}' style='width:50px;height:50px;object-fit:cover;'></td>
<td>
<button class='btn btn-complete' onclick='editImage({$img['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteImage({$img['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewImage({$img['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='4'>No images found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addImage()">Add Image</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Preview</th>
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