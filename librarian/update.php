<?php
require_once '../include/db.php';

if (isset($_POST['save-btn'])) {
    $id = $_GET['id'];
    $book_name = $_POST['book_name'];
    $book_author = $_POST['book_author'];
    $book_quantity = $_POST['book_quantity'];
    $book_avilable = $_POST['book_avilable'];

    $sql = "UPDATE books SET book_name = '$book_name', book_author = '$book_author', book_quantity = '$book_quantity', book_avilable = '$book_avilable' WHERE id = '$id'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header('Location: managebook.php?success=Book updated successfully!');
        exit;
    } else {
        header('Location: managebook.php?error=Failed to update book!');
        exit;
    }
}
?>

