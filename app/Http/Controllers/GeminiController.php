<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Gemini;
use Gemini\Enums\ModelVariation;
use Gemini\GeminiHelper;
use Illuminate\Http\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GeminiController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        // حقن التبعية (Dependency Injection)
        $this->geminiService = $geminiService;
    }
    public function processRequest()
    {
        $yourApiKey = env('GEMINI_API_KEY');
//        $client = Gemini::client($yourApiKey);
        $client=Gemini::factory()
            ->withApiKey($yourApiKey)
            ->withBaseUrl('https://generativelanguage.example.com/v1beta') // default: https://generativelanguage.googleapis.com/v1beta/
            ->withHttpHeader('X-My-Header', 'foo')
            ->withQueryParam('my-param', 'bar')
            ->withHttpClient($guzzleClient = new \GuzzleHttp\Client(['timeout' => 30]))  // default: HTTP client found using PSR-18 HTTP Client Discovery
            ->withStreamHandler(fn(RequestInterface $request): ResponseInterface => $guzzleClient->send($request, [
                'stream' => true // Allows to provide a custom stream handler for the http client.
            ]))
            ->make();

        $result = $client->generativeModel(model:GeminiHelper::generateGeminiModel(
            variation: ModelVariation::FLASH,
            generation: 2.5,
            version: "preview-04-17"
        ))->generateContent('Hello');
        return $result->text(); // Hello! How can I assist you today?
//        $validatedData = $request->validate([
//            'prompt' => ['required', 'string', 'max:2000']);

$word="سور خارجي";

        $prompt="how to setup and install project laravel 10?";
        try {
            $result = $this->geminiService->translation($word);

            return response()->json(['response' => $result]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
