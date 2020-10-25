<head>
	<title>Instrument Rentals</title>
	<!-- This is the header for the admin page. 
    It is very similar to inst_header2 except this one lists the password of the administrator currently logged in. -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style type="text/css">
    	.brand {
    		background: #cbb09c !important;
    	}
    	.brand-text {
    		color: #c334eb !important;
    	}
    	form {
    		max-width: 460px;
    		margin: 20px auto;
    		padding: 20px;
    	}
        .instrument{
            width: 100px;
            margin: 40px auto -30px;
            display: block;
            position: relative;
            top: -30px;
        }
        .btnback {
            background-color: #c334eb;
        }
    </style>

</head> 
	<body class = "grey lighten-4">
		<nav class="white z-depth-0">
			<div class="container">
                <ul id = "nav-mobile" class="left hide-on-small-and-down">
                    <a href="login.php" class="btn btnback z-depth-0">Log Out</a>
                </ul>
				<a href="" class="brand-logo brand-text center">Instrument Rentals</a>
                <ul id="nav-mobile" class="right hide-on-small-and-down">
                    <li class="grey-text">Admin with password: <?php echo htmlspecialchars($password); ?></li>
                </ul>
			</div>
		</nav>