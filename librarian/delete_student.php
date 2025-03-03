<?php
include('../include/db.php');

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the student record
    $sql = "DELETE FROM `students` WHERE `id` = $id";
    
    if (mysqli_query($con, $sql)) {
        // If successful, redirect back to the students page with a success message
        header("Location: students.php?message=Student deleted successfully");
    } else {
        // If there's an error, display it
        echo "Error deleting record: " . mysqli_error($con);
    }
}
?>
