<?php
require __DIR__ . "/../config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: /CS3311_FinalProject/index.php");
    exit;
}

$userId = (int) $_SESSION["user_id"];
$errors = [];
$successMessage = "";
$passwordInputInvalid = false;
$submittedEmail = "";


function fetchUserById(mysqli $conn, int $userId): ?array {
    $stmt = $conn->prepare("SELECT username, email, password_hash FROM users WHERE id = ?");
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username, $email, $passwordHash);

    if ($stmt->fetch()) {
        $user = [
            "username"      => $username,
            "email"         => $email,
            "password_hash" => $passwordHash,
        ];
    } else {
        $user = null;
    }

    $stmt->close();
    return $user;
}

$user = fetchUserById($conn, $userId);

if (!$user) {
    $errors[] = "We couldn't load your account details right now. Please try again later.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $user) {
    $currentPassword = $_POST["current_password"] ?? "";
    $newEmail = trim($_POST["new_email"] ?? "");
    $newPassword = $_POST["new_password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";
    $submittedEmail = $newEmail;

    if ($currentPassword === "") {
        $errors[] = "Enter your current password before making changes.";
        $passwordInputInvalid = true;
    } elseif (!password_verify($currentPassword, $user["password_hash"])) {
        $errors[] = "The current password you entered is incorrect.";
        $passwordInputInvalid = true;
    } else {
        $changesMade = false;
        $pendingEmail = null;
        $pendingPasswordHash = null;

        if ($newEmail !== "" && $newEmail !== $user["email"]) {
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Please enter a valid email address.";
            } else {
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id <> ?");
                if ($stmt) {
                    $stmt->bind_param("si", $newEmail, $userId);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $errors[] = "That email address is already being used.";
                    } else {
                        $pendingEmail = $newEmail;
                    }

                    $stmt->close();
                } else {
                    $errors[] = "Unable to validate email right now.";
                }
            }
        }

        if ($newPassword !== "" || $confirmPassword !== "") {
            if ($newPassword === "" || $confirmPassword === "") {
                $errors[] = "Enter and confirm your new password.";
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = "New passwords do not match.";
            } elseif (strlen($newPassword) < 8) {
                $errors[] = "New password must be at least 8 characters.";
            } else {
                $pendingPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            }
        }

        if (!$errors) {
            if ($pendingEmail !== null) {
                $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $pendingEmail, $userId);
                    if ($stmt->execute()) {
                        $changesMade = true;
                        $user["email"] = $pendingEmail;
                    } else {
                        $errors[] = "Failed to update your email. Please try again.";
                    }
                    $stmt->close();
                } else {
                    $errors[] = "Unable to update your email right now.";
                }
            }

            if (!$errors && $pendingPasswordHash !== null) {
                $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $pendingPasswordHash, $userId);
                    if ($stmt->execute()) {
                        $changesMade = true;
                    } else {
                        $errors[] = "Failed to update your password. Please try again.";
                    }
                    $stmt->close();
                } else {
                    $errors[] = "Unable to update your password right now.";
                }
            }
        }

        if (!$errors) {
            if ($changesMade) {
                $successMessage = "Your account has been updated.";
                $user = fetchUserById($conn, $userId);
                if ($user && isset($user["username"])) {
                    $_SESSION["username"] = $user["username"];
                }
            } else {
                $successMessage = "No changes were made.";
            }
            $submittedEmail = "";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account</title>
    <base href="http://localhost/CS3311_FinalProject/">
    <link rel="stylesheet" href="style/styles.css">
    <script src="scripts/open_close_reg.js" defer></script>
</head>
<body>
    <?php include __DIR__ . "/../partials/navbar.php"; ?>

    <main class="profile_page">
        <section class="profile_content">
            <article id="overview" class="profile_card">
                <h2>Welcome back, <?= htmlspecialchars($user["username"] ?? ""); ?></h2>
                <p><strong>Username:</strong> <?= htmlspecialchars($user["username"] ?? ""); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user["email"] ?? ""); ?></p>
            </article>

            <article id="update" class="profile_card">
                <h2>Update Account</h2>
                <p>Verify your current password, then pick what you would like to change.</p>

                <?php if ($errors): ?>
                    <div class="form_message error">
                        <?= implode("<br>", array_map("htmlspecialchars", $errors)); ?>
                    </div>
                <?php elseif ($successMessage !== ""): ?>
                    <div class="form_message success">
                        <?= htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="profile_form">
                    <label>
                        Current Password
                        <input type="password" name="current_password" class="<?= $passwordInputInvalid ? "input_error" : ""; ?>" required>
                    </label>
                    <label>
                        New Email
                        <input type="email" name="new_email" value="<?= htmlspecialchars($submittedEmail); ?>">
                    </label>
                    <label>
                        New Password
                        <input type="password" name="new_password" placeholder="Leave blank to keep current">
                    </label>
                    <label>
                        Confirm New Password
                        <input type="password" name="confirm_password" placeholder="Leave blank to keep current">
                    </label>
                    <button type="submit">Save Changes</button>
                </form>
            </article>
        </section>
    </main>

    <?php include __DIR__ . "/../partials/footer.php"; ?>
</body>
</html>
