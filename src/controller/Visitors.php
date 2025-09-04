<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\model\Visitor;
use Solobea\Go\errors\ErrorReporter;

class Visitors
{

    /**
     * Visitors constructor.
     */
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");

// Allow common methods
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow headers Angular sends (especially Content-Type)
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Always return JSON
        header("Content-Type: application/json; charset=UTF-8");
    }

    public function list_all()
    {
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("SELECT * FROM visitors order by id desc "));
    }

    public function save()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $ip=$this->getIPAddress();
        $currentUrl = $input['url'];
        $user_agent=['userAgent'];
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
                    "apikey: hvrGy5kldLuAOZ4pIEWt4KlcZ47CEFtM"
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
            $visitor= new Visitor();
            $visitor->setCity($city_name);
            $visitor->setRegion($region_name);
            $visitor->setContinent($continent_name);
            $visitor->setCountry($country_name);
            $visitor->setIp($ip);
            $visitor->setIpType($ip_type);
            $visitor->setIsp($isp);
            $visitor->setIsRegistered(false);
            $visitor->setUrl($currentUrl);
            $db=new Database();
            $db->save_visitor($visitor);
        }
        echo json_encode(["status"=>"success"]);
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

}