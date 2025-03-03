<?php
include('header.php');
include('../include/db.php');

// Check if an issue ID is provided in the URL parameter
if (isset($_GET['id'])) {
  $issue_id = base64_decode($_GET['id']); // Decode the base64 encoded ID

  // Query to fetch details of the specific returned book
  $sql = "SELECT
    students.name AS student_name,
    students.id AS student_id,
    books.book_name,
    issue_book.issue_date,
    issue_book.return_date,
    issue_book.fine
  FROM
    issue_book
  INNER JOIN
    students ON issue_book.student_id = students.id
  INNER JOIN
    books ON issue_book.book_id = books.id
  WHERE
    issue_book.id = ? AND
    issue_book.return_date IS NOT NULL";

  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $issue_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    $student_name = htmlspecialchars($row['student_name']);
    $student_id = htmlspecialchars($row['student_id']);
    $book_name = htmlspecialchars($row['book_name']);
    $issue_date = htmlspecialchars($row['issue_date']);
    $return_date = htmlspecialchars($row['return_date']);
    $fine = htmlspecialchars($row['fine']);
  } else {
    echo '<div class="alert alert-danger">Invalid book ID or book not returned!</div>';
  }
} else {
  echo '<div class="alert alert-danger">Missing book ID parameter!</div>';
}
?>

<?php if (isset($student_name)): ?>
<div class="content-header">
  <div class="leftside-content-header">
    <ul class="breadcrumbs">
      <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
      <li><a href="return_book.php">Return Books</a></li>
      <li><a href="#">Book Details (<?= $book_name ?>)</a></li>
    </ul>
  </div>
</div>

<div class="row animated fadeInUp">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-content">
        <h4><b>Returned Book Details</b></h4>
        <hr>
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th>Student Name:</th>
              <td><?= $student_name ?></td>
            </tr>
            <tr>
              <th>Student ID:</th>
              <td><?= $student_id ?></td>
            </tr>
            <tr>
              <th>Book Name:</th>
              <td><?= $book_name ?></td>
            </tr>
            <tr>
              <th>Issue Date:</th>
              <td><?= $issue_date ?></td>
            </tr>
            <tr>
              <th>Return Date:</th>
              <td><?= $return_date ?></td>
            </tr>
            <?php if ($fine > 0): ?>
            <tr>
              <th>Fine:</th>
              <td><?= $fine ?> (<?= $fine_per_day ?> per day)</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php include('footer.php'); ?>