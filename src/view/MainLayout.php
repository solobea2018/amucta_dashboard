<?php


namespace Solobea\Dashboard\view;


class MainLayout
{
    public static function render($content,$header=null,$title=null)
    {
        $org_name="Amucta";
        $org_logo="/images/logo.png";
        $title=$title??$org_name;
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
    <script src="/js/main.js" type="text/javascript"></script>
    <script src="/js/others.js" type="text/javascript"></script>
    <script src="/js/sweetalert2.js" type="text/javascript"></script>
    <script src="/js/toastify.js" type="text/javascript"></script>
    $header
</head>
<body>
    <section class="header"></section>
    <section class="main">$content</section>
    <section class="footer"></section>
</body>
</html>
HTML;
        echo $layout;

    }
}