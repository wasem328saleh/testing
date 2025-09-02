<?php

namespace App\Services;

use Exception;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
class GeminiService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected Client $httpClient;
    protected string $baseUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";
    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
//        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $this->apiKey;
        $this->httpClient = new Client();
    }

    public function translation(string $word)
    {
        try {
            $prompt = "Identify the language of the word 'مرحبا'. Then, translate it into English, Turkish, and Arabic. The response should be a JSON object with 'source_language' and 'translations'. The 'translations' property should be an array of objects, each containing 'language' and 'translation' properties for the languages other than the source language.";
//            $prompt = "Identify the language of the word '".$word."'. Then, translate it into English, Turkish, and Arabic. The response should be a JSON object with 'source_language' and 'translations'. The 'translations' property should be an array of objects, each containing 'language' and 'translation' properties for the languages other than the source language.";
            // استخدام النموذج gemini-2.5-flash لتوليد المحتوى
            $result = $this->httpClient
                ->withHeaders(['Content-Type'=>"application/json"])
                ->post($this->baseUrl."?key=".$this->apiKey, [
                    'contents' => ['parts' => [['text' => $prompt]]]]);
            $translationText =json_decode($result->getBody());

            return $translationText;
//            $translationText = json_decode($result->getBody(), true);
            // تحويل نص الـ JSON إلى مصفوفة PHP
            $translations = json_decode($translationText, true);

//            if (json_last_error() !== JSON_ERROR_NONE || !is_array($translations)) {
//                throw new Exception("Invalid JSON response from Gemini.");
//            }

            return response()->json([
                'status' => 'success',
                'word' => $word,
                'translations_data' => $translations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during translation: ' . $e->getMessage(),
            ], 500);
        }
    }
}
