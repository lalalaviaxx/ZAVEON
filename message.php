<?php
$messagesFile = "messages.json";
$messages = [];

if (file_exists($messagesFile)) {
    $messages = json_decode(file_get_contents($messagesFile), true) ?? [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Audition Messages</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #000;
            color: #fff;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 700px;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 40px;
            color: #fff;
            border-bottom: 2px solid #fff;
            padding-bottom: 10px;
        }

        .message-box {
            background: #111;
            border: 1px solid #444;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .message-box:hover {
            background: #1a1a1a;
        }

        .username {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 10px;
            color: #fff;
        }

        .message-box p {
            margin: 0;
            line-height: 1.6;
            color: #ccc;
        }

        .no-messages {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Messages to Applicants</h1>

        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $username => $msg): ?>
                <div class="message-box">
                    <div class="username"><?= htmlspecialchars($username) ?></div>
                    <p><?= nl2br(htmlspecialchars($msg)) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-messages">No messages yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
