<?php 
session_start(); // Ensure session is started

require_once '../include/db.php';
require_once 'header.php';

// Fetch current user data (assuming $id is stored in session)
$id = $_SESSION['admin_success_id'];
$sql = "SELECT * FROM libraian WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

// Check if the form is submitted
if (isset($_POST['update-btn'])) {
    $name = $_POST['name'];
    $tag = $_POST['tag'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];

    // Validate input data
    if (empty($email) || empty($phone) || empty($city) || empty($name)) {
        $error = 'Please fill in all fields!';
    } else {
        // Check if an image file is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $_FILES['image'];
            $image_name = $image['name'];
            $image_tmp = $image['tmp_name'];
            $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            if (in_array($image_ext, $allowed_ext)) {
                $image_new_name = uniqid() . '.' . $image_ext;
                $image_upload_path = '../images/libraian/' . $image_new_name;
                if (move_uploaded_file($image_tmp, $image_upload_path)) {
                    // Delete old image if a new one was uploaded
                    if (!empty($data['image']) && file_exists('../images/libraian/' . $data['image'])) {
                        unlink('../images/libraian/' . $data['image']);
                    }
                } else {
                    $image_new_name = $data['image'];
                }
            } else {
                $image_new_name = $data['image'];
            }
        } else {
            $image_new_name = $data['image'];
        }

        // Update database
        $sql = "UPDATE libraian SET name = ?, tag = ?, email = ?, phone = ?, city = ?, image = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssssi', $name, $tag, $email, $phone, $city, $image_new_name, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $success = 'Profile updated successfully!';
            // Update $data array with new values
            $data['name'] = $name;
            $data['tag'] = $tag;
            $data['email'] = $email;
            $data['phone'] = $phone;
            $data['city'] = $city;
            $data['image'] = $image_new_name;
        } else {
            $error = 'Failed to update profile!';
        }
    }
}

?>

<!-- HTML code starts here -->
<div class="content-header">
    <div class="leftside-content-header">
        <ul class="breadcrumbs">
            <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.php">Dashboard</a></li>
            <li><a href="#"> Update My Profile</a></li>
        </ul>
    </div>
</div>

<div class="row animated fadeInUp">
    <div class="col-md-6 col-lg-4">
        <!-- PROFILE -->
        <div>
            <div class="profile-photo">
                <img alt="User photo" src="../images/libraian/<?= $data['image'] ?>">
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="image" accept="image/*">
            </div>
            <div class="user-header-info">
                <input type="text" name="name" value="<?= $data['name'] ?>" placeholder="Name" class="form-control mb-2">
                <input type="text" name="tag" value="<?= $data['tag'] ?>" placeholder="Tag" class="form-control mb-2">
                <div class="user-social-media" style="margin-top: -3px;">
                    <span class="text-lg"><a href="#" class="fa fa-twitter-square"></a> <a href="#" class="fa fa-facebook-square"></a> <a href="#" class="fa fa-linkedin-square"></a> <a href="#" class="fa fa-google-plus-square"></a></span>
                </div>
            </div>
        </div>

        <!-- CONTACT INFO -->
        <div class="panel bg-scale-0 b-primary bt-sm mt-xl">
            <div class="panel-content">
                <h4 class=""><b>Contact Information</b></h4>
                <!-- Display success or error messages -->
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <ul class="user-contact-info ph-sm">
                    <li>
                        <b><i class="color-primary mr-sm fa fa-envelope"></i></b>
                        <input type="email" name="email" value="<?= $data['email']?>" placeholder="Email" class="form-control mb-2">
                    </li>
                    <li>
                        <b><i class="color-primary mr-sm fa fa-phone"></i></b>
                        <input type="text" name="phone" value="<?= $data['phone']?>" placeholder="Phone" class="form-control mb-2">
                    </li>
                    <li>
                        <b><i class="color-primary mr-sm fa fa-globe"></i></b>
                        <input type="text" name="city" value="<?= $data['city']?>" placeholder="City" class="form-control mb-2">
                    </li>
                </ul>
                <button type="submit" name="update-btn" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
