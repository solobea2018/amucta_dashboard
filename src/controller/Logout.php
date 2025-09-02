<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;

class Logout
{
    public function index()
    {
        Authentication::logout();
    }
}