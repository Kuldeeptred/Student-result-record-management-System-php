<?php
require_once 'db.php'; 

$showResults = false;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $showResults = true;
    $student_id = intval($_GET['id']); 
    $sql = "SELECT * FROM results WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th { 
            background-color: #f2f2f2;
        }
       
    </style>
</head>
<body>

<h2>Student Results</h2>
<div class="search-form">
    <form method="GET" action="">
        <label for="id">Enter Student ID:</label>
        <input type="text" name="id" id="id" placeholder="Enter Student ID" 
               value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
        <button type="submit">Search Results</button>
    </form>
</div>

<?php if ($showResults): ?>
<table>
    <tr>
        <th>SUBJECT CODE</th>
        <th>SUBJECT NAME</th>
        <th>ESE ABSENT</th>
        <th>Theory Grade ESE</th>
        <th>Theory Grade PA/CA</th>
        <th>Theory Grade TOTAL</th>
        <th>Practical Grade ESE</th>
        <th>Practical Grade PA/CA</th>
        <th>Practical Grade TOTAL</th>
        <th>Subject Grade</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['subject_code']}</td>
                <td>{$row['subject_name']}</td>
                <td>{$row['ese_absent']}</td>
                <td>{$row['theory_ese']}</td>
                <td>{$row['theory_pa_ca']}</td>
                <td>{$row['theory_total']}</td>
                <td>{$row['practical_ese']}</td>
                <td>{$row['practical_pa_ca']}</td>
                <td>{$row['practical_total']}</td>
                <td>{$row['subject_grade']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No results found</td></tr>";
    }
    ?>
</table>
<?php endif; ?>

</body>
</html>
