<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Gallery
{
    public function list()
    {
        $query = "SELECT * FROM images";
        $images = (new Database())->select($query);
        $tr = "";

        if (sizeof($images) > 0) {
            foreach ($images as $img) {
                $tr .= "<tr>
<td>{$img['name']}</td>
<td>{$img['category']}</td>
<td><img src='{$img['url']}' alt='{$img['name']}' style='width:50px;height:50px;object-fit:cover;'></td>
<td>
<button class='btn btn-complete' onclick='editImage({$img['id']})'>Edit <i class='bi bi-pencil'></i></button>
<button class='btn btn-danger' onclick='deleteResource(\"image\",{$img['id']})'>Delete <i class='bi bi-trash'></i></button>
<button class='btn btn-primary' onclick='viewImage({$img['id']})'>View <i class='bi bi-eye'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='4'>No images found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addImage()">Add Image</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Preview</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>$tr</tbody>
    </table>
</div>
HTML;

        MainLayout::render($content);
    }
    public function add()
    {
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            echo json_encode(['status' => "error", 'message' => "Not Authorized"]);
            return;
        }

        // Sanitize inputs
        $name     = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars(trim($_POST['category'] ?? ''), ENT_QUOTES, 'UTF-8');

        $imagePath = null;

        // Handle image upload securely
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];

            // 1. Check file size (max 2MB)
            if ($fileSize > 2 * 1024 * 1024) {
                echo json_encode(['status' => "error", 'message' => "Image size must be less than 2MB"]);
                return;
            }

            // 2. Validate image type
            $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $fileMime = mime_content_type($fileTmp);

            if (!in_array($fileMime, $allowedMime)) {
                echo json_encode(['status' => "error", 'message' => "Only JPG, PNG, GIF, or WEBP images are allowed"]);
                return;
            }

            // 3. Generate safe filename (prefix category + unique ID)
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($category));
            $uniqueId = uniqid();
            $fileName = $safeName . "_" . $uniqueId . ".webp";

            // 4. Destination folder
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/gallery/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $targetFile = $uploadDir . $fileName;

            // 5. Convert to WebP
            switch ($fileMime) {
                case 'image/jpeg':
                    $img = imagecreatefromjpeg($fileTmp);
                    break;
                case 'image/png':
                    $img = imagecreatefrompng($fileTmp);
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    break;
                case 'image/gif':
                    $img = imagecreatefromgif($fileTmp);
                    break;
                case 'image/webp':
                    $img = imagecreatefromwebp($fileTmp);
                    break;
                default:
                    $img = null;
            }

            if ($img) {
                if (!imageistruecolor($img)) {
                    $trueColor = imagecreatetruecolor(imagesx($img), imagesy($img));
                    imagecopy($trueColor, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
                    imagedestroy($img);
                    $img = $trueColor;
                }
                imagewebp($img, $targetFile, 60); // medium quality for gallery
                imagedestroy($img);
                $imagePath = "/images/gallery/" . $fileName; // relative path for DB
            } else {
                echo json_encode(['status' => "error", 'message' => "Failed to process image"]);
                return;
            }
        } else {
            echo json_encode(['status' => "error", 'message' => "Image file is required"]);
            return;
        }

        // Insert into database
        $db = new Database();
        $user_id = (new Authentication())->get_authenticated_user()->getId();
        $galleryData = [
            'name'     => $name,
            'category' => $category,
            'url'     => $imagePath,
            'user_id'  => $user_id,
            'created_at' => date("Y-m-d H:i:s")
        ];

        if ($db->insert("images", $galleryData)) {
            echo json_encode(['status' => "success", 'message' => "Image added successfully"]);
        } else {
            echo json_encode(['status' => "error", 'message' => "Failed to add image"]);
        }
    }
    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("select * from images"));
    }


}