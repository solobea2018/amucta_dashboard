<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Departments
{
    public function index()
    {
        
    }
    public function department($params)
    {
        if (!empty($params[1])) {
            $id = intval($params[1]);
            $db = new Database();
            $dept = $db->selectOne("SELECT * FROM department WHERE id={$id}");

            if ($dept) {
                // Directly echo HTML content from DB
                $content = htmlspecialchars_decode($dept['description']);
                $head = "";
                $title = $dept['name'];
            } else {
                $content = "<p>Department not found.</p>";
                $head = "";
                $title = "Department";
            }

            MainLayout::render($content, $head, $title);
        }
    }

}