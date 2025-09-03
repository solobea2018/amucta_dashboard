<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;

class Visitor
{
    public function list_all()
    {
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("SELECT * FROM visitors order by id desc "));
    }

    public function save()
    {

    }

}