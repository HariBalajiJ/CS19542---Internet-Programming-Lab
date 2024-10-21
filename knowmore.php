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
  } else {
    $student = null;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Details - Know More</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>Student Profile</h2>
    </div>

    <div class="row justify-content-center">
      <?php if ($student): ?>
        <div class="col-md-6 mb-4 info-section">
          <img src="images/<?php echo strtolower($student['gender']); ?>.jpg" class="profile-image" alt="Student Image" />
          <h3><?php echo $student['name']; ?></h3>
          <p>
            <strong>Roll Number:</strong>
            <?php echo $student['rollnumber']; ?>
          </p>
          <p>
            <strong>Name:</strong>
            <?php echo $student['name']; ?>
          </p>
          <p>
            <strong>Department:</strong>
            <?php echo $student['dept']; ?>
          </p>
          <p>
            <strong>Section:</strong>
            <?php echo $student['section']; ?>
          </p>
          <p>
            <strong>Bio:</strong>
            <?php echo $student['bio']; ?>
          </p>
          <p>
            <strong>CGPA:</strong>
            <?php echo $student['gpa']; ?>
          </p>

          <!-- Edit button, only shown if logged in as a student -->
          <?php if (isset($_SESSION['rollnumber']) && $_SESSION['rollnumber'] != 'staff'): ?>
            <a href="edit_student.php?rollnumber=<?php echo $student['rollnumber']; ?>" class="btn btn-warning">Edit
              Profile</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning">
          No student found with that roll number.
        </div>
      <?php endif; ?>
    </div>

    <div class="footer">
      <p>
        &copy; <?php echo date("Y"); ?> Student Management System
      </p>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>