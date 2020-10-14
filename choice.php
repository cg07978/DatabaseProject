<?php 

session_start();

$username = $_SESSION['username'];

 ?>

<!DOCTYPE html>
<html>

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