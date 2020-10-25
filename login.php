<?php
/*This is the login page. A session is started to keep track of variables for who is logged in across different pages.
When this page is accessed it is often from the log out button. Therefore it clears any remaining session variables
to prevent the possibility of unauthorized access.*/

session_start();

include('db_connection.php');

//an array of errors is maintained to record any issues present with a submission

$username = $password = '';
$errors = array('username' => '', 'password' => '');
$_SESSION['username'] = '';
$_SESSION['admin_password'] = '';

//This code executes if the submit button has been pressed. It checks for errors and updates the array as is needed.

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
			/*If there are errors present this code is run to see if the username field is empty
			and if the given password belongs to the administrator. In the event both are true the
			administrator is logged in.*/
			$apw = $_POST['password'];
			$sql3 = "SELECT * FROM administrator WHERE password = '$apw'";
			$apws = mysqli_query($conn, $sql3);

			if(empty($_POST['username']) && mysqli_num_rows($apws) != 0) {
				$_SESSION['admin_password'] = $apw;
				header('Location: admin.php');
			}
			
		} else {
			/*If nothing was wrong with the input itself the database is queried to see if the username exists,
			and if it does, if the correct password has been entered. If both are true the user is logged in.*/

			$username = mysqli_real_escape_string($conn, $_POST['username']);
			$password = mysqli_real_escape_string($conn, $_POST['password']);

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
				$_SESSION['username'] = $username;
				header('Location: choice.php');
			}
			
		
			

			


		}



		} 

		//If the create account button is pressed then the user is taken to the new account page.

		if(isset($_POST['newacc'])) {
			header('Location: newacc.php');
		}

?>

<!DOCTYPE html>
<html>

	<?php include 'inst_header.php'; ?>
	<!-- The login page itself. Any errors are reported to the user, but the information they entered will be
	preserved in the text fields. -->
	<section class="container grey-text">
		<h4 class="center">Login</h4>
		<form class="white" action="login.php" method="POST">
			<label>Username:</label>
			<input type= "text" name="username" value= "<?php echo htmlspecialchars($username) ?>">
			<div class="red-text"><?php echo $errors['username']; ?></div>
			<label>Password:</label>
			<input type= "password" name="password" value= "<?php echo htmlspecialchars($password) ?>">
			<div class="red-text"><?php echo $errors['password']; ?></div>
			<div class="center">
				<input type="submit" name="newacc" value="Create Account" class="btn btnback z-depth-0">
				<input type="submit" name="submit" value="submit" class="btn btnback z-depth-0">
			</div>

		</form>
	</section>


</html>