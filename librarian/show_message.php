<?php
include('header.php');
include('../include/db.php');

// Handle librarian's reply to the student's original message or follow-up reply
if (isset($_POST['send_reply'])) {
    $reply_message = $_POST['reply_message'] ?? null;
    $message_id = $_POST['message_id'];
    $uid = $_POST['uid']; // Get the uid value from the form data
    $followup_reply = $_POST['librarian_followup_reply'] ?? null;

    // Update the `reply_message` or `librarian_followup_reply` fields for the specific message
    if (!empty($followup_reply)) {
        // If the librarian is replying to the student's follow-up reply
        $sql = "UPDATE messages SET librarian_followup_reply = ? WHERE id = ? AND uid = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sii', $followup_reply, $message_id, $uid);
    } else if (!empty($reply_message)) {
        // If the librarian is replying to the student's original message
        $sql = "UPDATE messages SET reply_message = ? WHERE id = ? AND uid = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sii', $reply_message, $message_id, $uid);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Reply sent successfully!");</script>';
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch messages, student names, and follow-up details
$sql = "
    SELECT 
        messages.id, 
        messages.uid, 
        messages.message, 
        messages.reply_message, 
        messages.student_reply, 
        messages.librarian_followup_reply, 
        students.name AS student_name
    FROM 
        messages
    INNER JOIN 
        students 
    ON 
        messages.uid = students.id"; // assuming `students.id` matches `messages.uid`

$result = $con->query($sql);
?>
<!-- content HEADER -->
<!-- ========================================================= -->
<div class="content-header">
    <!-- leftside content header -->
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="">Show Messages</a></li>
        </ul>
    </div>
</div>  

<div class="container">
    <h2>Messages sent by the Student to the librarian</h2><br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Message</th>
                <th>Librarian Reply</th>
                <th>Student Follow-up Reply</th>
                <th>Librarian Follow-up Reply</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $previous_uid = null; // To track the previous student ID
            while ($row = $result->fetch_assoc()) { 
                if ($row['uid'] != $previous_uid) {
                    // New student, reset the reply message
                    $reply_message = '';
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['uid']) ?></td>
                    <td><?= htmlspecialchars($row['student_name']) ?></td> 
                    <td><?= htmlspecialchars($row['message']) ?></td>
                    <td>
                        <?php if (empty($row['reply_message'])) { ?>
                            <!-- Reply form for messages that haven't been replied to -->
                            <form method="POST" action="">
                                <input type="hidden" name="message_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <input type="hidden" name="uid" value="<?= htmlspecialchars($row['uid']) ?>"> 
                                <input type="text" name="reply_message" value="<?= htmlspecialchars($reply_message) ?>" placeholder="Type your reply" required>
                                <button type="submit" name="send_reply" class="btn btn-primary">Send Reply</button>
                            </form>
                        <?php } else { ?>
                            <!-- Display the existing reply -->
                            <?= htmlspecialchars($row['reply_message']) ?>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (!empty($row['student_reply'])) { ?>
                            <!-- Display the student's follow-up reply -->
                            <?= htmlspecialchars($row['student_reply']) ?>
                        <?php } else { ?>
                            <span>No follow-up reply</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (empty($row['librarian_followup_reply']) && !empty($row['student_reply'])) { ?>
                            <!-- Reply form for the student's follow-up reply -->
                            <form method="POST" action="">
                                <input type="hidden" name="message_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <input type="hidden" name="uid" value="<?= htmlspecialchars($row['uid']) ?>"> 
                                <input type="text" name="librarian_followup_reply" value="<?= htmlspecialchars($reply_message) ?>" placeholder="Reply to follow-up" required>
                                <button type="submit" name="send_reply" class="btn btn-primary">Send Follow-up Reply</button>
                            </form>
                        <?php } else if (!empty($row['librarian_followup_reply'])) { ?>
                            <!-- Display the existing librarian's follow-up reply -->
                            <?= htmlspecialchars($row['librarian_followup_reply']) ?>
                        <?php } else { ?>
                            <span>No follow-up reply</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (empty($row['reply_message'])) { ?>
                            <span>Pending</span>
                        <?php } else { ?>
                            <span>Replied</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php
                $previous_uid = $row['uid']; // Update the previous student ID
            } 
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
