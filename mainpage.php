<?php
session_start(); // Start the session

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "stu_details";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$studentData = [];
$filterRange = "";

// Check if a range filter is set via GET request
if (isset($_GET['range'])) {
    switch ($_GET['range']) {
        case 'A':
            $filterRange = "WHERE rollnumber BETWEEN '220701001' AND '220701066'";
            break;
        case 'B':
            $filterRange = "WHERE rollnumber BETWEEN '220701067' AND '220701132'";
            break;
        case 'C':
            $filterRange = "WHERE rollnumber BETWEEN '220701133' AND '220701198'";
            break;
        case 'D':
            $filterRange = "WHERE rollnumber BETWEEN '220701199' AND '220701264'";
            break;
        case 'E':
            $filterRange = "WHERE rollnumber BETWEEN '220701265' AND '220701330'";
            break;
        default:
            break;
    }
}

// Fetch students data based on the selected range
$sql = "SELECT * FROM students $filterRange";
$result = $conn->query($sql);

// Store fetched student data
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $studentData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <header class="header col-md-12">
                <h4 class="text-center">Student Details</h4>
                <div class="search-container text-center">
                    <form method="POST" class="form-inline" id="searchForm">
                        <input type="text" name="rollnumber" class="form-control mr-2" placeholder="Enter Roll Number"
                            required>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </header>

            <!-- Sidebar -->
            <nav class="col-md-3 sidebar d-none d-md-block">
                <h5 class="text-center">Sections</h5>
                <ul class="nav flex-column">
                    <li class="nav-item text-center">
                        <a class="nav-link" href="?range=A">CSE A</a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link" href="?range=B">CSE B</a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link" href="?range=C">CSE C</a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link" href="?range=D">CSE D</a>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link" href="?range=E">CSE E</a>
                    </li>
                </ul>
            </nav>

            <!-- Mobile Dropdown Navbar -->
            <div class="d-md-none">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sections
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="?range=A">CSE A</a>
                        <a class="dropdown-item" href="?range=B">CSE B</a>
                        <a class="dropdown-item" href="?range=C">CSE C</a>
                        <a class="dropdown-item" href="?range=D">CSE D</a>
                        <a class="dropdown-item" href="?range=E">CSE E</a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ml-sm-auto col-lg-9 px-4" id="main-content">
                <div class="row" id="card-area">
                    <?php if (!empty($studentData)): ?>
                        <?php foreach ($studentData as $student): ?>
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="images/<?php echo strtolower($student['gender']); ?>.jpg"
                                            class="card-img-top">
                                        <p class="card-text">Name:
                                            <?php echo $student['name']; ?><br><?php echo $student['descr']; ?>
                                        </p>
                                        <p class="card-text">Department: <?php echo $student['dept']; ?>
                                            <?php echo $student['section']; ?>
                                        </p>
                                        <p class="card-text">Roll Number: <?php echo $student['rollnumber']; ?></p>
                                        <button onclick="loadMoreInfo('<?php echo $student['rollnumber']; ?>')"
                                            class="btn btn-primary">Show More</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">No students found in this range.</div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function loadMoreInfo(rollnumber) {
            // Load the knowmore.php page into the card area
            $('#card-area').load('knowmore.php?rollnumber=' + rollnumber, function (response, status, xhr) {
                if (status == "error") {
                    var msg = "Sorry but there was an error: ";
                    console.log(msg + xhr.status + " " + xhr.statusText);
                    $('#card-area').html(msg + xhr.status + " " + xhr.statusText); // Show error message
                }
            });
        }

        // Search functionality
        $('#searchForm').submit(function (event) {
            event.preventDefault(); // Prevent default form submission
            var rollnumber = $('input[name="rollnumber"]').val();
            $('#card-area').html('<div class="text-center">Loading...</div>'); // Show loading text
            $.get('knowmore.php', { rollnumber: rollnumber }, function (data) {
                $('#card-area').html(data); // Update card area with fetched data
            }).fail(function () {
                $('#card-area').html('<div class="alert alert-danger">Error loading data.</div>'); // Error message
            });
        });
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>