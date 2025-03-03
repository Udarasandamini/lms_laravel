<?php
include('../include/db.php'); // Database connection

if (isset($_POST['save-btn'])) {
    $id = $_GET['id'];
    $author_name = mysqli_real_escape_string($con, $_POST['author_name']);
    $author_status = mysqli_real_escape_string($con, $_POST['author_status']);

    // Update query
    $sql = "UPDATE authors SET author_name = '$author_name', status = '$author_status' WHERE id = $id";

    if (mysqli_query($con, $sql)) {
        header('Location: manageauthor.php?success=Author updated successfully');
    } else {
        header('Location: manageauthor.php?error=Failed to update author');
    }
}
?>
