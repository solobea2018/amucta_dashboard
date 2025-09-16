<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\controller\Home;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\model\Visitor;
use Solobea\Dashboard\utils\Helper;
use Solobea\Dashboard\utils\Resource;
use Solobea\Dashboard\utils\ErrorReporter;

date_default_timezone_set('Africa/Nairobi');

session_start();

require_once "vendor/autoload.php";

$path = $_SERVER['PATH_INFO']??"";
$path_array = explode("/", trim($path, "/")); // Trim extra slashes
if (!empty($path_array) && $path_array[0]!="") {
    $raw = strtolower($path_array[0]);               // "employee-role"
    $page = str_replace(' ', '', ucwords(str_replace('-', ' ', $raw)));
    $full_path = "Solobea\\Dashboard\\controller\\" . $page;
    $method=lcfirst($path_array[1]??"");
    $params=[];
    if (sizeof($path_array)>2){
        $params=array_slice($path_array,2);
    }

    if (class_exists($full_path)) {
        $controller = new $full_path();
        if ($method!=null && method_exists($full_path,$method)){
            if (!empty($params)){
                $controller->$method($params);
            }
            else{
                $controller->$method();
            }

        } else if(method_exists($full_path,"index")){
            $controller->index();
        } else{
            //http_response_code(404);
            echo Resource::getErrorPage("Page not found ".$method);
        }
    } else {
        //http_response_code(404);
        echo Resource::getErrorPage("Page not found ".$page);
    }
} else {
    // Default route to Home
    $home = new Home();
    $home->index();
}
function getIPAddress() {
    //whether ip is from the share internet
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
//whether ip is from the remote address
    else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function save_visitor($ip){
    if (!Helper::is_human_ip($ip)){
        return;
    }
    $currentUrl = $_SERVER['REQUEST_URI'];
    if (isset($_SESSION['last_url'])) {
        if ($_SESSION['last_url'] === $currentUrl) {
            return;
        }
    }
    $_SESSION['last_url'] = $currentUrl;

    if(isset($_SESSION['visitor_data'])){
        $response=unserialize($_SESSION['visitor_data']);
    }
    else{
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/ip_to_location/$ip",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: tD8e24dm34V7HQbvFNB6YuZHciy8aZsp"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
        $response = curl_exec($curl);
        $_SESSION['visitor_data']=serialize($response);
        curl_close($curl);
    }

    $response_array=json_decode($response,true);
    if ($response_array && isset($response_array['message'])){
        ErrorReporter::report("Saving visitor error ","IP API Error retrieving the data",$_SERVER['REQUEST_URI']);
    }
    if(!(isset($response_array['type']))){
        //ErrorReporter::report("Saving visitor error ","IP type Unknown",$_SERVER['REQUEST_URI']);
    }
    else{
        $ip=$response_array['ip'];
        $ip_type=$response_array['type'];
        $continent_name=$response_array['continent_name'];
        $country_name=$response_array['country_name'];
        $region_name=$response_array['region_name'];
        $city_name=$response_array['city'];
        $isp=$response_array['connection']['isp'];
        $auth=new Authentication();
        if ($auth->is_authenticated()){
            $is_registered=true;
        }
        else $is_registered=false;
        $visitor= new Visitor();
        $visitor->setCity($city_name);
        $visitor->setRegion($region_name);
        $visitor->setContinent($continent_name);
        $visitor->setCountry($country_name);
        $visitor->setIp($ip);
        $visitor->setIpType($ip_type);
        $visitor->setIsp($isp);
        $visitor->setIsRegistered($is_registered);
        $visitor->setUrl($_SERVER['REQUEST_URI']);
        $db=new Database();
        $db->save_visitor($visitor);
    }
}