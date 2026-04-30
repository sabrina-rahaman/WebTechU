<?php
session_start();
 
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
 
$userName = $_SESSION["user_name"];
 
$previousLogin = "No previous login record found.";
 
if (isset($_SESSION["previous_login"])) {
    $previousLogin = $_SESSION["previous_login"];
}
?>
 
<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
 
<div class="container dashboard">
<h2>Dashboard</h2>
 
    <h3>Welcome, <?php echo htmlspecialchars($userName); ?>!</h3>
 
    <p>You have successfully logged in to your protected dashboard.</p>
 
    <div class="info-box">
<strong>Last Login:</strong>
<br>
<?php echo htmlspecialchars($previousLogin); ?>
</div>
 
    <a class="logout-btn" href="logout.php">Logout</a>
</div>
 
</body>
</html>