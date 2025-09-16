<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\model\Visitor;
use Solobea\Dashboard\view\MainLayout;
use Solobea\Dashboard\utils\ErrorReporter;

class Visitors
{

    /**
     * Visitors constructor.
     */
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        //header("Content-Type: application/json; charset=UTF-8");
    }

    public function list_all()
    {
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("SELECT * FROM visitors order by id desc "));
    }

    public function save()
    {
        header("Content-Type: application/json");
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
            ErrorReporter::report("Saving visitor error ","IP API Error {$response_array['message']}",$_SERVER['REQUEST_URI']);
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
    public function dashboard()
    {
        $db = new Database();

        // Total visitors
        $totalVisitors = $db->selectOne("SELECT COUNT(*) as total FROM visitors")['total'] ?? 0;

        // Registered vs Guest
        $registered = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE is_registered = 1")['total'] ?? 0;
        $guests = $totalVisitors - $registered;

        // IP Type
        $ipv4 = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE ip_type = 'IPv4'")['total'] ?? 0;
        $ipv6 = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE ip_type = 'IPv6'")['total'] ?? 0;

        // Top countries
        $topCountries = $db->select("SELECT country, COUNT(*) as total FROM visitors 
                                WHERE country IS NOT NULL AND country <> '' 
                                GROUP BY country 
                                ORDER BY total DESC LIMIT 5");

        // Top visited URLs
        $topUrls = $db->select("SELECT url, COUNT(*) as total FROM visitors 
                            WHERE url IS NOT NULL AND url <> '' 
                            GROUP BY url 
                            ORDER BY total DESC LIMIT 5");

        // Prepare country list
        $countryList = "";
        foreach ($topCountries as $c) {
            $countryList .= "<li>{$c['country']} - {$c['total']} visits</li>";
        }
        if (!$countryList) $countryList = "<li>No data</li>";

        // Prepare URL list
        $urlList = "";
        foreach ($topUrls as $u) {
            $urlList .= "<li>{$u['url']} - {$u['total']} hits</li>";
        }
        if (!$urlList) $urlList = "<li>No data</li>";

        $content = <<<HTML
<div class="dashboard grid grid-cols-1 md:grid-cols-2 gap-4 p-4">

    <!-- Overview Cards -->
    <div class="card bg-white shadow-md rounded-lg p-4">
        <h3 class="text-lg font-bold">Total Visitors</h3>
        <p class="text-3xl text-blue-600">$totalVisitors</p>
    </div>
    <div class="card bg-white shadow-md rounded-lg p-4">
        <h3 class="text-lg font-bold">Registered Users</h3>
        <p class="text-3xl text-green-600">$registered</p>
        <span class="text-sm text-gray-500">Guests: $guests</span>
    </div>
    <div class="card bg-white shadow-md rounded-lg p-4">
        <h3 class="text-lg font-bold">IP Type</h3>
        <p>IPv4: $ipv4</p>
        <p>IPv6: $ipv6</p>
    </div>

    <!-- Top Countries -->
    <div class="card bg-white shadow-md rounded-lg p-4">
        <h3 class="text-lg font-bold mb-2">Top Countries</h3>
        <ul class="list-disc pl-6">
            $countryList
        </ul>
    </div>

    <!-- Top URLs -->
    <div class="card bg-white shadow-md rounded-lg p-4">
        <h3 class="text-lg font-bold mb-2">Most Visited Pages</h3>
        <ul class="list-disc pl-6">
            $urlList
        </ul>
    </div>

</div>
HTML;
        $head='<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>';

        MainLayout::render($content,$head,"Visitors");
    }

    public function data()
    {
        $db = new Database();

        // All-time
        $totalVisitors = $db->selectOne("SELECT COUNT(*) as total FROM visitors")['total'] ?? 0;

        // Today
        $todayVisitors = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE DATE(date) = CURDATE()")['total'] ?? 0;

        // This week (Monday–Sunday)
        $weekVisitors = $db->selectOne("SELECT COUNT(*) as total FROM visitors 
                                    WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)")['total'] ?? 0;

        // Registered vs Guest
        $registered = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE is_registered = 1")['total'] ?? 0;
        $guests = $totalVisitors - $registered;

        // IP Type
        $ipv4 = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE ip_type = 'IPv4'")['total'] ?? 0;
        $ipv6 = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE ip_type = 'IPv6'")['total'] ?? 0;

        // Top countries
        $topCountries = $db->select("SELECT country, COUNT(*) as total FROM visitors 
                                WHERE country IS NOT NULL AND country <> '' 
                                GROUP BY country 
                                ORDER BY total DESC LIMIT 5");

        // Top visited URLs
        $topUrls = $db->select("SELECT url, COUNT(*) as total FROM visitors 
                            WHERE url IS NOT NULL AND url <> '' 
                            GROUP BY url 
                            ORDER BY total DESC LIMIT 5");

        // Build JSON
        $response = [
            "totals" => [
                "all_time" => $totalVisitors,
                "today" => $todayVisitors,
                "this_week" => $weekVisitors,
            ],
            "registered" => $registered,
            "guests" => $guests,
            "ip" => [
                "ipv4" => $ipv4,
                "ipv6" => $ipv6,
            ],
            "top_countries" => $topCountries,
            "top_urls" => $topUrls
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public static function dataArray(): array
    {
        $db = new Database();

        // All-time
        $totalVisitors = $db->selectOne("SELECT COUNT(*) as total FROM visitors")['total'] ?? 0;

        // Today
        $todayVisitors = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE DATE(date) = CURDATE()")['total'] ?? 0;

        // This week (Monday–Sunday)
        $weekVisitors = $db->selectOne("SELECT COUNT(*) as total FROM visitors 
                                    WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)")['total'] ?? 0;

        // Registered vs Guest
        $registered = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE is_registered = 1")['total'] ?? 0;
        $guests = $totalVisitors - $registered;

        // IP Type
        $ipv4 = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE ip_type = 'IPv4'")['total'] ?? 0;
        $ipv6 = $db->selectOne("SELECT COUNT(*) as total FROM visitors WHERE ip_type = 'IPv6'")['total'] ?? 0;

        // Top countries
        $topCountries = $db->select("SELECT country, COUNT(*) as total FROM visitors 
                                WHERE country IS NOT NULL AND country <> '' 
                                GROUP BY country 
                                ORDER BY total DESC LIMIT 5");

        // Top visited URLs
        $topUrls = $db->select("SELECT url, COUNT(*) as total FROM visitors 
                            WHERE url IS NOT NULL AND url <> '' 
                            GROUP BY url 
                            ORDER BY total DESC LIMIT 5");

        // Build JSON
        return [
            "totals" => [
                "all_time" => $totalVisitors,
                "today" => $todayVisitors,
                "this_week" => $weekVisitors,
            ],
            "registered" => $registered,
            "guests" => $guests,
            "ip" => [
                "ipv4" => $ipv4,
                "ipv6" => $ipv6,
            ],
            "top_countries" => $topCountries,
            "top_urls" => $topUrls
        ];
    }



}