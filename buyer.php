<?php

session_start();

include('db_connection.php');

$username = $_SESSION['username'];

if(isset($_POST['purchase'])) {
	$id = $_POST['id_to_buy'];

	$sql = "UPDATE instrument SET renter_username = '$username', status = 'rented', rent_time = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY) WHERE inst_id = $id";
	if(mysqli_query($conn, $sql)) {

	}
	else {
		echo 'query error: '. mysqli_error($conn);
	}
}

$sql = "SELECT * FROM instrument WHERE status = 'available' AND owner_username != '$username' ORDER BY date_posted";

$result = mysqli_query($conn, $sql);

$instruments = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);

//mysqli_close($conn);

?>

<!DOCTYPE html>
<html>

<?php include('inst_header2.php'); ?>

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
									 <li><?php echo 'Seller Rating: '.htmlspecialchars($res).'/5.0' ?></li>
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

	<?php 

	$sql = "SELECT * FROM instrument WHERE renter_username = '$username' AND CURRENT_TIMESTAMP >= rent_time";

	$result = mysqli_query($conn, $sql);

	$rents = mysqli_fetch_all($result, MYSQLI_ASSOC);

	if (mysqli_num_rows($result) != 0) {
	?>
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
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>


	<?php 

	$sql = "SELECT * FROM payment WHERE payer_username = '$username'";

	$result = mysqli_query($conn, $sql);

	$payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

	//mysqli_free_result($result);

	if (mysqli_num_rows($result) != 0) {
	?>
	<h3 class="center grey-text">Pending Payments:</h3>
		<?php } ?>

	<div class="container">
		<div class = "row">

			<?php foreach($payments as $payment): ?>

				<div class="col s6 md3">
					<div class="card z-depth-0">
						<div class = "card-content center">
							<h6><?php echo htmlspecialchars($paymernt['reciever_username']); ?></h6>
							<div>
								<ul>
								<?php echo "Amount: ".htmlspecialchars($payment['amount']); ?>
								<?php echo "Owed since: ".date($payment['start_date']) ?>
								</ul>
							</div>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>
</html> 