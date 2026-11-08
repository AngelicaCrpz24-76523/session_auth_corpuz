<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#4c1d95">
    <title>Dashboard</title>
    <style>
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; background: #f7f5fc; color: #252033; font-family: Arial, sans-serif; }
        .site-header { width: 100%; min-height: 52px; color: #fff; background: #4c1d95; }
        .site-nav { display: flex; align-items: center; gap: 20px; width: min(100% - 32px, 1140px); min-height: 52px; margin: 0 auto; }
        .site-brand, .nav-link { color: #fff; text-decoration: none; }
        .site-brand { font-size: 15px; font-weight: 700; }
        .nav-link { color: #d3d5d7; font-size: 13px; }
        .nav-account { display: flex; align-items: center; gap: 12px; margin-left: auto; font-size: 13px; }
        .nav-logout { padding: 4px 8px; border: 1px solid #c4a6f5; border-radius: 4px; color: #eadcff; text-decoration: none; }
        .nav-logout:hover { color: #4c1d95; background: #fff; }
        .dashboard-container { width: min(100% - 32px, 1140px); margin: 28px auto; }
        .welcome-panel { padding: 32px 28px; border: 1px solid #e5dcf2; border-left: 4px solid #7c3aed; border-radius: 5px; background: #fff; }
        .welcome-panel h1 { margin: 0 0 6px; color: #5b21b6; font-size: 28px; }
        .welcome-panel p { margin: 0 0 14px; color: #60666d; font-size: 14px; }
        .primary-button { display: inline-block; padding: 8px 12px; border-radius: 5px; color: #fff; background: #6d28d9; font-size: 13px; text-decoration: none; }
        .primary-button:hover { background: #5b21b6; }
        .dashboard-details { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-top: 24px; }
        .detail-column { min-width: 0; padding: 14px; border: 1px solid #e8e1f3; border-radius: 5px; background: #fff; color: #55505f; font-size: 12px; }
        .detail-column p { margin: 6px 0; line-height: 1.5; }
        .detail-title { margin: 0 0 8px; font-size: 14px; font-weight: 600; }
        .status-title { color: #6d28d9; }
        .user-title { color: #6d28d9; }
        .session-title { color: #6d28d9; }
        .detail-column code { font-size: 11px; }
        .session-value { display: block; overflow-wrap: anywhere; }
        .session-note { margin-top: 10px !important; color: #70767c; font-size: 11px; }
        .dashboard-footer { display: flex; justify-content: flex-end; margin: 24px 8px 0; color: #000; font-size: 12px; }
        .footer-ticker { position: relative; display: block; width: 100%; min-width: 0; min-height: 1.2em; overflow: hidden; white-space: nowrap; }
        .footer-ticker-text { position: absolute; top: 0; left: 100%; display: inline-block; width: max-content; color: #000; white-space: nowrap; animation: footer-slide 24s linear infinite; }
        .visually-hidden { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
        @keyframes footer-slide { from { left: 100%; } to { left: calc(-100% - 100vw); } }
        @media (prefers-reduced-motion: reduce) { .footer-ticker-text { position: static; animation: none; white-space: normal; } }
        @media (max-width: 640px) {
            .site-nav { gap: 12px; width: calc(100% - 24px); }
            .site-brand { font-size: 13px; }
            .nav-account { gap: 8px; font-size: 12px; }
            .dashboard-container { width: calc(100% - 24px); margin-top: 18px; }
            .welcome-panel { padding: 24px 20px; }
            .welcome-panel h1 { font-size: 24px; }
            .dashboard-details { grid-template-columns: 1fr; gap: 12px; }
            .detail-column { padding: 12px; border: 1px solid #e3e5e8; border-radius: 5px; background: #fff; }
        }
    </style>
</head>
<body class="dashboard-page">
    <header class="site-header">
        <nav class="site-nav" aria-label="Main navigation">
            <a class="site-brand" href="dashboard.php">SessionAuthSystem</a>
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <div class="nav-account">
                <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                <a class="nav-logout" href="logout.php">Log out</a>
            </div>
        </nav>
    </header>

    <main class="dashboard-container">
        <section class="welcome-panel">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            <p>This page is protected and only visible to logged-in users.</p>
            <a class="primary-button explore-button" href="#account-details">Explore Dashboard</a>
        </section>

        <section class="dashboard-details" id="account-details" aria-label="Account details">
            <article class="detail-column">
                <h2 class="detail-title status-title">Account Status</h2>
                <p>Your session is active and protected against session fixation using <code>session_regenerate_id(true)</code>.</p>
            </article>
            <article class="detail-column">
                <h2 class="detail-title user-title">User Details</h2>
                <p><strong>User ID:</strong> <?= htmlspecialchars($_SESSION['user_id']) ?></p>
                <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
            </article>
            <article class="detail-column">
                <h2 class="detail-title session-title">Active Session ID</h2>
                <p><strong>Current Session ID:</strong></p>
                <code class="session-value"><?= htmlspecialchars(session_id()) ?></code>
                <p class="session-note">Generated after login using <code>session_regenerate_id(true)</code>.</p>
            </article>
        </section>

        <footer class="dashboard-footer">
            <span class="footer-ticker" aria-hidden="true">
                <span class="footer-ticker-text">This is a demo dashboard. You can log out and log back in to see session regeneration in action.</span>
            </span>
            <span class="visually-hidden">This is a demo dashboard. You can log out and log back in to see session regeneration in action.</span>
        </footer>
    </main>
</body>
</html>
