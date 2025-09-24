<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class News
{
    public function list()
    {
        $query = "SELECT * FROM news";
        $newsItems = (new Database())->select($query);
        $tr = "";

        if (sizeof($newsItems) > 0) {
            foreach ($newsItems as $news) {
                $tr .= "<tr>
<td>{$news['name']}</td>
<td>{$news['category']}</td>
<td>{$news['created_at']}</td>
<td>
<button class='btn btn-danger' onclick='deleteResource(\"news\",{$news['id']})'>Delete <i class='bi bi-trash'></i></button>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='4'>No news found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addNews()">Add News</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Created At</th>
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

        // Check if user is admin
        if (!$auth->is_admin()) {
            echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
            return;
        }

        // Get and sanitize inputs
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        $expire = $_POST['expire'] ?? null;
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $user_id = $auth->get_authenticated_user()->getId();

        if ($name === '' || $content === '') {
            echo json_encode(['status' => 'error', 'message' => 'Title and content are required']);
            return;
        }

        // Allow basic HTML in content but prevent scripts
        /*$allowed_tags = '<p><br><b><i><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6>';
        $content = strip_tags($content, $allowed_tags);*/

        $content=htmlspecialchars($content);

        // Handle feature image upload
        $feature_image_path = null;
        if (!empty($_FILES['feature_image']['name'])) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/news/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $tmp_name = $_FILES['feature_image']['tmp_name'];
            $ext = strtolower(pathinfo($_FILES['feature_image']['name'], PATHINFO_EXTENSION));

            // Validate image type
            if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                echo json_encode(['status'=>'error','message'=>'Feature image must be a valid image']);
                return;
            }

            // Validate size (<1MB)
            if ($_FILES['feature_image']['size'] > 1024*1024) {
                echo json_encode(['status'=>'error','message'=>'Feature image must be less than 1MB']);
                return;
            }

            // Convert to webp
            $image_name = preg_replace('/\s+/', '_', strtolower($name)) . '_' . time() . '.webp';
            $target_file = $uploadDir . $image_name;

            $image = null;
            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($tmp_name);
                    break;
                case 'png':
                    $image = imagecreatefrompng($tmp_name);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($tmp_name);
                    break;
                case 'webp':
                    $image = imagecreatefromwebp($tmp_name);
                    break;
            }

            if ($image) {
                if (!imageistruecolor($image)) {
                    $trueColor = imagecreatetruecolor(imagesx($image), imagesy($image));
                    imagecopy($trueColor, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    imagedestroy($image);
                    $image = $trueColor;
                }
                imagewebp($image, $target_file, 40); // low quality
                imagedestroy($image);
                $feature_image_path = "/images/news/" . $image_name;
            }
        }

        // Handle attachment upload (optional PDF)
        $attachment_path = null;
        if (!empty($_FILES['attachment']['name'])) {
            $attachDir = $_SERVER['DOCUMENT_ROOT'] . "/attachments/news/";
            if (!is_dir($attachDir)) mkdir($attachDir, 0777, true);

            $tmp_name = $_FILES['attachment']['tmp_name'];
            $ext = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));

            if ($ext !== 'pdf') {
                echo json_encode(['status'=>'error','message'=>'Attachment must be a PDF']);
                return;
            }

            if ($_FILES['attachment']['size'] > 5*1024*1024) { // 5MB
                echo json_encode(['status'=>'error','message'=>'Attachment must be less than 5MB']);
                return;
            }

            $attachment_name = preg_replace('/\s+/', '_', strtolower($name)) . '_' . time() . '.pdf';
            $target_file = $attachDir . $attachment_name;
            if (move_uploaded_file($tmp_name, $target_file)) {
                $attachment_path = "/attachments/news/" . $attachment_name;
            }
        }

        // Insert into database
        $db = new Database();
        $inserted = $db->insert('news', [
            'name' => $name,
            'feature_image' => $feature_image_path,
            'category' => $category,
            'expire' => $expire,
            'content' => $content,
            'attachment' => $attachment_path,
            'user_id' => $user_id,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        if ($inserted) {
            echo json_encode(['status'=>'success','message'=>'News added successfully']);
        } else {
            echo json_encode(['status'=>'error','message'=>'Failed to add news']);
        }
    }
    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Type: application/json");
        echo json_encode((new Database())->select("select * from news"));
    }
    public function all()
    {
        $news=(new Database())->select("select id, feature_image, name, content, created_at, expire, category, attachment from news order by created_at desc");
        $news_list="";
        if (!empty($news)){
            foreach ($news as $prog) {
                $name=$prog['name'];
                $feature_image=$prog['feature_image'];
                $date = date("F d, Y", strtotime($prog['created_at']));
                $content = $prog['content'];
                $expire  = $prog['expire'];
                $img     = "";
                if (strtotime(date("Y-m-d")) <= strtotime($expire)) {
                    $img = '<img src="https://www.heslb.go.tz/assets/images/new.gif" alt="new" class="new-icon">';
                }
                $shortContent = mb_substr(strip_tags($content), 0, 100);
                if (strlen(strip_tags($content)) > 100) {
                    $shortContent .= "...";
                }
                $attachment =$prog['attachment']??"#";
                $news_list.=<<<atta
            <div class="news-item">
                <img src="$feature_image" class="news-img" alt="News">
                <div class="news-content">
                    <div class="news-title" onclick="popHtml('$name','$content')">{$name} $img</div>
                    <p class="news-desc">
                        {$shortContent} <a href="$attachment" class="read-more">Link →</a>
                    </p>
                    <p class="news-date">📅 Posted on: {$date}</p>
                </div>
            </div>
atta;
            }
        }
        $content=<<<pl
<div class="">
        <h2 class="section-title">News List</h2>
        <div class="news-list">
            $news_list
        </div>        
    </div>
pl;

        $head="<link rel='stylesheet' href='/css/home.css'>";
        MainLayout::render($content,$head,"News");
    }
    public function detail()
    {
        MainLayout::render("");
    }

}