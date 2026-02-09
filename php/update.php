<!-- filepath: d:\Kuldeep_Clg\sem-4\Project\php\update.php -->
<?php
include 'db.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $new_mobile = $_POST['new_mobile'];
    $new_email = $_POST['new_email'];

    $update_sql = "UPDATE users SET mobile='$new_mobile', email='$new_email' WHERE id='$user_id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "<p style='color:green;'>Information updated successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Info</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 60%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        label, input { display: block; width: 100%; margin-bottom: 10px; padding: 5px; }
        input[type="submit"] { background: orange; color: white; padding: 10px; border: none; }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Mobile and Email</h2>
    <form method="POST">
        
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" id="user_id" required>

        <label for="new_mobile">New Mobile:</label>
        <input type="text" name="new_mobile" id="new_mobile" required>

        <label for="new_email">New Email:</label>
        <input type="email" name="new_email" id="new_email" required>

        <input type="submit" name="update_user" value="Update Info">
    </form>
</div>

</body>
</html>