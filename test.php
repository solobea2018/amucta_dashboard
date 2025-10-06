<?php
require_once "vendor/autoload.php";

echo containsPhoneNumber("0629077526");

echo extractAllPhoneNumbers("hadg ahfg 0629077526 jdfk")[0];


function containsPhoneNumber(string $message): bool {
    // Find candidate substrings that look like phone numbers (digits, + and common separators)
    if (preg_match_all('/(\+?\d[\d\-\.\s()]{5,}\d)/', $message, $matches)) {
        foreach ($matches[0] as $candidate) {
            // Remove non-digits and count digits
            $digits = preg_replace('/\D+/', '', $candidate);
            $len = strlen($digits);
            // Accept plausible phone lengths (7 to 15 digits)
            if ($len >= 7 && $len <= 15) {
                return true;
            }
        }
    }
    return false;
}
function extractAllPhoneNumbers(string $message, bool $normalize = false, string $default_country_code = '255'): array {
    $found = [];
    if (preg_match_all('/(\+?\d[\d\-\.\s()]{5,}\d)/', $message, $matches)) {
        foreach ($matches[0] as $candidate) {
            $digits = preg_replace('/\D+/', '', $candidate);
            $len = strlen($digits);
            if ($len >= 7 && $len <= 15) {
                if ($normalize) {
                    if (strpos(trim($candidate), '+') === 0) {
                        $found[] = '+' . $digits;
                    } elseif (strpos($digits, '0') === 0) {
                        // convert leading 0 to country code (useful for local TZ numbers)
                        $found[] = '+' . $default_country_code . substr($digits, 1);
                    } else {
                        // assume it's international without plus; add plus
                        $found[] = '+' . $digits;
                    }
                } else {
                    // return the original matched format (trimmed)
                    $found[] = trim($candidate);
                }
            }
        }
    }
    return array_values(array_unique($found)); // unique + reindex
}