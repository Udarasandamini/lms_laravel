<?php
require_once 'header.php'; 
include('../include/db.php'); // Database connection file

if(isset($_POST['save-btn'])){
    $author_name = $_POST['author_name'];
    $author_status = $_POST['author_status']; // Active/Inactive status

    if(!empty($author_name) && !empty($author_status)) {
        $sql = "SELECT * FROM `authors` WHERE `author_name` = '$author_name'";
        $res = mysqli_query($con, $sql);
        $row = mysqli_num_rows($res);

        if($row == 0){
            // Insert new author into the authors table
            $sql = "INSERT INTO `authors`(`author_name`, `status`) VALUES ('$author_name', '$author_status')";
            if(mysqli_query($con, $sql)){
                $author_inserted_success = "Author added successfully!";
            }else{
                $author_not_inserted_success = "Failed to add author!";
            }
        } else {
            $error_author = "Author already exists!";
        }

    } else{
        $error = "All fields are required!";
    }
}
?>
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="">Add Author</a></li>
        </ul>
    </div>
</div>
<div class="row animated fadeInUp">
    <div class="col-md-8 form-box">
        <form class="form-horizontal" style="border:1px solid;padding:10px;" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
            <h5 class="mb-lg text-center">Add New Author</h5>
            <?php if(isset($error)){ ?>
                <div class="alert alert-danger alert-dismissible show" role="alert">
                    <b><?= $error ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if(isset($author_inserted_success)){ ?>
                <div class="alert alert-success alert-dismissible show" role="alert">
                    <b><?= $author_inserted_success ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if(isset($author_not_inserted_success)){ ?>
                <div class="alert alert-danger alert-dismissible show" role="alert">
                    <b><?= $author_not_inserted_success ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if(isset($error_author)){ ?>
                <div class="alert alert-danger alert-dismissible show" role="alert">
                    <b><?= $error_author ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <div class="form-group">
                <label for="authorname" class="col-sm-2 control-label">Author Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="authorname" placeholder="Author Name" name="author_name" value="<?= isset($author_name) ? $author_name : '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="col-sm-10">
                    <select name="author_status" class="form-control" id="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="submit" class="form-control btn btn-info btn-block" value="Save Author" name="save-btn">
                </div>
            </div>
        </form>
    </div>
</div>
<style>
    .form-box {
        margin-left: 200px;
    }
    @media only screen and (max-width: 480px) {
        .form-box {
            margin-left: 0px;
        }
    }
</style>
<?php require_once 'footer.php'; ?>
