<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$message = $_SESSION['registration_success'] ?? "";
unset($_SESSION['registration_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($login === '' || $password === '') {
        $error = "Please fill in all fields.";
    } else {
        $password_hash = md5($password);

        $stmt = $conn->prepare("SELECT id, username FROM users WHERE (username = ? OR email = ?) AND password_hash = ? LIMIT 1");
        $stmt->bind_param("sss", $login, $login, $password_hash);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            session_regenerate_id(true);

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['login_session_id'] = session_id();

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#6d28d9">
    <title>Login</title>
    <style>
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; padding: 24px; display: grid; place-items: center; background: #f7f5fc; color: #252033; font-family: Arial, sans-serif; }
        .auth-card { width: min(100%, 400px); padding: 30px; border: 1px solid #e8e1f3; border-top: 4px solid #7c3aed; border-radius: 8px; background: #fff; box-shadow: 0 8px 24px rgba(64, 36, 105, .09); }
        h1 { margin: 0 0 8px; color: #4c1d95; font-size: 25px; }
        .subtitle { margin: 0 0 24px; color: #666; }
        .form-field { margin-bottom: 16px; }
        .form-field label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 600; }
        .form-field input { width: 100%; padding: 10px 11px; border: 1px solid #cfd3da; border-radius: 5px; font: inherit; }
        .form-field input:focus { border-color: #7c3aed; outline: 2px solid rgba(124, 58, 237, .15); }
        .primary-button { display: inline-block; width: 100%; padding: 10px 14px; border: 0; border-radius: 5px; color: #fff; background: #6d28d9; font: inherit; text-align: center; text-decoration: none; cursor: pointer; }
        .primary-button:hover { background: #5b21b6; }
        .auth-switch { margin: 20px 0 0; color: #666; font-size: 14px; text-align: center; }
        a { color: #6d28d9; }
        .alert { margin-bottom: 16px; padding: 10px 12px; border-radius: 5px; font-size: 14px; }
        .alert-error { color: #a32121; background: #fdecec; }
        .alert-success { color: #176b3a; background: #eaf7ef; }
        @media (max-width: 480px) { .auth-card { padding: 22px; } }
    </style>
</head>
<body>
    <main class="auth-card">
        <h1>Login</h1>
        <p class="subtitle">Sign in to your account.</p>

        <?php if ($error): ?>
            <div class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="alert alert-success" role="status"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-field">
                <label for="login">Username or Email</label>
                <input id="login" type="text" name="login" placeholder="Enter your username or email" autocomplete="username" required>
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
            </div>

            <button class="primary-button" type="submit">Log in</button>
        </form>

        <p class="auth-switch">Don't have an account? <a href="register.php">Register here</a></p>
    </main>
</body>
</html>
