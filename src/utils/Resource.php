<?php

namespace Solobea\Dashboard\utils;

class Resource
{
    public static function getErrorPage($description): string
    {
        return <<<asd
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error-container {
            text-align: center;
            padding: 50px;
            margin-top: 10%;
        }
        .error-container h2 {
            font-size: 42px;
            color: red;
        }
        .error-container p {
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="error-container">
            <h2>Oops! Very Sorry!.</h2>
            <p>$description</p>           
        </div>
    </div>
</body>
</html>

asd;

    }
    public static function getSuccessPage($description): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-container {
            text-align: center;
            padding: 50px;
            margin-top: 10%;
        }
        .success-container h2 {
            font-size: 42px;
            color: green;
        }
        .success-container p {
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="success-container">
            <h2>Great! Success 🎉</h2>
            <p>$description</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

}

