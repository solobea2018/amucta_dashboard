<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\view\MainLayout;

class Sims
{
    public function index()
    {
        $title="Student Information Management System (SIMS)";
        $apk_link="https://solobea.com/projects/detail/amucta-android-app?project_id=63";
        $web_link="https://sims.amucta.ac.tz";

        $meta_tags=<<<meta
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Student Information Management System (SIMS) for accessing academic services online.">
meta;

        $style=<<<style
<style>
.main{
    max-width:700px;
    margin:60px auto;
    padding:30px;
    text-align:center;
    font-family:Arial, sans-serif;
}

.main h1{
    margin-bottom:20px;
}

.main p{
    font-size:16px;
    line-height:1.6;
    margin-bottom:30px;
}

.btn{
    display:inline-block;
    padding:12px 20px;
    margin:10px;
    text-decoration:none;
    border-radius:6px;
    font-weight:bold;
}

.btn-app{
    background:#28a745;
    color:white;
}

.btn-web{
    background:#007bff;
    color:white;
}

.btn:hover{
    opacity:0.9;
}
</style>
style;

        $head=$meta_tags.$style;

        $content=<<<content
<div class="main">

<h1>Student Information Management System (SIMS)</h1>

<p>
The Student Information Management System (SIMS) allows students to access academic services online such as course registration, results, fee statements, and personal academic information.
</p>

<p>
For the best experience, students are encouraged to use the <strong>SIMS Android Mobile App</strong>.
</p>

<div>
<a href="$apk_link" class="btn btn-app">
Download Android App
</a>

<a href="$web_link" class="btn btn-web">
Open Web SIMS
</a>
</div>

</div>
content;

        MainLayout::render($content,$head,$title);
    }

}