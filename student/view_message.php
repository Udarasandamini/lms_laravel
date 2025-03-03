<?php
session_start(); // Ensure the session is started

require_once 'header.php';
include('../include/db.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_success_id'])) {
    echo '<script>alert("User is not logged in. Please log in to view your messages.");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}

$uid = $_SESSION['user_success_id']; // Get student user ID from session
?>

<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="dashboard.php">Dashboard</a></li>
            <li><a href="#">View Messages</a></li>
        </ul>
    </div>
</div>

<div class="row animated fadeInUp">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel-content" style="background:rgba(255, 255, 255, 0.45);padding:20px">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>Your Messages with the Librarian</h4>
                </div>
            </div>

            <!-- Display Messages and Replies -->
            <div class="message-reply-content" style="margin-top:20px;">
                <?php
                // Fetch all messages for the student
                $sql = "SELECT id, message, reply_message, student_reply, librarian_followup_reply 
                        FROM messages WHERE uid = ? ORDER BY id DESC";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("s", $uid);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">';
                        echo '<strong>Your Message:</strong> ' . htmlspecialchars($row['message']) . '<br>';

                        if (!empty($row['reply_message'])) {
                            echo '<strong>Librarian\'s Reply:</strong> ' . htmlspecialchars($row['reply_message']) . '<br>';
                        } else {
                            echo '<strong>Reply Status:</strong> No reply yet.<br>';
                        }

                        if (!empty($row['student_reply'])) {
                            echo '<strong>Your Follow-up Reply:</strong> ' . htmlspecialchars($row['student_reply']) . '<br>';
                        }

                        if (!empty($row['librarian_followup_reply'])) {
                            echo '<strong>Librarian\'s Follow-up Reply:</strong> ' . htmlspecialchars($row['librarian_followup_reply']) . '<br>';
                        }

                        // Check if the librarian has replied and if the student has already replied
                        if (empty($row['student_reply']) && !empty($row['reply_message'])) {
                            echo '<form method="post" action="">
                                <input type="hidden" name="message_id" value="' . $row['id'] . '">
                                <textarea class="form-control" name="student_reply" placeholder="Type your reply here..." required></textarea>
                                <button type="submit" name="send_reply" class="btn btn-primary">Send Reply</button>
                            </form>';
                        } elseif (empty($row['reply_message'])) {
                            echo '<p>Waiting for the librarian\'s reply.</p>';
                        }

                        echo '</div>'; // Close message container
                    }
                } else {
                    echo '<p>No messages found.</p>';
                }
                ?>
            </div>
            <br>

            <!-- Display messages from the message table below the form -->
            <h3 class="mt-5">Messages sent to me by Librarian</h3><br>
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
                        // Fetch messages for the logged-in student only
                        $sql = "SELECT * FROM message WHERE student_id = ? ORDER BY timestamp DESC";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param("s", $id);  // Use the logged-in student's ID ($uid) from session
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['student_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['librarian_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['librarian_message']) . '</td>';
                                
                                // If the student has replied, show the reply; otherwise, show the reply form
                                if (!empty($row['student_reply'])) {
                                    echo '<td>' . htmlspecialchars($row['student_reply']) . '</td>';
                                } else {
                                    echo '<td>
                                        <form method="post" action="">
                                            <input type="hidden" name="message_id" value="' . $row['id'] . '">
                                            <textarea class="form-control" name="student_reply" placeholder="Type your reply..." required></textarea>
                                            <button type="submit" name="send_reply_message" class="btn btn-primary mt-2">Reply</button>
                                        </form>
                                    </td>';
                                }

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

<?php
// Handle the student's reply to librarian messages
if (isset($_POST['send_reply'])) {
    $message_id = $_POST['message_id'];
    $student_reply = $_POST['student_reply'];

    // Update the student reply in the 'messages' table
    $sql = "UPDATE messages SET student_reply = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $student_reply, $message_id);

    if ($stmt->execute()) {
        echo '<script>alert("Reply sent successfully!");</script>';
        echo '<script>window.location.reload();</script>'; // Reload the page to show the new reply
    } else {
        echo '<script>alert("Failed to send reply. Please try again.");</script>';
    }
}

// Handle the student's reply to the message table (for different context if needed)
if (isset($_POST['send_reply_message'])) {
    $message_id = $_POST['message_id'];
    $student_reply_message = $_POST['student_reply'];

    // Update the student reply in the 'message' table
    $sql = "UPDATE message SET student_reply = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $student_reply_message, $message_id);

    if ($stmt->execute()) {
        echo '<script>alert("Reply sent successfully!");</script>';
    } else {
        echo '<script>alert("Failed to send reply. Please try again.");</script>';
    }
}
?>

<?php require_once 'footer.php'; ?>
