<?php

namespace Solobea\Dashboard\utils;
use DOMDocument;
use DOMXPath;

class Helper
{
    static function extractCleanBodyContent($html): bool|string
    {
        // Load HTML into DOMDocument
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Remove <style>, <script>, <link>, <meta>, and <head> tags
        $tagsToRemove = ['style', 'script', 'link', 'meta', 'head'];
        foreach ($tagsToRemove as $tag) {
            while (($elements = $dom->getElementsByTagName($tag))->length) {
                $elements->item(0)->parentNode->removeChild($elements->item(0));
            }
        }

        // Remove inline style attributes
        $xpath = new DOMXPath($dom);
        foreach ($xpath->query('//*[@style]') as $node) {
            $node->removeAttribute('style');
        }

        // Extract content inside <body>
        $body = $dom->getElementsByTagName('body')->item(0);
        if ($body) {
            $cleanHtml = '';
            foreach ($body->childNodes as $child) {
                $cleanHtml .= $dom->saveHTML($child);
            }
            return $cleanHtml;
        }

        // If no body, return cleaned full document
        return $dom->saveHTML();
    }

    public static function slugify($name): string
    {
        // Replace non-alphanumeric characters (except spaces) with a dash
        $name = preg_replace('/[^a-zA-Z0-9\s]/', '-', $name);

        // Replace one or more whitespace characters with a single dash
        $name = preg_replace('/\s+/', '-', $name);

        // Optional: convert to lowercase
        $name = strtolower($name);

        // Optional: remove duplicate dashes
        $name = preg_replace('/-+/', '-', $name);

        // Optional: trim dashes from start and end
        return trim($name, '-');
    }

    public static function extractIntro(string $html, int $limit = 230): string
    {
        // 1) Remove noisy blocks entirely
        $html = preg_replace('#<(script|style|noscript|iframe|svg|canvas|form|nav|footer|header)[\s\S]*?</\1>#iu', ' ', $html);

        // 2) Convert to plain text
        $text = strip_tags($html);

        // 3) Decode HTML entities and normalize whitespace
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', trim($text));

        // 4) If already short enough, return
        if (mb_strlen($text, 'UTF-8') <= $limit) {
            return $text;
        }

        // 5) Trim to limit without cutting a word
        $snippet = mb_substr($text, 0, $limit, 'UTF-8');
        $lastSpace = mb_strrpos($snippet, ' ', 0, 'UTF-8');
        if ($lastSpace !== false && $lastSpace > 0) {
            $snippet = mb_substr($snippet, 0, $lastSpace, 'UTF-8');
        }

        // 6) Add ellipsis
        return rtrim($snippet, " \t\n\r\0\x0B.,;:") . '…';
    }
    public static function getCurrentUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];

        return $protocol . "://" . $host . $requestUri;
    }
    public static function generateThumbnailWithPadding($sourcePath, $destinationPath, $thumbSize = 300): bool
    {
        $imageInfo = getimagesize($sourcePath);
        $imageType = $imageInfo[2];

        if ($imageType == IMAGETYPE_JPEG) {
            $sourceImage = imagecreatefromjpeg($sourcePath);
        } elseif ($imageType == IMAGETYPE_PNG) {
            $sourceImage = imagecreatefrompng($sourcePath);
        } elseif ($imageType == IMAGETYPE_GIF) {
            $sourceImage = imagecreatefromgif($sourcePath);
        } else {
            return false; // Unsupported format
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        // Calculate new dimensions to fit within the thumbnail size
        if ($sourceWidth > $sourceHeight) {
            $newWidth = $thumbSize;
            $newHeight = intval($sourceHeight * ($thumbSize / $sourceWidth));
        } else {
            $newHeight = $thumbSize;
            $newWidth = intval($sourceWidth * ($thumbSize / $sourceHeight));
        }

        // Create a blank square image with a white background
        $thumbImage = imagecreatetruecolor($thumbSize, $thumbSize);
        $white = imagecolorallocate($thumbImage, 255, 255, 255);
        imagefill($thumbImage, 0, 0, $white);

        // Center the resized image onto the blank square
        $xOffset = ($thumbSize - $newWidth) / 2;
        $yOffset = ($thumbSize - $newHeight) / 2;
        imagecopyresampled(
            $thumbImage,
            $sourceImage,
            $xOffset, $yOffset, 0, 0,
            $newWidth, $newHeight,
            $sourceWidth,
            $sourceHeight
        );

        $result = imagejpeg($thumbImage, $destinationPath, 90);

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($thumbImage);

        return $result;
    }
    public static function generateWebpThumbnailWithPadding($sourcePath, $destinationPath, $thumbSize = 300): bool
    {
        $imageInfo = getimagesize($sourcePath);
        $imageType = $imageInfo[2];

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                return false; // Unsupported format
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        // Calculate new dimensions to fit within the thumbnail size
        if ($sourceWidth > $sourceHeight) {
            $newWidth = $thumbSize;
            $newHeight = intval($sourceHeight * ($thumbSize / $sourceWidth));
        } else {
            $newHeight = $thumbSize;
            $newWidth = intval($sourceWidth * ($thumbSize / $sourceHeight));
        }

        // Create a blank square image with a white background
        $thumbImage = imagecreatetruecolor($thumbSize, $thumbSize);
        $white = imagecolorallocate($thumbImage, 255, 255, 255);
        imagefill($thumbImage, 0, 0, $white);

        // Center the resized image onto the blank square
        $xOffset = ($thumbSize - $newWidth) / 2;
        $yOffset = ($thumbSize - $newHeight) / 2;
        imagecopyresampled(
            $thumbImage,
            $sourceImage,
            $xOffset, $yOffset, 0, 0,
            $newWidth, $newHeight,
            $sourceWidth,
            $sourceHeight
        );

        // Output WebP instead of JPEG
        $result = imagewebp($thumbImage, $destinationPath, 90);

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($thumbImage);

        return $result;
    }
    public static function convertToWebP($source, $destination, $quality = 80): bool
    {
        $info = getimagesize($source);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                imagecreatefromwebp($source);
                break;
            case 'image/webp':
                $image= imagecreatefromwebp($source);
                break;
            default:
                return false;
        }

        $result = imagewebp($image, $destination, $quality);
        imagedestroy($image);

        return $result;
    }
    public static function reduceImageQuality($source, $destination, $quality = 80): bool
    {
        $info = getimagesize($source);
        if ($info === false) {
            return false;
        }

        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                if (!$image) return false;
                $result = imagejpeg($image, $destination, $quality);
                break;

            case 'image/png':
                $image = imagecreatefrompng($source);
                if (!$image) return false;

                imagepalettetotruecolor($image);
                imagealphablending($image, false);
                imagesavealpha($image, true);

                // PNG quality: 0 (best) to 9 (worst)
                $pngQuality = (int) round((100 - $quality) / 10);
                $pngQuality = max(0, min(9, $pngQuality));

                $result = imagepng($image, $destination, $pngQuality);
                break;

            case 'image/gif':
                $image = imagecreatefromgif($source);
                if (!$image) return false;
                $result = imagegif($image, $destination);
                break;

            case 'image/webp':
                $image = imagecreatefromwebp($source);
                if (!$image) return false;
                $result = imagewebp($image, $destination, $quality);
                break;

            default:
                return false;
        }

        imagedestroy($image);
        return $result;
    }
   public static function generateCenteredWebpThumbnail(
        string $sourcePath,
        string $destinationPath,
        int $thumbSize = 300,
        int $quality = 75
    ): bool
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $info = getimagesize($sourcePath);
        if ($info === false) {
            return false;
        }

        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $srcImg = imagecreatefromjpeg($sourcePath);
                break;

            case 'image/png':
                $srcImg = imagecreatefrompng($sourcePath);
                imagepalettetotruecolor($srcImg);
                imagealphablending($srcImg, false);
                imagesavealpha($srcImg, true);
                break;

            case 'image/gif':
                $srcImg = imagecreatefromgif($sourcePath);
                break;

            case 'image/webp':
                $srcImg = imagecreatefromwebp($sourcePath);
                break;

            default:
                return false;
        }

        if (!$srcImg) {
            return false;
        }

        $srcWidth  = imagesx($srcImg);
        $srcHeight = imagesy($srcImg);

        // Determine square crop size
        $cropSize = min($srcWidth, $srcHeight);

        // Center crop coordinates
        $srcX = (int)(($srcWidth - $cropSize) / 2);
        $srcY = (int)(($srcHeight - $cropSize) / 2);

        // Create thumbnail canvas
        $thumbImg = imagecreatetruecolor($thumbSize, $thumbSize);

        // Preserve transparency
        imagealphablending($thumbImg, false);
        imagesavealpha($thumbImg, true);
        $transparent = imagecolorallocatealpha($thumbImg, 0, 0, 0, 127);
        imagefilledrectangle($thumbImg, 0, 0, $thumbSize, $thumbSize, $transparent);

        // Crop and resize
        imagecopyresampled(
            $thumbImg,
            $srcImg,
            0,
            0,
            $srcX,
            $srcY,
            $thumbSize,
            $thumbSize,
            $cropSize,
            $cropSize
        );

        // Ensure destination directory exists
        $dir = dirname($destinationPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Save as WebP
        $saved = imagewebp($thumbImg, $destinationPath, $quality);

        imagedestroy($srcImg);
        imagedestroy($thumbImg);

        return $saved;
    }

    public static function is_human_ip($ip): bool
    {
        $non_human_ips = [
            "54.174.58.233",
            "54.174.58.243",
            "54.174.58.249",
            "17.22.237.31",
            "17.22.245.58",
            "17.241.219.188",
            "17.241.75.118",
            "104.28.253.46",
            "45.12.136.67",
            "196.251.250.14",
            "45.80.210.92",
            "205.169.39.184",
            "205.169.39.56",
            "205.169.39.58",
            "173.252.83.1",
            "173.252.83.7",
            "31.13.115.112",
            "69.171.251.9",
            "34.116.174.147",
            "34.125.135.158",
            "34.94.168.147",
            "66.102.8.128",
            "66.102.8.129",
            "66.102.8.132",
            "66.249.66.164",
            "66.249.66.41",
            "66.249.66.6",
            "66.249.66.7",
            "66.249.76.196",
            "66.249.76.197",
            "66.249.76.198",
            "66.249.76.200",
            "66.249.79.1",
            "66.249.79.7",
            "66.249.79.8",
            "66.249.83.100",
            "66.249.83.101",
            "66.249.83.102",
            "66.249.93.132",
            "66.249.93.133",
            "66.249.93.134",
            "72.14.199.102",
            "72.14.199.103",
            "72.14.199.96",
            "74.125.213.36",
            "74.125.213.37",
            "74.125.213.38",
            "62.93.165.39",
            "213.239.200.253",
            "88.198.147.151",
            "79.127.142.116",
            "172.104.31.80",
            "45.79.159.178",
            "40.77.177.122",
            "40.77.177.223",
            "40.77.177.243",
            "40.77.177.250",
            "40.77.178.152",
            "40.77.178.31",
            "65.55.210.247",
            "203.159.81.73",
            "51.158.36.186",
            "51.195.255.139",
            "219.100.37.236",
            "219.100.37.238",
            "219.100.37.244",
            "169.150.218.35",
            "15.204.228.11",
            "188.178.26.104",
            "38.60.208.208",
            "5.11.81.40","35.240.184.64","41.59.95.2","219.100.37.239","41.59.92.126","41.59.94.82","190.120.96.26","196.249.105.179","196.249.102.167","104.223.85.126",
            "41.63.42.12","14.139.239.22","54.186.12.147","146.70.201.94","54.215.184.11","66.249.93.65","197.250.135.200","197.250.133.195","198.55.98.232","193.233.75.252","3.86.26.238","103.143.237.3","20.74.83.27","64.233.173.32","64.233.173.193","149.56.150.92","203.192.225.14","66.249.93.64","189.74.111.48","156.228.81.63","35.212.167.137","159.203.137.70","81.17.20.98","189.37.74.236","13.59.14.59","89.25.164.98","196.251.86.154","185.252.222.38","66.249.93.66","51.75.236.152","34.140.156.167","46.32.87.242","64.233.173.163","64.233.173.225","178.176.74.65","94.23.188.218","66.249.81.225","35.92.162.195","176.31.139.28","194.28.195.120","80.80.196.226","188.187.179.231","85.12.4.110","109.252.141.248","46.8.62.104","37.204.160.239","198.244.242.32","54.38.147.24","38.114.122.142","51.195.215.81","51.195.183.237","35.184.97.117","51.195.183.223","54.148.146.173","54.38.147.95","74.7.242.1","188.166.243.22","176.100.9.102","52.64.68.107","54.38.147.32","159.195.58.198","54.36.232.187","46.232.251.204","46.232.250.68","198.244.242.124","34.22.134.213","46.232.248.150","159.195.56.133","52.167.144.173","40.77.167.77","66.249.93.162","198.244.183.222","91.227.68.147","5.39.109.174","138.199.142.69","91.98.176.87","198.244.242.47","198.244.242.135","52.167.144.238","40.77.167.235","114.143.215.162","173.79.36.247","46.232.249.132","159.195.28.74","104.28.232.136","107.172.204.165","66.249.73.13","167.114.139.96","66.249.90.33","66.249.90.34","66.249.90.35","197.186.9.236","66.249.82.65","66.249.73.12","142.4.217.39","196.249.103.233","69.176.1.4","162.39.56.164","196.249.102.110",
            "95.174.67.164","34.23.70.187","34.70.60.201","34.58.184.237","34.79.89.161","34.75.93.144","185.177.72.55","34.86.42.217","135.220.210.220","35.227.147.218","34.48.35.109","35.187.78.25","34.83.29.29","45.154.98.45","54.226.168.213","164.92.141.175","34.10.3.74","208.109.189.9","35.193.98.59","34.11.20.183","136.115.127.217","34.138.102.151","34.26.52.208","136.117.78.42","35.243.207.216","34.123.212.181","172.192.16.240","45.156.87.98","35.238.156.120","20.171.145.154","35.226.33.186","104.199.112.8","136.115.192.69","20.168.40.87","34.173.248.126","34.56.29.206","34.133.34.227","35.224.251.34","52.163.96.247","34.59.170.163","34.86.100.34","172.207.95.60","13.67.205.98","34.138.121.253","34.138.117.145","34.7.98.88","34.138.224.148","2.58.56.222","35.239.201.150","35.193.236.150","34.135.161.146","34.11.216.141","4.206.10.90","20.222.232.51","20.171.157.114","34.171.111.92","172.176.168.113","4.242.217.225","239.55.142.129","164.191.168.95","6.152.137.143","34.132.6.99","34.105.80.146","34.145.228.91","104.197.53.71","34.148.229.188","255.139.18.155","135.209.48.193","53.67.206.194","35.240.235.180","64.28.35.180","99.248.121.199","69.167.12.46","136.107.48.18",
        ];
        return !in_array($ip, $non_human_ips, true);
    }

}