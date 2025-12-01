<?php
require_once __DIR__ . "/../config.php";

$isLoggedIn = isset($_SESSION["user_id"]);
$username   = $isLoggedIn ? ($_SESSION["username"] ?? "User") : null;
?>

<nav class="navbar">
    <div class="links">
        <a href="index.php">Home</a>
        <a href="pages/yard_lookup.php">See Our Junk</a>
        <a href="pages/haul_away.php">Junk Issues?</a>
        <a href="pages/contact.php">Contact Us</a>
        <a href="pages/about.php">About Us</a>
    </div>
    <?php if ($isLoggedIn): ?>
        <div class="user_menu">
            <div class="user_dropdown">
                <button
                    id="username_button"
                    class="user_badge"
                    type="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Hi, <?= htmlspecialchars($username); ?>
                </button>
                <div class="dropdown_menu" role="menu" aria-label="User menu">
                    <a class="profile_link" href="pages/profile.php" role="menuitem">Profile</a>
                    <a class="logout_link" href="pages/auth_logout.php" role="menuitem">Sign Out</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="login_btn">
            <button type="button" id="openRegisterBtn" class="login_button">Register</button>
            <button type="button" id="openLoginBtn" class="login_button">Login</button>
        </div>
    <?php endif; ?>
</nav>

<script src="scripts/dropdown_menu.js" defer></script>

<?php if (!$isLoggedIn): ?>
    <?php include __DIR__ . "/auth_popup.php"; ?>
<?php endif; ?>
