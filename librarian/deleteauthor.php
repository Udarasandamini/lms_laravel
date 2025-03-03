<?php
include('../include/db.php'); // Database connection

if (isset($_GET['id'])) {
    $id = base64_decode($_GET['id']); // Decode the ID

    // Delete query
    $sql = "DELETE FROM authors WHERE id = $id";

    if (mysqli_query($con, $sql)) {
        header('Location: manageauthor.php?success=Author deleted successfully');
    } else {
        header('Location: manageauthor.php?error=Failed to delete author');
    }
}
?>
