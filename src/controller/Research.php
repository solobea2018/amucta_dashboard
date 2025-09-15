<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\view\MainLayout;

class Research
{
    public function publications()
    {
        $content = <<<HTML
<section class="publications-section">
    <h2>Publications by AMUCTA</h2>
    <p>Our faculty and researchers have contributed to various academic publications, including books, journal articles, and conference papers. Notable publications include:</p>
    <ul>
        <li><a href="https://amucta.academia.edu/EzraNathanael">Geography Teaching in Secondary Schools: Contribution of Teacher's Methodological Competencies on Students' Academic Achievements</a></li>
        <li><a href="https://amucta.academia.edu/EzraNathanael">Technology Driven Curriculum for 21st Century Higher Education Students in Africa</a></li>
    </ul>
</section>
HTML;

        $head = <<<HTML
<style>
.publications-section {
    padding: 20px;
    background-color: #e9ecef;
    border-radius: 8px;
}
.publications-section h2 {
    color: #2a3d66;
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.publications-section p {
    color: #333;
    line-height: 1.6;
}
.publications-section ul {
    list-style-type: none;
    padding-left: 0;
}
.publications-section li {
    margin-bottom: 10px;
}
.publications-section a {
    color: #1d72b8;
    text-decoration: none;
}
.publications-section a:hover {
    text-decoration: underline;
}
</style>
HTML;

        $title = "Publications by AMUCTA";

        MainLayout::render($content, $head, $title);
    }

    public function research()
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


}