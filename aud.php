<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $role = htmlspecialchars($_POST["role"]);
    $date = htmlspecialchars($_POST["date"]);

    // Check required fields and file uploads
    if (!$name || !$email || !$role || !$date || empty($_FILES["resume"]["name"]) || empty($_FILES["video"]["name"])) {
        $message = "Error: All fields including resume and video uploads are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Error: Invalid email format.";
    } else {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Handle Resume upload
        $resumeFileName = basename($_FILES["resume"]["name"]);
        $resumeTargetPath = $uploadDir . time() . "_resume_" . $resumeFileName;
        $resumeFileType = strtolower(pathinfo($resumeTargetPath, PATHINFO_EXTENSION));
        $allowedResumeTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif'];

        // Handle Video upload
        $videoFileName = basename($_FILES["video"]["name"]);
        $videoTargetPath = $uploadDir . time() . "_video_" . $videoFileName;
        $videoFileType = strtolower(pathinfo($videoTargetPath, PATHINFO_EXTENSION));
        $allowedVideoTypes = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'mkv', 'webm'];

        if (!in_array($resumeFileType, $allowedResumeTypes)) {
            $message = "Error: Only PDF, Word, PowerPoint, and image files are allowed for resume.";
        } elseif (!in_array($videoFileType, $allowedVideoTypes)) {
            $message = "Error: Only video files (mp4, mov, avi, wmv, flv, mkv, webm) are allowed for audition video.";
        } elseif (
            move_uploaded_file($_FILES["resume"]["tmp_name"], $resumeTargetPath) &&
            move_uploaded_file($_FILES["video"]["tmp_name"], $videoTargetPath)
        ) {
            $submission = [
                "name" => $name,
                "email" => $email,
                "role" => $role,
                "video" => $videoTargetPath,
                "date" => $date,
                "resume" => $resumeTargetPath
            ];

            $submissionsFile = "submissions.json";
            $existing = [];

            if (file_exists($submissionsFile)) {
                $existing = json_decode(file_get_contents($submissionsFile), true) ?? [];
            }

            $existing[] = $submission;
            file_put_contents($submissionsFile, json_encode($existing, JSON_PRETTY_PRINT));

            header("Location: applicant.php");
            exit();
        } else {
            $message = "Error uploading the files.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>YG Online Audition</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #1e1e1e, #121212);
      color: #fff;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #000;
      text-align: center;
      padding: 40px;
      font-size: 32px;
      font-weight: bold;
    }
    section {
      max-width: 600px;
      margin: 50px auto;
      background: rgba(255, 255, 255, 0.05);
      padding: 30px;
      border-radius: 10px;
      border: 1px solid #444;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
    }
    input, select {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: none;
      border-radius: 6px;
      background: #333;
      color: #fff;
    }
    input[type="file"] {
      padding: 8px;
    }
    button {
      width: 100%;
      padding: 14px;
      background-color: #e91e63;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background-color: #c2185b;
    }
    #message {
      text-align: center;
      margin-top: 20px;
      color: lightgreen;
    }
    footer {
      text-align: center;
      padding: 20px;
      background-color: #000;
      color: #fff;
      position: fixed;
      width: 100%;
      bottom: 0;
    }
  </style>
</head>
<body>

<header>YG Entertainment - Online Audition</header>

<section>
  <h2>Audition Form</h2>
  <form method="POST" enctype="multipart/form-data">
    <label for="name">Full Name:</label>
    <input type="text" name="name" id="name" required />

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required />

    <label for="role">Audition For:</label>
    <select name="role" id="role" required>
      <option value="">Select</option>
      <option value="singer">Singer</option>
      <option value="rapper">Rapper</option>
      <option value="dancer">Dancer</option>
      <option value="acting">Actress/Actor</option>
      <option value="idol">K-pop Idol</option>
    </select>

    <label for="video">Upload Audition Video:</label>
    <input type="file" name="video" id="video" accept="video/*" required />

    <label for="resume">Upload Resume/Portfolio:</label>
    <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx,.ppt,.pptx,image/*" required />

    <label for="date">Submission Date:</label>
    <input type="date" name="date" id="date" required />

    <button type="submit">Submit Audition</button>
  </form>

  <?php if ($message): ?>
    <p id="message"><?= $message ?></p>
  <?php endif; ?>
</section>

<footer>&copy; 2025 YG Entertainment | <a href="mailto:contact@ygentertainment.com" style="color:#e91e63;">Contact Us</a></footer>

<script>
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('date').setAttribute('min', today);
</script>

</body>
</html>
