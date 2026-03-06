<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

header("Content-Type: application/json");
require_once "vendor/autoload.php";
/*$query="alter table employee add fulltext(name)";
$db=\Solobea\Dashboard\database\Database::get_instance();
$con=$db->getCon();
$res=$con->query($query);*/
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
    $employees[]=\Solobea\Dashboard\utils\EmployeeHelper::search_employee($name);
}
echo json_encode($employees);
