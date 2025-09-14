<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Faculties
{
    public function index()
    {
        
    }

    public function faculty($params)
    {
        if (!empty($params[1])) {
            $id = intval($params[1]);
            $db = new Database();
            $fac = $db->selectOne("SELECT * FROM faculty WHERE id={$id} AND active=1");

            if ($fac) {
                // Use the DB content directly (already contains HTML)
                $content = htmlspecialchars_decode($fac['description']);
                $head = ""; // no extra css
                $title = $fac['name'];
            } else {
                $content = "<p>Faculty not found.</p>";
                $head = "";
                $title = "Faculty";
            }
            MainLayout::render($content, $head, $title);
        }
    }

}