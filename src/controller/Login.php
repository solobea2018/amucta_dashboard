<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\utils\Helper;
use Solobea\Dashboard\view\MainLayout;

class Login
{
    public function index()
    {
        $content = <<<HTML
<div class="flex justify-center items-center" style="height:100vh;">
    <form class="form-container" onsubmit="sendFormSweet(this, event)" action="/login/process" method="POST" style="width:350px; padding:20px; border:1px solid #ddd; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); background:#fff;">
        <h2 style="text-align:center; margin-bottom:20px;">Login</h2>

        <div class="form-group" style="margin-bottom:15px;">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter username or email" required>
        </div>

        <div class="form-group" style="margin-bottom:15px;">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <div class="form-group" style="margin-bottom:15px; display:flex; justify-content:space-between; align-items:center;">
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
            <a href="/auth/forgot-password" style="font-size:0.9em;">Forgot Password?</a>
        </div>

        <div class="form-group" style="text-align:center;">
            <button type="submit" class="btn btn-primary" style="width:100%;">Login</button>
        </div>

        <p style="text-align:center; margin-top:15px; display: none">
            Don't have an account? <a href="/register">Register</a>
        </p>
    </form>
</div>
HTML;

        MainLayout::render($content);
    }
    public function process()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = trim($_POST['username']);
            $passwordInput = $_POST['password'];

            $db = new Database();
            $user = $db->find_user_by_username($username);

            if (!$user) {
                http_response_code(401);
                echo "Invalid username or password";
                return;
            }

            // Get hashed password from database
            $hashedPassword = $db->getPasswordByUsername($username);

            // Verify password
            if (password_verify($passwordInput, $hashedPassword)) {
                // Password correct, login user
                Authentication::login($user);
                http_response_code(200);
                echo "Login successful";
            } else {
                // Wrong password
                http_response_code(401);
                echo "Invalid username or password";
            }
        } else {
            http_response_code(400);
            echo "Username and password are required";
        }
    }


}