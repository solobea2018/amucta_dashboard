<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;

class Visitors
{

    /**
     * Visitors constructor.
     */
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");

// Allow common methods
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow headers Angular sends (especially Content-Type)
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Always return JSON
        header("Content-Type: application/json; charset=UTF-8");
    }

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