<?php
session_start();
require 'sender.php';
require 'receiver.php';

$answer = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear_history'])) {
        clearHistory();
    } else {
        $prompt = trim($_POST['prompt'] ?? '');

        if (!empty($prompt)) {
            $history = getConversationHistory();
            $apiResponse = sendToGemini($prompt, $history);
            $answer = extractGeminiText($apiResponse);

            if (strpos($answer, 'Error:') === 0) {
                $error = $answer;
            } else {
                addToHistory('user', $prompt);
                addToHistory('model', $answer);
            }
        }
    }
}

$chatHistory = getConversationHistory();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Gemini Chat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 80vh;
            max-height: 700px;
        }

        .header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #fafafa;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            animation: slideIn 0.3s ease-in;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 80%;
            padding: 12px 15px;
            border-radius: 8px;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .user .message-content {
            background: #667eea;
            color: white;
        }

        .model .message-content {
            background: #e0e0e0;
            color: #333;
        }

        .error {
            background: #ffebee !important;
            color: #c62828 !important;
            border-left: 4px solid #c62828;
        }

        .input-area {
            padding: 15px;
            background: white;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .input-wrapper {
            flex: 1;
            display: flex;
            gap: 10px;
        }

        textarea {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            max-height: 100px;
            min-height: 40px;
        }

        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        button {
            padding: 12px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        button:hover {
            background: #764ba2;
        }

        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .clear-btn {
            background: #f44336;
            padding: 10px 15px;
            font-size: 12px;
        }

        .clear-btn:hover {
            background: #d32f2f;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>🤖 Gemini Chat</h2>
        </div>

        <div class="chat-box" id="chatBox">
            <?php if (empty($chatHistory)): ?>
                <div style="text-align: center; color: #999; margin-top: 20px;">
                    <p>Start a conversation with Gemini AI</p>
                </div>
            <?php else: ?>
                <?php foreach ($chatHistory as $msg): ?>
                    <div class="message <?php echo $msg['role']; ?>">
                        <div class="message-content">
                            <?php echo nl2br(htmlspecialchars($msg['text'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="input-area">
            <form method="POST" style="flex: 1; display: flex; gap: 10px; width: 100%;">
                <div class="input-wrapper">
                    <textarea name="prompt" placeholder="Type your message..." required></textarea>
                    <button type="submit">Send</button>
                </div>
                <button type="submit" name="clear_history" value="1" class="clear-btn" title="Clear chat history">Clear</button>
            </form>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom
        const chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>

</body>

</html>