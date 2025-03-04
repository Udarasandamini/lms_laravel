<?php

include('../include/db.php');
session_start();
if(!isset($_SESSION['admin_success_id'])){
    header('location:../404.php');
}

$id = $_SESSION['admin_success_id'];
$sql = "SELECT * FROM libraian WHERE id = $id";
$result = mysqli_query($con,$sql);
$data = mysqli_fetch_assoc($result);
$_SESSION['for_issue_book_page'] = $data['username'];
$_SESSION['lib_user_name'] = $data['username'];
$page = explode('/', $_SERVER['PHP_SELF']);
$page = end($page);

// Query to count book requests
$sql_requests = "SELECT COUNT(*) AS request_count FROM request_book";
$request_result = mysqli_query($con, $sql_requests);
$request_data = mysqli_fetch_assoc($request_result);
$request_count = $request_data['request_count'];


?>

<!doctype html>
<html lang="en" class="fixed left-sidebar-top">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Librarian Admin Panel</title>
    <!--load progress bar-->
    <script src="../assets/vendor/pace/pace.min.js"></script>
    <link href="../assets/vendor/pace/pace-theme-minimal.css" rel="stylesheet" />
    
    <!--BASIC css-->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="../assets/vendor/animate.css/animate.css">
    
    <!--SECTION css-->
    <link rel="stylesheet" href="../assets/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="../assets/vendor/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="../assets/vendor/vendor/data-table/media/css/dataTables.bootstrap.min.css">
    
    <!--TEMPLATE css-->
    <link rel="stylesheet" href="../assets/stylesheets/css/style.css">
</head>

<body>
    <div class="wrap">
        <!-- page HEADER -->
        <div class="page-header">
            <div class="leftside-header">
                <div class="logo">
                    <a href="index.php" class="on-click">
                        <h3>LMS</h3>
                    </a>
                </div>
                <div id="menu-toggle" class="visible-xs toggle-left-sidebar" data-toggle-class="left-sidebar-open" data-target="html">
                    <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>
            <div class="rightside-header">
                <div class="header-middle"></div>

                <!-- Notification Header Box ---->
                <div class="header-section hidden-xs" id="notice-headerbox">
                    <!-- Alerts notices -->
                    <div class="notice" id="alerts-notice">
                        <i class="fa fa-bell-o" aria-hidden="true">
                            <span class="badge badge-xs badge-top-right x-danger"><?= $request_count ?></span>
                        </i>
                        
                        <div class="dropdown-box basic">
                            <div class="drop-header">
                                <h3><i class="fa fa-book" aria-hidden="true"></i> Book Requests</h3>
                                <span class="badge x-danger b-rounded"></span>
                            </div>
                            
                            
                            <div class="drop-content">
                                <div class="widget-list list-left-element list-sm">
                                    <ul>
                                        <?php
                                        $sql_requests_list = "SELECT * FROM request_book ORDER BY id DESC";
                                        $request_result_list = mysqli_query($con, $sql_requests_list);
                                        while ($request = mysqli_fetch_assoc($request_result_list)) { ?>
                                            <li>
                                                <a href="">
                                                    <div class="text">
                                                        <span class="title"><?= $request['book_name'] ?></span>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header-separator"></div>
                </div>
                
                <!--USER HEADERBOX -->
                <div class="header-section" id="user-headerbox">
                    <div class="user-header-wrap">
                        <div class="user-photo">
                            <img alt="profile photo" src="../images/libraian/<?= $data['image'];?>" />
                        </div>
                        <div class="user-info">
                            <span class="user-name"><?=$data['name'] ?></span>
                            <span class="user-profile">Librarian</span>
                        </div>
                        <i class="fa fa-plus icon-open" aria-hidden="true"></i>
                        <i class="fa fa-minus icon-close" aria-hidden="true"></i>
                    </div>
                    <div class="user-options dropdown-box">
                        <div class="drop-content basic">
                            <ul>
                                <li><a href="userprofile.php"><i class="fa fa-user" aria-hidden="true"></i>User Profile</a></li>
                                <li><a href="updateprofile.php"><i class="fa fa-user" aria-hidden="true"></i> Update Profile</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="header-separator"></div>
                <!--Log out -->
                <div class="header-section">
                    <a href="logout.php" data-toggle="tooltip" data-placement="left" title="Logout"><i class="fa fa-sign-out log-out" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <!-- page BODY -->
        <div class="page-body">
            <!-- LEFT SIDEBAR -->
            <div class="left-sidebar">
                <div class="left-sidebar-header">
                    <div class="left-sidebar-title">Navigation</div>
                    <div class="left-sidebar-toggle c-hamburger c-hamburger--htla hidden-xs" data-toggle-class="left-sidebar-collapsed" data-target="html">
                        <span></span>
                    </div>
                </div>
                <div id="left-nav" class="nano">
                    <div class="nano-content">
                        <nav>
                            <ul class="nav nav-left-lines" id="main-nav">
                                <li class="<?=($page == 'index.php') ? 'active-item' : '' ?>"><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i><span>Dashboard</span></a></li>
                                <li class="<?=($page == 'students.php') ? 'active-item' : '' ?>"><a href="students.php"><i class="fa fa-users" aria-hidden="true"></i><span>All Students</span></a></li>

                                <li class="has-child-item close-item <?=($page == 'managebook.php'|| $page == 'addbook.php') ? 'open-item' : '' ?>">
                                    <a><i class="fa fa-user-plus" aria-hidden="true"></i><span>Author</span></a>
                                    <ul class="nav child-nav level-1">
                                        <li><a href="addauthor.php">Add Author</a></li>
                                        <li><a href="manageauthor.php">Manage Author</a></li>
                                    </ul>
                                </li>
                                <li class="has-child-item close-item <?=($page == 'managebook.php'|| $page == 'addbook.php') ? 'open-item' : '' ?>">
                                    <a><i class="fa fa-book" aria-hidden="true"></i><span>Books</span></a>
                                    <ul class="nav child-nav level-1">
                                        <li><a href="addbook.php">Add Book</a></li>
                                        <li><a href="managebook.php">Manage Book</a></li>
                                    </ul>
                                </li>

                                

                                <li class="<?=($page == 'requestbook.php') ? 'active-item' : '' ?>"><a href="requestbook.php"><i class="fa fa-book" aria-hidden="true"></i><span>Request Book</span></a></li>
                                <li class="<?=($page == 'issuebook.php') ? 'active-item' : '' ?>"><a href="issuebook.php"><i class="fa fa-book" aria-hidden="true"></i><span>Issue Book</span></a></li>
                                <li class="<?=($page == 'returnbook.php') ? 'active-item' : '' ?>"><a href="returnbook.php"><i class="fa fa-book" aria-hidden="true"></i><span>Return Book</span></a></li>
                                <li class="<?=($page == 'borrow_return.php') ? 'active-item' : '' ?>"><a href="borrow_return.php"><i class="fa fa-book" aria-hidden="true"></i><span>Borrow/Return Books Details</span></a></li>

                                <li class="has-child-item close-item <?=($page == 'send_message.php'|| $page == 'show_message.php') ? 'open-item' : '' ?>">
                                    <a><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span>Message</span></a>
                                    <ul class="nav child-nav level-1">
                                        <li><a href="send_message.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Send Message</a></li>
                                        <li><a href="show_message.php"><i class="fa fa-envelope-o" aria-hidden="true"></i>Show Message</a></li>
                                    </ul>
                                </li>


                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- CONTENT -->
            <div class="content">
                <!-- Your content goes here -->
                 
           