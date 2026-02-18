<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\authentication\Authentication;
use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class AmuctaOutreach
{
    public function list()
    {
        Authentication::require_roles(['admin','dupr']);
        $query = "SELECT * FROM amucta_outreach ORDER BY year DESC, id DESC ";
        $researches = (new Database())->select($query);
        $tr = "";

        if (sizeof($researches) > 0) {
            foreach ($researches as $r) {
                $id = $r['id'];
                $tr .= "<tr>
<td>{$r['activity_title']}</td>
<td>{$r['year']}</td>
<td>{$r['author']}</td>
<td>
<button class='btn btn-danger hidden' onclick='deleteResource(\"amucta_outreach\",{$id})'><i class='bi bi-trash'></i></button>
<a href='/amucta-outreach/edit/{$id}' class='btn btn-secondary'><i class='bi bi-pencil'></i></a>
</td>
</tr>";
            }
        } else {
            $tr = "<tr><td colspan='7'>No research entries found</td></tr>";
        }

        $content = <<<HTML
<div class="flex flex-col">
    <div class="w-full">
        <button class="btn btn-complete" onclick="addOutreach()">Add Outreach</button>
    </div>
    <table class="solobea-table">
        <thead>
            <tr>
                <th>Title</th>               
                <th>Year</th>               
                <th>Author</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>$tr</tbody>
    </table>
</div>
HTML;

        MainLayout::render($content);
    }

    public function edit($params)
    {
        Authentication::require_roles(['admin','dupr']);
        if (isset($params) && !empty($params)){
            $id=intval($params[0]);
            $research=Database::get_instance()->select_prepared("select * from amucta_outreach where id=?",[$id],"i");
            if (!empty($research) && count($research)>0){
                $research=$research[0];
                $content= <<<FORM
<form class="form-container max-w-md"
      onsubmit="sendFormSweet(this,event)"
      action="/amucta-outreach/add"
      method="post"
      enctype="multipart/form-data">

    <input type="hidden" name="id" value="{$research['id']}">

    <div class="form-group">
        <label for="authors">Author(s)</label>
        <input type="text"
               id="authors"
               name="authors"
               value="{$research['author']}"
               class="form-control"
               placeholder="e.g. John Doe, Jane Smith"
               required>
    </div>

    <div class="form-group">
        <label for="title">Title</label>
        <input type="text"
               id="title"
               name="title"
               value="{$research['activity_title']}"
               class="form-control"
               placeholder="Enter publication title"
               required>
    </div>

    <div class="form-group">
        <label for="abstract_text"> Description</label>
        <textarea id="abstract_text"
                  name="abstract_text"
                  class="form-control"
                  rows="4"
                  placeholder="Enter abstract or brief description">{$research['description']}</textarea>
    </div>
    <div class="form-group">
        <label for="feature_image">Feature Image</label>
        <input type="file" 
               accept="image/*"
               id="feature_image" 
               name="feature_image" 
               class="form-control">
      </div>

    <div class="form-group">
        <label for="year">Year</label>
        <input type="text"
               id="year"
               name="year"
               value="{$research['year']}"
               class="form-control"
               placeholder="e.g. 2025">
    </div>

    <div class="form-group">
        <label for="link">External Link</label>
        <input type="url"
               id="link"
               name="link"
               value="{$research['link']}"
               class="form-control"
               placeholder="https://example.com (optional)">
    </div>

    <div class="form-group">
        <label for="file">Upload File (Optional)</label>
        <input type="file"
               id="file"
               name="file"
               class="form-control"
               accept=".pdf,.doc,.docx">
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status"
                name="status"
                class="form-control">
            <option value="complete">Complete</option>
            <option value="manuscript">Manuscript</option>
            <option value="onprogress">In Progress</option>
        </select>
    </div>

    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary">💾 Update</button>
    </div>

</form>
FORM;
                MainLayout::render($content,"","Edit research");
            }
        }
    }

    public function get_outreach($params)
    {
        if (isset($params) && !empty($params)) {
            try {
                $id = intval($params[0]);
                $db = Database::get_instance();

                $research = $db->select("SELECT * FROM amucta_outreach where id='{$id}' limit 1");

                if ($research && count($research) > 0) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        "status" => "success",
                        "data" => $research[0]
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Research entry not found"
                    ]);
                }
            } catch (\Exception $exception) {
                echo json_encode([
                    "status" => "error",
                    "message" => $exception->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid Request"
            ]);
        }
    }

    public function add()
    {
        Authentication::require_roles(['admin','dupr']);
        // Sanitize inputs
        $authors = htmlspecialchars($_POST['authors'] ?? "Anonymous");
        $title       = htmlspecialchars(trim($_POST['title'] ?? ''), ENT_QUOTES, 'UTF-8');
        $abstract_text = htmlspecialchars(trim($_POST['abstract_text'] ?? ''), ENT_QUOTES, 'UTF-8');
        $year    = htmlspecialchars(trim($_POST['year'] ?? ''), ENT_QUOTES, 'UTF-8');
        $link        = htmlspecialchars(trim($_POST['link'] ?? ''), ENT_QUOTES, 'UTF-8');
        $status = strtolower($_POST['status'] ?? 'complete');

        if (!$authors || !$title) {
            echo json_encode(['status' => "error", 'message' => "Author and title are required"]);
            return;
        }

        // Handle optional file upload
        $filePath = null;
        if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['file']['tmp_name'];
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($title));
            $uniqueId = uniqid();
            $fileName = $safeName . "_" . $uniqueId . "." . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/files/outreach/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmp, $targetFile)) {
                $filePath = "/files/outreach/" . $fileName;
            }
        }

        // Handle feature image upload
        $feature_image_path = null;
        if (!empty($_FILES['feature_image']['name'])) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/outreach/";
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
            $image_name = preg_replace('/\s+/', '_', 'Outreach') . '_' . time() . '.webp';
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
                $feature_image_path = "/images/outreach/" . $image_name;
            }
        }
        $db = Database::get_instance();
        $user_id = Authentication::user()->getId();
        $data = [
            'author' => $authors,
            'activity_title'       => $title,
            'description' => $abstract_text,
            'year'  => $year ?: null,
            'file_path'    => $filePath ?: null,
            'link'        => $link ?: null,
            'user_id'     =>$user_id,
            'status'      =>$status,
            'image'         =>$feature_image_path
        ];
        if (isset($_POST['id'])){
            $re_id=intval($_POST['id']);
            if ($feature_image_path==null){
                $feature_image_path=$db->select_prepared("select image from amucta_outreach where id=? limit 1",[$re_id],'i')[0]['image']??null;
                $data['image']=$feature_image_path;
            }
            if ($db->update("amucta_outreach",$data,['id'=>$re_id])) {
                echo json_encode(['status' => "success", 'message' => "Outreach updated successfully"]);
            }
        }
        elseif ($db->insert("amucta_outreach", $data)) {
            echo json_encode(['status' => "success", 'message' => "Outreach added successfully"]);
        } else {
            echo json_encode(['status' => "error", 'message' => "Failed to add outreach"]);
        }
    }

    public function get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode((Database::get_instance())->select("SELECT * FROM amucta_outreach ORDER BY year DESC, id DESC"));
    }
}