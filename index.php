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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and sanitize it
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = $conn->real_escape_string(trim($_POST['password']));

    // Query to check if the user exists in users table
    $sql = "SELECT rollnumber, rollnumber FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Set session variable for user role
        $_SESSION['rollnumber'] = $row['rollnumber'];

        // Redirect based on the value of rollnumber
        if ($_SESSION['rollnumber'] === 'staff') {
            header("Location: mainpage.php");
            exit();
        } else {
            // Not a staff member, redirect to knowmore.php with rollnumber
            header("Location: knowmore.php?rollnumber=" . $row['rollnumber']);
            exit();
        }
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2e6ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
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

    <div class="login-container">
        <h2 class="text-center">Login</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <p class="text-center mt-3">Don't have an account? <a href="signup.php">Signup here</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>