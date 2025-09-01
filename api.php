<?php

use Solobea\Dashboard\database\Database;

$db=new Database();

if ($db->getCon()){
    echo "Database connected successfully";

}else{
    echo "Database connection failed";
}