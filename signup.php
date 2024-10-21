<?php
session_start(); // Start the session

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "stu_details";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = '';
$name = '';
$email = '';
$password = '';
$rollnumber = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and sanitize it
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $conn->real_escape_string(trim($_POST['password']));
    $rollnumber = $conn->real_escape_string(trim($_POST['rollnumber']));

    // Validate fields
    if (empty($name) || empty($email) || empty($password)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Check if the email already exists
        $checkEmailSql = "SELECT * FROM users WHERE email='$email'";
        $emailResult = $conn->query($checkEmailSql);
        if ($emailResult->num_rows > 0) {
            $message = "Email already exists. Please use another one.";
        } else {
            // Insert the user into the database
            $sql = "INSERT INTO users (username, email, password,rollnumber) VALUES ('$name', '$email', '$password','$rollnumber')";
            $SQL1 = "INSERT INTO students (rollnumber) values('$rollnumber')";
            if ($conn->query($sql) === TRUE && $conn->query($SQL1) === TRUE) {
                $message = "Signup successful! You can now log in.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2e6ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #6a0dad;
            border-color: #6a0dad;
        }

        .btn-primary:hover {
            background-color: #5a0a9d;
            border-color: #5a0a9d;
        }
    </style>
</head>

<body>

    <div class="signup-container">
        <h2 class="text-center">Signup</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="rollnumber">Roll Number</label>
                    <input type="text" class="form-control" id="rollnumber" name="rollnumber"
                        value="<?php echo htmlspecialchars($rollnumber); ?>" placeholder="Enter 'staff' if applicable">
                    <small class="form-text text-muted">Please enter 'staff' if you are a staff member.</small>
                </div>
                <!-- Other form fields -->

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Signup</button>
            </form>
            <p class="text-center mt-3">Already have an account? <a href="./index.php">Login here</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>