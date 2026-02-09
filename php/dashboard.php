<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
    <h2>Welcome, <?php echo $_SESSION['name']; ?></h2>
    <ul>
        <li><a href="view_results.php">View Results</a></li> 
        <li><a href="info.php">View Details</a></li>
        <li><a href="academic_user.php">View Academic</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>