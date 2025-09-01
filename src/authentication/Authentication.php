<?php

namespace Solobea\Dashboard\authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\model\User;
use Solobea\Go\errors\ErrorReporter;

class Authentication
{
    public function is_authenticated(): bool
    {
        $token=$this->getRequestToken();
        if ($token!=null && $token!=""){
            $db=new Database();
            return $db->isExistAccessToken($token);
        }
        return !empty($_SESSION['username']) && !empty($_SESSION['id']);
    }

    public function is_admin(): bool
    {
        if ($this->is_authenticated()) {
            if ($this->get_authenticated_user()->getRole()==="admin"){
                return true;
            }
            else if ($this->is_manager()){
                return true;
            }
        }
        return  false;
    }
    public function get_authenticated_user(): ?User
    {
        if ($this->is_authenticated()){
            $username=$_SESSION['username']??null;
            // Todo remember to sanitize string for username query
            if ($username){
                $database= new Database();
                $user =$database->find_user_by_email($username);
                if (!($user==null)){
                    return $user;
                }
            }
            if(($token_user=$this->getAuthUserByToken())!=null) {
                    return $token_user;
            }
        }

        return null;
    }
    public static function login(User $user) {
        if (!$user->isBurned()) {
            if (session_status() == PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }
            // Set session lifetime (12 hours)
            $session_lifetime = 24 * 60 * 60 * 30; //  30 days
            //$session_lifetime = 120; //  1 min
            ini_set('session.gc_maxlifetime', $session_lifetime);
            session_set_cookie_params($session_lifetime);
            session_start();
            // Store user information in the session
            $_SESSION['username'] = $user->getEmail();
            $_SESSION['id'] = $user->getId();

            // Log user login in the database
            $db = new Database();
            try {
                $db->log_user_login($_SESSION['id']);
            } catch (\Exception $exception) {
                ErrorReporter::report("Save Logins Failed", $exception->getMessage(), $_SERVER['REQUEST_URI']);
            }
        }
    }

    public static function logout(){
        $_SESSION=array();
        setcookie(session_name(),'',time()-2592000,'/');
        session_unset();    // Unsets all session variables
        session_destroy();
    }

    public function is_manager(): bool
    {
        if ($this->is_authenticated()) {
            return $this->get_authenticated_user()->getRole()==="manager";
        }
        else return  false;
    }

    private function getAuthUserByToken(): ?User
    {
        $token = $this->getRequestToken();
        if ($token){
            // 5. Look up user
            $db=new Database();
            return$db->find_user_by_access_token($token);
        }
        return null;
    }

    private function getRequestToken(): ?string
    {
        $token = null;
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = trim(substr($authHeader, 7));
        }
        if (!$token && isset($_GET['access_token'])) {
            $token = $_GET['access_token'];
        }
        if (!$token && isset($_GET['token'])) {
            $token = $_GET['token'];
        }
        if (!$token && isset($_POST['access_token'])) {
            $token = $_POST['access_token'];
        }
        if (!$token && isset($_POST['token'])) {
            $token = $_POST['token'];
        }
        if (!$token) {
            $jsData = file_get_contents("php://input");
            if ($jsData) {
                $data = json_decode($jsData, true);
                if (is_array($data)) {
                    if (!empty($data['access_token'])) {
                        $token = $data['access_token'];
                    } elseif (!empty($data['token'])) {
                        $token = $data['token'];
                    }
                }
            }
        }
        if (!$token){
            return null;
        }
        return htmlspecialchars(trim($token));
    }

}