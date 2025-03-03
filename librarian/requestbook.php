<?php include('header.php'); ?>

<!-- content HEADER -->
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="">Request Book</a></li>
        </ul>
    </div>
</div>    

<div class="row animated fadeInUp">
    <h4 class="section-subtitle"><b>Request Books Overview</b></h4>
    <div class="panel">
        <div class="panel-content">
            <div class="table-responsive">
                <table id="basic-table" class="data-table table table-striped nowrap table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Book Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        include('../include/db.php');
                        $sql = "SELECT * FROM `request_book` ORDER BY id DESC";
                        $result = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= htmlspecialchars($row['student_id']) ?></td>
                                <td><?= htmlspecialchars($row['book_name']) ?></td>
                                <td>
                                    <!-- Pass student_name as a parameter in the URL -->
                                    <a href="requestbook.php?req_id=<?= htmlspecialchars($row['id']) ?>&book_id=<?= htmlspecialchars($row['book_id']) ?>&student_name=<?= htmlspecialchars($row['student_name']) ?>">Issue Book</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_GET['req_id']) && isset($_GET['book_id'])) {
    include('../include/db.php');
    session_start();
    $lib = $_SESSION['for_issue_book_page'];
    $req_id = mysqli_real_escape_string($con, $_GET['req_id']);
    $book_id = mysqli_real_escape_string($con, $_GET['book_id']);
    $issue_date = date('Y-m-d');

    // Get the student ID based on the request
    $query = "SELECT students.`id` FROM `students` INNER JOIN request_book ON students.uid = request_book.student_id WHERE request_book.id = $req_id";
    $res = mysqli_query($con, $query);
    
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $student_id = $row['id'];

        // Insert into issue_book
        $insert_issue = "INSERT INTO `issue_book` (`student_id`, `book_id`, `lib_name`, `issue_date`, `due_date`) VALUES ('$student_id', '$book_id', '$lib', '$issue_date', DATE_ADD('$issue_date', INTERVAL 14 DAY))";
        if (mysqli_query($con, $insert_issue)) {
            // Update book availability
            $update_book = "UPDATE `books` SET `book_avilable` = `book_avilable` - 1 WHERE `id` = $book_id";
            mysqli_query($con, $update_book);

            // Remove request from request_book table
            $delete_request = "DELETE FROM `request_book` WHERE `id` = $req_id";
            mysqli_query($con, $delete_request);
            echo "<script>alert('Book Issued Successfully!'); window.location.href = 'requestbook.php';</script>";
        } else {
            echo "<script>alert('Failed to Issue Book!');</script>";
        }
    } else {
        echo "<script>alert('Student not found!');</script>";
    }
}
?>

<?php include('footer.php'); ?>
