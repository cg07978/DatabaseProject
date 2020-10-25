<?php 

//This code connects to the database with an account that has already been set up and has access to it.
	$conn = mysqli_connect('localhost', 'groupC4', 'bronzespring19', 'instrument_rentals');

	//The connection is checked to ensure that it is working.

	if(!$conn) {
		echo 'Connection error: ' . mysqli_connect_error();
	}

 ?>