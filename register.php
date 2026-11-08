<?php
session_start();
require_once 'db.php';

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $email === '' || $password === '') {
        $error = "Please fill in all fields.";
    } else {
        $password_hash = md5($password);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {
            $_SESSION['registration_success'] = "Registration successful! You can now log in.";
            header("Location: index.php");
            exit;
        } else {
            $error = "Registration failed: " . $stmt->error;
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
    <title>Register</title>
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
        <h1>Create account</h1>
        <p class="subtitle">Enter your details to create an account.</p>

        <?php if ($message): ?>
            <div class="alert alert-success" role="status"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-field">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" placeholder="Choose a username" autocomplete="username">
            </div>

            <div class="form-field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="you@example.com" autocomplete="email">
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Create a password" autocomplete="new-password">
            </div>

            <button class="primary-button" type="submit">Create account</button>
        </form>

        <p class="auth-switch">Already have an account? <a href="index.php">Log in</a></p>
    </main>
</body>
</html>
