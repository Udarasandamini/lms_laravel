<?php 
require_once 'header.php'; 
?>
<div class="content-header">
    <!-- leftside content header -->
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Student Dashboard</a></li>
        </ul>
    </div>
</div>    

<div class="row animated fadeInUp">
    <!-- Issued Books Count -->
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="panel widgetbox wbox-2 bg-darker-2 color-light" style="background:red">
            <a href="issuebooks.php">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-xs-4">
                            <span class="icon fa fa-book color-lighter-1"></span>
                        </div>
                        <?php
                        // Assuming $data['id'] contains the logged-in student's ID
                        $id = $data['id']; 
                        $sql = "SELECT `issue_book`.`issue_date`, `books`.`book_name`, `books`.`book_image` 
                                FROM `books` 
                                INNER JOIN `issue_book` ON `issue_book`.`book_id` = `books`.`id` 
                                WHERE `issue_book`.`student_id` = ? AND `issue_book`.`return_date` IS NULL";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param('i', $id);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $total_issue_books = $res->num_rows;
                        ?>
                        <div class="col-xs-8">
                            <h4 class="subtitle color-lighter-1">Issue Books</h4>
                            <h1 class="title color-light"><b><?= $total_issue_books ?></b></h1>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Books to be Returned Count -->
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="panel widgetbox wbox-2 bg-darker-2 color-light" style="background:red">
            <a href="#">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-xs-4">
                            <span class="icon fa fa-book color-lighter-1"></span>
                        </div>
                        <?php
                        $sql = "SELECT `issue_book`.`issue_date`, `books`.`book_name`, `books`.`book_image` 
                                FROM `books` 
                                INNER JOIN `issue_book` ON `issue_book`.`book_id` = `books`.`id` 
                                WHERE `issue_book`.`student_id` = ? AND `issue_book`.`return_date` IS NULL";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param('i', $id);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $total_return_books = $res->num_rows;
                        ?>
                        <div class="col-xs-8">
                            <h4 class="subtitle color-lighter-1">Books to be Returned</h4>
                            <h1 class="title color-light"><b><?= $total_return_books ?></b></h1>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

   

    <!-- Total Books Count -->
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="panel widgetbox wbox-2 bg-darker-2 color-light" style="background:red">
            <a href="#">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-xs-4">
                            <span class="icon fa fa-book color-lighter-1"></span>
                        </div>
                        <?php
                        $sql = "SELECT * FROM `books`";
                        $res = mysqli_query($con, $sql);
                        $total_books = mysqli_num_rows($res);
                        ?>
                        <div class="col-xs-8">
                            <h4 class="subtitle color-lighter-1">Total Books</h4>
                            <h1 class="title color-light"><?= $total_books ?></h1>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quantity and Available Books -->
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="panel widgetbox wbox-2 bg-darker-2 color-light" style="background:red">
            <a href="#">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-xs-4">
                            <span class="icon fa fa-book color-lighter-1"></span>
                        </div>
                        <?php
                        $sql = "SELECT SUM(`book_quantity`) AS total_quantity_book FROM `books`";
                        $res = mysqli_query($con, $sql);
                        $total_quan_book = mysqli_fetch_assoc($res);

                        $sql = "SELECT SUM(`book_avilable`) AS total_avilable_book FROM `books`";
                        $res = mysqli_query($con, $sql);
                        $total_avilable_book = mysqli_fetch_assoc($res);
                        ?>
                        <div class="col-xs-8">
                            <h4 class="subtitle color-lighter-1">Quantity and Available</h4>
                            <h6 class="title color-light"><?= $total_quan_book['total_quantity_book'] . ' - ' . $total_avilable_book['total_avilable_book'] ?></h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>

<?php require_once 'footer.php'; ?>
