<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class ChangePassword
{
    public function index()
    {
        Authentication::requireAuthentication();
        $user=Authentication::user();
        if ($user){
            $content=<<<content
<div class="change-password flex flex-col justify-center items-center">
<form class="max-w-md card" action="/change-password/change" onsubmit="sendFormSweet(this,event)">
<div class="form-group">
<label class="" for="password">New Password</label>
<input type="password" name="password" id="password" class="form-control">
</div>
<div class="form-group">
<label for="confirm" class="">Confirm</label>
<input id="confirm" type="password" name="confirm" class="form-control">
</div>
<div class="form-group">
<input type="submit" value="Update" class="btn btn-blue">
</div>
</form>
</div>
content;
            MainLayout::render($content,'',"Change Password");

        }
    }

    public function change()
    {
        Authentication::requireAuthentication();
        $user_id=Authentication::user()->getId();
        if (isset($_POST['password'])){
            $password=trim($_POST['password']);
            $confirm=trim($_POST['confirm']);
            if ($confirm!==$password){
                http_response_code(400);
                echo json_encode(['status'=>'error','message'=>'Password does not match']);
                exit();
            }
            $hashed=password_hash($password,PASSWORD_DEFAULT);
            if (Database::get_instance()->update('users',['password'=>$hashed],['id'=>$user_id])){
                echo json_encode(['status'=>"success",'message'=>'User password updated successfully']);
            }else{
                http_response_code(500);
                echo json_encode(['status'=>'error','message'=>'Failed to update the password']);
            }
        }else{
            http_response_code(400);
            echo json_encode(['status'=>'error','message'=>'Invalid request']);
        }
    }

}