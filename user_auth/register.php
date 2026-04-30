<?php
require_once "db.php";
 
$message = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
 
    if (empty($name) || empty($email) || empty($password)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        $checkSql = "SELECT id FROM users WHERE email = :email";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(":email", $email);
        $checkStmt->execute();
 
        if ($checkStmt->rowCount() > 0) {
            $message = "Email already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
 
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashedPassword);
 
            if ($stmt->execute()) {
                header("Location: login.php?registered=success");
                exit();
            } else {
                $message = "Registration failed.";
            }
        }
    }
}
?>
 
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
 
<div class="container">
<h2>Create Account</h2>
 
    <?php if (!empty($message)) { ?>
<p class="error"><?php echo $message; ?></p>
<?php } ?>
 
    <form method="POST" action="">
<label>Name</label>
<input type="text" name="name" placeholder="Enter your name" required>
 
        <label>Email</label>
<input type="email" name="email" placeholder="Enter your email" required>
 
        <label>Password</label>
<input type="password" name="password" placeholder="Enter your password" required>
 
        <button type="submit">Register</button>
</form>
 
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
 
</body>
</html>