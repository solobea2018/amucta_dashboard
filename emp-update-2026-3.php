<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

//header("Content-Type: application/json");
require_once "vendor/autoload.php";
$query="alter table employee
    add highest_qualification varchar(50
    )";
$db=\Solobea\Dashboard\database\Database::get_instance();
$con=$db->getCon();
$res=$con->query($query);
exit();
// LOAD JSON FILE
$json = file_get_contents(__DIR__ . '/contacts.json');

if (!$json) {
    die("Failed to read JSON file");
}

// DECODE JSON
$data = json_decode($json, true);

if (!$data) {
    die("Invalid JSON format");
}

$employees=[];
foreach ($data as $datum) {
    $email=$datum['Email'];
    $name=$datum['Name'];
    $employees[]=\Solobea\Dashboard\utils\EmployeeHelper::search_employee($name,$email);
}
foreach ($employees as $employee) {
    $ids=$employee['ids'];
    $email=$employee['email'];
    $name=$employee['query'];
    $db=\Solobea\Dashboard\database\Database::get_instance();
    if (empty($ids)){
        //insert new employee
        echo "Employee .".$name." {$email} not found in database . Skipping.\n";
        echo "\n <br>";
        echo "\n <br>";
        echo "\n <br>";
    }elseif(count($ids)>1){
        //much matches
        //echo "Multiple matches for ".$name." email: {$email} skipping\n <br>";
        echo "\n <br>";
        echo "\n <br>";
    }else{

    }
}
