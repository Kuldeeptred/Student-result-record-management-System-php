<?php
include 'db.php';

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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_academic']) && $student) {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $course = $_POST['course'];
    $branch = $_POST['branch'];
    $college = $_POST['college'];
    $status = $_POST['status'];
    $last_exam = $_POST['last_exam'];
    $cpi = $_POST['cpi'];
    $cgpa = $_POST['cgpa'];
    $final_sem = $_POST['final_sem'];
    $term_end = $_POST['term_end'];

    if ($id) {
    
        $sql = "UPDATE academic SET 
                course='$course', branch='$branch', college='$college', 
                status='$status', last_exam='$last_exam', cpi='$cpi', 
                cgpa='$cgpa', final_sem='$final_sem', term_end='$term_end' 
                WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Record updated successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    } else {
       
        $sql = "INSERT INTO academic (course, branch, college, status, last_exam, cpi, cgpa, final_sem, term_end)
                VALUES ('$course', '$branch', '$college', '$status', '$last_exam', '$cpi', '$cgpa', '$final_sem', '$term_end')";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Academic Info saved successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_academic'])) {
    echo "<p style='color:red;'>Please fetch a valid student ID before adding academic details.</p>";
}


$editRow = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM academic WHERE id = $id");
    $editRow = $result->fetch_assoc();
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
        .edit-link { color: blue; text-decoration: none; }
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
        <p><strong>Name:</strong> <?= $student["name"] ?></p>
        <p><strong>Email:</strong> <?= $student["email"] ?></p>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch_student'])): ?>
        <p style="color:red;">No student found with the given ID.</p>
    <?php endif; ?>
</div>

<div class="container">
    <h2><?= $editRow ? "Edit Academic Information" : "Enter Academic Information" ?></h2>
    <form action="" method="POST">
        <?php if ($editRow): ?>
            <input type="hidden" name="id" value="<?= $editRow['id'] ?>">
        <?php endif; ?>
        <label>Course:</label> 
        <input type="text" name="course" value="<?= $editRow['course'] ?? '' ?>" required>
        <label>Branch:</label> 
        <input type="text" name="branch" value="<?= $editRow['branch'] ?? '' ?>" required>
        <label>College:</label> 
        <input type="text" name="college" value="<?= $editRow['college'] ?? '' ?>" required>
        <label>Academic Status:</label> 
        <select name="status">
            <option value="Studying" <?= isset($editRow) && $editRow['status'] == 'Studying' ? 'selected' : '' ?>>Studying</option>
            <option value="Completed" <?= isset($editRow) && $editRow['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
            <option value="Dropped" <?= isset($editRow) && $editRow['status'] == 'Dropped' ? 'selected' : '' ?>>Dropped</option>
        </select>
        <label>Last Appeared Exam:</label> 
        <input type="text" name="last_exam" value="<?= $editRow['last_exam'] ?? '' ?>">
        <label>CPI:</label> 
        <input type="number" step="0.01" name="cpi" value="<?= $editRow['cpi'] ?? '' ?>">
        <label>CGPA:</label> 
        <input type="number" step="0.01" name="cgpa" value="<?= $editRow['cgpa'] ?? '' ?>">
        <label>Final Semester:</label> 
        <input type="number" name="final_sem" value="<?= $editRow['final_sem'] ?? '' ?>">
        <label>Term End:</label> 
        <input type="text" name="term_end" value="<?= $editRow['term_end'] ?? '' ?>">
        
        <input type="submit" name="save_academic" value="<?= $editRow ? "Update" : "Save" ?>">
    </form>
</div>

<div class="container">
    <h2>Stored Academic Records</h2>
    <table>
        <tr>
            <th>Course</th>
            <th>Branch</th>
            <th>College</th>
            <th>Status</th>
            <th>Last Exam</th>
            <th>CPI</th>
            <th>CGPA</th>
            <th>Final Sem</th>
            <th>Term End</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row["course"] ?></td>
            <td><?= $row["branch"] ?></td>
            <td><?= $row["college"] ?></td>
            <td><?= $row["status"] ?></td>
            <td><?= $row["last_exam"] ?></td>
            <td><?= $row["cpi"] ?></td>
            <td><?= $row["cgpa"] ?></td>
            <td><?= $row["final_sem"] ?></td>
            <td><?= $row["term_end"] ?></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>" class="edit-link">Edit</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
