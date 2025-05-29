<?php
// Path to settings file
$settingsFile = 'settings.json';

// Define available languages
$languages = [
    'en' => 'English',
    'es' => 'Spanish',
    'fr' => 'French',
    'de' => 'German',
    'zh' => 'Chinese',
    'ar' => 'Arabic',
    'th' => 'Thai',
    'ko' => 'Korean',
];

// Load current settings or set defaults
if (file_exists($settingsFile)) {
    $settings = json_decode(file_get_contents($settingsFile), true);
} else {
    $settings = [
        'site_title' => 'YG Admin Panel',
        'admin_email' => 'admin@example.com',
        'language' => 'en',
    ];
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple sanitization
    $site_title = trim($_POST['site_title'] ?? '');
    $admin_email = trim($_POST['admin_email'] ?? '');
    $language = $_POST['language'] ?? 'en';

    if ($site_title === '' || $admin_email === '') {
        $message = 'Both Site Title and Admin Email are required.';
    } elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
    } elseif (!array_key_exists($language, $languages)) {
        $message = 'Selected language is invalid.';
    } else {
        // Save settings
        $settings = [
            'site_title' => htmlspecialchars($site_title, ENT_QUOTES),
            'admin_email' => htmlspecialchars($admin_email, ENT_QUOTES),
            'language' => $language,
        ];
        file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
        $message = 'Settings saved successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Settings - YG Admin</title>
<style>
  body {
    background-color: #000;
    color: #fff;
    font-family: Arial, sans-serif;
    margin: 0; padding: 20px;
  }
  h1 {
    border-bottom: 1px solid #fff;
    padding-bottom: 10px;
  }
  form {
    max-width: 400px;
    margin-top: 20px;
  }
  label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
  }
  input[type="text"], input[type="email"], select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    background: #111;
    border: 1px solid #fff;
    color: #fff;
    border-radius: 3px;
    appearance: none;
  }
  input[type="submit"] {
    margin-top: 20px;
    background: #fff;
    color: #000;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 3px;
    font-weight: bold;
  }
  input[type="submit"]:hover {
    background: #ccc;
  }
  .message {
    margin-top: 20px;
    padding: 10px;
    border-radius: 3px;
  }
  .error {
    background: #660000;
    color: #ffdddd;
  }
  .success {
    background: #006600;
    color: #ddffdd;
  }
  /* Simple arrow for select */
  select {
    background-image: linear-gradient(45deg, transparent 50%, white 50%), linear-gradient(135deg, white 50%, transparent 50%);
    background-position: calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px);
    background-size: 5px 5px, 5px 5px;
    background-repeat: no-repeat;
  }
</style>
</head>
<body>

<h1>Settings</h1>

<?php if ($message): ?>
  <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
    <?php echo $message; ?>
  </div>
<?php endif; ?>

<form method="POST" action="">

  <label for="site_title">Site Title</label>
  <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($settings['site_title']); ?>" required />

  <label for="admin_email">Admin Email</label>
  <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email']); ?>" required />

  <label for="language">Language</label>
  <select id="language" name="language" required>
    <?php foreach ($languages as $code => $name): ?>
      <option value="<?php echo $code; ?>" <?php if ($settings['language'] === $code) echo 'selected'; ?>>
        <?php echo $name; ?>
      </option>
    <?php endforeach; ?>
  </select>

  <input type="submit" value="Save Settings" />
</form>

</body>
</html>
