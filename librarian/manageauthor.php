<?php 
require_once 'header.php'; 
include('../include/db.php'); // Database connection
?>
<div class="content-header">
    <!-- leftside content header -->
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Dashboard</a></li>
            <li><a href="">Manage Authors</a></li>
        </ul>
    </div>
</div>    
<div class="row animated fadeInUp">
    <div class="col-12 col-sm-10 col-md-12">
        <h4 class="section-subtitle"><b>Authors Overview</b></h4>
        <?php if(isset($_GET['success'])){ ?>
        <div class="alert alert-success alert-dismissible show" role="alert">
            <b><?= $_GET['success'] ?></b> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php } ?>
        <?php if(isset($_GET['error'])){ ?>
        <div class="alert alert-danger alert-dismissible show" role="alert">
            <b><?= $_GET['error'] ?></b> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php } ?>
        <div class="panel">
            <div class="panel-content">
                <div class="table-responsive">
                    <table id="basic-table" class="data-table table table-striped nowrap table-hover table-bordered border" cellspacing="0" width="100%">
                    <thead>
    <tr>
        <th class="text-center" style="width: 100px;">ID</th>
        <th class="text-left" style="vertical-align: middle; width: 250px;">Author Name</th>
        <th class="text-center" style="width: 250px;">Status</th>
        <th class="text-center" style="width: 250px;">Actions</th>
    </tr>
</thead>
<tbody class="text-center">
    <?php
    $sql = "SELECT * FROM `authors` ORDER BY `id` DESC";
    $result = mysqli_query($con, $sql);
    while($row = mysqli_fetch_assoc($result)){ ?>
    <tr>
        <td class="text-center"><?= $row['id'] ?></td>
        <td class="text-left" style="vertical-align: middle;"><?= $row['author_name'] ?></td>
        <td class="text-center"><?= $row['status'] ?></td>
        <td class="text-center">
            <a href="" class="btn btn-info btn-sm" data-toggle="modal" data-target="#author-<?= $row['id'] ?>"><i class="fa fa-eye"></i></a>
            <a href="" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#authoredit-<?= $row['id'] ?>"><i class="fa fa-pencil"></i></a>
            <a href="deleteauthor.php?id=<?= base64_encode($row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></a>
        </td>
    </tr>
    <?php } ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Author Details Modal -->
<?php
$sql = "SELECT * FROM authors ORDER BY id DESC";
$res = mysqli_query($con, $sql);
while ($author = mysqli_fetch_assoc($res)) { 
?>
<div class="modal fade" id="author-<?= $author['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal-info-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header state modal-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-info-label"><i class="fa fa-user"></i> Author Details</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped nowrap table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td><?= $author['id'] ?></td>
                        </tr>
                        <tr>
                            <th>Author Name</th>
                            <td><?= $author['author_name'] ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?= $author['status'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Edit Author Modal -->
<?php
$sql = "SELECT * FROM authors ORDER BY id DESC";
$res = mysqli_query($con, $sql);
while ($author = mysqli_fetch_assoc($res)) { 
?>
<div class="modal fade" id="authoredit-<?= $author['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal-info-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header state modal-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-info-label"><i class="fa fa-user"></i> Update Author</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="updateauthor.php?id=<?= $author['id'] ?>" enctype="multipart/form-data"> 
                    <h5 class="mb-lg text-center">Update Author</h5>
                    <div class="form-group">
                        <label for="authorname" class="col-sm-3 control-label">Author Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="authorname" placeholder="Author Name" name="author_name" value="<?= $author['author_name'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <select name="author_status" class="form-control" id="status">
                                <option value="Active" <?= $author['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= $author['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="submit" class="form-control btn btn-info btn-block" value="Update Author" name="save-btn">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php require_once 'footer.php'; ?>
