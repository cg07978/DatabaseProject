<?php

session_start();

include('db_connection.php');

/*This is the page for users who want to offer instruments.*/

$username = $_SESSION['username'];

$sql = "SELECT inst_id, name, status FROM instrument WHERE owner_username = '$username'";

$result = mysqli_query($conn, $sql);

$instruments = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);

?>

<!DOCTYPE html>
<html>

<?php include('seller_header.php'); ?>

<!-- This section lists the instruments that the user owns. If they want more information and options about a specific
instrument there will be a link for them to click. It also calculates and lists their rating beforehand. -->

<div class="center grey-text">
	<?php 
		$sql = "SELECT * FROM user WHERE username = '$username'";
		$result = mysqli_query($conn, $sql);
		$user = mysqli_fetch_assoc($result);

		mysqli_free_result($result);	
		$res;
		if ($user['feedback_count'] == 0) {
			$res = 'None'; ?>
			<p><?php echo "Your current rating is: ".$res ?></p>
			<?php
		}
		else {
			$res = $user['total_points'] / $user['feedback_count']; ?>
			<p><?php echo "Your current rating is: ".number_format((float)$res, 2, '.', '') ?></p>
			<?php
		}	
	 ?>
</div>

<h4 class="center grey-text">Your Instruments:</h4>

	<div class="container">
		<div class = "row">

			<?php foreach($instruments as $instrument): ?>

				<div class="col s6 md3">
					<div class="card z-depth-0">
						<div class = "card-content center">
							<h6><?php echo htmlspecialchars($instrument['name']); ?></h6>
							<div>
								<ul>
								<?php echo htmlspecialchars($instrument['status']); ?>
								</ul>
							</div>
						</div>
						<div class="card-action right-align">
							<a class="brand-text" href="inst_details.php?id=<?php echo $instrument['inst_id'] ?>">more info</a>
						</div>
					</div>
				</div>

			<?php endforeach; ?>

		</div>
	</div>

<!-- This section lists all the pending payments that are owed to the user. -->

	<?php 

	$sql = "SELECT * FROM payment WHERE reciever_username = '$username'";

	$result = mysqli_query($conn, $sql);

	$payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
							<h6><?php echo htmlspecialchars($payment['payer_username']); ?></h6>
							<div>
								<ul>
								<?php echo "Amount: $".htmlspecialchars($payment['amount']); ?>
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