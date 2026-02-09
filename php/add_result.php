<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];
    $ese_absent = $_POST['ese_absent'];
    $theory_ese = $_POST['theory_ese'];
    $theory_pa_ca = $_POST['theory_pa_ca'];
    $theory_total = $_POST['theory_total'];
    $practical_ese = $_POST['practical_ese'];
    $practical_pa_ca = $_POST['practical_pa_ca'];
    $practical_total = $_POST['practical_total'];
    $subject_grade = $_POST['subject_grade'];

    $stmt = $conn->prepare("INSERT INTO results (subject_code, subject_name, ese_absent, theory_ese, theory_pa_ca, theory_total, practical_ese, practical_pa_ca, practical_total, subject_grade) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $subject_code, $subject_name, $ese_absent, $theory_ese, $theory_pa_ca, $theory_total, $practical_ese, $practical_pa_ca, $practical_total, $subject_grade);

    if ($stmt->execute()) {
        echo "New record added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student Result</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Add Student Result</h2>
<form method="POST">
    Subject Code: <input type="text" name="subject_code" required><br>
    Subject Name: <input type="text" name="subject_name" required><br>
    ESE Absent: <input type="text" name="ese_absent"><br>
    Theory Grade ESE: <input type="text" name="theory_ese"><br>
    Theory Grade PA/CA: <input type="text" name="theory_pa_ca"><br>
    Theory Grade TOTAL: <input type="text" name="theory_total"><br>
    Practical Grade ESE: <input type="text" name="practical_ese"><br>
    Practical Grade PA/CA: <input type="text" name="practical_pa_ca"><br>
    Practical Grade TOTAL: <input type="text" name="practical_total"><br>
    Subject Grade: <input type="text" name="subject_grade"><br>
    <input type="submit" value="Add Result">
</form>

</body>
</html>
