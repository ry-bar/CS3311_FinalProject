<?php 

require __DIR__."/config.php";

$errors = [];


if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';


    if(!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)){
        $errors[] = "Username must be between 3 to 30 characters";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Please enter a valid form of email";
    }

    if(strlen($password) < 8){
        $errors[] = "Password must be at least 8 characters long";
    }

    if($password !== $confirm){
        $errors[] = "Passwords do not match";
    }

    if(!$errors){
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if($count > 0){
            $errors[] = "Username or email already exists in the database";
        }

        if(!$errors){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt1 = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES(?,?,?)");
            $stmt1->bind_param("sss", $username, $email, $hash);
            $stmt1->execute();
            $stmt1->close();
            header("Location:login.php?registered=1");
            exit;
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style src="style/styles.css"></style>
    <title>Register</title>
</head>
<body>
    <?php
    foreach($errors as $e){
        echo "<p style='color:red;'>$e</p>";
    }
    ?>
    <h2>Register Page</h2>

    <form method="post" novalidate>
        <label>Username <input type="text" name="username"></label><br>
        <label>Email <input type="email" name="email"></label><br>
        <label>Password <input type="password" name="password"></label><br>
        <label>Confirm <input type="password" name="confirm"></label><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>