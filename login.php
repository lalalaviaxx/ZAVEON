<?php
session_start();

$usersFile = __DIR__ . '/users.json';
if (!file_exists($usersFile)) {
    file_put_contents($usersFile, "{}");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $users = json_decode(file_get_contents($usersFile), true);

    if ($action === 'signup') {
        if (!isset($users[$username])) {
            $users[$username] = [
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role
            ];
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            header("Location: " . $_SERVER['PHP_SELF'] . "?signup=success");
            exit();
        } else {
            $message = '<span class="error">❌ Username already exists.</span>';
        }
    } elseif ($action === 'login') {
        $foundUser = null;
        $foundUsername = null;
        foreach ($users as $userKey => $userData) {
            if (strcasecmp($userData['email'], $email) === 0) {
                $foundUser = $userData;
                $foundUsername = $userKey;
                break;
            }
        }

        if ($foundUser !== null && password_verify($password, $foundUser['password'])) {
            $_SESSION['username'] = $foundUsername;
            $_SESSION['role'] = $foundUser['role'];

            if ($_SESSION['role'] === 'YG ADMIN') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $message = '<span class="error">❌ Invalid email or password.</span>';
        }
    }
}

if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
    $message = '<span class="success">✅ Signup successful. Please log in.</span>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>YG Entertainment Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;600;800&display=swap" rel="stylesheet">
<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Urbanist', sans-serif;
}
body {
    min-height: 100vh;
    background: 
        linear-gradient(to bottom, rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.65)),
        url('bp1.webp') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    color: #fff;
    overflow: hidden;
}
.wrapper {
    width: 100%;
    max-width: 400px;
    background: rgba(0, 0, 0, 0.65);
    border-radius: 20px;
    padding: 40px 30px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
    border: 1.5px solid rgba(255, 255, 255, 0.12);
    transition: all 0.3s ease-in-out;
}
.wrapper:hover {
    box-shadow: 0 25px 60px rgba(255, 255, 255, 0.12);
}
.site-header {
    text-align: center;
    margin-bottom: 25px;
}
.site-header h1 {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: 1px;
    text-shadow: 0 0 12px #fff;
}
.toggle-btns {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 30px;
}
.toggle-btns button {
    padding: 10px 26px;
    border-radius: 30px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    background: transparent;
    color: #fff;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.25s ease;
}
.toggle-btns button.active,
.toggle-btns button:hover {
    background: #fff;
    color: #111;
    box-shadow: 0 0 12px #fff;
}
form {
    display: flex;
    flex-direction: column;
    gap: 18px;
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
}
input[type="text"],
input[type="password"],
input[type="email"],
select {
    padding: 12px 16px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #fff;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}
input:focus,
select:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #fff;
    outline: none;
    color: #000;
}
input[type="submit"] {
    background: #fff;
    color: #000;
    border: none;
    padding: 14px;
    border-radius: 14px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.25s ease;
}
input[type="submit"]:hover {
    background: #e0e0e0;
    box-shadow: 0 0 12px #fff;
}
.message {
    margin-top: 18px;
    text-align: center;
    font-size: 0.95rem;
    min-height: 22px;
}
.message .success {
    color: #56f27c;
    font-weight: 700;
    text-shadow: 0 0 5px #56f27c;
}
.message .error {
    color: #f25a5a;
    font-weight: 700;
    text-shadow: 0 0 5px #f25a5a;
}
@media (max-width: 450px) {
    .wrapper {
        padding: 30px 20px;
    }
    .site-header h1 {
        font-size: 1.2rem;
    }
    .toggle-btns button {
        padding: 8px 18px;
        font-size: 0.85rem;
    }
    input[type="submit"] {
        font-size: 0.95rem;
    }
}
</style>

<script>
function toggleForm(type) {
    const login = document.getElementById('login-form');
    const signup = document.getElementById('signup-form');
    const loginBtn = document.getElementById('login-btn');
    const signupBtn = document.getElementById('signup-btn');

    if (type === 'login') {
        login.style.display = 'flex';
        signup.style.display = 'none';
        loginBtn.classList.add('active');
        signupBtn.classList.remove('active');
    } else {
        login.style.display = 'none';
        signup.style.display = 'flex';
        signupBtn.classList.add('active');
        loginBtn.classList.remove('active');
    }
}

window.onload = () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('signup') === 'success') {
        toggleForm('login');
    } else {
        toggleForm('login');
    }
};
</script>
</head>
<body>

<div class="wrapper">
    <header class="site-header">
        <h1>YG ENTERTAINMENT</h1>
    </header>

    <div class="toggle-btns">
        <button id="login-btn" onclick="toggleForm('login')">Login</button>
        <button id="signup-btn" onclick="toggleForm('signup')">Signup</button>
    </div>

    <form id="login-form" method="POST" autocomplete="off">
        <input type="hidden" name="action" value="login" />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="hidden" name="role" value="User" />
        <input type="submit" value="Login" />
    </form>

    <form id="signup-form" method="POST" style="display: none;" autocomplete="off">
        <input type="hidden" name="action" value="signup" />
        <input type="text" name="username" placeholder="Username" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="User">User</option>
            <option value="YG ADMIN">Admin</option>
        </select>
        <input type="submit" value="Signup" />
    </form>

    <div class="message"><?php echo $message; ?></div>
</div>

</body>
</html>
