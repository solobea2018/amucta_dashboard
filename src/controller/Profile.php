<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\utils\Helper;
use Solobea\Dashboard\utils\Resource;
use Solobea\Dashboard\view\MainLayout;
use Solobea\Helpers\helper\MobileNumberValidation;

class Profile
{
    public function index()
    {
        Authentication::requireAuthentication();

        $user = Authentication::user();

        $name     = htmlspecialchars($user->getFullName());
        $email    = htmlspecialchars($user->getEmail());
        $phone    = htmlspecialchars($user->getPhoneNumber());
        $role     = htmlspecialchars($user->getRole());
        $username = htmlspecialchars($user->getUsername());
        $profile  = $user->getProfileUrl();

        $content = <<<content
<div class="user-profile flex justify-center">
    <form class="card max-w-lg w-full" 
          action="/profile/update" 
          method="post" 
          enctype="multipart/form-data"
          onsubmit="sendFormSweet(this,event)">

        <div class="text-center mb-4">
            <img onclick="zoomImage()" src="$profile" class="w-24 h-24 rounded-full mx-auto object-cover">
        </div>

        <div class="form-group">
            <label>Profile Image</label>
            <input type="file" name="profile_image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" value="$name" class="form-control">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="$email" class="form-control">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="$phone" class="form-control">
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" value="$username" class="form-control" disabled>
        </div>

        <div class="form-group">
            <label>Role</label>
            <input type="text" value="$role" class="form-control" disabled>
        </div>

        <div class="form-group">
            <button class="btn btn-blue w-full">Update Profile</button>
        </div>
    </form>
</div>
content;

        MainLayout::render($content, '', 'User Profile');
    }
    public function update()
    {
        Authentication::requireAuthentication();

        $user = Authentication::user();
        $user_id = $user->getId();

        $name  = trim(htmlentities($_POST['name']) ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($name === '' || $email === '' || !filter_var($email,FILTER_VALIDATE_EMAIL) || !MobileNumberValidation::isMobileNumber($phone)) {
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'Name, Phone number and email are required']);
            return;
        }

        $data = [
            'full_name' => $name,
            'phone_number'     => $phone
        ];

        /* profile image */
        if (!empty($_FILES['profile_image']['name'])) {
            $mime = mime_content_type($_FILES['profile_image']['tmp_name']);

            if (!str_starts_with($mime, 'image/')) {
                http_response_code(400);
                echo json_encode(['status'=>'error','message'=>'Invalid image file']);
                return;
            }

            $dir = 'images/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $path = $dir . $user_id . '_' . time() . '_' . basename($_FILES['profile_image']['name']);
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $path);

            $data['profile_url'] = '/'.$path;
        }

        if (Database::get_instance()->update('users', $data, ['id'=>$user_id])) {
            echo json_encode(['status'=>'success','message'=>'Profile updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Failed to update profile']);
        }
    }
    private function profStyle(): string
    {
        return <<<style
<style>
.profile-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px 30px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    font-family: "Segoe UI", Tahoma, sans-serif;
    color: #333;
}

.profile-container h2 {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #2c3e50;
}

.profile-container p {
    margin: 6px 0;
    font-size: 0.95rem;
    line-height: 1.5;
}

.profile-container strong {
    color: #555;
}

.profile-container img {
    display: block;
    margin-top: 10px;
    border-radius: 8px;
    max-width: 150px;
    height: auto;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.profile-container h3 {
    margin-top: 30px;
    margin-bottom: 12px;
    font-size: 1.3rem;
    color: #1e293b;
    border-left: 4px solid #2563eb;
    padding-left: 8px;
}

.profile-container .solobea-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 0.9rem;
}

.profile-container .solobea-table th,
.profile-container .solobea-table td {
    border: 1px solid #e5e7eb;
    padding: 8px 10px;
    text-align: left;
}

.profile-container .solobea-table th {
    background-color: #f8fafc;
    font-weight: 600;
    color: #374151;
}

.profile-container .solobea-table tr:nth-child(even) {
    background-color: #f9fafb;
}

.profile-container .solobea-table a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.profile-container .solobea-table a:hover {
    text-decoration: underline;
}

</style>
style;
    }
    public function profile($params)
    {
        if (!isset($params[0]) || empty($params[0])) {
            echo Resource::getErrorPage("Invalid Request:");
            return;
        }

        $employee_id = intval($params[0]);
        $db = new Database();

        // Fetch employee info
        $employee = $db->select_prepared("SELECT e.*, d.name as department_name
                             FROM employee e
                             LEFT JOIN department d ON e.department_id = d.id
                             WHERE e.id = ? LIMIT 1", [$employee_id],"i");

        if (!$employee || count($employee) === 0) {
            echo Resource::getErrorPage("Employee not found");
            return;
        }

        $emp = $employee[0];

        // Fetch employee roles
        $roles = $db->select_prepared("SELECT r.role_name, r.start_date, r.end_date, d.name as department_name
                          FROM employee_role r
                          LEFT JOIN department d ON r.department_id = d.id
                          WHERE r.employee_id = ? AND r.active = 1
                          ORDER BY r.start_date DESC", [$employee_id],"i");

        // Fetch employee researches, publications, projects
        $researches = $db->select_prepared("SELECT title, type, description, start_date, end_date, link, file_path, active
                               FROM employee_research
                               WHERE employee_id = ?
                               ORDER BY start_date DESC", [$employee_id],"i");

        // Build Roles Table
        $rolesHtml = "";
        if ($roles) {
            foreach ($roles as $role) {
                $rolesHtml .= "<tr>
                <td>{$role['role_name']}</td>
                <td>{$role['department_name']}</td>
                <td>{$role['start_date']}</td>
                <td>{$role['end_date']}</td>
            </tr>";
            }
        } else {
            $rolesHtml = "<tr><td colspan='4'>No active roles found</td></tr>";
        }

        // Build Research Table
        $researchHtml = "";
        if ($researches) {
            foreach ($researches as $r) {
                $researchHtml .= "<tr>
                <td>{$r['title']}</td>
                <td>{$r['type']}</td>
                <td>{$r['description']}</td>
                <td>{$r['start_date']}</td>
                <td>{$r['end_date']}</td>
                <td>".($r['link'] ? "<a href='{$r['link']}' target='_blank'>Link</a>" : "-")."</td>
                <td>".($r['file_path'] ? "<a href='{$r['file_path']}' target='_blank'>File</a>" : "-")."</td>
                <td>".($r['active'] ? "Yes" : "No")."</td>
            </tr>";
            }
        } else {
            $researchHtml = "<tr><td colspan='8'>No research records found</td></tr>";
        }

        $profile_img = ($emp['profile'] ? "<img loading='lazy' src='{$emp['profile']}' width='120' style='border-radius:8px;'>" : "No Image");

        // --- SEO & SOCIAL META TAGS ---
        $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $description = htmlspecialchars(strip_tags($emp['qualification'] . " - " . $emp['department_name']));
        $title = htmlspecialchars($emp['name'] . " | " . $emp['title']);
        $image = !empty($emp['profile']) ? "https://" . $_SERVER['HTTP_HOST']."/".$emp['profile'] : "https://" . $_SERVER['HTTP_HOST'] . "/assets/default-profile.png";

        $meta_tags = <<<mt
<!-- SEO Meta Tags -->
<meta name="title" content="{$title}">
<meta name="description" content="{$description}">
<meta name="keywords" content="{$emp['name']}, {$emp['title']}, {$emp['department_name']}, staff profile, research, education, Tanzania">
<meta name="robots" content="index, follow">
<meta name="author" content="{$emp['name']}">

<!-- Open Graph / Facebook / WhatsApp -->
<meta property="og:type" content="profile">
<meta property="og:title" content="{$title}">
<meta property="og:description" content="{$description}">
<meta property="og:image" content="{$image}">
<meta property="og:url" content="{$url}">
<meta property="og:site_name" content="Your Institution Name">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{$title}">
<meta name="twitter:description" content="{$description}">
<meta name="twitter:image" content="{$image}">
mt;

        // --- PAGE CONTENT ---
        $content = <<<HTML
<div class="profile-container">
    <h2>{$emp['name']}</h2>
    <div class="w-full flex flex-row items-center justify-center">$profile_img</div>
    <p><strong>Title:</strong> {$emp['title']}</p>
    <p><strong>Email:</strong> {$emp['email']}</p>
    <p><strong>Phone:</strong> {$emp['phone']}</p>
    <p><strong>Qualification:</strong> {$emp['qualification']}</p>
    <p><strong>Entry Year:</strong> {$emp['entry_year']}</p>
    <p><strong>Department:</strong> {$emp['department_name']}</p>   

    <h3>Roles</h3>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Role Name</th>
                <th>Department</th>             
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            $rolesHtml
        </tbody>
    </table>

    <h3>Researches / Publications / Projects</h3>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Link</th>
                <th>File</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            $researchHtml
        </tbody>
    </table>
</div>
HTML;

        $head = $meta_tags . $this->profStyle();
        MainLayout::render($content, $head, $emp['name']);
    }

}