<?php

session_start();

include('db_connection.php');

/*This page is for users who are looking to rent instruments. It will keep a session variable that tells what the latest
sorting method for available instruments was. The sorting method can be changed at the bottom of the page.
It lists the more recently posted instruments first, as a default.*/

$username = $_SESSION['username'];
$_SESSION['sort'] = "newest_first";

/*This code runs when the user decides to change the sorting method.*/
if (isset($_POST['new_sort'])) {
$sort = $_POST['sort'];
	if(strcmp($sort, 'oldest_first') == 0) {
		header("Location: oldestfirst.php");
	}
	else if(strcmp($sort, 'a-z') == 0) {
		header("Location: atoz.php");
	}
	else if(strcmp($sort, 'z-a') == 0) {
		header("Location: ztoa.php");
	}
	else if(strcmp($sort, 'hi_to_low') == 0) {
		header("Location: hitolow.php");
	}
	else if(strcmp($sort, 'low_to_hi') == 0) {
		header("Location: lowtohi.php");
	}
	else {
		header("Location: buyer.php");
	}

}

/*This code runs when the user decides to purchase an instrument. Since they will not obtain the instrument for 24 hours,
the rent time will be exactly one day in the future relative to when the button is pressed.*/

if(isset($_POST['purchase'])) {
	$id = $_POST['id_to_buy'];

	$sql = "UPDATE instrument SET renter_username = '$username', status = 'rented', rent_time = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY) WHERE inst_id = $id";
	if(mysqli_query($conn, $sql)) {

	}
	else {
		echo 'query error: '. mysqli_error($conn);
	}
}

/*This code runs when the user decides to pay money that they owe. This eliminates the need to keep track of the payment, so
it is deleted from the database.*/

if(isset($_POST['pay'])) {
	$id_to_pay = $_POST['id_to_pay'];
	$sql = "DELETE FROM payment WHERE payment_id = $id_to_pay";
	if(mysqli_query($conn, $sql)) {

	}
	else {
		echo 'query error: '. mysqli_error($conn);
	}
}

$sql = "SELECT * FROM instrument WHERE status = 'available' AND owner_username != '$username' ORDER BY date_posted DESC";

$result = mysqli_query($conn, $sql);

$instruments = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>

<?php include('inst_header2.php'); ?>

<!-- This section shows all available instruments in the database. -->

<?php if (mysqli_num_rows($result) != 0) {
	?>

<h4 class="center grey-text">Available Instruments:</h4>

	<div class="container">
		<div class = "row">

			<?php foreach($instruments as $instrument): ?>

				<div class="col s6 md3">
					<div class="card z-depth-0">
						<div class = "card-content center">
							<h6><?php echo htmlspecialchars($instrument['name']); ?></h6>
							<div>
								<ul>
									<li><?php echo 'Posted: '.htmlspecialchars($instrument['date_posted']); ?></li>
									<li><?php echo 'Daily Price: $'.htmlspecialchars($instrument['price']) ?></li>
									<li><?php echo 'Offered by: '.htmlspecialchars($instrument['owner_username']) ?></li>
									<?php 

									$owner = $instrument['owner_username'];

									$sql = "SELECT * FROM user WHERE username = '$owner'";
									$result = mysqli_query($conn, $sql);
									$user = mysqli_fetch_assoc($result);

									mysqli_free_result($result);	
									$res;
									if ($user['feedback_count'] == 0) {
									$res = 'None';
									}
									else {
									$res = $user['total_points'] / $user['feedback_count'];
									}

									 ?>
									 <li><?php echo 'Seller Rating: '.htmlspecialchars(number_format((float)$res, 2, '.', '')).'/5.0' ?></li>
								</ul>
							</div>
						</div>
						<div class="card-action center">
							<form class="white" action="buyer.php" method="POST">
							<input type="submit" name="purchase" value="Purchase" class="btn btnback z-depth-0">
							<input type="hidden" name="id_to_buy" value="<?php echo $instrument['inst_id']; ?>">
						</form>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>

	<?php }

	$sql = "SELECT * FROM instrument WHERE renter_username = '$username' AND CURRENT_TIMESTAMP >= rent_time";

	$result = mysqli_query($conn, $sql);

	$rents = mysqli_fetch_all($result, MYSQLI_ASSOC);

	if (mysqli_num_rows($result) != 0) {
	?>
	<!-- This section displays the instruments that have been recieved by the user. If they click the return button
		they will be sent to return to process it. -->
	<h3 class="center grey-text">Instruments You're Renting:</h3>
		<?php } ?>

	<div class="container">
		<div class = "row">

			<?php foreach($rents as $rent): ?>

				<div class="col s6 md3">
					<div class="card z-depth-0">
						<div class = "card-content center">
							<h6><?php echo htmlspecialchars($rent['name']); ?></h6>
							<div>
								<ul>
								<li><?php echo 'Rented Since: '.htmlspecialchars($rent['rent_time']); ?></li>
								<li><?php 
								/*MySQL provides a function for determining date difference, so it is used to calculate it here.*/
								$time = $rent['rent_time'];
								$sql2 = "SELECT DATEDIFF(CURRENT_TIMESTAMP, '$time') AS diff";
								$answer = mysqli_query($conn, $sql2);
								$costs = mysqli_fetch_all($answer, MYSQLI_ASSOC);
								mysqli_free_result($answer);

								$diff;

								foreach($costs as $cost) {
									$diff = $cost['diff'];
								}

								$amt = $rent['price'] * $diff;
								echo 'So far you owe: $'.htmlspecialchars($amt); ?>
								</li>
								<li><?php echo 'Owner: '.htmlspecialchars($rent['owner_username']); ?></li>
								</ul>
							</div>
						</div>
							<div class="card-action center">
							<a class="brand-text" href="return.php?id=<?php echo $rent['inst_id'] ?>">RETURN</a>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>


	<?php 

	$sql = "SELECT * FROM payment WHERE payer_username = '$username'";

	$result = mysqli_query($conn, $sql);

	$payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

	if (mysqli_num_rows($result) != 0) {
	?>
	<!-- This section shows any pending payments that the user owes money on. There is a button the user can select if
	they decide to pay a specific payment. -->
	<h3 class="center grey-text">Pending Payments:</h3>
		<?php } ?>

	<div class="container">
		<div class = "row">

			<?php foreach($payments as $payment): ?>

				<div class="col s6 md3">
					<div class="card z-depth-0">
						<div class = "card-content center">
							<h6><?php echo htmlspecialchars($payment['reciever_username']); ?></h6>
							<div>
								<ul>
								<?php echo "Amount: $".htmlspecialchars($payment['amount']); ?>
								<?php echo "Owed since: ".date($payment['start_date']) ?>
								</ul>
							</div>
						</div>
						<div class="card-action center">
							<form class="white" action="buyer.php" method="POST">
							<input type="submit" name="pay" value="Pay" class="btn btnback z-depth-0">
							<input type="hidden" name="id_to_pay" value="<?php echo $payment['payment_id']; ?>">
						</form>
						</div>
					</div>
				</div>

			<?php endforeach; ?>
	<!-- This is where the user can select their preferred sorting method for available instruments. Regardless of the page
	this form appears on it will initially send the user to buyer, where their selection will be processed and they will be sent
	to the appropriate page from there. -->
		<form action="buyer.php" method="POST">
			<select name="sort" class="browser-default">
				<option value="newest_first">newest first</option>
				<option value="oldest_first">oldest first</option>
				<option value="a-z">a-z</option>
				<option value="z-a">z-a</option>
				<option value="hi_to_low">high to low</option>
				<option value="low_to_hi">low to high</option>
			</select>
			<input type="submit" name="new_sort" value="set sorting" class="btn btnback z-depth-0">
		</form>

		</div>
	</div>
</html> 