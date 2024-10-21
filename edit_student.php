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

// Initialize variable $student = null; // Check if rollnumber is set in the URL
if (isset($_GET['rollnumber'])) {
    $rollnumber = $conn->real_escape_string($_GET['rollnumber']);
    $sql = "SELECT * FROM students WHERE rollnumber='$rollnumber'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    }
}

// Handle form submission for updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated data
    $name = $conn->real_escape_string(trim($_POST['name']));
    $dept = $conn->real_escape_string(trim($_POST['dept']));
    $section = $conn->real_escape_string(trim($_POST['section']));
    $bio = $conn->real_escape_string(trim($_POST['bio']));
    $gpa = $conn->real_escape_string(trim($_POST['gpa']));
    $gender = $conn->real_escape_string(trim($_POST['gender']));
    $descr = $conn->real_escape_string(trim($_POST['descr']));

    // Update query
    $updateSql = "UPDATE students SET name='$name', dept='$dept', section='$section', bio='$bio', gpa='$gpa',gender='$gender',descr='$descr' WHERE rollnumber='$rollnumber'";

    if ($conn->query($updateSql) === TRUE) {
        // Redirect to knowmore.php with the rollnumber
        header("Location: knowmore.php?rollnumber=$rollnumber");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Student</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <h2>Edit Student Profile</h2>

        <?php if ($student): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $student['name']; ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="dept">Department</label>
                    <input type="text" class="form-control" id="dept" name="dept" value="<?php echo $student['dept']; ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="section">Section</label>
                    <input type="text" class="form-control" id="section" name="section"
                        value="<?php echo $student['section']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea class="form-control" id="bio" name="bio" required><?php echo $student['bio']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="gpa">CGPA</label>
                    <input type="text" class="form-control" id="gpa" name="gpa" value="<?php echo $student['gpa']; ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="gender">gender</label>
                    <input type="text" class="form-control" id="gender" name="gender"
                        value="<?php echo $student['gender']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="descr">Small description</label>
                    <input type="text" class="form-control" id="descr" name="descr" value="<?php echo $student['descr']; ?>"
                        required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">No student found with that roll number.</div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>