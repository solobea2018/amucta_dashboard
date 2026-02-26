<?php
$url = "https://isabucoltd.com/";

// Fetch the page
$context = stream_context_create([
    "http" => [
        "header" => "User-Agent: Mozilla/5.0\r\n"
    ]
]);

$content = @file_get_contents($url, false, $context);

if ($content === false) {
    echo "Unable to load page.";
    exit;
}

// Fix relative links (CSS, JS, images)
$content = preg_replace_callback(
    '/\b(href|src)\s*=\s*["\'](?!https?:\/\/|\/\/|#|mailto:|tel:)([^"\']+)["\']/i',
    function ($matches) {
        $url = $matches[2];

        if (strpos($url, '/') === 0) {
            return $matches[1] . '="https://isabucoltd.com' . $url . '"';
        } else {
            return $matches[1] . '="https://isabucoltd.com/' . $url . '"';
        }
    },
    $content
);

// Output page
echo $content;