<?php
require_once 'header.php';
include('../include/db.php');

// Fetch the list of authors from the authors table
$author_sql = "SELECT DISTINCT author_name FROM authors WHERE status = 'Active'";
$author_res = mysqli_query($con, $author_sql);
?>

<?php
if(isset($_POST['save-btn'])){
    $book_name = $_POST['book_name'];
    $book_author = $_POST['book_author'];
    $book_quantity = $_POST['book_quantity'];
    $book_avilable = $_POST['book_avilable'];
    $book_image = $_FILES['image']['name'];
    $lib = $_SESSION['lib_user_name'];
    
    if(!empty($book_name) && !empty($book_author) && !empty($book_quantity) && !empty($book_avilable) && !empty($book_image)) {
        $image = rand(1,8888) . $book_image;
        $sql = "SELECT * FROM books WHERE book_name = '$book_name'";
        $res = mysqli_query($con, $sql);
        $row = mysqli_num_rows($res);
        
        if($row == 0){
            $sql = "INSERT INTO books (book_name, book_image, book_author, book_quantity, book_avilable, librian_name) 
                    VALUES ('$book_name', '$image', '$book_author', $book_quantity, $book_avilable, '$lib')";
            if(mysqli_query($con, $sql)){
                $img_upload = '../images/book/' . $image;
                move_uploaded_file($_FILES['image']['tmp_name'], $img_upload);
                $book_inserted_success = "Inserted Successfully!";
            } else {
                $book_not_inserted_succss = "Not Inserted!";
            }
        } else {
            $error_book = "Book already exists!";
        }
    } else {
        $error = "Please fill all the required fields!";
    }
}
?>

<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="">Add Book</a></li>
        </ul>
    </div>
</div>

<div class="row animated fadeInUp">
    <div class="col-md-8 form-box">
        <form class="form-horizontal" style="border:1px solid; padding:10px;" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <h5 class="mb-lg text-center">Add New Book</h5>
            
            <!-- Error or Success Messages -->
            <?php if(isset($error)){ ?>
                <div class="alert alert-danger alert-dismissible show" role="alert">
                    <b><?php echo $error; ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            
            <?php if(isset($book_inserted_success)){ ?>
                <div class="alert alert-success alert-dismissible show" role="alert">
                    <b><?php echo $book_inserted_success; ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            
            <?php if(isset($book_not_inserted_succss)){ ?>
                <div class="alert alert-danger alert-dismissible show" role="alert">
                    <b><?php echo $book_not_inserted_succss; ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            
            <?php if(isset($error_book)){ ?>
                <div class="alert alert-danger alert-dismissible show" role="alert">
                    <b><?php echo $error_book; ?></b>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            
            <!-- Book Name Input -->
            <div class="form-group">
                <label for="bookname" class="col-sm-2 control-label">Book Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="bookname" placeholder="Book Name" name="book_name" value="<?php echo isset($book_name) ? $book_name : ''; ?>" required>
                </div>
            </div>

            <!-- Book Author Input with Dropdown -->
            <div class="form-group">
                <label for="bookauthor" class="col-sm-2 control-label">Book Author</label>
                <div class="col-sm-10">
                    <select name="book_author" class="form-control" required>
                        <option value="">Select Author</option>
                        <?php 
                        while ($author_row = mysqli_fetch_assoc($author_res)) { 
                            echo "<option value='" . $author_row['author_name'] . "'>" . $author_row['author_name'] . "</option>";
                        } 
                        ?>
                    </select>
                </div>
            </div>

            <!-- Other Book Fields -->
            <div class="form-group">
                <label for="bookquantity" class="col-sm-2 control-label">Book Quantity</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="bookquantity" placeholder="Book Quantity" name="book_quantity" value="<?php echo isset($book_quantity) ? $book_quantity : ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="bookavilable" class="col-sm-2 control-label">Book Available</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="bookavilable" placeholder="Book Available" name="book_avilable" value="<?php echo isset($book_avilable) ? $book_avilable : ''; ?>" required>
                </div>
            </div>

            <!-- Book Image Upload -->
            <div class="form-group">
                <label for="bookimage" class="col-sm-2 control-label">Book Image</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" name="image" required>
                </div>
            </div>

            <!-- Save Button -->
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="submit" class="form-control btn btn-info btn-block" value="Save Book" name="save-btn">
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
