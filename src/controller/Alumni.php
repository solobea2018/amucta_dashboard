<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Alumni
{
    public function alumni()
    {
        $content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMUCTA Alumni</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="/css/sweetalert2.css">
    <script src="/js/others.js"></script>
    <script src="/js/sweetalert2.js"></script>
    <script src="/js/main.js"></script>
    <link rel="icon" href="/logo.png">
    <style>
        body { font-family: Arial, sans-serif; margin:0; background:#f5f6f7; }
        .alumni-container { max-width: 1200px; margin: 40px auto; padding: 20px; background:#fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .alumni-header { text-align:center; padding: 30px 10px; background: var(--amucta-blue); color:#fff; border-radius: 10px 10px 0 0; }
        .alumni-header h1 { margin:0; font-size:2em; }
        .alumni-header p { margin:10px 0 0; font-size:1.1em; }
        .alumni-sections { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:20px; margin-top:30px; }
        .alumni-card { background:#f9f9f9; padding:20px; border-radius:8px; border-left:5px solid var(--amucta-green); box-shadow:0 2px 6px rgba(0,0,0,0.08); }
        .alumni-card h2 { margin:0 0 10px; color:#1D4ED8; font-size:1.3em; }
        .alumni-card p { margin:0; color:#444; line-height:1.6; }
        .alumni-footer { margin-top:40px; text-align:center; color:#777; font-size:0.9em; }
        .join-btn { display:inline-block; margin-top:15px; padding:10px 20px; background: var(--amucta-blue); color:#fff; border-radius:6px; text-decoration:none; }
        .join-btn:hover { background:#1D4ED8; }
    </style>
</head>
<body>
    <div class="alumni-container animate__animated animate__fadeIn">
        <div class="alumni-header">
            <h1>Welcome to AMUCTA Alumni</h1>
            <p>Stay connected, grow your network, and give back to the AMUCTA community.</p>
        </div>

        <div class="alumni-sections">
            <div class="alumni-card">
                <h2>Networking</h2>
                <p>Connect with fellow alumni across different industries and build lifelong professional and personal relationships.</p>
            </div>
            <div class="alumni-card">
                <h2>Events &amp; Reunions</h2>
                <p>Join exclusive alumni events, workshops, and reunions to celebrate memories and create new opportunities.</p>
            </div>
            <div class="alumni-card">
                <h2>Career Support</h2>
                <p>Access job postings, mentorship programs, and career advice from fellow alumni and AMUCTA career services.</p>
            </div>
            <div class="alumni-card">
                <h2>Give Back</h2>
                <p>Support scholarships, research initiatives, and community projects to empower future generations at AMUCTA.</p>
            </div>
        </div>

        <div class="alumni-footer">
            <p>Want to become part of the Alumni community?</p>
            <a href="/alumni/#" class="join-btn" onclick="addAlumni()">Join Alumni Network</a>
            <p style="margin-top:20px;">&copy; 2010 - 2024 Archbishop Mihayo University College of Tabora (AMUCTA)</p>
        </div>
    </div>
</body>
</html>
HTML;

        echo $content;
    }
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alumni = new \Solobea\Dashboard\model\Alumni();

            $alumni->setFullName(htmlspecialchars($_POST['full_name']));
            $alumni->setEmail($_POST['email']);
            $alumni->setPhone($_POST['phone']);
            $alumni->setGraduationYear($_POST['graduation_year']);
            $alumni->setCourse($_POST['course']);
            $alumni->setEmploymentStatus($_POST['employment_status']);
            $alumni->setMessage(htmlspecialchars($_POST['message']));

            if ($alumni->save()) {
                echo "Your alumni details have been saved successfully.";
            } else {
                echo  "Failed to save alumni details.";
            }
            exit;
        }
    }
    public function index()
    {
        $db = new Database();
        $query = "SELECT * FROM alumni ORDER BY full_name";
        $alumni = $db->select($query);

        $cards = "";

        if (sizeof($alumni) > 0) {
            foreach ($alumni as $a) {
                $cards .= "
            <div class='alumni-card'>
                <h2 class='alumni-name'>{$a['full_name']}</h2>
                <p><strong>Email:</strong> {$a['email']}</p>
                <p><strong>Phone:</strong> {$a['phone']}</p>
                <p><strong>Graduation Year:</strong> {$a['graduation_year']}</p>
                <p><strong>Course:</strong> {$a['course']}</p>
                <p><strong>Employment:</strong> {$a['employment_status']}</p>
                <div class='alumni-actions hidden'>
                    <button class='btn btn-primary' onclick='editAlumni({$a['id']})'>
                        Edit <i class='bi bi-pencil'></i>
                    </button>                   
                </div>
            </div>";
            }
        } else {
            $cards = "<p class='text-gray-500'>No alumni registered yet.</p>";
        }

        $content = <<<HTML
<div class="alumni-container animate__animated animate__fadeIn">
        <div class="alumni-header">
            <h1>Welcome to AMUCTA Alumni</h1>
            <p>Stay connected, grow your network, and give back to the AMUCTA community.</p>
        </div>

        <div class="alumni-sections">
            <div class="alumni-card">
                <h2>Networking</h2>
                <p>Connect with fellow alumni across different industries and build lifelong professional and personal relationships.</p>
            </div>
            <div class="alumni-card">
                <h2>Events &amp; Reunions</h2>
                <p>Join exclusive alumni events, workshops, and reunions to celebrate memories and create new opportunities.</p>
            </div>
            <div class="alumni-card">
                <h2>Career Support</h2>
                <p>Access job postings, mentorship programs, and career advice from fellow alumni and AMUCTA career services.</p>
            </div>
            <div class="alumni-card">
                <h2>Give Back</h2>
                <p>Support scholarships, research initiatives, and community projects to empower future generations at AMUCTA.</p>
            </div>
        </div>

        <div class="alumni-footer">
            <p>Want to become part of the Alumni community?</p>
            <a href="/alumni/#" class="join-btn" onclick="addAlumni()">Join Alumni Network</a>
            <p style="margin-top:20px;">&copy; 2010 - 2024 Archbishop Mihayo University College of Tabora (AMUCTA)</p>
        </div>
    </div>
<div class="flex flex-col">
    <div class="w-full flex justify-between items-center mb-4">
        <button class="btn btn-complete" onclick="addAlumni()">Register as Alumni</button>
        <div id="wrapperSearch">
            <input type="text" id="alumniSearch" class="solobea-input" placeholder="🔍 Search alumni by name or course" onkeyup="filterAlumni()">
        </div>
    </div>
    <div id="alumniContainer" class="alumni-grid">
        $cards
    </div>
</div>
HTML;

        $head=<<<alu
<style>
        body { font-family: Arial, sans-serif; margin:0; background:#f5f6f7; }
        .alumni-container { max-width: 1200px; margin: 40px auto; padding: 20px; background:#fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .alumni-header { text-align:center; padding: 30px 10px; background: var(--amucta-blue); color:#fff; border-radius: 10px 10px 0 0; }
        .alumni-header h1 { margin:0; font-size:2em; }
        .alumni-header p { margin:10px 0 0; font-size:1.1em; }
        .alumni-sections { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:20px; margin-top:30px; }
        .alumni-card { background:#f9f9f9; padding:20px; border-radius:8px; border-left:5px solid var(--amucta-green); box-shadow:0 2px 6px rgba(0,0,0,0.08); }
        .alumni-card h2 { margin:0 0 10px; color:#1D4ED8; font-size:1.3em; }
        .alumni-card p { margin:0; color:#444; line-height:1.6; }
        .alumni-footer { margin-top:40px; text-align:center; color:#777; font-size:0.9em; }
        .join-btn { display:inline-block; margin-top:15px; padding:10px 20px; background: var(--amucta-blue); color:#fff; border-radius:6px; text-decoration:none; }
        .join-btn:hover { background:#1D4ED8; }
    </style>
alu;

        MainLayout::render($content,$head);
    }
    public function get($params)
    {
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = new Database();

                // Fetch employee by ID
                $employee = $db->select("SELECT * FROM alumni WHERE id = {$id} LIMIT 1");

                if ($employee && count($employee) > 0) {
                    // Return JSON response
                    header('Content-Type: application/json');
                    echo json_encode([
                        "status" => "success",
                        "data" => $employee[0],
                    ]);
                } else {
                    // Employee not found
                    echo json_encode([
                        "status" => "error",
                        "message" => "Employee not found"
                    ]);
                }
            } catch (\Exception $exception) {
                echo json_encode([
                    "status" => "error",
                    "message" => $exception->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid Request"
            ]);
        }
    }



}