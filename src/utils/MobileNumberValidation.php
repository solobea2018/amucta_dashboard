<?php


namespace Solobea\Dashboard\utils;


class MobileNumberValidation {
    /**
     * Validates a Tanzanian mobile number.
     *
     * @param $number
     * @return bool
     */
    public function isValidTanzanianNumber($number): bool
    {
        // Remove spaces, dashes, or parentheses from the number
        $number = preg_replace('/[\s\-\(\)]/', '', $number);

        // Pattern to match Tanzanian mobile numbers
        $pattern = '/^(?:\+?255|0)(?:7[1-9]|6[1-9]|4[1-9])[0-9]{7}$/';

        // Validate the number using the regex
        return preg_match($pattern, $number) === 1;
    }
    public static function isMobileNumber($number): bool
    {
        // Remove spaces, dashes, or parentheses from the number
        $number = preg_replace('/[\s\-\(\)]/', '', $number);

        // Pattern to match Tanzanian mobile numbers
        $pattern = '/^(?:\+?255|0)(?:7[1-9]|6[1-9]|4[1-9])[0-9]{7}$/';

        // Validate the number using the regex
        return preg_match($pattern, $number) === 1;
    }
}