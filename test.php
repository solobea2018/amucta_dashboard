<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "vendor/autoload.php";
header("Content-Type: application/json");

use Solobea\Dashboard\database\Database;
$db=Database::get_instance();

$row=$db->execute("
alter table amucta_research modify publication_type varchar(100);
");
echo $row;
exit();

$basePath = __DIR__ . '/new';
$baseUrl  = '/new/';

if (!is_dir($basePath)) {
    echo json_encode([]);
    exit;
}

$files = scandir($basePath);
$links = [];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }

    $fullPath = $basePath . '/' . $file;

    if (!is_file($fullPath)) {
        continue;
    }

    // Check file size greater than 600 KB
    $sizeKB = filesize($fullPath) / 1024;
/*
    if ($sizeKB > 600) {
        // Reduce quality and overwrite same file
        Helper::reduceImageQuality(
            $fullPath,
            $fullPath,
            70
        );
    }*/

    $links[] = $baseUrl . $file;
}
$id=40;
foreach ($links as $link) {
    $name="Graduation Image";

    $category="gallery";
    $date=date("Y-m-d H:i:s");
    $user_id=72;
    /*if ($database->insert("images",["created_at"=>$date,"name"=>$name,"description"=>"Graduation Image","category"=>$category,"url"=>$link,"user_id"=>$user_id])){
        echo $link." saved \n";
    }*/
    $id++;
}
