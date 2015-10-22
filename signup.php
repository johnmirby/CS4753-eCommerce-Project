<?php
	$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
	$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
	if( $firstname != '' && $lastname != '' )
	{
    	echo "Welcome ". $firstname ." ". $lastname ."</br>";
    	echo "You have successfully signed up!";
      
    	exit();
    }
?>

<html>
	<head>
		<title>Weblytics - Sign Up</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body>
		<div id="page-wrapper">
			<div id="header-wrapper">
				<div class="container">
					<div class="row">
						<div class="12u">

							<header id="header">
								<h1><a href="#" id="logo"><img src="images/logo.jpg" alt="" class="left" />weblytics</a></h1>
								<nav id="nav">
									<a href="home.html">Home</a>
									<a href="about.html">About Us</a>
									<a href="signup.php" class="current-page-item">Sign Up</a>
								</nav>
							</header>

						</div>
					</div>
				</div>
			</div>
			<div id="main">
				<div class="container">
					<div class="row main-row">
						<div class="8u 12u(mobile)">

							<section class="left-content">
								<h2>Sign Up</h2>
								<form action="<?php $_PHP_SELF ?>" method="POST">
									<h4>First Name</h4>
									<input type="text" name="firstname">
									<h4>Last Name</h4>
									<input type="text" name="lastname">
									<h4>Email</h4>
									<input type="text" name="email">
									<h4>Street Address</h4>
									<input type="text" name="streetaddress">
									<h4>City</h4>
									<input type="text" name="city">
									<h4>State</h4>
									<input type="text" name="state">
									<h4>Zipcode</h4>
									<input type="text" name="zipcode">
									<h4>Password</h4>
									<input type="password" name="password">
									<h4>Confirm Password</h4>
									<input type="password" name="c_password">
									</br></br>
									<input type="submit" class="button">
								</form>
							</section>

						</div>
					</div>
				</div>
			</div>
			<div id="footer-wrapper">
				<div class="container">
					<div class="row">
						<div class="8u 12u(mobile)">

							<section>
								<h2>Links</h2>
								<div>
									<div class="row">
										<div class="3u 12u(mobile)">
											<ul class="link-list">
												<li><a href="home.html">Home</a></li>
												<li><a href="about.html">About us</a></li>
												<li><a href="signup.php">Sign up</a></li>	
											</ul>
										</div>										
									</div>
								</div>
							</section>

						</div>
						<div class="12u">
							<div id="copyright">
								&copy; Weblytics. All rights reserved. | Design: <a href="http://html5up.net">HTML5 UP</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/skel-viewport.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>