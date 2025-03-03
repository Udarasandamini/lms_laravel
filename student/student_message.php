<?php
// Start session and connect to the database
session_start();
include ('../include/db.php');

// Check if the student is logged in
if (!isset($_SESSION['login_user'])) {
   // header("Location: login.php");
   // exit();
}

$_uid = $_SESSION['login_user'];  // Student's UID
$id = 'LIBRARIAN_UID';  // Set librarian's UID here

// Send a new message
if (isset($_POST['submit'])) {
    $message = mysqli_real_escape_string($db, $_POST['message']);
    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$uid', '$id', '$message')";
    mysqli_query($db, $sql);
    header("Location: student_message.php");  // Prevent form resubmission
    exit();
}

// Fetch all messages between this student and the librarian
$sql = "SELECT * FROM messages WHERE (sender_id = '$uid' AND receiver_id = '$id') OR (sender_id = '$id' AND receiver_id = '$uid') ORDER BY sent_at ASC";
$res = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat with Librarian</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <div class="chat-box">
        <h3>Chat with Librarian</h3>
        <div class="messages">
            <?php
            while ($row = mysqli_fetch_assoc($res)) {
                if ($row['sender_id'] == $uid) {
                    echo "<div class='student-msg'>{$row['message']}</div>";
                } else {
                    echo "<div class='librarian-msg'>{$row['message']}</div>";
                }
            }
            ?>
        </div>
        <form method="post">
            <input type="text" name="message" placeholder="Type a message..." required>
            <button type="submit" name="submit">Send</button>
        </form>
    </div>
</body>
</html>
