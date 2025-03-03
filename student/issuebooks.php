<?php
include('header.php');
include('../include/db.php');

// Fetch the student ID from the session
$s_id = $_SESSION['user_success_id'];

// Define fine amount per day (adjust as needed)
$fine_per_day = 1;

// Handle the book issue process
if (isset($_POST['issue_book'])) {
    $book_id = $_POST['book_id'];
    $issue_date = date('Y-m-d'); // Set issue date to today

    // Calculate due date as two weeks from the issue date
    $due_date = date('d-m-Y', strtotime($issue_date));
    $due_date_obj = DateTime::createFromFormat('d-m-Y', $due_date);
    $due_date_obj->modify('+14 days');
    $due_date = $due_date_obj->format('d-m-Y');

    // Insert the new book issue record with the correct issue date and due date
    $sql = "INSERT INTO issue_book (student_id, book_id, issue_date, due_date) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('iiss', $s_id, $book_id, $issue_date, $due_date);
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
}

// Handle updating the return date and calculating fines
if (isset($_POST['update_return_date'])) {
    $book_id = $_POST['book_id'];
    $return_date = $_POST['return_date'];

    // Fetch the due date from the database
    $sql = "SELECT due_date FROM issue_book WHERE book_id = ? AND student_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $book_id, $s_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $due_date = $row['due_date'];

    // Calculate fine based on return date and due date
    $fine = 0;
    if (strtotime($return_date) > strtotime($due_date)) {
        $overdue_days = (strtotime($return_date) - strtotime($due_date)) / (60 * 60 * 24);
        $fine = $overdue_days * $fine_per_day;
    }

    // Update the return date and fine in the database
    $update_sql = "UPDATE issue_book SET return_date = ?, fine = ? WHERE book_id = ? AND student_id = ?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param('siii', $return_date, $fine, $book_id, $s_id);
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
}
?>

<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="dashboard.php">Student Dashboard</a></li>
            <li><a href="#">Issue Books</a></li>
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
                <th>Book Name</th>
                <th>Book Image</th>
                <th>Issue Date</th>
                <th>Due Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Query to fetch the borrowed books and their due dates for the student
              $sql = "SELECT
                        books.id AS book_id,
                        books.book_name,
                        books.book_image,
                        issue_book.issue_date,
                        issue_book.due_date
                      FROM
                        books
                      INNER JOIN
                        issue_book
                      ON
                        issue_book.book_id = books.id
                      WHERE
                        issue_book.student_id = ?
                      ORDER BY
                        issue_book.id DESC";

              $stmt = $con->prepare($sql);
              $stmt->bind_param('i', $s_id);
              $stmt->execute();
              $result = $stmt->get_result();

              // Check if records exist
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  ?>
                  <tr>
                    <td><strong><?= htmlspecialchars($row['book_name']) ?></strong></td>
                    <td class="text-center"><img src="../images/book/<?= htmlspecialchars($row['book_image']) ?>" alt="" width="100"></td>
                    <td class="text-center"><?= htmlspecialchars(date('Y-m-d', strtotime($row['issue_date']))) ?></td>
                    <td class="text-center"><?= htmlspecialchars(date('d-m-Y', strtotime($row['due_date']))) ?></td>
                  </tr>
                  <?php
                }
              } else {
                  echo "<tr><td colspan='4' class='text-center'>No books issued</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php') ?>