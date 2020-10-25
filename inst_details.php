<?php 

	session_start();

	$username = $_SESSION['username'];
	$illegal = false;

	include('db_connection.php');

	/*This page lists more information about a specific instrument. The user must be the owner of the instrument to view it.
	This page also allows the user to change the status of the instrument if it is available or hidden. Instruments are
	hidden by default, so they cannot be seen by other users. When instruments are made available they are posted, and the
	date is recorded. If the instrument is rented it cannot have its status changed or be deleted, which is an option given if the instrument is hidden.*/

	/*This code is run if the delete button is pressed. If successful, it takes the user back to the seller page.*/

	if(isset($_POST['delete'])) {

		$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);

		$sql = "DELETE FROM instrument WHERE inst_id = $id_to_delete";

		if(mysqli_query($conn, $sql)) {

			header('Location: seller.php');
		} else {
			echo 'query error: '. mysqli_error($conn);
		}
	}

	/*This code is run if a new status is set.*/

	if(isset($_POST['new_status'])) {
		$new_status = mysqli_real_escape_string($conn, $_POST['status']);

		$id_to_update = $_POST['id_to_update'];

		$id = mysqli_real_escape_string($conn, $_POST['id_to_update']);

		$sql = "UPDATE instrument SET status = '$new_status' WHERE inst_id = $id_to_update";


		if(mysqli_query($conn, $sql)) {
			if(strcmp($new_status, 'available') == 0) {
			
			$sql = "UPDATE instrument SET date_posted = CURRENT_TIMESTAMP WHERE inst_id = $id_to_update";
			}
			else {
			$sql = "UPDATE instrument SET date_posted = NULL WHERE inst_id = $id_to_update";
			}

			if(mysqli_query($conn, $sql)) {

				header('Location: seller.php');
			}

			else {
				echo 'query error: '. mysqli_error($conn);
			}


			
		} else {
			echo 'query error: '. mysqli_error($conn);
		}

	}

	/*This code generates the page based on what instrument was selected to get to it.*/

	if(isset($_GET['id'])) {

		$id = mysqli_real_escape_string($conn, $_GET['id']);

		$sql = "SELECT * FROM instrument WHERE inst_id = $id";

		$result = mysqli_query($conn, $sql);

		$instrument = mysqli_fetch_assoc($result);

		mysqli_free_result($result);
		mysqli_close($conn);

		if(empty($username)) {
			$illegal = true;
		}

		else if(strcmp($instrument['owner_username'], $username) != 0) {
			$illegal = true;
		}


	}

 ?>

 <!DOCTYPE html>
 <html>
 <?php include 'inst_header2.php'; ?>

<div class="container center grey-text">
	<!-- The page will not be displayed if no instrument corresponds to the id or if the user is not the one who owns
	 the instrument. -->
	<?php if($instrument && !$illegal): ?>

		<h4><?php echo htmlspecialchars($instrument['name']); ?></h4>
		<p>Daily Price: $<?php echo htmlspecialchars($instrument['price']); ?></p>
		<p>Status: <?php echo htmlspecialchars($instrument['status']); ?></p>
		<?php if (!empty($instrument['renter_username'])) { ?>
			<p>Rented by: <?php echo htmlspecialchars($instrument['renter_username']); ?></p>
			<p>Rented since: <?php echo date($instrument['rent_time']); ?></p>
		<?php } ?>

		<?php if(strcmp($instrument['status'], 'rented') != 0) { ?>
		<form action="inst_details.php" method="POST">
			<select name="status" class="browser-default">
				<option value="available">available</option>
				<option value="hidden">hidden</option>
			</select>
			<input type="hidden" name="id_to_update" value="<?php echo $instrument['inst_id']; ?>">
			<input type="submit" name="new_status" value="set status" class="btn btnback z-depth-0">
		</form>
	<?php } ?>


		<?php if(strcmp($instrument['status'], 'hidden') == 0) { ?>
		<form action="inst_details.php" method="POST">
			<input type="hidden" name="id_to_delete" value="<?php echo $instrument['inst_id']; ?>">
			<input type="submit" name="delete" value="delete" class="btn btnback z-depth-0">
		</form>
	<?php } ?>

		<?php else: ?>

			<h5>No such instrument exists!</h5>

		<?php endif; ?>
</div>

 </html>