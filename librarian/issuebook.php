<?php include('header.php'); ?>

<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="">Issue Book</a></li>
        </ul>
    </div>
</div>    

<div class="row animated fadeInUp">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel-content" style="background:rgba(255, 255, 255, 0.45);padding:20px">
            <div class="row">
                <div class="col-md-12 text-center">
                    <form class="form-inline" method="post" action="">
                        <div class="form-group">
                            <!-- Automatically populate the search bar with student_name if passed in the URL -->
                            <input type="text" class="form-control" id="search_term" placeholder="Student ID or Name" required name="search_term" value="<?= isset($_GET['student_name']) ? htmlspecialchars($_GET['student_name']) : (isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : '') ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" name="search-btn">Search</button>
                        </div>
                    </form>
                </div> 
            </div>  

            <?php
            if (isset($_POST['search-btn'])) {
                include('../include/db.php');
                $searchTerm = mysqli_real_escape_string($con, $_POST['search_term']);
                $sql = "SELECT * FROM `students` WHERE (`uid` = '$searchTerm' OR `name` LIKE '%$searchTerm%') AND `status` = 1";
                $res = mysqli_query($con, $sql);

                if (mysqli_num_rows($res) > 0) {
                    $data = mysqli_fetch_assoc($res);
            ?>
            <div class="issue-content" style="margin-top:20px;border:1px solid;padding:15px;box-shadow:2px 2px 3px #ccc"> 
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="student_name">Student Name</label>
                                    <input type="text" class="form-control" id="student_name" value="<?= htmlspecialchars($data['name']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="student_id">Student ID</label>
                                    <input type="text" class="form-control" id="student_id" value="<?= htmlspecialchars($data['uid']); ?>" readonly>
                                    <input type="hidden" value="<?= htmlspecialchars($data['id']); ?>" name="sid">
                                </div>
                                <div class="form-group">
                                    <label for="book">Book</label>
                                    <select name="book_id" id="book" class="form-control" required>
                                        <option value="">Select A Book</option>
                                        <?php
                                        $sql = "SELECT * FROM `books` WHERE `book_avilable` > 0 ORDER BY id DESC";
                                        $res = mysqli_query($con, $sql);
                                        while ($book = mysqli_fetch_assoc($res)) { ?>
                                        <option value="<?= htmlspecialchars($book['id']) ?>"><?= htmlspecialchars($book['book_name']) ?></option> 
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="issue_date">Issue Date</label>
                                    <input type="text" class="form-control" id="issue_date" value="<?= date('Y-m-d') ?>" readonly name="issue_date">
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input type="text" class="form-control" id="due_date" value="<?= date('Y-m-d', strtotime(date('Y-m-d') . ' + 14 days')) ?>" readonly name="due_date"> 
                                </div>
                                <div class="form-group">
                                    <label for="lib_name">Librarian</label>
                                    <input type="text" class="form-control" id="lib_name" value="<?= htmlspecialchars($_SESSION['for_issue_book_page']) ?>" readonly name="lib_name">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="issue-book">Issue Book</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display previously issued books -->
            <div class="student_issued_book mt-5" style="border-top:1.5px solid;padding:15px">
                <h4 class="text-center">Previously Issued Books</h4>
                <table class="table table-striped nowrap table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Book Name</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $id = $data['id'];
                        $sql = "SELECT `issue_book`.`due_date`, `issue_book`.`return_date`, `issue_book`.`issue_date`, `issue_book`.`id`, `books`.`book_name` 
                                FROM `books` 
                                INNER JOIN `issue_book` ON `issue_book`.`book_id` = `books`.`id` 
                                WHERE `issue_book`.`student_id` = $id";
                        $res = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_assoc($res)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['book_name']) ?></td>
                            <td><?= htmlspecialchars($row['issue_date']) ?></td>
                            <td><?= htmlspecialchars($row['due_date']) ?></td>
                            <td><?= htmlspecialchars($row['return_date']) ?></td>
                            <td class="text-center">
                                <?php if (is_null($row['return_date'])) { ?>
                                <a href="returnbook.php?issue=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-danger"><i class="fa fa-undo"></i> Return</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div> 

            <?php
                } else {
                    echo "<h4>Student Not Found</h4>";
                }
            }
            ?>
        </div>
    </div>
</div>

<?php
if (isset($_POST['issue-book'])) {
    include('../include/db.php');
    $student_id = $_POST['sid'];
    $book_id = $_POST['book_id'];
    $issue_date = $_POST['issue_date'];
    $due_date = $_POST['due_date'];
    $lib_name = $_POST['lib_name'];

    $sql = "INSERT INTO `issue_book` (`student_id`, `book_id`, `lib_name`, `issue_date`, `due_date`) VALUES ('$student_id', '$book_id', '$lib_name', '$issue_date', '$due_date')";
    if (mysqli_query($con, $sql)) {
        $update_book = "UPDATE `books` SET `book_avilable` = `book_avilable` - 1 WHERE `id` = $book_id";
        mysqli_query($con, $update_book);
        echo "<script>alert('Book Issued Successfully!'); window.location.href = 'issuebook.php';</script>";
    } else {
        echo "<script>alert('Failed to Issue Book!');</script>";
    }
}
?>

<?php include('footer.php'); ?>
