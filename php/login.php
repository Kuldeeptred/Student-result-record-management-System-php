<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    if ($user_type == 'admin') {
        $stmt = $conn->prepare("SELECT id, name, password FROM admins WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT student_id, name, password FROM students WHERE email = ?");
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        if ($user_type == 'admin') {
            $stmt->bind_result($admin_id, $name, $hashed_password);
        } else {
            $stmt->bind_result($student_id, $name, $hashed_password);
        }
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            if ($user_type == 'admin') {
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['name'] = $name;
                $_SESSION['is_admin'] = true;
                header("Location: admin_dashboard.php");
            } else {
                $_SESSION['student_id'] = $student_id;
                $_SESSION['name'] = $name;
                $_SESSION['is_admin'] = false;
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid Credentials.";
        }
    } else {
        $error = "Invalid Credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Record/Result Management - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333333;
        }
        h3 {
            margin-bottom: 20px;
            color: #666666;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        select, input, button {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .login-link {
            margin-top: 15px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <h3>Access your account</h3> <!-- Added subtitle -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <select name="user_type" required>
                <option value="">Select User Type</option>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
    <div class="login-link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>