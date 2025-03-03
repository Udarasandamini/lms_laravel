<?php 
session_start(); // Ensure session is started

require_once '../include/db.php';
require_once 'header.php';

// Fetch current student data (assuming $id is stored in session)
$id = $_SESSION['user_success_id'];
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

// Check if the form is submitted
if (isset($_POST['update-btn'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $batch = $_POST['batch'];
    $uid = $_POST['uid'];
    $gpa = $_POST['gpa'];
    $blood_grp = $_POST['blood_grp'];

    // Validate input data
    if (empty($email) || empty($phone) || empty($address) || empty($name) || empty($batch) || empty($uid) || empty($gpa) || empty($blood_grp)) {
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
                $image_upload_path = '../images/student/' . $image_new_name;
                if (move_uploaded_file($image_tmp, $image_upload_path)) {
                    // Delete old image if a new one was uploaded
                    if (!empty($data['image']) && file_exists('../images/student/' . $data['image'])) {
                        unlink('../images/student/' . $data['image']);
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
        $sql = "UPDATE students SET name = ?, email = ?, phone = ?, address = ?, batch = ?, uid = ?, gpa = ?, blood_grp = ?, image = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'sssssssssi', $name, $email, $phone, $address, $batch, $uid, $gpa, $blood_grp, $image_new_name, $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $success = 'Profile updated successfully!';
            // Update $data array with new values
            $data['name'] = $name;
            $data['email'] = $email;
            $data['phone'] = $phone;
            $data['address'] = $address;
            $data['batch'] = $batch;
            $data['uid'] = $uid;
            $data['gpa'] = $gpa;
            $data['blood_grp'] = $blood_grp;
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
            <li><a href="#">Update Profile</a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-4" style="box-shadow:2px 3px 8px #ccc">
        <!-- PROFILE -->
        <div>
            <div class="profile-photo">
                <img alt="User photo" src="../images/student/<?= $data['image'] ?>">
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="image" accept="image/*">
            </div>
            <div class="user-header-info">
                <input type="text" name="name" value="<?= $data['name'] ?>" placeholder="Name" class="form-control mb-2">
                <div class="user-social-media" style="margin-top:-3px">
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
                        <input type="text" name="address" value="<?= $data['address']?>" placeholder="Address" class="form-control mb-2">
                    </li>
                </ul>
            </div>
        </div>

        <!-- ADDITIONAL INFO -->
        <div class="panel  b-primary bt-sm mt-xl">
            <div class="panel-content">
                <h4 class=""><b>Additional Information</b></h4>
                <table class="table table-striped nowrap table-hover table-bordered">
                    <tr>
                        <th><b>Batch</b></th>
                        <td style="text-align:center"><input type="text" name="batch" value="<?= $data['batch']?>" class="form-control"></td>
                    </tr>
                    <tr>
                        <th><b>ID</b></th>
                        <td style="text-align:center"><input type="text" name="uid" value="<?= $data['uid']?>" class="form-control"></td>
                    </tr>
                    <tr>
                        <th><b>Result (GPA)</b></th>
                        <td style="text-align:center"><input type="text" name="gpa" value="<?= $data['gpa']?>" class="form-control"></td>
                    </tr>
                    <tr>
                        <th><b>Blood Group</b></th>
                        <td style="text-align:center"><input type="text" name="blood_grp" value="<?= $data['blood_grp']?>" class="form-control"></td>
                    </tr>
                </table>
                <button type="submit" name="update-btn" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
