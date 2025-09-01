<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\view\MainLayout;

class Home
{
    public function index()
    {
        $head='<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">';
        $content=<<<content
<div>
    <table class="solobea-table">
        <thead class="">
           <td>Name</td>
           <td>Email</td>
           <td>Phone</td>
</thead>
<tr>
    <td>John</td>
    <td>john@mail.com</td>
    <td>0629088273</td>
</tr>
<tr>
    <td>John</td>
    <td>john@mail.com</td>
    <td>0629088273</td>
</tr>
<tr>
    <td>John</td>
    <td>john@mail.com</td>
    <td>0629088273</td>
</tr>
<tr>
    <td>John</td>
    <td>john@mail.com</td>
    <td>0629088273</td>
</tr>
<tr>
    <td>John</td>
    <td>john@mail.com</td>
    <td>0629088273</td>
</tr>
</table>
</div>
<div> <button class="btn btn-primary" onclick="previewHtml('<h1>hello word</h1>')">Test</button></div>
<div> <button class="btn btn-primary" onclick="createNotification('success',false,300,'Hello word')">Notification <i class="bi bi-bell"></i></button></div>
content;

        MainLayout::render($content,$head);
    }
}