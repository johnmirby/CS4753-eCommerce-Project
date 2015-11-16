<?php
	$e_firstname = '';
	$e_lastname = '';
	$e_email = '';
	$e_streetaddress = '';
	$e_city = '';
	$e_state = '';
	$e_zipcode = '';
	$e_domain = '';
	$e_servers = '';
	$e_pages = '';
	$firstname = '';
	$lastname = '';
	$email = '';
	$streetaddress = '';
	$city = '';
	$state = '';
	$zipcode = '';
	$domain = '';
	$servers = '';
	$pages = '';

	$success = '';
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
		$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$streetaddress = isset($_POST['streetaddress']) ? $_POST['streetaddress'] : '';
		$city = isset($_POST['city']) ? $_POST['city'] : '';
		$state = isset($_POST['state']) ? $_POST['state'] : '';
		$zipcode = isset($_POST['zipcode']) ? $_POST['zipcode'] : '';
		$domain = isset($_POST['domain']) ? $_POST['domain'] : '';
		$servers = isset($_POST['servers']) ? $_POST['servers'] : '';
		$pages = isset($_POST['pages']) ? $_POST['pages'] : '';

		if (validateFormFields($firstname, $lastname, $email, $streetaddress, 
			$city, $state, $zipcode, $domain, $servers, $pages)){
			$firstname = mysql_real_escape_string($firstname);
			$lastname = mysql_real_escape_string($lastname);
			$email = mysql_real_escape_string($email);
			$streetaddress = mysql_real_escape_string($streetaddress);
			$city = mysql_real_escape_string($city);
			$state = mysql_real_escape_string($state);
			$zipcode = mysql_real_escape_string($zipcode);
			$domain = mysql_real_escape_string($domain);
			$servers = mysql_real_escape_string($servers);
			$pages = mysql_real_escape_string($pages);

			$dbhost = 'localhost';
			$dbuser = 'root';
			$dbpass = '';
			$conn = mysql_connect($dbhost, $dbuser, $dbpass);
			if(! $conn )
			{
				die('Could not connect: ' . mysql_error());
			}
			$sql = "INSERT INTO currentUsers (firstname, lastname, email, streetaddress, city, state, zipcode, domain, servers, pages)
				VALUES ( '$firstname', '$lastname', '$email', '$streetaddress', '$city', '$state', '$zipcode' , '$domain', '$servers', '$pages' )";

			mysql_select_db('weblytics');
			mysql_query( $sql, $conn );
			mysql_close($conn);
			$success = 'User successfully created.';
			$firstname = '';
			$lastname = '';
			$email = '';
			$streetaddress = '';
			$city = '';
			$state = '';
			$zipcode = '';
			$domain = '';
			$servers = '';
			$pages = '';
		}
		else {
			if (empty($firstname)) {
				$e_firstname = 'First Name is Required';
			}
			if (empty($lastname)) {
				$e_lastname = 'Last Name is Required';
			}
			if (empty($email)) {
				$e_email = 'Email is Required';
			}
			if (empty($streetaddress)) {
				$e_streetaddress = 'Street Address is Required';
			}
			if (empty($city)) {
				$e_city = 'City is Required';
			}
			if (empty($state)) {
				$e_state = 'State is Required';
			}
			if (empty($zipcode)) {
				$e_zipcode = 'Zipcode is Required';
			}
			if (!validateEmail($email) && !empty($email)){
				$e_email = 'Email address is invalid';
			}
			if (!validateName($firstname) && !empty($firstname)){
				$e_firstname = 'First Name may not contain numbers or special characters';
			}
			if (!validateName($lastname) && !empty($lastname)){
				$e_lastname = 'Last Name may not contain numbers or special characters';
			}
			if (!validateZipcode($zipcode) && !empty($zipcode)){
				$e_zipcode = 'Zipcode must by five digits';
			}
			if (!validateInt($servers) && !empty($servers)){
				$e_servers = "Server Number must be an integer";
			}
			if (!validateInt($pages) && !empty($pages)){
				$e_pages = "Page Number must be an integer";
			}
			if (!validateDomain($domain) && !empty($domain)){
				$e_domain = "Domain is invalid";
			}
			if (!validateCityState($city) && !empty($city)){
				$e_city = 'City may not contain numbers or special characters';
			}
			if (!validateCityState($state) && !empty($state)){
				$e_state = 'State may not contain numbers or special characters';
			}
			if (!validateAddress($streetaddress) && !empty($streetaddress)){
				$e_streetaddress = 'Street Address may not contain special characters';
			}
		}
	}

	function validateFormFields($firstname, $lastname, $email, $streetaddress, $city, $state, $zipcode, $domain, $servers, $pages){
		if (!(empty($firstname) || empty($lastname) || empty($email) || empty($streetaddress) 
			|| empty($city) || empty($state) || empty($zipcode)) 
			&& validateName($firstname) && validateName($lastname) && validateZipcode($zipcode) && validateEmail($email)
			&& validateCityState($city) && validateCityState($state) && validateAddress($streetaddress) 
			&& (validateInt($servers) || empty($servers)) && (vaidateInt($pages) || empty($pages))
			&& (validateDomain($domain) || empty($domain))){
			return true;
		}
		return false;
	}

	function validateEmail($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	function validateName($name){
		return preg_match("/^[a-zA-Z'-]*$/", $name);
	}

	function validateZipcode($zipcode){
		 return preg_match("/^[0-9]{5}$/", $zipcode);
	}

	function validateInt($num){
		return filter_var($num, FILTER_VALIDATE_INT);
	}

	function validateCityState($city_state){
		return preg_match("/^[a-zA-Z ]*$/", $city_state);
	}

	function validateAddress($address){
		return preg_match("/^[0-9a-zA-Z- ]*$/", $address);
	}

	function validateDomain($domain){
		return preg_match("/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/", $domain);
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
						<div class="4u 12u(mobile)">

							<section class="left-content">
								<h2>Sign Up</h2>
								<span><?php echo $success;?></span>
								<p>* denotes a required field.</p>
								<form action="<?php $_PHP_SELF ?>" method="POST">
									<h4>First Name</h4>
									<input type="text" name="firstname" value="<?php echo $firstname; ?>">
									<span class="error">* <?php echo $e_firstname;?></span>
									<h4>Last Name</h4>
									<input type="text" name="lastname" value="<?php echo $lastname; ?>">
									<span class="error">* <?php echo $e_lastname;?></span>
									<h4>Email</h4>
									<input type="text" name="email" value="<?php echo $email; ?>">
									<span class="error">* <?php echo $e_email;?></span>
									<h4>Street Address</h4>
									<input type="text" name="streetaddress" value="<?php echo $streetaddress; ?>">
									<span class="error">* <?php echo $e_streetaddress;?></span>
									<h4>City</h4>
									<input type="text" name="city" value="<?php echo $city; ?>">
									<span class="error">* <?php echo $e_city;?></span>
									<h4>State</h4>
									<input type="text" name="state" value="<?php echo $state; ?>">
									<span class="error">* <?php echo $e_state;?></span>
									<h4>Zipcode</h4>
									<input type="text" name="zipcode" value="<?php echo $zipcode; ?>">
									<span class="error">* <?php echo $e_zipcode;?></span>
									<h4>Site Domain Name</h4>
									<input type="text" name="domain" value="<?php echo $domain; ?>">
									<span class="error"><?php echo $e_domain;?></span>
									<h4>Number of Servers</h4>
									<input type="text" name="servers" value="<?php echo $servers; ?>">
									<span class="error"><?php echo $e_servers;?></span>
									<h4>Number of Pages</h4>
									<input type="text" name="pages" value="<?php echo $pages; ?>">
									<span class="error"><?php echo $e_pages;?></span>
									</br></br>
									<input type="submit" class="button">
								</form>
							</section>
						</div>

						<div class="8u 12u(mobile)">

							<section>
								<h2>Signing Up with Weblytics</h2>
								<img src="images/email-signup.jpg" alt="" class="top blog-post-image" />								
								<p>Enter your information into the form on the left to register with our service. 
									Pricing will vary with the number of pages and servers you wish to test, 
									so you do not have to pay until you utilize the service. FOr a single domain, 
									the first page or server will start at $5.00 USD. Subsequent servers and pages 
									can be added at a price of $1.00 a piece.</p>
								<p>Note: The domain, server, and page fields on the form are only to give us an idea of 
									the level of service you would require.</p>
								<p>Signing up is no charge to you, so register for an account today!</p>
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