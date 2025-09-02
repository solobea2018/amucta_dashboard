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
        <tbody>       
        $tr
</tbody>
    </table>
</div>
HTML;

        MainLayout::render($content);
    }
    public function add()
    {
        if (!isset($_POST['username'], $_POST['email'], $_POST['full_name'])) {
            http_response_code(400);
            echo "Required fields missing";
            exit();
        }

        // Sanitize input
        $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $full_name = htmlspecialchars(trim($_POST['full_name']), ENT_QUOTES, 'UTF-8');
        $password = $_POST['phone_number'] ?? "user";
        $phone_number = $_POST['phone_number'] ?? null;
        $role = $_POST['role'] ?? 'user';
        $active = isset($_POST['active']) ? (bool)$_POST['active'] : true;
        $verified = isset($_POST['verified']) ? (bool)$_POST['verified'] : false;

        $db = new Database();

        // Check if username or email already exists
        if ($db->find_user_by_username($username)) {
            http_response_code(409);
            echo "Username already exists";
            exit();
        }

        if ($db->find_user_by_email($email)) {
            http_response_code(409);
            echo "Email already exists";
            exit();
        }

        // Handle image upload
        $profile_url = null;
        if (isset($_FILES['profile_url']) && $_FILES['profile_url']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['profile_url']['tmp_name'];
            $fileSize = $_FILES['profile_url']['size'];
            $fileInfo = getimagesize($fileTmp);

            // Validate file type
            if ($fileInfo === false) {
                http_response_code(400);
                echo "Uploaded file is not a valid image";
                exit();
            }

            // Validate size (1MB max)
            if ($fileSize > 1024 * 1024) {
                http_response_code(400);
                echo "Image size must be less than 1MB";
                exit();
            }

            // Generate unique safe name (employee name + timestamp)
            $safeName = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($full_name));
            $newFileName = $safeName . "_" . time() . ".webp";
            $uploadDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . "/images/";
            $uploadPath = $uploadDir . $newFileName;

            // Make sure upload dir exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Convert to WebP low quality
            switch ($fileInfo['mime']) {
                case 'image/jpeg':
                    $img = imagecreatefromjpeg($fileTmp);
                    break;
                case 'image/png':
                    $img = imagecreatefrompng($fileTmp);
                    break;
                case 'image/gif':
                    $img = imagecreatefromgif($fileTmp);
                    break;
                default:
                    http_response_code(400);
                    echo "Only JPG, PNG, and GIF are allowed";
                    exit();
            }

            if ($img === false) {
                http_response_code(500);
                echo "Failed to process image";
                exit();
            }

            // Save WebP (low quality 50)
            if (!imageistruecolor($img)) {
                $trueColor = imagecreatetruecolor(imagesx($img), imagesy($img));
                imagecopy($trueColor, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
                imagedestroy($img);
                $img = $trueColor;
            }
            if (!imagewebp($img, $uploadPath, 50)) {
                http_response_code(500);
                echo "Failed to save image";
                exit();
            }
            imagedestroy($img);

            $profile_url = "/images/" . $newFileName;
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

        // Save user in database
        if ($db->save_user($user)) {
            http_response_code(200);
            echo "User created successfully";
        } else {
            http_response_code(500);
            echo "Failed to create user";
        }
    }

}