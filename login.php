<?php

session_start();

include('db_connection.php');

$username = $password = '';
$errors = array('username' => '', 'password' => '');
$_SESSION['username'] = '';

	if(isset($_POST['submit'])) {
		
		if(empty($_POST['username'])) {
			$errors['username'] = 'You must enter a username.<br />';
		}
		else {
			$username = $_POST['username'];
			if(strlen($username) > 32) {
				$errors['username'] =  'Username can be no longer than 32 characters.';
			}
		}
		
		if(empty($_POST['password'])) {
			$errors['pasword'] = 'You must enter a password.<br />';
		}
		else {
			$password = $_POST['password'];
			if(strlen($password) > 128) {
				$errors['password'] = 'Password can be no longer than 128 characters.';
			}
		}

		if(array_filter($errors)) {
			
		} else {

			$username = mysqli_real_escape_string($conn, $_POST['username']);
			$password = mysqli_real_escape_string($conn, $_POST['password']);

			//create sql
			$sql = "SELECT username, password FROM user WHERE username = '$username'";

			$result = mysqli_query($conn, $sql);
				
			$feedback = mysqli_fetch_all($result, MYSQLI_ASSOC);

			$pw;

			foreach($feedback as $fb) {
				$pw = $fb['password'];
			}

			if (mysqli_num_rows($result) == 0) {
				$errors['username'] = 'Invalid username.';
			}
			else if (strcmp($_POST['password'], $pw) != 0) {
				$errors['password'] = 'Invalid password.';
			}
			else {
				//success
				$_SESSION['username'] = $username;
				header('Location: choice.php');
			}
			



				//success
				//header('Location: index.php');
		
			

			


		}



		} //end of post check

		if(isset($_POST['newacc'])) {
			header('Location: newacc.php');
		}

?>

<!DOCTYPE html>
<html>

	<?php include 'inst_header.php'; ?>

	<section class="container grey-text">
		<h4 class="center">Login</h4>
		<form class="white" action="login.php" method="POST">
			<label>Username:</label>
			<input type= "text" name="username" value= "<?php echo htmlspecialchars($username) ?>">
			<div class="red-text"><?php echo $errors['username']; ?></div>
			<label>Password:</label>
			<input type= "text" name="password" value= "<?php echo htmlspecialchars($password) ?>">
			<div class="red-text"><?php echo $errors['password']; ?></div>
			<div class="center">
				<input type="submit" name="newacc" value="Create Account" class="btn btnback z-depth-0">
				<input type="submit" name="submit" value="submit" class="btn btnback z-depth-0">
			</div>

		</form>
	</section>


</html>