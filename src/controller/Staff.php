<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Staff
{
    public function index()
    {
        $auth=new Authentication();
        if (!$auth->is_authenticated()) {
            header("Location: /login"); // Redirect to login page
            exit;
        }

        // Optional: fetch staff info from DB
        $staff = $auth->get_authenticated_user();

        // Self-contained HTML for dashboard
        $content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - AMUCTA</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="/css/toastify.css">
    <link rel="icon" href="/logo.png">
    <script src="/js/main.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .dashboard-container { max-width: 1200px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; }
        .dashboard-header h1 { color: var(--amucta-green); }
        .dashboard-welcome { margin-top: 20px; font-size: 1.2em; }
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .card { padding: 20px; background: #f0f8ff; border-left: 5px solid var(--amucta-green); border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .card h2 { margin: 0 0 10px 0; color: #1D4ED8; }
    </style>
</head>
<body>
    <div class="dashboard-container animate__animated animate__fadeIn">
        <div class="dashboard-header">
            <h1>Staff Dashboard</h1>
            <a href="/logout" style="text-decoration:none; color:red;">Logout</a>
        </div>
        <div class="dashboard-welcome">
            Welcome, <strong>{$staff->getFullName()}</strong>!
        </div>
        <div class="dashboard-cards">
            <div class="card">
                <h2>Profile</h2>
                <p>View and update your profile information.</p>
            </div>
            <div class="card">
                <h2>Messages</h2>
                <p>Check unread messages or notifications.</p>
            </div>
            <div class="card">
                <h2>Tasks</h2>
                <p>See your assigned tasks and deadlines.</p>
            </div>
            <div class="card">
                <h2>Reports</h2>
                <p>Access staff reports and statistics.</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;

        echo $content;
    }


    public function amucta1($params)
    {
        if (!empty($params)){
            $category=$params[0];
            $id=intval($params[1]);
            $db=new Database();
            $stfs=$db->select("SELECT e.*,er.role_name,er.start_date,er.end_date,er.active from employee e join employee_role er on e.id = er.employee_id where er.id={$id} order by e.name");
            $content="<div class=''>hello</div>";
            $head="";
            $title=$category;
            MainLayout::render($content,$head,$title);
        }
    }
    public function amucta($params)
    {
        if (!empty($params)) {
            $category = $params[0];
            $id = intval($params[1]);
            $db = new Database();
            $stfs = $db->select("
            SELECT e.*, er.role_name, er.start_date, er.end_date, er.active 
            FROM employee e 
            JOIN employee_role er ON e.id = er.employee_id 
            WHERE er.role_group_id={$id} 
            ORDER BY e.name
        ");

            // If no staff found, show toast message
            if (!$stfs || count($stfs) === 0) {
                $content = "<script>
                Toastify({
                    text: 'No employees found in this category',
                    backgroundColor: 'linear-gradient(to right, #ff416c, #ff4b2b)',
                    className: 'animate__animated animate__shakeX',
                    gravity: 'top',
                    position: 'right',
                    duration: 4000
                }).showToast();
            </script>";
            } else {
                $content = "<div class='employee-grid animate__animated animate__fadeInUp'>";
                foreach ($stfs as $emp) {
                    $profileImg = !empty($emp['profile']) ? $emp['profile'] : "/images/default-profile.png";
                    $activeStatus = $emp['active'] ? "<span class='active'>Active</span>" : "<span class='inactive'>Inactive</span>";

                    $content .= "
                <div class='employee-card'>
                    <div class='profile-pic'>
                        <img src='{$profileImg}' alt='{$emp['name']}'>
                    </div>
                    <div class='employee-info'>
                        <h3>{$emp['name']}</h3>
                        <p class='title'>{$emp['title']}</p>
                        <p class='role'>{$emp['role_name']} since {$emp['start_date']}</p>
                        {$activeStatus}
                        <a href='/profile/profile/{$emp['id']}' class='view-profile'>View Profile</a>
                    </div>
                </div>";
                }
                $content .= "</div>";
            }

            // CSS styling
            $head = "
        <style>
        .employee-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px;
        }
        .employee-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .employee-card:hover {
            transform: translateY(-5px);
        }
        .employee-card .profile-pic {
            height: 180px;
            background: var(--amucta-blue);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .employee-card .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .employee-info {
            padding: 15px;
            text-align: center;
        }
        .employee-info h3 {
            margin: 10px 0 5px;
            color: var(--amucta-dark);
        }
        .employee-info .title {
            font-size: 14px;
            color: var(--amucta-dark);
        }
        .employee-info .role {
            font-size: 13px;
            color: var(--amucta-blue);
            margin: 5px 0;
        }
        .employee-info .active {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            background: var(--amucta-green);
            color: #fff;
            margin-bottom: 10px;
        }
        .employee-info .inactive {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            background: darkred;
            color: #fff;
            margin-bottom: 10px;
        }
        .employee-info .view-profile {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            background: var(--amucta-blue);
            color: #fff;
            transition: background 0.3s ease;
        }
        .employee-info .view-profile:hover {
            background: darkblue;
        }
        </style>
        ";

            $title = ucfirst(str_replace("-"," ",$category));
            MainLayout::render($content, $head, $title);
        }
    }


}