<?php
include('../include/db.php');

// Fetch messages from the database
$sql = "SELECT * FROM message ORDER BY timestamp DESC";
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
