<?php
// sender.php

require 'config.php';

function sendToGemini($prompt, $conversationHistory = [])
{
  // Build contents with conversation history
  $contents = [];

  // Add previous messages if available
  if (!empty($conversationHistory)) {
    foreach ($conversationHistory as $msg) {
      $contents[] = [
        "role" => $msg['role'],
        "parts" => [["text" => $msg['text']]]
      ];
    }
  }

  // Add current user message
  $contents[] = [
    "role" => "user",
    "parts" => [["text" => $prompt]]
  ];

  $data = [
    "contents" => $contents
  ];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, GEMINI_API_URL . "?key=" . GEMINI_API_KEY);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
  ]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);

  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  if (curl_errno($ch)) {
    curl_close($ch);
    return [
      "error" => "Connection error: " . curl_error($ch)
    ];
  }

  curl_close($ch);

  $decoded = json_decode($response, true);

  if ($httpCode !== 200) {
    return [
      "error" => $decoded['error']['message'] ?? "API Error (HTTP $httpCode)"
    ];
  }

  return $decoded;
}
