<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Research
{
    public function overview()
    {
        $content = <<<HTML
<section class="research-section">
    <h2>Research at AMUCTA</h2>
    <p>AMUCTA is committed to advancing knowledge through rigorous research across various disciplines. Our faculty and students engage in research that addresses local and global challenges, contributing to the academic community and society at large.</p>
    <p>Recent research includes:</p>
        <ul>
        <li><a href="https://www.researchgate.net/publication/321075086_Exploring_Reading_Culture_in_Tanzanian_Higher_Education_Institutions_The_Case_Study_of_AMUCTA_Education_Undergraduates">Exploring Reading Culture in Tanzanian Higher Education Institutions</a></li>
        <li><a href="https://internationaljournalcorner.com/index.php/ijird_ojs/article/download/154066/106932/379932">Students' Perspectives on Course Evaluation at AMUCTA</a></li>
    </ul>
    <ul> 
        <li><a href="https://www.researchgate.net/publication/321075086_Exploring_Reading_Culture_in_Tanzanian_Higher_Education_Institutions_The_Case_Study_of_AMUCTA_Education_Undergraduates">Exploring Reading Culture in Tanzanian Higher Education Institutions: The Case Study of AMUCTA Education Undergraduates</a> – A case study on reading habits among education undergraduates at AMUCTA, examining factors that enable or limit reading culture. :contentReference[oaicite:0]{index=0}</li>
        <li><a href="https://doi.org/10.59324/ejceel.2024.2(1).05">Realization of Access, Equity, Diversity and Inclusion in Inclusive Education: What Are the Missing Gaps in Tanzania?</a> – Research by an AMUCTA faculty member on inclusive education policy challenges and gaps in Tanzania. :contentReference[oaicite:1]{index=1}</li>
        
        <li><a href="https://journals.out.ac.tz/index.php/jipe/article/view/1620">Assessing Lecturers’ Teaching Strategies for Learners with Hearing Impairment in Tanzanian Higher Learning Institutions</a> – Study involving AMUCTA lecturers on effective teaching strategies for learners with hearing impairment. :contentReference[oaicite:2]{index=2}</li>
        
        <li><a href="https://educationalchallenges.org.ua/index.php/education_challenges/article/view/295">The Psychosocial Wellbeing of Children with Disabilities in Inclusive Secondary School Education Settings</a> – Partly involving an AMUCTA researcher, this study discusses psychosocial challenges facing children with disabilities in Tanzania. :contentReference[oaicite:3]{index=3}</li>
        
        <li><a href="https://www.academia.edu/109572259/The_Contribution_of_Sign_Language_Interpreters_to_Academic_Achievement_of_Deaf_Students_A_Case_Study_of_Archbishop_Mihayo_University_College_of_Tabora">The Contribution of Sign Language Interpreters to Academic Achievement of Deaf Students: A Case Study of AMUCTA</a> – Research on the role of sign language interpretation in academic success for deaf students at AMUCTA. :contentReference[oaicite:4]{index=4}</li>
        
        <li><em>Factors Hindering Deaf Learners’ Transition from Primary to Secondary School</em> – A student research project from AMUCTA investigating barriers affecting transition rates of deaf learners in Tabora Municipality. :contentReference[oaicite:5]{index=5}</li>
        
        <li><em>Students’ Responses on Their Background for English Medium Instruction</em> – A research project conducted at AMUCTA looking at student perspectives on English medium education. :contentReference[oaicite:6]{index=6}</li>
        
        <li><em>Researching Education Leadership in Tanzanian Secondary Schools</em> – Work by an AMUCTA academic on leadership preparation and development in Tanzania’s educational system. :contentReference[oaicite:7]{index=7}</li>
        
        <li><em>Researching Educational Leadership and Management</em> – Related academic work by AMUCTA faculty on leadership, management, and school administration. :contentReference[oaicite:8]{index=8}</li>
        
        <li><a href="https://www.researchgate.net/publication/322008051_Using_technology_to_assess_students_at_a_university_in_Tanzania_Lecturers'_perspectives">Using Technology to Assess Students at a University in Tanzania: Lecturers’ Perspectives</a> – A mixed methods study involving AMUCTA that examines how assessment technologies are used at university level. :contentReference[oaicite:9]{index=9}</li>
        
        </ul>
</section>
HTML;

        $head = <<<HTML
<style>
.research-section {
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 8px;
}
.research-section h2 {
    color: #2a3d66;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.research-section p {
    color: #333;
    line-height: 1.6;
}
.research-section ul {
    list-style-type: none;
    padding-left: 0;
}
.research-section li {
    margin-bottom: 10px;
}
.research-section a {
    color: #1d72b8;
    text-decoration: none;
}
.research-section a:hover {
    text-decoration: underline;
}
</style>
HTML;

        $title = "Research at AMUCTA";

        MainLayout::render($content, $head, $title);
    }
    public function student_research()
    {
        $content = <<<HTML
<section class="student-research">
    <h2>Student Research</h2>
    <p>AMUCTA students actively participate in research projects, theses, and dissertations under faculty supervision. This strengthens their analytical, technical, and critical thinking skills.</p>
    <ul>
        <li>Undergraduate Research Projects</li>
        <li>Master's Thesis Supervision</li>
        <li>Research Competitions and Exhibitions</li>
    </ul>
</section>
HTML;

        $head = <<<HTML
<style>
.student-research {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
}
.student-research h2 {
    color: #2a3d66;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.student-research p {
    color: #333;
    line-height: 1.6;
}
.student-research ul {
    list-style-type: disc;
    padding-left: 20px;
}
.student-research li {
    margin-bottom: 10px;
}
</style>
HTML;

        $title = "Student Research - AMUCTA";
        MainLayout::render($content, $head, $title);
    }
    public function events()
    {
        $content = <<<HTML
<section class="events">
    <h2>Events and Conferences</h2>
    <p>AMUCTA regularly hosts academic events, workshops, and conferences to foster knowledge sharing and networking among researchers and students.</p>
    <ul>
        <li>Annual Research Symposium</li>
        <li>ICT and Innovation Conference</li>
        <li>Environmental Studies Workshop</li>
    </ul>
</section>
HTML;

        $head = <<<HTML
<style>
.events {
    padding: 20px;
    background-color: #eef2f7;
    border-radius: 8px;
}
.events h2 {
    color: #2a3d66;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.events p {
    color: #333;
    line-height: 1.6;
}
.events ul {
    list-style-type: disc;
    padding-left: 20px;
}
.events li {
    margin-bottom: 10px;
}
</style>
HTML;

        $title = "Events & Conferences - AMUCTA";
        MainLayout::render($content, $head, $title);
    }
    public function collaborations()
    {
        $content = <<<HTML
<section class="collaborations">
    <h2>AMUCTA Collaborations</h2>
    <p>AMUCTA works closely with local and international universities, research institutions, and industry partners to promote research and knowledge exchange.</p>
    <ul>
        <li>Partnership with University of Dar es Salaam for joint research projects</li>
        <li>Collaboration with international universities in ICT and environmental studies</li>
        <li>Industry partnerships for applied research and innovation</li>
    </ul>
</section>
HTML;

        $head = <<<HTML
<style>
.collaborations {
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 8px;
}
.collaborations h2 {
    color: #2a3d66;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.collaborations p {
    color: #333;
    line-height: 1.6;
}
.collaborations ul {
    list-style-type: disc;
    padding-left: 20px;
}
.collaborations li {
    margin-bottom: 10px;
}
</style>
HTML;

        $title = "Collaborations - AMUCTA";
        MainLayout::render($content, $head, $title);
    }
    public function centers()
    {
        $content = <<<HTML
<section class="research-centers">
    <h2>Research Centers at AMUCTA</h2>
    <p>AMUCTA has established specialized research centers to advance knowledge and innovation across multiple disciplines:</p>
    <ul>
        <li>Center for Sustainable Development</li>
        <li>Center for ICT and Innovation</li>
        <li>Center for Environmental Studies</li>
        <li>Center for Social and Economic Research</li>
    </ul>
</section>
HTML;

        $head = <<<HTML
<style>
.research-centers {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
}
.research-centers h2 {
    color: #2a3d66;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.research-centers p {
    color: #333;
    line-height: 1.6;
}
.research-centers ul {
    list-style-type: disc;
    padding-left: 20px;
}
.research-centers li {
    margin-bottom: 10px;
}
</style>
HTML;

        $title = "Research Centers - AMUCTA";
        MainLayout::render($content, $head, $title);
    }
    public function funding()
    {
        $content = <<<HTML
<section class="funding-opportunities">
    <h2>Funding Opportunities for Research at AMUCTA</h2>
    <p>AMUCTA offers a range of funding options to support innovative and impactful research. Here are some of the available opportunities:</p>
    <ul>
        <li><strong>AMUCTA Research Grants:</strong> Internal funding for groundbreaking projects across all disciplines.</li>
        <li><strong>Government Research Funding:</strong> Access to national research funding and grants.</li>
        <li><strong>Industry Collaboration Grants:</strong> Partner with leading industries for applied research funding.</li>
        <li><strong>International Research Scholarships:</strong> Funding for collaborative research projects with global institutions.</li>
    </ul>
    <p>For more information and application guidelines, visit our <a href="#">Funding Portal</a>.</p>
</section>
HTML;

        $head = <<<HTML
<style>
.funding-opportunities {
    padding: 20px;
    background-color: #f0f4f8;
    border-radius: 8px;
}
.funding-opportunities h2 {
    color: #2a3d66;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.funding-opportunities p {
    color: #333;
    line-height: 1.6;
}
.funding-opportunities ul {
    list-style-type: disc;
    padding-left: 20px;
}
.funding-opportunities li {
    margin-bottom: 10px;
}
.funding-opportunities a {
    color: #007bff;
    text-decoration: none;
}
.funding-opportunities a:hover {
    text-decoration: underline;
}
</style>
HTML;

        $title = "Funding Opportunities - AMUCTA";
        MainLayout::render($content, $head, $title);
    }
    public function projects()
    {
        $content = <<<HTML
<section class="research-projects">
    <h2>Research Projects at AMUCTA</h2>
    <p>Explore the ongoing and completed research projects at AMUCTA University:</p>
    <ul>
        <li><strong>Smart Grid for Sustainable Energy:</strong> A project focused on developing smart grid technology to enhance energy efficiency.</li>
        <li><strong>Artificial Intelligence in Healthcare:</strong> Research into the application of AI to improve diagnostic accuracy and treatment in healthcare.</li>
        <li><strong>Climate Change and Urban Planning:</strong> A study analyzing the effects of climate change on urban development and planning.</li>
        <li><strong>Blockchain for Secure Data Exchange:</strong> Investigating the use of blockchain technology for secure and transparent data sharing across industries.</li>
    </ul>
    <p>For more information about each project, click <a href="#">here</a>.</p>
</section>
HTML;

        $head = <<<HTML
<style>
.research-projects {
    padding: 20px;
    background-color: #eef1f4;
    border-radius: 8px;
}
.research-projects h2 {
    color: #2a3d66;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.research-projects p {
    color: #333;
    line-height: 1.6;
}
.research-projects ul {
    list-style-type: disc;
    padding-left: 20px;
}
.research-projects li {
    margin-bottom: 10px;
}
.research-projects a {
    color: #007bff;
    text-decoration: none;
}
.research-projects a:hover {
    text-decoration: underline;
}
</style>
HTML;

        $title = "Research Projects - AMUCTA";
        MainLayout::render($content, $head, $title);
    }
    public function publications()
    {
        // Database connection (adjust as needed)
        $db = Database::get_instance(); // your DB connection method

        $publications = $db->select("SELECT * FROM amucta_research ORDER BY year DESC, id DESC");

        $itemsHtml = "";

        if (count($publications) > 0) {
            foreach ($publications as $pub) {
                $title = htmlspecialchars($pub["title"]);
                $authors = htmlspecialchars($pub["authors"]);
                $year = htmlspecialchars($pub["year"]??"");
                $link = htmlspecialchars($pub["link"]??"");
                $abstract = htmlspecialchars($pub["abstract_text"]??"");

                $itemsHtml .= <<<HTML
<li class="pub-item">
    <a href="$link" target="_blank">$title</a><br>
    <span class="pub-authors">$authors</span>,
    <span class="pub-year">($year)</span><br>
    <span class="pub-abstract">$abstract</span>
</li>
HTML;
            }
        } else {
            $itemsHtml = "<li>No publications found. Please add records.</li>";
        }

        $content = <<<HTML
<section class="amucta-publications">
    <h2>AMUCTA Research & Publications</h2>
    <p>
        Archbishop Mihayo University College of Tabora (AMUCTA) is committed to research
        and academic excellence in education, inclusive practices, and community studies.  
        Below are selected publications and research outputs affiliated with AMUCTA.
    </p>
    <ul>
        $itemsHtml
    </ul>
</section>
HTML;

        $head = <<<HTML
<style>
.amucta-publications {
    padding: 26px;
    background-color: #f9fafb;
    border-radius: 10px;
    font-family: "Times New Roman", serif;
}

.amucta-publications h2 {
    font-size: 28px;
    color: #1f2f4d;
    margin-bottom: 14px;
}

.amucta-publications p {
    font-size: 16px;
    line-height: 1.7;
    color: #333;
    margin-bottom: 22px;
}

.amucta-publications ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.pub-item {
    padding: 16px 18px;
    margin-bottom: 16px;
    background-color: #ffffff;
    border-left: 5px solid #2a3d66;
    border-radius: 6px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.06);
}

.pub-item a {
    font-size: 17px;
    font-weight: bold;
    color: #1d4ed8;
    text-decoration: none;
    line-height: 1.4;
}

.pub-item a:hover {
    text-decoration: underline;
}

.pub-authors {
    display: inline-block;
    margin-top: 6px;
    font-style: italic;
    font-size: 15px;
    color: #444;
}

.pub-year {
    font-size: 15px;
    font-weight: bold;
    color: #222;
}

.pub-abstract {
    margin-top: 8px;
    font-size: 15px;
    color: #555;
    line-height: 1.6;
}
</style>
HTML;

        $title = "AMUCTA Research & Publications";

        MainLayout::render($content, $head, $title);
    }

    public function outreach()
    {
        $db = Database::get_instance();
        $rows = $db->select("SELECT * FROM amucta_outreach ORDER BY year DESC, id DESC");

        $items = "";
        foreach ($rows as $r) {
            $author = $r['authors'] ?? "Anonymouus";
            $items .= "
        <li>
            <strong>{$r['activity_title']}</strong><br>
            {$r['description']}<br>           
            <em>{$author}</em><br>
            <a href='{$r['link']}' target='_blank'>View activity</a> ({$r['year']})
        </li>";
        }

        $content = "
    <section class='amucta-section'>
        <h2>Outreach and Consultancy</h2>
        <p>
            AMUCTA staff engage in community service and consultancy activities aimed at improving
            inclusive education, health services, and academic support within Tanzania.
        </p>
        <ul>$items</ul>
    </section>";
        $head=<<<style
<style>
.amucta-section {
    padding: 26px;
    background-color: #f9fafb;
    border-radius: 10px;
    font-family: "Times New Roman", serif;
}

.amucta-section h2 {
    font-size: 28px;
    color: #1f2f4d;
    margin-bottom: 14px;
}

.amucta-section p {
    font-size: 16px;
    line-height: 1.7;
    color: #333;
    margin-bottom: 22px;
}

.amucta-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.amucta-section li {
    padding: 16px 18px;
    margin-bottom: 16px;
    background-color: #ffffff;
    border-left: 5px solid #198754;
    border-radius: 6px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.06);
}

.amucta-section li strong {
    font-size: 17px;
    color: #1a1a1a;
}

.amucta-section li a {
    display: inline-block;
    margin-top: 6px;
    font-size: 14px;
    color: #0d6efd;
    text-decoration: none;
    font-weight: bold;
}

.amucta-section li a:hover {
    text-decoration: underline;
}

.amucta-section li span,
.amucta-section li small {
    font-size: 14px;
    color: #555;
}

</style>
style;


        MainLayout::render($content, $head, 'AMUCTA Outreach and Consultancy');
    }
    public function research()
    {
        $db = Database::get_instance();
        $rows = $db->select("SELECT * FROM amucta_research ORDER BY year DESC, id DESC");
        $items = "";
        foreach ($rows as $r) {
            $items .= "
        <li>
            <a href='{$r['link']}' target='_blank'>{$r['title']}</a><br>
            <em>{$r['authors']}</em><br>
            {$r['publication_type']} – {$r['publisher']} ({$r['year']})
        </li>";
        }

        $content = "
    <section class='amucta-section'>
        <h2>Research and Publications</h2>
        <p>
            This section presents peer reviewed journal articles, book chapters, and scholarly
            publications authored by AMUCTA academic staff, reflecting contributions to inclusive
            education, linguistics, and disability studies.
        </p>     
        <p>AMUCTA is committed to advancing knowledge through rigorous research across various disciplines. Our faculty and students engage in research that addresses local and global challenges, contributing to the academic community and society at large.</p>
        <p>Recent research includes:</p>
        <ul>$items</ul>
    </section>";

        $head = <<<style
<style>
.amucta-section {
    padding: 26px;
    background-color: #f8f9fa;
    border-radius: 10px;
    font-family: "Times New Roman", serif;
}

.amucta-section h2 {
    color: #2a3d66;
    font-size: 26px;
    margin-bottom: 14px;
}

.amucta-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.amucta-section li {
    padding: 14px 16px;
    margin-bottom: 16px;
    background-color: #ffffff;
    border-left: 4px solid #198754;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    line-height: 1.6;
}

.amucta-section li strong {
    font-size: 16px;
    color: #1f2937;
}

.amucta-section a {
    display: inline-block;
    margin-top: 6px;
    color: #1d72b8;
    font-weight: 600;
    text-decoration: none;
}

.amucta-section a:hover {
    text-decoration: underline;
}

</style>
style;


        MainLayout::render($content, $head, 'AMUCTA Research and Publications');
    }


}