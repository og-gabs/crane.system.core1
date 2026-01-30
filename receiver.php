<?php
// receiver.php

function extractGeminiText($response)
{
    // Check for errors
    if (isset($response['error'])) {
        return "Error: " . $response['error'];
    }

    if (!isset($response['candidates'][0]['content']['parts'][0]['text'])) {
        return "No response from Gemini. Please try again.";
    }

    return $response['candidates'][0]['content']['parts'][0]['text'];
}

function getConversationHistory()
{
    if (!isset($_SESSION['chat_history'])) {
        $_SESSION['chat_history'] = [];
    }
    return $_SESSION['chat_history'];
}

function addToHistory($role, $text)
{
    if (!isset($_SESSION['chat_history'])) {
        $_SESSION['chat_history'] = [];
    }
    $_SESSION['chat_history'][] = [
        'role' => $role,
        'text' => $text
    ];
}

function clearHistory()
{
    $_SESSION['chat_history'] = [];
}
