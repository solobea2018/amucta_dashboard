<?php


namespace Solobea\Dashboard\utils;

class Sms
{
    public static function sendSms($phone, $message): bool|string
    {
        $url = "https://messaging.tetea.store/send/single";
        $accessToken = "YOUR_ACCESS_TOKEN"; // Replace with your actual token
        $sender = "0629077526"; // Or make this dynamic if needed

        $data = [
            "recipient"   => $phone,
            "sender"      => $sender,
            "content"     => $message,
            "created_at"  => date("Y-m-d H:i:s"),
            "status"      => "unsent"
        ];

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            error_log("cURL Error: " . $error);
            return false;
        }

        // Optionally log or process the response
        // error_log("Response: " . $response);

        return $response;
    }
}