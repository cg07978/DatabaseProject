<?php 

//connect to database
	$conn = mysqli_connect('localhost', 'groupC4', 'bronzespring19', 'instrument_rentals');

	//check connection

	if(!$conn) {
		echo 'Connection error: ' . mysqli_connect_error();
	}

 ?>