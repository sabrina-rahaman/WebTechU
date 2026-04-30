<?php

session_start();

require_once "db.php";
 
$message = "";

$savedEmail = "";
 
if (isset($_COOKIE["user_email"])) {

    $savedEmail = $_COOKIE["user_email"];

}
 
if (isset($_GET["registered"]) && $_GET["registered"] == "success") {

    $message = "Registration successful. Please login.";

}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);

    $password = $_POST["password"];
 
    if (empty($email) || empty($password)) {

        $message = "Email and password are required.";

    } else {

        $sql = "SELECT * FROM users WHERE email = :email";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":email", $email);

        $stmt->execute();
 
        if ($stmt->rowCount() == 1) {

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];

                $_SESSION["user_name"] = $user["name"];

                $_SESSION["user_email"] = $user["email"];
 
                if (isset($_COOKIE["last_login"])) {

                    $_SESSION["previous_login"] = $_COOKIE["last_login"];

                } else {

                    $_SESSION["previous_login"] = "This is your first login.";

                }
 
                setcookie("user_email", $user["email"], time() + (86400 * 30), "/");

                setcookie("last_login", date("Y-m-d H:i:s"), time() + (86400 * 30), "/");
 
                header("Location: dashboard.php");

                exit();

            } else {

                $message = "Invalid password.";

            }

        } else {

            $message = "No account found with this email.";

        }

    }

}

?>
 
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
 
<div class="container">
<h2>User Login</h2>
 
    <?php if (!empty($message)) { ?>
<p class="<?php echo ($message == 'Registration successful. Please login.') ? 'success' : 'error'; ?>">
<?php echo $message; ?>
</p>
<?php } ?>
 
    <form method="POST" action="">
<label>Email</label>
<input 

            type="email" 

            name="email" 

            placeholder="Enter your email" 

            value="<?php echo htmlspecialchars($savedEmail); ?>" 

            required
>
 
        <label>Password</label>
<input type="password" name="password" placeholder="Enter your password" required>
 
        <button type="submit">Login</button>
</form>
 
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
 
</body>
</html>
 