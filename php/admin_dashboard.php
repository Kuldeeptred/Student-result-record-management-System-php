<?php
session_start();


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>

ul li {
    display: inline-block;
    margin: 10px 15px;
}

ul li a {
    text-decoration: none;
    color: #007BFF;
    font-weight: bold;
    padding: 10px 15px;
    border: 2px solid #007BFF;
    border-radius: 5px;
    transition: all 0.3s ease;
}

ul li a:hover {
    color: #fff;
    background-color: #007BFF;
    text-decoration: none;
}

@media (max-width: 600px) {
    ul li {
        display: block;
        margin: 10px 0;
    }
}
</style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
    <p>This is the admin dashboard.</p>
    <ul>
        <li><a href="add_result.php">Add Results</a></li>
        
        <li><a href="infoadmin.php">Add infoadmin</a></li>
        <li><a href="academic.php">Add academic</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>

</html>