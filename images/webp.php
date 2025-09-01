<?php
function convertToWebP($source, $destination, $quality = 80) {
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

// Get command line arguments
if ($argc < 3) {
    echo "Usage: php convert.php input.jpg output.webp\n";
    exit(1);
}

$inputFile = $argv[1];
$outputFile = $argv[2];

if (convertToWebP($inputFile, $outputFile)) {
    echo "Converted: $inputFile -> $outputFile\n";
} else {
    echo "Failed to convert $inputFile\n";
}
?>
