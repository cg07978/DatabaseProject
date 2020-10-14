<?php

session_start();

$username = $_SESSION['username'];

include('db_connection.php');

$name = $price = '';
$errors = array('name' => '', 'price' => '');

	if(isset($_POST['submit'])) {
		
		if(empty($_POST['name'])) {
			$errors['name'] = 'A name is required.<br />';
		}
		else {
			$name = $_POST['name'];
			if(strlen($name) > 255) {
				$errors['name'] =  'That name is way too long.';
			}
		}
		
		if(empty($_POST['price'])) {
			$errors['price'] = 'A price is required.<br />';
		}
		else {
			$price = $_POST['price'];
			if(!preg_match('/^\d+.\d\d$/', $price)) {
				$errors['price'] = 'Price must be in the form N+.NN';
			}
			else if(strlen($price) > 13) {
				$errors['price'] = "That price is way too high.";
			}
		}

		if(array_filter($errors)) {
			
		} else {

			$name = mysqli_real_escape_string($conn, $_POST['name']);
			$price = mysqli_real_escape_string($conn, $_POST['price']);

			//create sql
			$sql = "INSERT INTO instrument(name, price, owner_username) VALUES('$name', '$price', '$username')";

			//save to db and check

			if(mysqli_query($conn, $sql)) {
				//success
				header('Location: seller.php');
			} else {
				//error
				echo 'query error: '.mysqli_error($conn);
			}

		}



		} //end of post check

?>

<!DOCTYPE html>
<html>

	<?php include 'inst_header2.php'; ?>

	<section class="container grey-text">
		<h4 class="center">Add an Instrument</h4>
		<form class="white" action="inst_add.php" method="POST">
			<label>Instrument Name:</label>
			<input type= "text" name="name" value= "<?php echo htmlspecialchars($name) ?>">
			<div class="red-text"><?php echo $errors['name']; ?></div>
			<label>Price:</label>
			<input type= "text" name="price" value= "<?php echo htmlspecialchars($price) ?>">
			<div class="red-text"><?php echo $errors['price']; ?></div>
			<div class="center">
				<input type="submit" name="submit" value="submit" class="btn btnback z-depth-0">
			</div>

		</form>
	</section>

</html>