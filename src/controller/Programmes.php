<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Programmes
{
    public function index()
    {

    }
    public function detail($params)
    {
        if(!empty($params)){
            $program_id = intval($params[0]);
            $query = "SELECT p.*, 
                         d.name AS department_name, 
                         f.name AS faculty_name, 
                         l.name AS level_name 
                  FROM program p 
                  left JOIN department d ON d.id = p.department_id 
                  left JOIN faculty f ON f.id = d.faculty_id 
                  left JOIN level l ON l.id = p.level_id 
                  WHERE p.id = {$program_id} 
                  ORDER BY level_id";
            $program = (new Database())->selectOne($query);
        } else {
            $program = null;
        }

        $content = '<div class="program-detail-container">';

        if($program){
            $content1=htmlspecialchars_decode($program['content']);
            $fees = htmlspecialchars_decode($program['fees']);
            $entry_requirements = htmlspecialchars_decode($program['entry_requirements']);
            $content .= <<<HTML
<h1 class="program-detail-title">{$program['name']}</h1>
<p class="program-detail-meta"><strong>Faculty:</strong> {$program['faculty_name']}</p>
<p class="program-detail-meta"><strong>Department:</strong> {$program['department_name']}</p>
<p class="program-detail-meta"><strong>Level:</strong> {$program['level_name']}</p>
<p class="program-detail-meta"><strong>Code:</strong> {$program['short_name']}</p>
<p class="program-detail-meta"><strong>Intakes per year:</strong> {$program['intakes']}</p>
<p class="program-detail-meta"><strong>Duration in years:</strong> {$program['duration']}</p>
<p class="program-detail-meta"><strong>Capacity:</strong> {$program['capacity']}</p>

<section class="program-detail-section">
<h2>Description</h2>  
    <div class="program-detail-text">{$content1}</div>
</section>

<section class="program-detail-section">
    <h2>Fees</h2>
    <div class="program-detail-text">{$fees}</div>
    <p class="info">Meals and Accommodation allowances are arranged personally between the student and his/her sponsor as per companies/government scales</p>
</section>

<section class="program-detail-section">
    <h2>Entry Requirements</h2>
    <div class="program-detail-text">{$entry_requirements}</div>
</section>

<section class="program-detail-section">
    <h2>Accreditation</h2>
    <p>Year Accredited: {$program['accreditation_year']}</p>
</section>
HTML;
        } else {
            $content .= '<p class="no-program">Program not found.</p>';
        }

        $content .= '</div>'; // close program-detail-container

        $head = <<<HTML
<style>
tr {background-color:var(--amucta-dark);color: white;text-align: center;padding: 0.5rem 1rem}
.program-detail-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

.program-detail-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--amucta-blue);
    margin-bottom: 10px;
    text-align: start;
}

.program-detail-meta {
    font-size: 1rem;
    color: #374151;
    margin: 5px 0;
    text-align: start;
}

.program-detail-section {
    margin-top: 25px;
    padding: 20px;
    background: #f3f4f6;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.program-detail-section h2 {
    font-size: 1.75rem;
    color:var(--amucta-blue);
    margin-bottom: 10px;
}

.program-detail-text {
    font-size: 1rem;
    color: var(--amucta-dark);7;
}

.no-program {
    text-align: center;
    font-size: 1.125rem;
    color: #b91c1c;
    margin-top: 30px;
}
</style>
HTML;

        $title = $program ? $program['name'] : "Program Detail";

        MainLayout::render($content, $head, $title);
    }

    public function level($params)
    {
        if(!empty($params)){
            $level_id = intval($params[1]);
            $query = "SELECT p.id, p.name, l.name AS level_name, short_name, intakes, duration, p.description, capacity 
                  FROM program p 
                  JOIN level l ON p.level_id = l.id 
                  WHERE level_id = {$level_id}";
            $programmes = (new Database())->select($query);
        } else {
            $programmes = [];
        }

        $content = '<div class="programs-container">';
        $content .= '<h1 class="programs-title">Programs Offered</h1>';

        if(!empty($programmes)){
            $content .= '<div class="programs-grid">';
            foreach($programmes as $p){
                $content .= <<<HTML
<div class="program-card" data-aos="fade-up" data-aos-duration="1000">
    <h2 class="program-name">{$p['name']}</h2>
    <p class="program-level"><strong>Level:</strong> {$p['level_name']}</p>
    <p class="program-capacity"><strong>Capacity:</strong> {$p['capacity']}</p>
    <p class="program-description">{$p['description']}</p>
    <a href="/programmes/detail/{$p['id']}">
        <button class="program-btn">View Program</button>
    </a>
</div>
HTML;
            }
            $content .= '</div>'; // close programs-grid
        } else {
            $content .= '<p class="no-programs">No programs found for this level.</p>';
        }

        $content .= '</div>'; // close programs-container

        $head = <<<HTML
<style>
tr {background-color:var(--amucta-dark);color: white;text-align: center;padding: 0.5rem 1rem}
.programs-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.programs-title {
    font-size: 2.5rem;
    color: var(--amucta-blue);
    font-weight: bold;
    text-align: center;
    margin-bottom: 30px;
}

.programs-grid {
    display: flex;
    flex-direction: column;
}
.program-card {
    background: #f3f4f6;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.program-name {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--amucta-blue);
    margin-bottom: 10px;
}

.program-level, .program-capacity, .program-description {
    margin: 5px 0;
    font-size: 1rem;
}
.program-description{text-align: justify}

.program-btn {
    margin-top: 15px;
    padding: 10px 15px;
    background-color: var(--amucta-blue);
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.program-btn:hover {
    background-color: var(--amucta-blue);
}

.no-programs {
    text-align: center;
    font-size: 1.125rem;
    color: #374151;
    margin-top: 20px;
}
</style>
HTML;

        $title = "Programs Offered";

        MainLayout::render($content, $head, $title);
    }

    public function search()
    {
        if (isset($_POST['course'])){
            $program_id=intval($_POST['course']);
                $params[]=$program_id;
                $this->detail($params);
        }
    }

}