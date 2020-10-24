<?php 

	session_start();

	$username = $_SESSION['username'];

	include('db_connection.php');

	$illegal = false;

	//check get request id parameter
	if(isset($_GET['id'])) {

		$id = mysqli_real_escape_string($conn, $_GET['id']);

		//make sql
		$sql = "SELECT * FROM instrument WHERE inst_id = $id";

		//get the query results

		$result = mysqli_query($conn, $sql);

		//fetch the result in array format
		$instrument = mysqli_fetch_assoc($result);

		mysqli_free_result($result);

		if(strcmp($instrument['renter_username'], $username) != 0) {
			$illegal = true;
		}


	}

	$errors = array('rating' => '');
	$rating = '';

	if(isset($_POST['submit'])) {
		$mistake = false;
		$rating = $_POST['rating'];
		
		if(empty($rating)) {
			$errors['rating'] = 'You must enter a number with at most one number after the decimal.<br />';
		}
		else if (!preg_match('/^-?(?:\d+|\d*\.\d)$/', $rating)) {
			$errors['rating'] = 'You must enter a number with at most one number after the decimal.';
		}
		
		else if ($rating > 5 || $rating <= 0) {
			$errors['rating'] = "You can only enter 5 at the most, and must enter a number greater than 0.";
		}

		if(array_filter($errors)) {
			
		} else {

			$renter = $instrument['owner_username'];

			//create sql
			$sql = "UPDATE user SET feedback_count = feedback_count + 1, total_points = total_points + $rating WHERE username = '$renter'";

			if(!mysqli_query($conn, $sql)) {
				echo 'query error: '.mysqli_error($conn);
				$mistake = true;
			}
				
			$sql = "UPDATE instrument SET renter_username = NULL, status = 'hidden', date_posted = NULL, rent_time = NULL WHERE inst_id = $id";

			if(!mysqli_query($conn, $sql)) {
				echo 'query error: '.mysqli_error($conn);
				$mistake = true;
			}


		$time = $instrument['rent_time'];
		$sql2 = "SELECT DATEDIFF(CURRENT_TIMESTAMP, '$time') AS diff";
		$answer = mysqli_query($conn, $sql2);
		$costs = mysqli_fetch_all($answer, MYSQLI_ASSOC);
		mysqli_free_result($answer);

		$diff;

		foreach($costs as $cost) {
			$diff = $cost['diff'];
		}

		$amt = $instrument['price'] * $diff;

		$sql = "INSERT INTO payment (start_date, amount, payer_username, reciever_username) VALUES (CURRENT_TIMESTAMP, $amt, '$username', '$renter')";

		if(!mysqli_query($conn, $sql)) {
			echo 'query error: '.mysqli_error($conn);
			$mistake = true;
		}

		if (!$mistake) {
			$sort = $_SESSION['sort'];
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

		}


	}

 ?>

 <!DOCTYPE html>
 <html>
 <?php include 'inst_header2.php'; ?>

<div class="container center grey-text">
	<?php if($instrument && !$illegal): ?>

		<h4><?php echo 'Return '.htmlspecialchars($instrument['owner_username'])."'s ".htmlspecialchars($instrument['name']).':'; ?></h4>
		<?php 
		$time = $instrument['rent_time'];
		$sql2 = "SELECT DATEDIFF(CURRENT_TIMESTAMP, '$time') AS diff";
		$answer = mysqli_query($conn, $sql2);
		$costs = mysqli_fetch_all($answer, MYSQLI_ASSOC);
		mysqli_free_result($answer);

		$diff;

		foreach($costs as $cost) {
			$diff = $cost['diff'];
		}

		$amt = $instrument['price'] * $diff; ?>
		<p>Your cost: $<?php echo $amt; ?></p>
		<p>Rate the seller:</p>
		<form action="return.php?id=<?php echo $id ?>" method="POST">
			<input type= "text" name="rating" value= "<?php echo htmlspecialchars($rating) ?>">
			<div class="red-text"><?php echo $errors['rating']; ?></div>
			<input type="submit" name="submit" value="submit" class="btn btnback z-depth-0">
		</form>

		<?php else: ?>

			<h5>No such instrument exists!</h5>

		<?php endif; ?>
</div>

 </html>