<?php
session_start(); // Make sure the session is started

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'lms'); // Update with your actual credentials

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

require_once 'header.php';

// Check if the user is logged in by verifying if user_success_id exists
if (!isset($_SESSION['user_success_id'])) {
    echo '<script>alert("User is not logged in. Please log in to send a message.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}
?>

<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Dashboard</a></li>
            <li><a href="">Message</a></li>
        </ul>
    </div>
</div>

<div class="row animated fadeInUp">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel-content" style="background:rgba(255, 255, 255, 0.45);padding:20px">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>Send Message to Librarian</h4>
                </div>
            </div>

            <div class="message-content" style="margin-top:20px;border:1px solid;padding:15px;box-shadow:2px 2px 3px #ccc">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea class="form-control" id="message" name="message" required></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="send-message">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

if (isset($_POST['send-message'])) {
    // Ensure user_success_id is set in session
    if (isset($_SESSION['user_success_id'])) {
        $message = $_POST['message'];
        $uid = $_SESSION['user_success_id']; // Get user ID from session

        // Prepare to check if the id exists in the student table
        $checkId = $con->prepare("SELECT COUNT(*) FROM `students` WHERE `id` = ?");
        $checkId->bind_param("s", $uid); // Update from "i" to "s"
        $checkId->execute();
        $checkId->bind_result($count);
        $checkId->fetch();
        $checkId->close(); // Close the prepared statement

        if ($count == 0) {
            echo '<script>alert("User ID does not exist in the student table.");</script>';
            exit();
        }

        // Prepare and execute the SQL statement to insert the message
        $sql = "INSERT INTO `messages`(`message`, `uid`) VALUES (?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $message, $uid); // Already correct, but double-check

        if ($stmt->execute()) {
            echo '<script>alert("Message sent successfully!");</script>';
        } else {
            echo '<script>alert("Failed to send message");</script>';
        }
        $stmt->close(); // Close the statement after executing
    }
}

?>

<?php require_once 'footer.php'; ?>
