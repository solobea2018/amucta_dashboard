<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class News
{
    public function list()
    {
        $query = "SELECT * FROM news";
        $newsItems = (new Database())->select($query);
        $tr = "";

        if (sizeof($newsItems) > 0) {
            foreach ($newsItems as $news) {
                $tr .= "<tr>
<td>{$news['name']}</td>
<td>{$news['category']}</td>
<td>{$news['created_at']}</td>
<td>
<button class='btn btn-complete' onclick='editNews({$news['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteNews({$news['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewNews({$news['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='4'>No news found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addNews()">Add News</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Created At</th>
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