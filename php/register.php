<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : 'student';
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $created_at = date('Y-m-d H:i:s'); 

    if (!$name || !$email || !$password) {
        $error = "All fields are required.";
    } else {
        try {

            $allowed_tables = ['students', 'admins'];
            $table = ($user_type == 'admin') ? 'admins' : 'students';
            if (!in_array($table, $allowed_tables)) {
                throw new Exception("Invalid user type.");
            }

            $stmt = $conn->prepare("SELECT email FROM $table WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Email already registered.";
            } else {

                if ($user_type == 'admin') {
                    $stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param('sss', $name, $email, $password);
                } else {
                    $phone = $_POST['phone'] ?? null;
                    $course = $_POST['course'] ?? null;
                    $dob = $_POST['dob'] ?? null;
                    $gender = $_POST['gender'] ?? null;

                    if (!$phone || !$course || !$dob || !$gender) {
                        throw new Exception("All student fields are required.");
                    }

                    // Generate a unique student ID (e.g., using a timestamp or UUID)
                    $student_id = uniqid('STU_');

                    $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, phone, course, dob, gender, password, created_at) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('sssssssss', $student_id, $name, $email, $phone, $course, $dob, $gender, $password, $created_at);
                }

 
                if ($stmt->execute()) {
                    $success = "Registration Successful! You can now login.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        } catch (mysqli_sql_exception $e) {
            $error = "Database error: " . $e->getMessage();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration System</title>
    <style>
       
        .tab.active {
            background-color: #ddd;
            font-weight: bold;
        }
        .form-container {
            display: none;
        }
        .form-container.active {
            display: block;
        }
      
        .register-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-link {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registration System</h2>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <div class="tab-container">
            <button class="tab active" onclick="openForm('student-form', this)">Student Registration</button>
            <button class="tab" onclick="openForm('admin-form', this)">Admin Registration</button>
        </div>
        
        <div id="student-form" class="form-container active">
            <form method="POST" action="">
                <input type="hidden" name="user_type" value="student">
                
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" required>

                <label for="course">Course:</label>
                <input type="text" id="course" name="course" required>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>

                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="8">

                <button type="submit">Register as Student</button>
            </form>
        </div>
        

        <div id="admin-form" class="form-container">
            <form method="POST" action="">
                <input type="hidden" name="user_type" value="admin">
                
                <label for="admin_name">Full Name:</label>
                <input type="text" id="admin_name" name="name" required>

                <label for="admin_email">Email:</label>
                <input type="email" id="admin_email" name="email" required>

                <label for="admin_password">Password:</label>
                <input type="password" id="admin_password" name="password" required minlength="8">

                <button type="submit">Register as Admin</button>
            </form>
        </div>
        
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
    
    <script>
        function openForm(formId, element) {

            const formContainers = document.getElementsByClassName('form-container');
            for (let container of formContainers) {
                container.classList.remove('active');
            }
            

            document.getElementById(formId).classList.add('active');
            
            const tabs = document.getElementsByClassName('tab');
            for (let tab of tabs) {
                tab.classList.remove('active');
            }
            element.classList.add('active');
        }
    </script>
</body>
</html>