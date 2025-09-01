<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Attachment
{
    public function list()
    {
        $query = "SELECT * FROM attachments";
        $attachments = (new Database())->select($query);
        $tr = "";

        if (sizeof($attachments) > 0) {
            foreach ($attachments as $att) {
                $tr .= "<tr>
<td>{$att['name']}</td>
<td>{$att['file_url']}</td>
<td>{$att['type']}</td>
<td>{$att['related_to']}</td>
<td>
<button class='btn btn-complete' onclick='editAttachment({$att['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteAttachment({$att['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewAttachment({$att['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='5'>No attachments found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addAttachment()">Add Attachment</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>File URL</th>
                <th>Type</th>
                <th>Related To</th>
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