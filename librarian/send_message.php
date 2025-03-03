<?php
session_start();
ob_start(); // Start output buffering
include('header.php');
include('../include/db.php');

// Check if librarian is logged in
if (!isset($_SESSION['admin_success_id'])) {
    header('location:login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch logged-in librarian's details
$librarian_id = $_SESSION['admin_success_id'];
$sql = "SELECT name FROM libraian WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $librarian_id);
$stmt->execute();
$librarian_result = $stmt->get_result();
$librarian_name = $librarian_result->fetch_assoc()['name'];

if (isset($_POST['send-message'])) {
    // Get form input values
    $student_id = $_POST['student_id'];
    $librarian_message = $_POST['message'];

    // Fetch the student's name based on the ID
    $stmt = $con->prepare("SELECT name FROM students WHERE uid = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $student_name = $result->fetch_assoc()['name'];

        // Insert the message into the message table
        $sql = "INSERT INTO message (student_id, student_name, librarian_name, librarian_message) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssss", $student_id, $student_name, $librarian_name, $librarian_message);

        if ($stmt->execute()) {
            // Redirect to the same page to prevent form resubmission
            header("Location: send_message.php?success=1");
            exit();
        } else {
            echo '<script>alert("Failed to send message: ' . $stmt->error . '");</script>';
        }
    } else {
        echo '<script>alert("Student ID not found!");</script>';
    }
    $stmt->close();
}

// Show success message if redirected after successful form submission
if (isset($_GET['success'])) {
    echo '<script>alert("Message sent successfully!");</script>';
}
?>

<!-- Content Header -->
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="">Send Message</a></li>
        </ul>
    </div>
</div>

<div class="row animated fadeInUp">
    <h4 class="section-subtitle"><b>Send Message to Student</b></h4>
    <div class="panel">
        <div class="panel-content">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="student_id">Student User ID</label>
                    <input type="text" name="student_id" id="student_id" class="form-control" placeholder="Enter Student ID" required onkeyup="fetchStudentName()">
                </div>
                <div class="form-group">
                    <label for="student_name">Student Name</label>
                    <input type="text" name="student_name" id="student_name" class="form-control" placeholder="Student Name will be auto-filled" readonly>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea name="message" id="message" class="form-control" placeholder="Enter your message here" required></textarea>
                </div>
                <button type="submit" name="send-message" class="btn btn-success">Send Message</button>
            </form>

            <!-- Display messages below the form -->
            <h3 class="mt-5">Messages</h3>
            <div class="table-responsive">
                <table class="data-table table table-striped nowrap table-hover table-bordered border" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Librarian Name</th>
                            <th>Librarian Message</th>
                            <th>Student Reply</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch messages from the database in ascending order by timestamp
                        $sql = "SELECT * FROM message ORDER BY timestamp ASC";
                        $result = $con->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['student_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['librarian_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['librarian_message']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['student_reply'] ? $row['student_reply'] : 'No reply yet') . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No messages found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript function to fetch the student name based on student ID
function fetchStudentName() {
    var student_id = document.getElementById('student_id').value;

    if (student_id.length > 0) {
        // Create an AJAX request to fetch the student name
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_student_name.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Update the student name field with the response
                document.getElementById('student_name').value = xhr.responseText;
            }
        };
        xhr.send('student_id=' + student_id);
    } else {
        document.getElementById('student_name').value = '';
    }
}
</script>

<?php 
ob_end_flush(); // End output buffering
include('footer.php'); 
?>
