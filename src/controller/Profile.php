<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\utils\Resource;
use Solobea\Dashboard\view\MainLayout;

class Profile
{
    public function index()
    {
        
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
        $employee = $db->select("SELECT e.*, d.name as department_name
                             FROM employee e
                             LEFT JOIN department d ON e.department_id = d.id
                             WHERE e.id = {$employee_id} LIMIT 1");

        if (!$employee || count($employee) === 0) {
            echo Resource::getErrorPage("Employee not found");
            return;
        }

        $emp = $employee[0];

        // Fetch employee roles
        $roles = $db->select("SELECT r.role_name, r.start_date, r.end_date, d.name as department_name
                          FROM employee_role r
                          LEFT JOIN department d ON r.department_id = d.id                        
                          WHERE r.employee_id = {$employee_id} AND r.active = 1
                          ORDER BY r.start_date DESC");

        // Fetch employee researches, publications, projects
        $researches = $db->select("SELECT title, type, description, start_date, end_date, link, file_path, active
                               FROM employee_research
                               WHERE employee_id = {$employee_id}
                               ORDER BY start_date DESC");

        // Render HTML
        $rolesHtml = "";
        foreach ($roles as $role) {
            $rolesHtml .= "<tr>
            <td>{$role['role_name']}</td>
            <td>{$role['department_name']}</td>
            <td>{$role['start_date']}</td>
            <td>{$role['end_date']}</td>
        </tr>";
        }

        $researchHtml = "";
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
        $profile_tag=($emp['profile'] ? "<img src='{$emp['profile']}' width='100'>" : "No Image");

        $content = <<<HTML
<div class="profile-container">
    <h2>{$emp['name']}</h2>
    <div class="w-full flex flex-row items-center justify-center">$profile_tag</div>
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
        $meta_tags=<<<mt
<meta name='og:'
mt;

        $head=$this->profStyle();
        MainLayout::render($content,$head,$emp['name']);
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
}