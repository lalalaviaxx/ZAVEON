<?php
session_start();

// Initialize artist categories if not set
if (!isset($_SESSION['artists'])) {
    $_SESSION['artists'] = [
        "Singers" => ["Jennie", "Rosé", "Taeyang", "AKMU"],
        "Dancers" => ["Lisa", "Minzy", "Taeyang"],
        "K-Pop Actors/Actresses" => ["Jisoo", "Seungri", "Dara"],
        "Rappers" => ["G-Dragon", "T.O.P", "Bobby", "CL"],
        "K-Pop Groups" => ["BLACKPINK", "BIGBANG", "TREASURE", "BABYMONSTER", "2NE1"]
    ];
}

$artists = $_SESSION['artists'];
$message = "";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';
    $category = $_POST['category'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $name = htmlspecialchars($name);

    if ($action === "add" && $category && $name && array_key_exists($category, $artists)) {
        if (!in_array($name, $artists[$category], true)) {
            $artists[$category][] = $name;
            $message = "✅ '$name' added to '$category'.";
        } else {
            $message = "⚠️ '$name' already exists in '$category'.";
        }
    }

    if ($action === "delete" && $category && array_key_exists($category, $artists)) {
        $index = array_search($name, $artists[$category]);
        if ($index !== false) {
            unset($artists[$category][$index]);
            $artists[$category] = array_values($artists[$category]);
            $message = "🗑️ '$name' removed from '$category'.";
        }
    }

    $_SESSION['artists'] = $artists;
    $_SESSION['message'] = $message;

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>YG Entertainment - Auditions Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: url('BP.webp') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        /* Slight dark overlay, no blur */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
            z-index: -1;
        }

        header {
            background: rgba(0, 0, 0, 1); /* solid black */
            color: #fff;
            padding: 2rem;
            text-align: center;
            border-bottom: 2px solid #ff1493; /* bright pink border */
            box-shadow: 0 4px 15px rgba(255, 20, 147, 0.4); /* subtle pink glow */
        }

        header h1 {
            font-size: 2.8rem;
            margin: 0;
            text-shadow: 0 0 10px #ff1493; /* pink glow on text */
        }

        header p {
            font-size: 1.2rem;
            color: #ff69b4; /* lighter pink for subtitle */
            text-shadow: 0 0 5px #ff69b4;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin: 1rem auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            width: fit-content;
        }

        form.add-form {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            margin: 2rem auto;
            padding: 1.5rem;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        form.add-form h2 {
            margin-top: 0;
        }

        select,
        input[type="text"] {
            width: 100%;
            padding: 0.6rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        button {
            background: #000;
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #444;
        }

        .delete-btn {
            background: none;
            border: none;
            color: #ff6b6b;
            cursor: pointer;
            font-size: 1.25rem;
            transition: transform 0.2s, color 0.2s;
        }

        .delete-btn:hover {
            color: #ff4d4d;
            transform: scale(1.2);
        }

        main {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 2rem;
        }

        .artist-category {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border-radius: 15px;
            margin: 1rem;
            padding: 1.5rem;
            width: 300px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .artist-category:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .artist-category h2 {
            margin-top: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 0.5rem;
        }

        .artist-category ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .artist-category li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .artist-name {
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.7);
        }

        footer {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            color: #ccc;
            text-align: center;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>YG Entertainment</h1>
        <p>Manage, Add, and Remove Artists & Groups</p>
    </header>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form class="add-form" method="POST" action="">
        <h2>Add New Artist or Group</h2>
        <input type="hidden" name="action" value="add" />
        <label for="category">Select Category:</label>
        <select id="category" name="category" required>
            <?php foreach (array_keys($artists) as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="name">Artist/Group Name:</label>
        <input type="text" id="name" name="name" required placeholder="Enter name (e.g. iKON)" />

        <button type="submit">Add</button>
    </form>

    <main>
        <?php foreach ($artists as $category => $names): ?>
            <section class="artist-category">
                <h2><?php echo htmlspecialchars($category); ?></h2>
                <ul>
                    <?php foreach ($names as $name): ?>
                        <li>
                            <span class="artist-name"><?php echo htmlspecialchars($name); ?></span>
                            <form method="POST" action="" style="margin: 0;">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>" />
                                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>" />
                                <button class="delete-btn" type="submit" title="Delete <?php echo htmlspecialchars($name); ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> YG Entertainment. All rights reserved.</p>
    </footer>
</body>
</html>
