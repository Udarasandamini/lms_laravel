<?php
	include ('../include/db.php');
	//include "navbar.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Message</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body {
			background-image: url("images/msg.png");
			background-repeat: no-repeat;
		}
		.wrapper {
			height: 600px;
			width: 500px;
			background-color: black;
			opacity: .9;
			color: white;
			margin: -20px auto;
			padding: 10px;
		}
		.form-control {
			height: 47px;
			width: 76%;
		}
		.msg {
			height: 450px;
			overflow-y: scroll;
		}
		.btn-info {
			background-color: #02c5b6;
		}
		.chat {
			display: flex;
			flex-flow: row wrap;
		}
		.user .chatbox {
			height: 50px;
			width: 400px;
			padding: 13px 10px;
			background-color: #821b69;
			color: white;
			border-radius: 10px;
			order: -1;
		}
		.admin .chatbox {
			height: 50px;
			width: 400px;
			padding: 13px 10px;
			background-color: #423471;
			color: white;
			border-radius: 10px;
		}
	</style>
</head>

<body>
	<?php
		$librarian_uid = 'LIBRARIAN_UID';  // Librarian's UID from session
		$student_uid = $_GET['student_uid'];  // Get the student's UID from URL or form

		if (isset($_POST['submit'])) {
			$message = $_POST['message'];
			$sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$librarian_uid', '$student_uid', '$message')";
			mysqli_query($db, $sql);
		}

		$sql = "SELECT * FROM messages WHERE (sender_id = '$librarian_uid' AND receiver_id = '$student_uid') OR (sender_id = '$student_uid' AND receiver_id = '$librarian_uid') ORDER BY sent_at ASC";
		$res = mysqli_query($db, $sql);
	?>

	<div class="wrapper">
		<div style="height: 70px; width: 100%; background-color: #2eac8b; text-align: center; color:white; ">
			<h3 style="margin-top: -5px; padding-top: 10px;">Student</h3>
		</div>
		<div class="msg">
			<?php
				while ($row = mysqli_fetch_assoc($res)) {
					if ($row['sender_id'] == $librarian_uid) {
			?>
						<!-- Librarian's message -->
						<br><div class="chat admin">
							<div style="float: left; padding-top: 5px;">
								<img style="height: 40px; width: 40px; border-radius: 50%;" src="images/p.jpg">
								&nbsp
							</div>
							<div style="float: left;" class="chatbox">
								<?php echo $row['message']; ?>
							</div>
						</div>
			<?php
						} else {
			?>
						<!-- Student's message -->
						<br><div class="chat user">
							<div style="float: left; padding-top: 5px;">
								<img class='img-circle profile_img' height=40 width=40 src='images/student_pic.jpg'> <!-- Student's pic -->
								&nbsp
							</div>
							<div style="float: left;" class="chatbox">
								<?php echo $row['message']; ?>
							</div>
						</div>
			<?php
					}
				}
			?>
		</div>

		<div style="height: 100px; padding-top: 10px;" >
			<form action="" method="post">
				<input type="text" name="message" class="form-control" required="" placeholder="Write Message..." style="float: left"> &nbsp
				<button class="btn btn-info btn-lg" type="submit" name="submit"><span class="glyphicon glyphicon-send "></span>&nbsp Send</button>
			</form>
		</div>
	</div>
</body>
</html>
