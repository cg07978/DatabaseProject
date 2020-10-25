<?php

session_start();

include('db_connection.php');

/*This is page for an admin to user. By default, the one admin monitors all users, instruments, and payments. This interface
allows the admin to view all details of each one, and to delete them if they decide to. To keep the database orderly a user
cannot be deleted unless they have no relationship with any instruments or payments, which can be accomplished by deletions if need be. This page will display nothing but a message if the one accessing it is not an administrator. Any deletion errors
are saved and displayed.*/

$password = $_SESSION['admin_password'];
$error = '';
$illegal = false;

if(empty($password)) {
	$illegal = true;
}

/*This code runs when the administrator decides to delete a user. It will either delete them or display an appropriate error message*/

if(isset($_POST['delete_u'])) {
$mistake = false;
$du = $_POST['user_to_delete'];
$sql = "SELECT * FROM instrument WHERE owner_username = '$du'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) != 0) {
	$error = "That user owns at least one instrument. Their account cannot be deleted unless they does not own one.";
	$mistake = true;
}
$sql = "SELECT * FROM instrument WHERE renter_username = '$du'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) != 0) {
	$error = "That user is renting at least one instrument. Their account cannot be deleted unless they are not renting one.";
	$mistake = true;
}
$sql = "SELECT * FROM payment WHERE payer_username = '$du'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) != 0) {
	$error = "That user owes money on at least one payment. Their account cannot be deleted unless they do not owe any money.";
	$mistake = true;
}
$sql = "SELECT * FROM payment WHERE reciever_username = '$du'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) != 0) {
	$error = "That user is to recieve money on at least one payment. Their account cannot be deleted unless they have no pending incoming payments.";
	$mistake = true;
}

if(!$mistake) {
	$sql = "DELETE FROM user WHERE username = '$du'";
	if(!mysqli_query($conn, $sql)) {
		echo 'query error: '. mysqli_error($conn);
	}
}


}

/*This code runs if the administrator decides to delete an instrument.*/
if(isset($_POST['delete_i'])) {
$di = $_POST['inst_id_to_delete'];
$sql = "DELETE FROM instrument WHERE inst_id = $di";
	if(!mysqli_query($conn, $sql)) {
		echo 'query error: '. mysqli_error($conn);
	}
}

/*This code runs if the administrator decides to delete a pending payment.*/

if(isset($_POST['delete_p'])) {
$pid = $_POST['pid_to_delete'];
$sql = "DELETE FROM payment WHERE payment_id = $pid";
	if(!mysqli_query($conn, $sql)) {
		echo 'query error: '. mysqli_error($conn);
	}
}

/*The following blocks of code update the tables that store what administrator monitors what with the administator monitoring all other entities.*/

$sql = "SELECT * FROM user";

$result = mysqli_query($conn, $sql);

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach($users as $user) {
	$un = $user['username'];
	$sql = "INSERT IGNORE INTO a_u_monitors (admin_password, user_username) VALUES ('$password', '$un')";
	if(!mysqli_query($conn, $sql)) {
		echo 'query error: '.mysqli_error($conn);
		break;
	}
}

$sql = "SELECT * FROM instrument";

$result = mysqli_query($conn, $sql);

$instruments = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach($instruments as $instrument) {
	$id = $instrument['inst_id'];
	$sql = "INSERT IGNORE INTO a_i_monitors (admin_password, instrument_id) VALUES ('$password', '$id')";
	if(!mysqli_query($conn, $sql)) {
		echo 'query error: '.mysqli_error($conn);
		break;
	}
}

$sql = "SELECT * FROM payment";

$result = mysqli_query($conn, $sql);

$payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach($payments as $payment) {
	$pid = $payment['payment_id'];
	$sql = "INSERT IGNORE INTO a_p_monitors (admin_password, payment_id) VALUES ('$password', '$pid')";
	if(!mysqli_query($conn, $sql)) {
		echo 'query error: '.mysqli_error($conn);
		break;
	}
}

/*This is the actual page that displays the users, instruments, and payments the administrator monitors.*/



$sql = "SELECT * FROM a_u_monitors WHERE admin_password = '$password'";

$result = mysqli_query($conn, $sql);

$userswatched = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

	<!DOCTYPE html>
	<html>
	<?php include 'admin_header.php';

	if($illegal) { ?>
		<h6 class="red-text">Access forbidden.</h6>
	<?php }

	if(!empty($error)) { ?>
		<h6 class="red-text"><?php echo $error ?></h6>
	<?php }

	if (mysqli_num_rows($result) != 0) {
	?>
	<h3 class="center grey-text">Users:</h3>

	<div class="container">
		<div class = "row">

			<?php foreach($userswatched as $userwatched): ?>

				<div class="col s6 md3">
					<div class="card z-depth-0">
						<div class = "card-content center">
							<?php $uw = $userwatched['user_username']; ?>
							<h6><?php echo htmlspecialchars($uw); ?></h6>
							<div>
								<?php 

								$sql = "SELECT * FROM user WHERE username = '$uw'";
								$result2 = mysqli_query($conn, $sql);
								$usw = mysqli_fetch_assoc($result2);
								 ?>
								<ul>
									<li>
								<?php echo "Password: ".htmlspecialchars($usw['password']); ?>
								</li>
								<li>
								<?php echo "Email: ".htmlspecialchars($usw['email']) ?>
								</li>
								<li>
								<?php echo "Address: ".htmlspecialchars($usw['address']) ?>
								</li>
								<li>
								<?php 
								if($usw['feedback_count'] != 0) {
									$res = $usw['total_points'] / $usw['feedback_count'];
									echo "Rating: ".number_format((float)$res, 2, '.', ''); 
								}
								else {
									echo "Rating: None";
								}
								?>
								</li>
								<li>
								<?php echo "Credit card #: ".htmlspecialchars($usw['dig']); ?>
								</li>
								<li>
								<?php echo "Credit card security code: ".htmlspecialchars($usw['security_code']); ?>
								</li>
								<li>
								<?php echo "Credit card expiration date: ".date($usw['expiration_date']); ?>
								</li>
								</ul>
							</div>
							<div class="card-action center">
							<form class="white" action="admin.php" method="POST">
							<input type="submit" name="delete_u" value="Delete" class="btn btnback z-depth-0">
							<input type="hidden" name="user_to_delete" value="<?php echo $uw; ?>">
						</form>
						</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>

	<?php }

$sql = "SELECT * FROM a_i_monitors WHERE admin_password = '$password'";

$result = mysqli_query($conn, $sql);

$intswatched = mysqli_fetch_all($result, MYSQLI_ASSOC);


	if (mysqli_num_rows($result) != 0) {
	?>
	<h3 class="center grey-text">Instruments:</h3>

	<div class="container">
		<div class = "row">

			<?php foreach($intswatched as $intwatched): ?>

				<div class="col s6 md3">
					<div class="card z-depth-0">
						<div class = "card-content center">
							<?php $ui = $intwatched['instrument_id']; ?>
							<h6><?php echo htmlspecialchars($ui); ?></h6>
							<div>
								<?php 

								$sql = "SELECT * FROM instrument WHERE inst_id = '$ui'";
								$result2 = mysqli_query($conn, $sql);
								$usw = mysqli_fetch_assoc($result2);
								 ?>
								<ul>
									<li>
								<?php echo "Name: ".htmlspecialchars($usw['name']); ?>
								</li>
								<?php if(!empty($usw['date_posted'])) { ?>
								<li>
								<?php echo "Date posted: ".date($usw['date_posted']) ?>
								</li>
								<?php } ?>
								<li>
								<?php echo "Price: $".htmlspecialchars($usw['price']) ?>
								</li>
								<li>
								<?php echo "Status: ".htmlspecialchars($usw['status']) ?>
								</li>
								<?php if(!empty($usw['rent_time'])) { ?>
								<li>
								<?php echo "Rent time: ".htmlspecialchars($usw['rent_time']); ?>
								</li>
								<?php } ?>
								<li>
								<?php echo "Owner: ".htmlspecialchars($usw['owner_username']); ?>
								</li>
								<?php if(!empty($usw['renter_username'])) { ?>
								<li>
								<?php echo "Renter: ".date($usw['renter_username']); ?>
								</li>
								<?php } ?>
								</ul>
							</div>
							<div class="card-action center">
							<form class="white" action="admin.php" method="POST">
							<input type="submit" name="delete_i" value="Delete" class="btn btnback z-depth-0">
							<input type="hidden" name="inst_id_to_delete" value="<?php echo $ui; ?>">
						</form>
						</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>

	<?php } 

$sql = "SELECT * FROM a_p_monitors WHERE admin_password = '$password'";

$result = mysqli_query($conn, $sql);

$payswatched = mysqli_fetch_all($result, MYSQLI_ASSOC);


	if (mysqli_num_rows($result) != 0) {
	?>
	<h3 class="center grey-text">Pending Payments:</h3>

	<div class="container">
		<div class = "row">

			<?php foreach($payswatched as $paywatched): ?>

				<div class="col s6 md3">
					<div class="card z-depth-0">
						<div class = "card-content center">
							<?php $pi = $paywatched['payment_id']; ?>
							<h6><?php echo htmlspecialchars($pi); ?></h6>
							<div>
								<?php 

								$sql = "SELECT * FROM payment WHERE payment_id = '$pi'";
								$result2 = mysqli_query($conn, $sql);
								$usw = mysqli_fetch_assoc($result2);
								 ?>
								<ul>
									<li>
								<?php echo "Start Date: ".date($usw['start_date']); ?>
								</li>
								<li>
								<?php echo "Amount: $".htmlspecialchars($usw['amount']) ?>
								</li>
								<li>
								<?php echo "Payer: ".htmlspecialchars($usw['payer_username']) ?>
								</li>
								<li>
								<?php echo "Reciever: ".htmlspecialchars($usw['reciever_username']) ?>
								</li>
								</ul>
							</div>
							<div class="card-action center">
							<form class="white" action="admin.php" method="POST">
							<input type="submit" name="delete_p" value="Delete" class="btn btnback z-depth-0">
							<input type="hidden" name="pid_to_delete" value="<?php echo $pi; ?>">
						</form>
						</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>

	<?php } ?>


</html>
