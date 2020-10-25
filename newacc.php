<?php
/*This is the page for creating a new account. It works similarly to the login page, but the errors and text
fields are of course different.*/
include('db_connection.php');

$username = $password = $email = $address = $dig16 = $security = $expiration = '';
$errors = array('username' => '', 'password' => '', 'email' => '', 'address' => '', "dig16" => '', 'security' => '', 'expiration' => '');
/*If the submit button is pressed the text fields are checked for errors.*/
	if(isset($_POST['makeacc'])) {
		
		if(empty($_POST['username'])) {
			$errors['username'] = 'You must enter a username.<br />';
		}
		else {
			$username = $_POST['username'];
			if(strlen($username) > 32) {
				$errors['username'] =  'Username can be no longer than 32 characters.';
			}
			else {
			$sql = "SELECT username FROM user WHERE username = '$username'";

			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) != 0) {
				$errors['username'] = 'This username already exists.';
			}

			}

		}
		
		if(empty($_POST['password'])) {
			$errors['password'] = 'You must enter a password.<br />';
		}
		else {
			$password = $_POST['password'];
			if(strlen($password) > 128) {
				$errors['password'] = 'Password can be no longer than 128 characters.';
			}
		}

		if(empty($_POST['email'])) {
			$errors['email'] = 'You must enter an email.';
		}
		else {
			$email = $_POST['email'];
			if(strlen($email) > 255) {
				$errors['email'] = 'That email is way too long.';
			}
			else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors['email'] =  'Email must be a valied email address.';
			}
		}

		if(empty($_POST['address'])) {
			$errors['address'] = 'You must enter an address.<br />';
		}
		else {
			$address = $_POST['address'];
			if(strlen($address) > 255) {
				$errors['address'] = 'That address is way too long.';
			}
		}

		if(empty($_POST['dig16'])) {
			$errors['dig16'] = 'You must enter a 16-digit credit card number.<br />';
		}
		else {
			$dig16 = $_POST['dig16'];
			if(strlen($dig16) != 16) {
				$errors['dig16'] = 'You must enter 16 digits.';
			}
			else if(!preg_match('/^\d+$/', $dig16)) {
				$errors['dig16'] = 'You must enter only digits.';
			}
		}

		if(empty($_POST['security'])) {
			$errors['security'] = 'You must enter a security code for your credit card.<br />';
		}
		else {
			$security = $_POST['security'];
			if(strlen($security) > 9) {
				$errors['security'] = 'That security code is way too long.';
			}
			else if(!preg_match('/^\d+$/', $security)) {
				$errors['security'] = 'You must enter only digits.';
			}
		}

		if(empty($_POST['expiration'])) {
			$errors['expiration'] = 'You must enter an expiration date for your credit card.<br />';
		}
		else {
			$expiration = $_POST['expiration'];
			if(!preg_match('/^\d\d\d\d-\d\d-\d\d$/', $expiration)) {
				$errors['expiration'] = 'Please enter a date: YYYY-MM-DD';
			}
			else {
				$year = substr($expiration, 0, 4);
				$month = substr($expiration, 5, 2);
				$day = substr($expiration, 8);
				if (!checkdate($month, $day, $year)) {
					$errors['expiration'] = 'Please enter a valid date.';
				}
			}
		}





		if(array_filter($errors)) {
			
		} else {
			/*If there are no errors the account is created.*/
			$username = mysqli_real_escape_string($conn, $_POST['username']);
			$password = mysqli_real_escape_string($conn, $_POST['password']);
			$email = mysqli_real_escape_string($conn, $_POST['email']);
			$address = mysqli_real_escape_string($conn, $_POST['address']);
			$dig16 = mysqli_real_escape_string($conn, $_POST['dig16']);
			$security = mysqli_real_escape_string($conn, $_POST['security']);
			$expiration = mysqli_real_escape_string($conn, $_POST['expiration']);


			$sql = "INSERT INTO user(username, password, email, address, dig, security_code, expiration_date) VALUES('$username', '$password', '$email', '$address', '$dig16', '$security', '$expiration')";
			/*If the database is updated properly the user will be taken back to the login page. If not, an error will be displayed.*/
			if(mysqli_query($conn, $sql)) {
				header('Location: login.php');
			} else {
				echo 'query error: '.mysqli_error($conn);
			}
			
		
			

			


		}



		} 


?>

<!DOCTYPE html>
<html>

	<?php include 'inst_header.php'; ?>

	<section class="container grey-text">
		<h4 class="center">Create Account</h4>
		<form class="white" action="newacc.php" method="POST">
			<label>Username:</label>
			<input type= "text" name="username" value= "<?php echo htmlspecialchars($username) ?>">
			<div class="red-text"><?php echo $errors['username']; ?></div>
			<label>Password:</label>
			<input type= "password" name="password" value= "<?php echo htmlspecialchars($password) ?>">
			<div class="red-text"><?php echo $errors['password']; ?></div>
			<label>Email:</label>
			<input type= "text" name="email" value= "<?php echo htmlspecialchars($email) ?>">
			<div class="red-text"><?php echo $errors['email']; ?></div>
			<label>Address:</label>
			<input type= "text" name="address" value= "<?php echo htmlspecialchars($address) ?>">
			<div class="red-text"><?php echo $errors['address']; ?></div>
			<label>Credit Card Number:</label>
			<input type= "text" name="dig16" value= "<?php echo htmlspecialchars($dig16) ?>">
			<div class="red-text"><?php echo $errors['dig16']; ?></div>
			<label>Security Code:</label>
			<input type= "text" name="security" value= "<?php echo htmlspecialchars($security) ?>">
			<div class="red-text"><?php echo $errors['security']; ?></div>
			<label>Expiration Date:</label>
			<input type= "text" name="expiration" value= "<?php echo htmlspecialchars($expiration) ?>">
			<div class="red-text"><?php echo $errors['expiration']; ?></div>

			<div class="center">
				<input type="submit" name="makeacc" value="submit" class="btn btnback z-depth-0">
			</div>

		</form>
	</section>


</html>