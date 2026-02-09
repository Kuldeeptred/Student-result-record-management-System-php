<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $abc_id = $_POST['abc_id'];
    $aadhaar_no = $_POST['aadhaar_no'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $parent_mobile = $_POST['parent_mobile'];
    $parent_email = $_POST['parent_email'];
    $account_detail = $_POST['account_detail'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $address = $_POST['address'];

    $sql = "INSERT INTO users (name, abc_id, aadhaar_no, dob, gender, mobile, email, parent_mobile, parent_email, account_detail, password, address)
            VALUES ('$name', '$abc_id', '$aadhaar_no', '$dob', '$gender', '$mobile', '$email', '$parent_mobile', '$parent_email', '$account_detail', '$password', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Data saved successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

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

$student = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch_student'])) {
    $student_id = $_POST['student_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 60%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }     
        label, input, select, textarea { display: block; width: 100%; margin-bottom: 10px; padding: 5px; }
        input[type="submit"] { background: orange; color: white; padding: 10px; border: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background: orange; color: white; }
    </style>
</head>
<body>
    </table>
<br><br>


<div class="container">
    <h2>Search Student by ID</h2>
    <form action="" method="POST">
        <label for="student_id">Enter Student ID:</label>
        <input type="number" name="student_id" id="student_id" required>
        <input type="submit" name="fetch_student" value="Fetch Info">
    </form>

    <?php if ($student): ?>
        <h2>Student Information</h2>
        <table>
            <tr><th>ID</th><td><?= $student["id"] ?></td></tr>
            <tr><th>Name</th><td><?= $student["name"] ?></td></tr>
            <tr><th>ABC ID</th><td><?= $student["abc_id"] ?></td></tr>
            <tr><th>Aadhaar No.</th><td><?= $student["aadhaar_no"] ?></td></tr>
            <tr><th>DOB</th><td><?= $student["dob"] ?></td></tr>
            <tr><th>Gender</th><td><?= $student["gender"] ?></td></tr>
            <tr><th>Mobile</th><td><?= $student["mobile"] ?></td></tr>
            <tr><th>Email</th><td><?= $student["email"] ?></td></tr>
            <tr><th>Parent's Mobile</th><td><?= $student["parent_mobile"] ?></td></tr>
            <tr><th>Parent's Email</th><td><?= $student["parent_email"] ?></td></tr>
            <tr><th>Account</th><td><?= $student["account_detail"] ?></td></tr>
            <tr><th>Address</th><td><?= $student["address"] ?></td></tr>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p style="color:red;">No student found with the given ID.</p>
    <?php endif; ?>
</div><br><br>
<div class="container">
        <h2>Update Mobile and Email</h2>
        <form action="update.php" method="GET">
            <input type="submit" value="Go to Update Page">
        </form>
    </div>

</body>
</html>

<?php $conn->close(); ?>
