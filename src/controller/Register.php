<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\model\User;
use Solobea\Dashboard\utils\MobileNumberValidation;
use Solobea\Dashboard\view\MainLayout;
use Solobea\Helpers\data\Sanitizer;

class Register
{
    public function index()
    {
        Authentication::logout();
        $content = <<<HTML
<div class="flex justify-center items-center" style="height:100vh;">
    <form class="form-container" onsubmit="sendFormSweet(this, event)" action="/register/save" method="POST" style="width:350px; padding:20px; border:1px solid #ddd; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); background:#fff;">
        <h2 style="text-align:center; margin-bottom:20px;">Register</h2>
        
        <div class="form-group" style="margin-bottom:15px;">
            <label for="username">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter Full name" required>
        </div>
        <div class="form-group" style="margin-bottom:15px;">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required>
        </div>
        <div class="form-group" style="margin-bottom:15px;">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="form-group" style="margin-bottom:15px;">
            <label for="phone">Phone number</label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="0765539743" required>
        </div>

        <div class="form-group" style="margin-bottom:15px;">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>

        <div class="form-group" style="text-align:center;">
            <button type="submit" class="btn btn-primary" style="width:100%;">Register</button>
        </div>

        <p style="text-align:center; margin-top:15px;">
            Have an account? <a href="/login">Login here</a>
        </p>
    </form>
</div>
HTML;

        MainLayout::render($content,"","Register");
    }

    public function save()
    {
        Authentication::logout();
        if (isset($_POST['username']) && isset($_POST['password'])){
            $full_name=Sanitizer::sanitize($_POST['name']??"Anonymous");
            $phone=Sanitizer::sanitize($_POST['phone']??"");
            $username=Sanitizer::sanitize($_POST['username']);
            $email=Sanitizer::sanitize($_POST['email']);
            $db = Database::get_instance();
            if ($db->exists('users',['email'=>$email]) || $db->exists('users',['username'=>$username])){
                http_response_code(400);
                echo "Username or email taken";
                exit();
            }
            if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                echo "Email address is invalid";
                exit();
            }
            if (str_contains($username," " || $username=="")){
                http_response_code(400);
                echo "Username is Invalid";
                exit();
            }
            if (strlen($username)<5){
                http_response_code(400);
                echo "Username must be 5 or more characters";
                exit();
            }
            if (!MobileNumberValidation::isMobileNumber($phone)){
                http_response_code(400);
                echo "Phone number is Invalid";
                exit();
            }
            $password=$_POST['password'];

            $user = new User();;
            $user->setEmail($email);
            $user->setFullName($full_name);
            $user->setPassword($password);
            $user->setPhoneNumber($phone);
            $user->setProfileUrl("/images/user.png");
            $user->setRole("user");
            $user->setVerified(false);
            $user->setUsername($username);
            $user->setBurned(false);
            if ($db->save_user($user)){
                http_response_code(300);
                echo "/login";
                header("Location: /login");
            }else{
                http_response_code(500);
                echo "Server error";
            }
        }else{
            http_response_code(400);
            echo "Invalid request";
        }
    }
}