<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait ImageGeneratorTrait
{


    public function generateImage($keyword, $topic, $orientation = null)
    {
        $url = "https://api.unsplash.com/photos/random";
        $apiKey = env("UPSPLASH_API_ACCESS_KEY", "lQtp6Mdun-v4fMu5vDYJp1SbvSgmjgPMoH0cTWkmaIA");
        $payload = [
            "query" => $keyword,
            "topics" => $topic,
            "client_id" => $apiKey,
            "orientation" => $orientation,
        ];
        // logger("Payload", [$url, $payload]);
        $response = Http::get($url, $payload);
        if ($response->successful()) {
            // logger("response", [$response->json()]);
            $urls = $response->json()['urls'];
            $smImg = $urls['small'];
            $regularImg = $urls['regular'];
            $img = $regularImg ?? $smImg;
            return $img;
        } else {
            logger("Error getting Image url", [$response->json()]);
            throw new Exception(__("An error occured on our server"), 1);
        }
    }
}