<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\view\MainLayout;

class Admin
{

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        $auth=new Authentication();
        if (!$auth->is_admin()){
            echo "Hello World";
            exit();
        }
    }

    public function index()
    {
        $menu=MainLayout::menu();
        $content="".$menu;
        $title="Admin";
        MainLayout::render($content,null,$title);
    }
}