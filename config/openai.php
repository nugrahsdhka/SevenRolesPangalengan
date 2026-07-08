<?php
// config/openai.php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),
    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),
    
    // Groq
    'groq_api_key' => env('GROQ_API_KEY'),
    'groq_model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),
    
    // Gemini
    'gemini_api_key' => env('GEMINI_API_KEY'),
    'gemini_model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    
    // OpenRouter
    'openrouter_api_key' => env('OPENROUTER_API_KEY'),
    'openrouter_model' => env('OPENROUTER_MODEL', 'meta-llama/llama-3.1-8b-instruct:free'),
];