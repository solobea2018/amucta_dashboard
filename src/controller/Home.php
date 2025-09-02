<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\view\MainLayout;

class Home
{
    public function index()
    {
        $head='';
        $content=<<<content
content;

        MainLayout::render($content,$head);
    }
}