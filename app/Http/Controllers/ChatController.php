<?php

// app/Http/Controllers/ChatController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $history = $request->input('history', []);
    
        $openaiMessages = [
            ['role' => 'system', 'content' => 'Tu es un assistant pour une plateforme d\'annonces. Réponds de façon concise, pertinente et amicale.']
        ];
    
        foreach ($history as $msg) {
            $openaiMessages[] = [
                'role' => $msg['role'],
                'content' => $msg['content']
            ];
        }
    
        $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => $openaiMessages,
        ]);
    
        if ($response->successful() && isset($response['choices'][0]['message']['content'])) {
            return response()->json([
                'reply' => $response['choices'][0]['message']['content']
            ]);
        } else {
            Log::error('Erreur OpenAI', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
    
            return response()->json([
                'reply' => "Désolé, une erreur est survenue.",
                'error' => $response->json()
            ]);
        }
    }
}
