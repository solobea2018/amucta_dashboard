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
                break;
            default:
                return false;
        }

        $result = imagewebp($image, $destination, $quality);
        imagedestroy($image);

        return $result;
    }
    public static function generateCenteredWebpThumbnail($sourcePath, $destinationPath, $thumbSize = 300): bool
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

        // Calculate the size of the square to crop
        $sideLength = min($sourceWidth, $sourceHeight);

        // Calculate crop start points (centered)
        $cropX = ($sourceWidth - $sideLength) / 2;
        $cropY = ($sourceHeight - $sideLength) / 2;

        // Crop the image to a square
        $croppedImage = imagecrop($sourceImage, [
            'x' => $cropX,
            'y' => $cropY,
            'width' => $sideLength,
            'height' => $sideLength
        ]);

        if ($croppedImage === false) {
            imagedestroy($sourceImage);
            return false;
        }

        // Resize cropped image to thumbnail size
        $thumbImage = imagecreatetruecolor($thumbSize, $thumbSize);
        imagecopyresampled(
            $thumbImage,
            $croppedImage,
            0, 0, 0, 0,
            $thumbSize, $thumbSize,
            $sideLength, $sideLength
        );

        // Save as WebP
        $result = imagewebp($thumbImage, $destinationPath, 90);

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($croppedImage);
        imagedestroy($thumbImage);

        return $result;
    }

}