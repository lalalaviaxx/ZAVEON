<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $role = htmlspecialchars($_POST["role"]);
    $date = htmlspecialchars($_POST["date"]);
    $nationality = htmlspecialchars(trim($_POST["nationality"]));
    $age = (int) $_POST["age"];

    if (!$name || !$email || !$role || !$date || !$nationality || !$age || empty($_FILES["resume"]["name"]) || empty($_FILES["video"]["name"])) {
        $message = "Error: All fields including nationality, age, resume, and video uploads are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Error: Invalid email format.";
    } elseif ($age < 1 || $age > 120) {
        $message = "Error: Please enter a valid age between 1 and 120.";
    } else {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $resumeFileName = basename($_FILES["resume"]["name"]);
        $resumeTargetPath = $uploadDir . time() . "_resume_" . $resumeFileName;
        $resumeFileType = strtolower(pathinfo($resumeTargetPath, PATHINFO_EXTENSION));
        $allowedResumeTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif'];

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
                "resume" => $resumeTargetPath,
                "nationality" => $nationality,
                "age" => $age
            ];

            $submissionsFile = "submissions.json";
            $existing = [];

            if (file_exists($submissionsFile)) {
                $existing = json_decode(file_get_contents($submissionsFile), true) ?? [];
            }

            $existing[] = $submission;
            file_put_contents($submissionsFile, json_encode($existing, JSON_PRETTY_PRINT));

            $_SESSION['last_submission'] = $submission;
            $message = "Success: Your audition has been submitted.";
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
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('bp1.webp') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
      color: #000;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: rgba(0, 0, 0, 0.75);
      text-align: center;
      padding: 40px 20px;
      font-size: 32px;
      font-weight: bold;
      color: #fff;
    }

    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    section {
      width: 100%;
      max-width: 500px;
      background: rgba(0, 0, 0, 0.35);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.6);
      padding: 30px 25px;
      box-shadow: 0 8px 32px 0 rgba(255, 255, 255, 0.1);
      color: #eee;
      transition: box-shadow 0.3s ease;
    }

    section:hover {
      box-shadow: 0 12px 40px 0 rgba(255, 255, 255, 0.3);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
      font-size: 28px;
      color: #fff;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
      color: #ddd;
    }

    input, select {
      width: 100%;
      padding: 13px 12px;
      margin-bottom: 22px;
      border: 1px solid rgba(255, 255, 255, 0.5);
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.1);
      font-size: 15px;
      color: #eee;
      transition: all 0.3s ease;
    }

    input::placeholder {
      color: #bbb;
    }

    input:focus, select:focus {
      background: rgba(255, 255, 255, 0.25);
      border-color: #fff;
      color: #000;
      outline: none;
    }

    input[type="file"] {
      padding: 10px 5px;
      background: rgba(255, 255, 255, 0.1);
      color: #eee;
    }

    button {
      width: 100%;
      padding: 15px;
      background-color: rgba(255, 255, 255, 0.9);
      border: none;
      color: #000;
      font-size: 17px;
      font-weight: 700;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s;
    }

    button:hover {
      background-color: #0073e6;
      color: #fff;
      transform: scale(1.05);
    }

    #message {
      text-align: center;
      margin-top: 20px;
      color: #0f0;
      font-weight: bold;
    }

    #message.error {
      color: #f33;
    }

    #message a {
      color: #00e6e6;
      text-decoration: underline;
    }

    footer {
      text-align: center;
      padding: 15px;
      background-color: rgba(0, 0, 0, 0.75);
      color: #ccc;
    }

    footer a {
      color: #fff;
      text-decoration: underline;
    }

    @media (max-width: 600px) {
      section {
        padding: 25px 20px;
      }

      h2 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>

<header>YG Entertainment - Online Audition</header>

<main>
  <section>
    <h2>Audition Form</h2>
    <form method="POST" enctype="multipart/form-data">
      <label for="name">Full Name:</label>
      <input type="text" name="name" id="name" required />

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required />

      <label for="nationality">Nationality:</label>
      <input type="text" name="nationality" id="nationality" required />

      <label for="age">Age:</label>
      <input type="number" name="age" id="age" min="1" max="120" required />

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
      <p id="message" class="<?= strpos($message, 'Error') === 0 ? 'error' : '' ?>"><?= $message ?></p>
    <?php endif; ?>
  </section>
</main>

<footer>&copy; 2025 YG Entertainment | <a href="mailto:contact@ygentertainment.com">Contact Us</a></footer>

<script>
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('date').setAttribute('min', today);
</script>

</body>
</html>
