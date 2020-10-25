<?php 

session_start();

$username = $_SESSION['username'];

 ?>

<!DOCTYPE html>
<html>
<!-- This page gives the user the option to choose if they would like to buy or sell instruments, and directs them to the appropriate page. -->
	<?php include 'inst_header2.php'; ?>

	<section class="container grey-text">
		<h4 class="center">Are you buying or selling?</h4>
		<form class="white" action="" method="">
			<div class="center">
				<a href="buyer.php" class="btn btnback z-depth-0">Buying</a>
				<a href="seller.php" class="btn btnback z-depth-0">Selling</a>
			</div>

		</form>
	</section>


</html>