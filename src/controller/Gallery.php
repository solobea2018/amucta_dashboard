<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;
use Solobea\Helpers\helper\Helper;

class Gallery
{
    public function list()
    {
        $query = "SELECT * FROM images order by created_at desc ";
        $images = (new Database())->select($query);
        $tr = "";

        if (sizeof($images) > 0) {
            foreach ($images as $img) {
                $thumb=$img['thumbnail_url'];
                $link=$thumb??$img['url'];
                $tr .= "<tr>
<td>{$img['name']}</td>
<td>{$img['category']}</td>
<td>{$img['image_size']}</td>
<td><img src='{$link}' loading='lazy' alt='{$img['name']}' style='width:50px;height:50px;object-fit:cover;'></td>
<td>
<button class='btn btn-amucta' onclick='editImage({$img['id']})'>Edit</i></button>
<button class='btn btn-amucta' onclick='createThumbNail({$img['id']})'>Create Thumbnail</i></button>
<button class='btn btn-complete' onclick='reduceQuality({$img['id']})'>Reduce Quality</i></button>
<button class='btn btn-danger' onclick='deleteResource(\"images\",{$img['id']})'>Delete</i></button>
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
                <th>Image Size</th>
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

        $db = new Database();
        $user = (new Authentication())->get_authenticated_user();
        $user_id = $user->getId();

        $id = intval($_POST['id'] ?? 0);

        // Sanitize inputs
        $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars(trim($_POST['description'] ?? ''), ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars(trim($_POST['category'] ?? ''), ENT_QUOTES, 'UTF-8');

        /* =========================
           UPDATE MODE
           ========================= */
        if ($id > 0) {

            if ($name === '' && $description === '') {
                echo json_encode(['status' => "error", 'message' => "Nothing to update"]);
                return;
            }

            $updateData = [
                'name' => $name,
                'description' => $description
            ];

            if ($db->update("images", $updateData, ["id" => $id])) {
                echo json_encode(['status' => "success", 'message' => "Image details updated successfully"]);
            } else {
                echo json_encode(['status' => "error", 'message' => "Failed to update image details"]);
            }
            return;
        }

        /* =========================
           INSERT MODE
           ========================= */

        if (empty($_FILES['image']['name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => "error", 'message' => "Image file is required"]);
            return;
        }

        $fileTmp  = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];

        if ($fileSize > 1 * 1024 * 1024) {
            echo json_encode(['status' => "error", 'message' => "Image size must be less than 1MB"]);
            return;
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $fileMime = mime_content_type($fileTmp);

        if (!in_array($fileMime, $allowedMime)) {
            echo json_encode(['status' => "error", 'message' => "Only JPG, PNG, GIF, or WEBP images are allowed"]);
            return;
        }

        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($category));
        $fileName = $safeName . "_" . uniqid() . ".webp";

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/gallery/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetFile = $uploadDir . $fileName;

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

        if (!$img) {
            echo json_encode(['status' => "error", 'message' => "Failed to process image"]);
            return;
        }

        if (!imageistruecolor($img)) {
            $trueColor = imagecreatetruecolor(imagesx($img), imagesy($img));
            imagecopy($trueColor, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
            imagedestroy($img);
            $img = $trueColor;
        }

        imagewebp($img, $targetFile, 60);
        imagedestroy($img);

        $imagePath = "/images/gallery/" . $fileName;

        $galleryData = [
            'name' => $name,
            'category' => $category,
            'url' => $imagePath,
            'description' => $description,
            'user_id' => $user_id,
            'created_at' => date("Y-m-d H:i:s")
        ];

        if ($db->insert("images", $galleryData)) {
            echo json_encode(['status' => "success", 'message' => "Image added successfully"]);
        } else {
            echo json_encode(['status' => "error", 'message' => "Failed to add image"]);
        }
    }
    public function get($params=null)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = new Database();

                // Fetch employee by ID
                $employee = $db->select("SELECT * FROM images WHERE id = '{$id}' LIMIT 1");

                if ($employee && count($employee) > 0) {
                    echo json_encode([
                        "status" => "success",
                        "data" => $employee[0],
                    ]);
                } else {
                    // Employee not found
                    echo json_encode([
                        "status" => "error",
                        "message" => "Employee not found"
                    ]);
                }
            } catch (\Exception $exception) {
                echo json_encode([
                    "status" => "error",
                    "message" => $exception->getMessage()
                ]);
            }
        } else {
            echo json_encode((new Database())->select("select * from images order by created_at desc "));
        }
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
        $query = "SELECT * FROM images where category= 'gallery' or category='slides' order by created_at desc";
        $images = (new Database())->select($query);
        $cards = "";
        if (sizeof($images) > 0) {
            foreach ($images as $img) {
                $cards .= "
        <div class='gallery-card'>
            <div class='gallery-image'>
                <img onclick='previewImage(\"{$img['url']}\",\"{$img['description']}\")' loading='lazy' src='{$img['url']}' alt='{$img['name']}'>
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

    public function thumbnail()
    {
        Authentication::requireAdmin();
        header("Content-Type: application/json");

        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['id'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid request"
            ]);
            return;
        }

        $id = intval($input['id']);
        $db = Database::get_instance();

        $rows = $db->select("SELECT url FROM images WHERE id = '{$id}' LIMIT 1");

        if (empty($rows)) {
            echo json_encode([
                "status" => "error",
                "message" => "Image record not found"
            ]);
            return;
        }

        // Original image
        $relativePath = $rows[0]['url'];
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $relativePath;

        if (!file_exists($fullPath)) {
            echo json_encode([
                "status" => "error",
                "message" => "Image file not found on server"
            ]);
            return;
        }

        /* =========================
           THUMBNAIL PATH
           ========================= */

        $thumbDir = $_SERVER['DOCUMENT_ROOT'] . "/images/gallery/thumbs/";
        if (!is_dir($thumbDir)) {
            mkdir($thumbDir, 0777, true);
        }

        $fileName = pathinfo($fullPath, PATHINFO_FILENAME);
        $thumbnailFile = $fileName . "_thumb.webp";

        $thumbnailFullPath = $thumbDir . $thumbnailFile;
        $thumbnailRelativePath = "/images/gallery/thumbs/" . $thumbnailFile;

        /* =========================
           GENERATE THUMBNAIL
           ========================= */


        $generated = \Solobea\Dashboard\utils\Helper::generateCenteredWebpThumbnail(
            $fullPath,
            $thumbnailFullPath,
            300 // thumbnail size in px
        );

        if (!$generated || !file_exists($thumbnailFullPath)) {
            echo json_encode([
                "status" => "error",
                "message" => "Thumbnail generation failed"
            ]);
            return;
        }

        /* =========================
           UPDATE DATABASE
           ========================= */

        $db->update(
            'images',
            ['thumbnail_url' => $thumbnailRelativePath],
            ['id'=>$id]
        );

        echo json_encode([
            "status" => "success",
            "message" => "Thumbnail generated successfully"
        ]);
    }


    public function quality()
    {
        Authentication::requireAdmin();
        header("Content-Type: application/json");
        $input = json_decode(file_get_contents("php://input"), true);
        if (isset($input['id'])){
            $id= intval($input['id']);
            $db = Database::get_instance();

            $urls = $db->select("SELECT url FROM images WHERE id = '{$id}' LIMIT 1");

            if (!empty($urls) && count($urls) > 0) {

                // Relative path stored in DB
                $relativePath = $urls[0]['url'];
                // example: /images/gallery/gallery_68c6ded4bf322.webp

                // Build absolute filesystem path
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . $relativePath;

                if (!file_exists($fullPath)) {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Image file not found on server"
                    ]);
                    return;
                }
                $before = filesize($fullPath);

                // Reduce quality only if above size threshold
                $optimized = Helper::optimizeProjectImage(
                    $fullPath,   // source
                    $fullPath,   // overwrite same file
                    60,
                    100
                );
                $afterBytes = filesize($fullPath);
                $after = number_format(round($afterBytes / 1024, 2),2);
                if ($optimized) {
                    $db->update('images',['image_size'=>$after],['id'=>$id]);
                    $message = "Quality reduced successfully from" . $before . " to " . $after;
                    echo json_encode([
                        "status" => "success",
                        "message" => $message
                    ]);
                }
                else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Image optimization failed"
                    ]);
                }

            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Image record not found"
                ]);
            }
        }
        else{
            echo json_encode([
                "status" => "error",
                "message" => "Invalid Request"
            ]);
        }
    }



}