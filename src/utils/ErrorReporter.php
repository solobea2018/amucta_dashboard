<?php

namespace Solobea\Go\errors;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\model\Error;
use Solobea\Dashboard\utils\Helper;

class ErrorReporter{
    public Database $db;

    public function __construct()
    {
        $this->db=new Database();
    }

    public static function report($title,$message,$cause_url=""){
        if ($cause_url==""){
            $cause_url=Helper::getCurrentUrl();
        }
        $db=new Database();
        $error=new Error($title,$message,$cause_url);
        $db->report_error($error);
    }

}