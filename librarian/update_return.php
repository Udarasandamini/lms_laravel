<?php
include('../include/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $student_id = $_POST['student_id'];
    $return_date = $_POST['return_date'];

    // Define fine amount per day
    $fine_per_day = 1;

    // Fetch the due date
    $sql = "SELECT due_date FROM issue_book WHERE book_id = ? AND student_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $book_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $due_date = $row['due_date'];

        // Calculate fine
        $fine = 0;
        if ($return_date && strtotime($return_date) > strtotime($due_date)) {
            $overdue_days = (strtotime($return_date) - strtotime($due_date)) / (60 * 60 * 24);
            $fine = $overdue_days * $fine_per_day;
        }

        // Update return date and fine
        $update_sql = "UPDATE issue_book SET return_date = ?, fine = ? WHERE book_id = ? AND student_id = ?";
        $stmt = $con->prepare($update_sql);
        $stmt->bind_param('sdii', $return_date, $fine, $book_id, $student_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'fine' => $fine]);
        } else {
            echo json_encode(['success' => false, 'message' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No due date found']);
    }
}
?>
