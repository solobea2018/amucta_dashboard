<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;

class Visitors
{
    public function list_all()
    {
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("SELECT * FROM visitors order by id desc "));
    }

    public function save()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        echo json_encode(["data"=>$input]);
    }

}