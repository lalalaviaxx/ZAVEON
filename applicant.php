<?php 
session_start();

$submissionsFile = "submissions.json"; 
$messagesFile = "messages.json";

$submissions = [];  
$messages = [];

if (file_exists($submissionsFile)) {     
    $submissions = json_decode(file_get_contents($submissionsFile), true) ?? []; 
} else {     
    $error = "No submissions found."; 
}  

if (file_exists($messagesFile)) {
    $messages = json_decode(file_get_contents($messagesFile), true) ?? [];
}

$searchQuery = '';
$filteredSubmissions = $submissions;

if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $searchQuery = trim($_GET['search']);
    $filteredSubmissions = array_filter($submissions, function($applicant) use ($searchQuery) {
        $searchLower = mb_strtolower($searchQuery);
        return (mb_stripos($applicant['name'], $searchLower) !== false)
            || (mb_stripos($applicant['email'], $searchLower) !== false)
            || (mb_stripos($applicant['nationality'], $searchLower) !== false)
            || (mb_stripos($applicant['role'], $searchLower) !== false);
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
    $index = intval($_POST['index']);     
    if ($_POST['action'] === 'update' && isset($submissions[$index])) {         
        $newStatus = $_POST['status'] ?? 'Pending';         
        $submissions[$index]['status'] = $newStatus;

        $username = $submissions[$index]['name']; 
        $message = '';

        if ($newStatus === 'Accepted') {
            $message = "🎉 Congratulations $username! You have been accepted for the YG Entertainment audition.";
        } elseif ($newStatus === 'Declined') {
            $message = "😔 Sorry $username, you have not been selected for this round. Thank you for your effort!";
        }

        $submissions[$index]['message'] = $message;

        if ($message !== '') {
            $messages[$username] = $message;
            file_put_contents($messagesFile, json_encode($messages, JSON_PRETTY_PRINT));
        }

        file_put_contents($submissionsFile, json_encode($submissions, JSON_PRETTY_PRINT));         
        header("Location: " . $_SERVER['PHP_SELF'] . ($searchQuery ? '?search=' . urlencode($searchQuery) : ''));         
        exit;         
    }     

    if ($_POST['action'] === 'delete' && isset($submissions[$index])) {         
        $username = $submissions[$index]['name'] ?? null;
        if ($username && isset($messages[$username])) {
            unset($messages[$username]);
            file_put_contents($messagesFile, json_encode($messages, JSON_PRETTY_PRINT));
        }

        array_splice($submissions, $index, 1);         
        file_put_contents($submissionsFile, json_encode($submissions, JSON_PRETTY_PRINT));         
        header("Location: " . $_SERVER['PHP_SELF'] . ($searchQuery ? '?search=' . urlencode($searchQuery) : ''));         
        exit;     
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>YG Audition Applicants</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('bp1.webp') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            padding: 30px;
            margin: 0;
            min-height: 100vh;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 0;
        }
        body > * {
            position: relative;
            z-index: 1;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 0 0 10px rgba(0,0,0,0.85);
            font-weight: 700;
        }

        form.search-form {
            max-width: 400px;
            margin: 0 auto 30px auto;
            display: flex;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(12px);
        }

        form.search-form input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 12px 0 0 12px;
            background: rgba(255,255,255,0.85);
            color: #000;
            font-weight: 600;
            outline: none;
        }

        form.search-form button {
            padding: 0 18px;
            background: #000;
            border: none;
            border-radius: 0 12px 12px 0;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            color: #fff;
        }

        th {
            background: rgba(255, 255, 255, 0.85);
            font-weight: 700;
            text-transform: uppercase;
            color: #000;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        form.inline {
            display: inline;
        }

        select.status-select {
            padding: 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            border: 1px solid #ccc;
            background-color: rgba(255, 255, 255, 0.8);
            color: #000;
            cursor: pointer;
            min-width: 100px;
        }

        select.status-select.pending {
            border-color: #7f8c8d;
            color: #7f8c8d;
        }

        select.status-select.accepted {
            border-color: #27ae60;
            color: #27ae60;
        }

        select.status-select.declined {
            border-color: #c0392b;
            color: #c0392b;
        }

        button.delete {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }

        button.delete i {
            margin-right: 5px;
        }

        button.delete:hover {
            background: #c0392b;
        }

        a {
            color: #a8d0e6;
            text-decoration: underline;
            font-weight: 600;
        }

        a:hover {
            color: #f0f8ff;
            text-decoration: none;
        }

        .message {
            text-align: center;
            font-weight: bold;
            color: #eee;
            margin-top: 50px;
            text-shadow: 0 0 6px rgba(0,0,0,0.7);
        }
    </style>
</head>
<body>

<h1>YG Entertainment - Audition Applicants</h1>

<form method="get" class="search-form" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($searchQuery) ?>" />
    <button type="submit" title="Search"><i class="fas fa-search"></i></button>
</form>

<?php if (!empty($filteredSubmissions)): ?>
<table>
    <thead>
        <tr>
            <th>#</th><th>Name</th><th>Email</th><th>Nationality</th><th>Age</th><th>Role</th><th>Date</th>
            <th>Resume</th><th>Video</th><th>Status</th><th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filteredSubmissions as $index => $applicant): ?>
        <?php $status = $applicant['status'] ?? 'Pending'; $statusClass = strtolower($status); ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($applicant['name']) ?></td>
            <td><?= htmlspecialchars($applicant['email']) ?></td>
            <td><?= htmlspecialchars($applicant['nationality']) ?></td>
            <td><?= htmlspecialchars($applicant['age']) ?></td>
            <td><?= htmlspecialchars($applicant['role']) ?></td>
            <td><?= htmlspecialchars($applicant['date']) ?></td>
            <td><?= !empty($applicant['resume']) ? "<a href='".htmlspecialchars($applicant['resume'])."' target='_blank'>View</a>" : "N/A" ?></td>
            <td><?= !empty($applicant['video']) ? "<a href='".htmlspecialchars($applicant['video'])."' target='_blank'>View</a>" : "N/A" ?></td>
            <td>
                <form method="post" class="inline">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <input type="hidden" name="action" value="update">
                    <select name="status" class="status-select <?= $statusClass ?>" onchange="this.form.submit()">
                        <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Accepted" <?= $status === 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                        <option value="Declined" <?= $status === 'Declined' ? 'selected' : '' ?>>Declined</option>
                    </select>
                </form>
            </td>
            <td>
                <form method="post" class="inline" onsubmit="return confirm('Delete this applicant?');">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="delete"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <div class="message">No submissions found<?= $searchQuery ? " for '<strong>" . htmlspecialchars($searchQuery) . "</strong>'" : '' ?>.</div>
<?php endif; ?>

</body>
</html>
