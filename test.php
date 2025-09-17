<?php
require_once "vendor/autoload.php";
$str="hl?\.";
if(\Solobea\Helpers\data\Sanitizer::is_valid_message($str)){
    echo "sms is valid";
}
else echo "invalid";

echo \Solobea\Helpers\visitor\VisitorData::getIPAddress();