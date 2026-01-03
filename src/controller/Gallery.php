<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Gallery
{
    public function list()
    {
        $query = "SELECT * FROM images order by created_at desc ";
        $images = (new Database())->select($query);
        $tr = "";

        if (sizeof($images) > 0) {
            foreach ($images as $img) {
                $tr .= "<tr>
<td>{$img['name']}</td>
<td>{$img['category']}</td>
<td><img src='{$img['url']}' alt='{$img['name']}' style='width:50px;height:50px;object-fit:cover;'></td>
<td>
<button class='btn btn-danger' onclick='deleteResource(\"images\",{$img['id']})'>Delete <i class='bi bi-trash'></i></button>
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
        $header=<<<header
<style>
</style>
header;

        MainLayout::render($content,$header);
    }
    public function add()
    {
        Authentication::require_roles(['admin','pro']);
        $auth = new Authentication();
        if (!$auth->is_admin()) {
            echo json_encode(['status' => "error", 'message' => "Not Authorized"]);
            return;
        }

        // Sanitize inputs
        $name     = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars(trim($_POST['category'] ?? ''), ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars(trim($_POST['description'] ?? ''), ENT_QUOTES, 'UTF-8');

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
            'description' =>$description,
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
        echo json_encode((new Database())->select("select * from images order by created_at desc "));
    }

    public function background()
    {
        $images=(new Database())->select("select url from images where category='slides' order by  rand() limit 4");
        $slides=[];
        foreach ($images as $image) {
            $slides[]=$image['url'];
        }
        header("Content-Type: application/json");
        echo json_encode(["images"=>$slides]);
    }

    public function index()
    {
        $query = "SELECT * FROM images where category= 'gallery' or category='slides' order by created_at desc  limit 25";
        $images = (new Database())->select($query);
        $cards = "";
        if (sizeof($images) > 0) {
            foreach ($images as $img) {
                $cards .= "
        <div class='gallery-card'>
            <div class='gallery-image'>
                <img onclick='previewImage(\"{$img['url']}\",\"{$img['description']}\")' src='{$img['url']}' alt='{$img['name']}'>
            </div>
            <div class='gallery-info'>
                <div class='gallery-category'>{$img['description']}</div>                
            </div>
        </div>
        ";
            }
        } else {
            $cards = "<div class='gallery-empty'>No images found</div>";
        }

        $content=<<<Content
<div class="gallery-wrapper"> $cards </div>
Content;

        $header = <<<HTML
<style> .gallery-wrapper { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; } .gallery-card { border: 1px solid #ddd; border-radius: 6px; overflow: hidden; background: #fff; } .gallery-image { width: 100%; height: 150px; overflow: hidden; } .gallery-image img { width: 100%; height: 100%; object-fit: cover; } .gallery-info { padding: 10px; text-align: center; } .gallery-name { font-weight: 600; margin-bottom: 4px; } .gallery-category { font-size: 13px; color: #666; margin-bottom: 8px; } .gallery-empty { padding: 20px; text-align: center; color: #777; } </style>
HTML;
        MainLayout::render($content, $header);


    }




}