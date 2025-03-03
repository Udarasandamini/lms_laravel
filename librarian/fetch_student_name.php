<?php
include('../include/db.php');

// Check if student_id is received
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Query the student table to get the student's name
    $stmt = $con->prepare("SELECT name FROM students WHERE uid = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo $result->fetch_assoc()['name']; // Return the student's name
    } else {
        echo ''; // Return an empty string if no student is found
    }

    $stmt->close();
}
?>
