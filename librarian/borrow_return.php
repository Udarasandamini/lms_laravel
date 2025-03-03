<?php
ob_start(); 
include('header.php');
include('../include/db.php');

// Start the session to use session variables for notifications
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define fine amount per day (adjust as needed)
$fine_per_day = 1;

// Handle the book issue process
if (isset($_POST['issue_book'])) {
    $s_id = $_POST['student_id']; 
    $book_id = $_POST['book_id'];
    $issue_date = date('Y-m-d'); // Today's date
    $due_date = date('Y-m-d', strtotime('+14 days')); // 14 days from today

    // Insert into the database
    $sql = "INSERT INTO issue_book (student_id, book_id, issue_date, due_date) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('iiss', $s_id, $book_id, $issue_date, $due_date);
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        } else {
            // After successfully issuing the book, reduce the available quantity by 1
            $sql_update = "UPDATE books SET book_avilable = book_avilable - 1 WHERE id = ?";
            $stmt_update = $con->prepare($sql_update);
            $stmt_update->bind_param('i', $book_id);
            if ($stmt_update->execute()) {
                $_SESSION['message'] = 'Book Issued Successfully'; // Store message in session
            } else {
                $_SESSION['message'] = 'Failed to update book availability'; // Store message in session
            }
            header("Location: same_page.php"); // Redirect to avoid form resubmission
            exit;
        }
    } else {
        echo "Error preparing statement: " . $con->error;
    }
}

// Handle updating the return date and calculating fines
if (isset($_POST['update_return_date'])) {
    $book_id = $_POST['book_id'];
    $return_date = $_POST['return_date'];
    $s_id = $_POST['student_id'];

    // Fetch the due date from the database
    $sql = "SELECT due_date FROM issue_book WHERE book_id = ? AND student_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $book_id, $s_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $due_date = $row['due_date'];

        // Calculate fine based on return date and due date
        $fine = 0;
        if ($return_date && strtotime($return_date) > strtotime($due_date)) {
            $overdue_days = (strtotime($return_date) - strtotime($due_date)) / (60 * 60 * 24);
            $fine = $overdue_days * $fine_per_day;
        }

        // Update the return date and fine in the database
        $update_sql = "UPDATE issue_book SET return_date = ?, fine = ? WHERE book_id = ? AND student_id = ?";
        $stmt = $con->prepare($update_sql);
        $stmt->bind_param('sdii', $return_date, $fine, $book_id, $s_id);
        if ($stmt->execute()) {
            // After successful return update, increase the book availability by 1
            $sql_update_book = "UPDATE books SET book_avilable = book_avilable + 1 WHERE id = ?";
            $stmt_update_book = $con->prepare($sql_update_book);
            $stmt_update_book->bind_param('i', $book_id);
            if ($stmt_update_book->execute()) {
                $_SESSION['message'] = 'Book Returned Successfully'; // Store message in session
            } else {
                $_SESSION['message'] = 'Failed to update book availability'; // Store message in session
            }
            header("Location: borrow_return.php"); // Redirect after updating return date and fine
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "No due date found for the specified book and student.";
    }
}
?>

<!-- content HEADER -->
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="">Borrow Return Book Details</a></li>
        </ul>
    </div>
</div>  

<div class="row animated fadeInUp">
    <div class="col-12 col-sm-12 col-md-12">
        <div class="panel">
            <div class="panel-content">
                <div class="table-responsive">
                    <table id="basic-table" class="data-table table table-striped nowrap table-hover table-bordered border" cellspacing="0" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Book Name</th>
                                <th>Book Image</th>
                                <th>Book Issue Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Fine</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch the borrowed and returned books for all students, ordered by due_date and then by return_date
                            $sql = "SELECT issue_book.id AS id, students.name AS student_name, students.id AS student_id, 
                                    books.id AS book_id, books.book_name, books.book_image, issue_book.issue_date, 
                                    issue_book.due_date, issue_book.return_date, issue_book.fine 
                                    FROM issue_book 
                                    INNER JOIN books ON issue_book.book_id = books.id 
                                    INNER JOIN students ON issue_book.student_id = students.id 
                                    ORDER BY issue_book.due_date ASC, issue_book.return_date IS NULL ASC, issue_book.return_date ASC";

                            $stmt = $con->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $issue_date_display = date('Y-m-d', strtotime($row['issue_date']));
                                $due_date_display = date('Y-m-d', strtotime($row['due_date']));
                                $return_date_display = $row['return_date'] ? date('Y-m-d', strtotime($row['return_date'])) : '';
                                $fine = $row['fine'] ?? 0;
                                ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($row['id']) ?></td>
                                    <td><strong><?= htmlspecialchars($row['student_name']) ?></strong></td>
                                    <td class="text-center"><?= htmlspecialchars($row['student_id']) ?></td>
                                    <td><strong><?= htmlspecialchars($row['book_name']) ?></strong></td>
                                    <td class="text-center"><img src="../images/book/<?= htmlspecialchars($row['book_image']) ?>" alt="" width="100"></td>
                                    <td class="text-center"><?= htmlspecialchars($issue_date_display) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($due_date_display) ?></td>
                                    <td class="text-center">
                                        <form method="POST" action="">
                                            <input type="hidden" name="student_id" value="<?= htmlspecialchars($row['student_id']) ?>">
                                            <input type="hidden" name="book_id" value="<?= htmlspecialchars($row['book_id']) ?>">
                                            <input type="date" name="return_date" value="<?= htmlspecialchars($return_date_display) ?>" required>
                                    </td>
                                    <td class="text-center"><?= $fine > 0 ? 'Rs.' . htmlspecialchars($fine) : 'No Fine' ?></td>
                                    <td class="text-center">
                                        <button type="submit" name="update_return_date" class="btn btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Display session message if exists
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // Clear the message after displaying it
}

ob_end_flush();
include('footer.php'); 
?>
