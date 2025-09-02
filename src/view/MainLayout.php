<?php


namespace Solobea\Dashboard\view;


class MainLayout
{
    public static function render($content,$header=null,$title=null)
    {
        $org_name="Amucta";
        $org_logo="/images/logo.png";
        $title=$title??$org_name;
        $menu =self::menu();
        $layout=<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$title}</title>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/animate.css">
    <link rel="stylesheet" href="/css/sweetalert2.css">
    <link rel="stylesheet" href="/css/toastify.css">
    <link rel="icon" href="$org_logo">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="/js/main.js" type="text/javascript"></script>
    <script src="/js/others.js" type="text/javascript"></script>
    <script src="/js/sweetalert2.js" type="text/javascript"></script>
    <script src="/js/toastify.js" type="text/javascript"></script>
    $header
</head>
<body>
    <section class="header p-4">
    $menu
</section>
    <section class="main p-4">$content</section>
    <section class="footer"></section>
</body>
</html>
HTML;
        echo $layout;

    }

    public static function menu(): string
    {
        return <<<menu
<div class="flex flex-row flex-wrap">
    <a href="/" class="btn btn-primary"><i class="bi bi-house mx-2"></i>Home</a>
    <a href="/faculty/list" class="btn btn-primary"><i class="bi bi-dash-circle mx-2"></i>Faculty</a>
    <a href="/department/list" class="btn btn-primary"><i class="bi bi-house mx-2"></i>Department</a>
    <a href="/Level/list" class="btn btn-primary"><i class="bi bi-graph-up mx-2"></i>Level</a>
    <a href="/program/list" class="btn btn-primary"><i class="bi bi-book mx-2"></i>Program</a>
    <a href="/news/list" class="btn btn-primary"><i class="bi bi-newspaper mx-2"></i>News</a>
    <a href="/events/list" class="btn btn-primary"><i class="bi bi-calendar-event mx-2"></i>Events</a>
    <a href="/attachment/list" class="btn btn-primary"><i class="bi bi-file mx-2"></i>Attachments</a>
    <a href="/employee/list" class="btn btn-primary"><i class="bi bi-people mx-2"></i>Employee</a>
    <a href="/users/list" class="btn btn-primary"><i class="bi bi-people mx-2"></i>Users</a>
    <a href="/gallery/list" class="btn btn-primary"><i class="bi bi-medium mx-2"></i>Gallery</a>
    <a href="/login" class="btn btn-primary"><i class="bi bi-arrow-bar-left mx-2"></i>Login</a>
</div> 
menu;

    }
}