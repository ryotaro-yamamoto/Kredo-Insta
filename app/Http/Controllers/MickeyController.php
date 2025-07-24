<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MickeyController extends Controller
{
    public function index()
    {
        return view('users.mickey');
    }

    public function chat(Request $request)
    {
        $prompt = $request->input('message');

        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ],
        ]);

        $body = json_decode((string) $response->getBody(), true);
        return response()->json(['reply' => $body['choices'][0]['message']['content']]);
    }

}
