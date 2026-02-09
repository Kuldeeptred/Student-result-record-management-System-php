<?php
include 'db.php';

$student = null;

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $student_id = $_POST['student_id'];
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>User deleted successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Fetch student details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch_student'])) {
    $student_id = $_POST['student_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_academic']) && $student) {
    $course = $_POST['course'];
    $branch = $_POST['branch'];
    $college = $_POST['college'];
    $status = $_POST['status'];
    $last_exam = $_POST['last_exam'];
    $cpi = $_POST['cpi'];
    $cgpa = $_POST['cgpa'];
    $final_sem = $_POST['final_sem'];
    $term_end = $_POST['term_end'];

    $sql = "INSERT INTO academic (course, branch, college, status, last_exam, cpi, cgpa, final_sem, term_end)
            VALUES ('$course', '$branch', '$college', '$status', '$last_exam', '$cpi', '$cgpa', '$final_sem', '$term_end')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Academic Info saved successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

$result = $conn->query("SELECT * FROM academic");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Info</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 60%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        label, input, select { display: block; width: 100%; margin-bottom: 10px; padding: 5px; }
        input[type="submit"] { background: orange; color: white; padding: 10px; border: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background: orange; color: white; }
    </style>
</head>
<body>

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
        <form action="" method="POST" style="margin-top: 20px;">
            <input type="hidden" name="student_id" value="<?= $student["id"] ?>">
            <input type="submit" name="delete_user" value="Delete User" style="background: red; color: white;">
        </form>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch_student'])): ?>
        <p style="color:red;">No student found with the given ID.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
