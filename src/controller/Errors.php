<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Errors
{
    public function list($params=null)
    {
        Authentication::require_roles(['admin','developer']);

        if (isset($params) && !empty($params)){
            if ($params[0]=="all"){
                $query = "SELECT id, title, message, cause_url, create_date FROM errors ORDER BY create_date DESC";
            }else{
                $limit=intval($params[0]);
                $query = "SELECT id, title, message, cause_url, create_date FROM errors ORDER BY create_date DESC limit {$limit}";
            }
        }else{
            $query = "SELECT id, title, message, cause_url, create_date FROM errors ORDER BY create_date DESC limit 50";
        }
        $errors = (new Database())->select($query);

        $items = "";

        if (sizeof($errors) > 0) {
            foreach ($errors as $error) {
                $items .= <<<HTML
<div class="error-card" style="border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:15px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
    <h3 style="margin:0; font-size:18px; color:#b71c1c;">{$error['title']}</h3>
    <p style="margin:8px 0; font-size:15px; line-height:1.5; color:#333;">{$error['message']}</p>
    <p style="margin:4px 0; font-size:13px; color:#555;">
        <strong>Cause URL:</strong> 
        <a href="{$error['cause_url']}" target="_blank" style="color:#1976d2; text-decoration:none;">{$error['cause_url']}</a>
    </p>
    <div style="margin-top:10px; display:flex; justify-content:space-between; align-items:center;">
        <small style="color:#777;">{$error['create_date']}</small>
        <button class="btn btn-danger" onclick="deleteResource('errors', {$error['id']})">Delete</i></button>
    </div>
</div>
HTML;
            }
        } else {
            $items = "<p style='color:#777; font-style:italic;'>No error logs found.</p>";
        }

        $content = <<<HTML
<div class="flex flex-col" style="max-width:800px; margin:0 auto;">
    <h2 style="margin-bottom:20px; color:#333;">Error Logs</h2>
    $items
</div>
<a href="/errors/list/all" class="text-blue-400">View All</a>
HTML;

        MainLayout::render($content);
    }

}