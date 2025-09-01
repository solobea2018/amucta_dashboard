<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Events
{
    public function list()
    {
        $query = "SELECT * FROM events";
        $events = (new Database())->select($query);
        $tr = "";

        if (sizeof($events) > 0) {
            foreach ($events as $event) {
                $tr .= "<tr>
<td>{$event['name']}</td>
<td>{$event['start_date']}</td>
<td>{$event['end_date']}</td>
<td>{$event['location']}</td>
<td>
<button class='btn btn-complete' onclick='editEvent({$event['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteEvent({$event['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewEvent({$event['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='5'>No events found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addEvent()">Add Event</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Location</th>
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