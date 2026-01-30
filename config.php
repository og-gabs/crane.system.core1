<?php
// config.php

// Get API key from environment variable or define it here
$gemini_api_key = getenv('GEMINI_API_KEY');
if (!$gemini_api_key) {
    $gemini_api_key = 'AIzaSyBIo1ZdXa367PRS_O1r4FxwUwlW0XAt0pk'; // Replace with your actual Gemini API key
}

define('GEMINI_API_KEY', $gemini_api_key);
define(
    'GEMINI_API_URL',
    'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent'
);
