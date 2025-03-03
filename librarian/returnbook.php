<?php
include('header.php');
include('../include/db.php');
?>

<!-- content HEADER -->
<!-- ========================================================= -->
<div class="content-header">
    <!-- leftside content header -->
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="">Return Book</a></li>
        </ul>
    </div>
</div>    

<div class="row animated fadeInUp">
    <h4 class="section-subtitle"><b>Return Books Overview</b></h4>
    <div class="panel">
        <div class="panel-content">
            <div class="table-responsive">
                <table id="basic-table" class="data-table table table-striped nowrap table-hover table-bordered border" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Book Name</th>
                            <th>Issue Date</th>
                            <th>Issued Librarian</th>
                            <th>Return Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query to fetch the books that have been returned
                        $sql = "SELECT
                                    issue_book.id AS issue_id,       /* Adding the issue_id */
                                    students.name AS student_name,
                                    students.id AS student_id,
                                    books.book_name,
                                    issue_book.issue_date,
                                    issue_book.lib_name AS issued_librarian,
                                    issue_book.return_date
                                FROM
                                    issue_book
                                INNER JOIN
                                    students ON issue_book.student_id = students.id
                                INNER JOIN
                                    books ON issue_book.book_id = books.id
                                WHERE
                                    issue_book.return_date IS NOT NULL
                                ORDER BY
                                    issue_book.return_date ASC";

                        $result = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['issue_id']) ?></td>     <!-- Displaying the issue_id -->
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= htmlspecialchars($row['student_id']) ?></td>
                                <td><?= htmlspecialchars($row['book_name']) ?></td>
                                <td><?= htmlspecialchars($row['issue_date']) ?></td>
                                <td><?= htmlspecialchars($row['issued_librarian']) ?></td>
                                <td><?= htmlspecialchars($row['return_date']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?> 
