<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Update existing record
        $id = $_POST['id'];
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
        $address = $_POST['address'];

        $sql = "UPDATE users SET 
                name='$name', abc_id='$abc_id', aadhaar_no='$aadhaar_no', dob='$dob', 
                gender='$gender', mobile='$mobile', email='$email', 
                parent_mobile='$parent_mobile', parent_email='$parent_email', 
                account_detail='$account_detail', address='$address' 
                WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Data updated successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    } else {
        // Insert new record
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

<div class="container">
    <h2>Enter Personal Information</h2>
    <form action="" method="POST">
        <label>Name:</label> <input type="text" name="name" required>
        <label>ABC ID:</label> <input type="text" name="abc_id" required>
        <label>Aadhaar No.:</label> <input type="text" name="aadhaar_no" required>
        <label>Date of Birth:</label> <input type="date" name="dob" required>
        <label>Gender:</label> 
        <select name="gender">
            <option value="M">Male</option>
            <option value="F">Female</option>
            <option value="Other">Other</option>
        </select>
        <label>Mobile No.:</label> <input type="text" name="mobile" required>
        <label>Email:</label> <input type="email" name="email" required>
        <label>Parent's Mobile No.:</label> <input type="text" name="parent_mobile">
        <label>Parent's Email:</label> <input type="email" name="parent_email">
        <label>Account Detail:</label> <input type="text" name="account_detail">
        <label>Password:</label> <input type="password" name="password" required>
        <label>Address:</label> <textarea name="address" required></textarea>
        
        <input type="submit" value="Save">
    </form>
</div>
    <h2>Stored Records</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>ABC ID</th>
            <th>Aadhaar No.</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Parent's Mobile</th>
            <th>Parent's Email</th>
            <th>Account</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <td><input type="text" name="name" value="<?= $row['name'] ?>"></td>
                <td><input type="text" name="abc_id" value="<?= $row['abc_id'] ?>"></td>
                <td><input type="text" name="aadhaar_no" value="<?= $row['aadhaar_no'] ?>"></td>
                <td><input type="date" name="dob" value="<?= $row['dob'] ?>"></td>
                <td>
                    <select name="gender">
                        <option value="M" <?= $row['gender'] == 'M' ? 'selected' : '' ?>>Male</option>
                        <option value="F" <?= $row['gender'] == 'F' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= $row['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </td>
                <td><input type="text" name="mobile" value="<?= $row['mobile'] ?>"></td>
                <td><input type="email" name="email" value="<?= $row['email'] ?>"></td>
                <td><input type="text" name="parent_mobile" value="<?= $row['parent_mobile'] ?>"></td>
                <td><input type="email" name="parent_email" value="<?= $row['parent_email'] ?>"></td>
                <td><input type="text" name="account_detail" value="<?= $row['account_detail'] ?>"></td>
                <td><textarea name="address"><?= $row['address'] ?></textarea></td>
                <td><input type="submit" name="update" value="Update"></td>
            </form>
        </tr>
        <?php } ?>
    </table>


</body>
</html>

<?php $conn->close(); ?>
