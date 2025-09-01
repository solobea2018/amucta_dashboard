<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\model\User;
use Solobea\Dashboard\view\MainLayout;

class Users
{
    public function list()
    {
        $query = "SELECT * FROM users";
        $users = (new Database())->select($query);
        $tr = "";

        if (sizeof($users) > 0) {
            foreach ($users as $user) {
                $profile = $user['profile_url'] ? "<img src='{$user['profile_url']}' style='width:50px;height:50px;border-radius:50%;object-fit:cover;'>" : "-";
                $tr .= "<tr>
<td>{$user['full_name']}</td>
<td>{$user['username']}</td>
<td>{$user['email']}</td>
<td>{$user['phone_number']}</td>
<td>{$user['role']}</td>
<td>" . ($user['active'] ? 'Yes' : 'No') . "</td>
<td>" . ($user['verified'] ? 'Yes' : 'No') . "</td>
<td>{$profile}</td>
<td>
<button class='btn btn-complete' onclick='editUser({$user['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteUser({$user['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewUser({$user['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='9'>No users found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addUser()">Add User</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Active</th>
                <th>Verified</th>
                <th>Profile</th>
                <th>Actions</th>
            </tr>
        </thead>
        $tr
    </table>
</div>
HTML;

        MainLayout::render($content);
    }
    public function add()
    {
        if (!isset($_POST['username'], $_POST['email'], $_POST['full_name'], $_POST['password'], $_POST['recovery_question'], $_POST['recovery_answer'])) {
            http_response_code(400);
            echo "Required fields missing";
            return;
        }

        // Sanitize input
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $full_name = trim($_POST['full_name']);
        $password = $_POST['password'];
        $recovery_question = trim($_POST['recovery_question']);
        $recovery_answer = $_POST['recovery_answer'];
        $phone_number = $_POST['phone_number'] ?? null;
        $profile_url = $_POST['profile_url'] ?? null;
        $role = $_POST['role'] ?? 'user';
        $active = isset($_POST['active']) ? (bool)$_POST['active'] : true;
        $verified = isset($_POST['verified']) ? (bool)$_POST['verified'] : false;

        $db = new Database();

        // Check if username or email already exists
        if ($db->find_user_by_username($username)) {
            http_response_code(409);
            echo "Username already exists";
            return;
        }

        if ($db->find_user_by_email($email)) {
            http_response_code(409);
            echo "Email already exists";
            return;
        }

        // Create new User object
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setFullName($full_name);
        $user->setPhoneNumber($phone_number);
        $user->setProfileUrl($profile_url);
        $user->setRole($role);
        $user->setActive($active);
        $user->setVerified($verified);
        $user->setBurned(false);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setRecoveryQuestion($recovery_question);
        $user->setRecoveryAnswerHash(password_hash($recovery_answer, PASSWORD_DEFAULT));

        // Save user in database
        if ($db->save_user($user)) {
            http_response_code(201);
            echo "User created successfully";
        } else {
            http_response_code(500);
            echo "Failed to create user";
        }
    }


}