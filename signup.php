<?php

	require_once("assets/stripe-php-3.4.0/init.php");
	require_once("assets/PHPMailer/PHPMailerAutoload.php");

	$stripe = array(
  		"secret_key"      => "sk_test_r49vVKG5Xu4axWE7wBcDL8zL",
  		"publishable_key" => "pk_test_khiDUOkMK4V06spg3cRCA8V4"
	);

	\Stripe\Stripe::setApiKey($stripe['secret_key']);

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
	$mail_sent = '';
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

		$email = isset($_POST['email']) ? $_POST['email'] : '';

		$mail = new PHPMailer;
		$mail->IsSMTP(); // send via SMTP
		$mail->SMTPAuth = true; // turn on SMTP authentication
		$mail->SMTPSecure = 'tls';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->Username = 'cs4753.eCommerce@gmail.com'; // Enter your SMTP username
		$mail->Password = '12qwas3.'; // SMTP password
		$mail->FromName = 'Weblytics';
		$mail->addAddress($email);
		$mail->Subject = 'Weblytics Sign-Up';
		$mail->Body    = 'Thank you for signing up with Weblytics!';
		if(!$mail->send()) {
		    $mail_sent = 'Confirmation email could not be sent.';
		} else {
		    $mail_sent = 'Confirmation email has been sent';
		}

		processStripePayment(500, $email);
	}

	function processStripePayment($cents_amount, $email){
		if (isset($_POST['stripeToken'])){
			$token = $_POST['stripeToken'];

			$customer = \Stripe\Customer::create(array(
				'card' => $token,
				'email' => strip_tags(trim($_POST['email']))
			));
			$customer_id = $customer->id;

			try {
	  		$charge = \Stripe\Charge::create(array(
	    		"amount" => $cents_amount,
	    		"currency" => "usd",
	    		"description" => "Weblytics Sign-Up",
	    		"customer" => $customer_id
	    	));

	  		$mail = new PHPMailer;
	  		$mail->IsSMTP(); // send via SMTP
			$mail->SMTPAuth = true; // turn on SMTP authentication
			$mail->SMTPSecure = 'tls';
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = 587;
			$mail->Username = 'cs4753.eCommerce@gmail.com'; // Enter your SMTP username
			$mail->Password = '12qwas3.'; // SMTP password
			$mail->FromName = 'Weblytics';
			$mail->addAddress($email);
			$mail->Subject = 'Weblytics - Payment Received';
			$mail->Body    = 'Your payment of $5.00 associated with our sign-up fee has been received.';
			if(!$mail->send()) {
			    //error
			} else {
			    //good
			}

			} catch(\Stripe\Error\Card $e) {
				//Card has been declined
			}
		}
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
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
		<script type="text/javascript">
			Stripe.setPublishableKey('pk_test_khiDUOkMK4V06spg3cRCA8V4');
			function isEmpty(str) {
    			return (!str || 0 === str.length);
			};
			function validateName(name){
				var regex = /^[a-zA-Z'-]*$/;
				return regex.test(name);
			};
			function validateInt(num){
				var regex = /^[0-9]*$/;
				return regex.test(num);
			};
			function validateDomain(domain){
				var regex = /^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/;
				return regex.test(domain);
			};
			function validateZipcode(zipcode){
				var regex = /^[0-9]{5}$/;
				return regex.test(zipcode);
			};
			function validateCityState(citystate){
				var regex = /^[a-zA-Z ]*$/;
				return regex.test(citystate);
			};
			function validateAddress(address) {
				var regex = /^[0-9a-zA-Z- ]*$/;
				return regex.test(address);
			};
			function validateEmail(email) {
			    var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
			    return regex.test(email);
			};
			function validateFormFields() {
				var valid = true;
				var $form = $('#payment-form');
				var firstname = $form.find('#firstname').val();
				var lastname = $form.find('#lastname').val();
				var email = $form.find('#email').val();
				var streetaddress = $form.find('#streetaddress').val();
				var city = $form.find('#city').val();
				var state = $form.find('#state').val();
				var zipcode = $form.find('#zipcode').val();
				var domain = $form.find('#domain').val();
				var servers = $form.find('#servers').val();
				var pages = $form.find('#pages').val();
				$form.find('#firstname').css("border", "1px solid gray");
				$form.find('#lastname').css("border", "1px solid gray");
				$form.find('#email').css("border", "1px solid gray");
				$form.find('#streetaddress').css("border", "1px solid gray");
				$form.find('#city').css("border", "1px solid gray");
				$form.find('#state').css("border", "1px solid gray");
				$form.find('#zipcode').css("border", "1px solid gray");
				$form.find('#domain').css("border", "1px solid gray");
				$form.find('#servers').css("border", "1px solid gray");
				$form.find('#pages').css("border", "1px solid gray");
				$form.find('.firstname-error').text("*");
				$form.find('.lastname-error').text("*");
				$form.find('.email-error').text("*");
				$form.find('.streetaddress-error').text("*");
				$form.find('.state-error').text("*");
				$form.find('.city-error').text("*");
				$form.find('.zipcode-error').text("*");
				$form.find('.domain-error').text("*");
				$form.find('.pages-error').text("");
				$form.find('.servers-error').text("");

				if (isEmpty(firstname)) {
					$form.find('.firstname-error').text("* First Name is required");
					valid = false;
					$form.find('#firstname').css("border", "2px solid #FF0000");
				}
				else if (!validateName(firstname)) {
					$form.find('.firstname-error').text("* First Name may not contain numbers or special characters");
					valid = false;
					$form.find('#firstname').css("border", "2px solid #FF0000");
				}
				if (isEmpty(lastname)) {
					$form.find('.lastname-error').text("* Last Name is required");
					valid = false;
					$form.find('#lastname').css("border", "2px solid #FF0000");
				}
				else if (!validateName(lastname)) {
					$form.find('.lastname-error').text("* Last Name may not contain numbers or special characters");
					valid = false;
					$form.find('#lastname').css("border", "2px solid #FF0000");
				}
				if (isEmpty(email)) {
					$form.find('.email-error').text("* Email is required");
					valid = false;
					$form.find('#email').css("border", "2px solid #FF0000");
				}
				else if (!validateEmail(email)) {
					$form.find('.email-error').text("* Email is invalid");
					valid = false;
					$form.find('#email').css("border", "2px solid #FF0000");
				}
				if (isEmpty(streetaddress)) {
					$form.find('.streetaddress-error').text("* Street Address is required");
					valid = false;
					$form.find('#streetaddress').css("border", "2px solid #FF0000");
				}
				else if (!validateAddress(streetaddress)) {
					$form.find('.streetaddress-error').text("* Street Address may not contain special characters");
					valid = false;
					$form.find('#streetaddress').css("border", "2px solid #FF0000");
				}
				if (isEmpty(city)) {
					$form.find('.city-error').text("* City is required");
					valid = false;
					$form.find('#city').css("border", "2px solid #FF0000");
				}
				else if (!validateCityState(city)) {
					$form.find('.city-error').text("* City may not contain numbers or special characters");
					valid = false;
					$form.find('#city').css("border", "2px solid #FF0000");
				}
				if (isEmpty(state)) {
					$form.find('.state-error').text("* State is required");
					valid = false;
					$form.find('#state').css("border", "2px solid #FF0000");
				}
				else if (!validateCityState(state)) {
					$form.find('.state-error').text("* State may not contain numbers or special characters");
					valid = false;
					$form.find('#state').css("border", "2px solid #FF0000");
				}
				if (isEmpty(zipcode)) {
					$form.find('.zipcode-error').text("* Zipcode is required");
					valid = false;
					$form.find('#zipcode').css("border", "2px solid #FF0000");
				}
				else if (!validateZipcode(zipcode)) {
					$form.find('.zipcode-error').text("* Zipcode must by five digits");
					valid = false;
					$form.find('#zipcode').css("border", "2px solid #FF0000");
				}
				if (isEmpty(domain)) {
					$form.find('.domain-error').text("* Domain is required");
					valid = false;
					$form.find('#domain').css("border", "2px solid #FF0000");
				}
				else if (!validateDomain(domain)) {
					$form.find('.domain-error').text("* Domain is invalid");
					valid = false;
					$form.find('#domain').css("border", "2px solid #FF0000");
				}
				if (!validateInt(pages)) {
					$form.find('.pages-error').text("* Page Number must be an integer");
					valid = false;
					$form.find('#pages').css("border", "2px solid #FF0000");
				} 
				if (!validateInt(servers)) {
					$form.find('.servers-error').text("* Server Number must be an integer");
					valid = false;
					$form.find('#servers').css("border", "2px solid #FF0000");
				}
				return valid;
			};
			jQuery(function($) {
			$('#payment-form').submit(function(event) {
			    var $form = $(this);
			    $form.find('#cardnumber').css("border", "1px solid gray");
			    $form.find('#cardcvc').css("border", "1px solid gray");
			    $form.find('#cardexp-month').css("border", "1px solid gray");
			    $form.find('#cardexp-year').css("border", "1px solid gray");

			    $form.find('.button').prop('disabled', true);
			    Stripe.card.createToken($form, stripeResponseHandler);

			    // Prevent the form from submitting with the default action
			    return false;
			  });
			});
			function stripeResponseHandler(status, response) {
			var $form = $('#payment-form');

			if (response.error) {
			    // Post the errors
			    $form.find('#cardnumber').css("border", "2px solid #FF0000");
			    $form.find('#cardcvc').css("border", "2px solid #FF0000");
			    $form.find('#cardexp-month').css("border", "2px solid #FF0000");
			    $form.find('#cardexp-year').css("border", "2px solid #FF0000");

			    validateFormFields();
			    $form.find('.payment-error').text(response.error.message);
			    $form.find('.button').prop('disabled', false);
			} else {
				if (validateFormFields()) {
				    // response contains id and card, which contains additional card details
				    var token = response.id;
				    // Insert the token into the form so it gets submitted to the server
				    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
				    // and submit
				    $form.get(0).submit();
				}
			  }
			};
		</script>

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
								<span>Signing up with Weblytics requires a one-time $5.00 fee.</span>
								<span>Please enter your information below.</span>
								</br></br>
								<span><?php echo $success;?></span></br>
								<span><?php echo $mail_sent;?></span>
								<p>* denotes a required field.</p>
								<form id="payment-form" action="<?php $_PHP_SELF ?>" method="POST">
									<h4>First Name</h4>
									<input type="text" id="firstname" name="firstname">
									<span class="firstname-error">*</span>
									<h4>Last Name</h4>
									<input type="text" id="lastname" name="lastname">
									<span class="lastname-error">*</span>
									<h4>Email</h4>
									<input type="text" id="email" name="email">
									<span class="email-error">*</span>
									<h4>Street Address</h4>
									<input type="text" id="streetaddress" name="streetaddress">
									<span class="streetaddress-error">*</span>
									<h4>City</h4>
									<input type="text" id="city" name="city">
									<span class="city-error">*</span>
									<h4>State</h4>
									<input type="text" id="state" name="state">
									<span class="state-error">*</span>
									<h4>Zipcode</h4>
									<input type="text" id="zipcode" name="zipcode">
									<span class="zipcode-error">*</span>
									<h4>Site Domain Name</h4>
									<input type="text" id="domain" name="domain">
									<span class="domain-error">*</span>
									<h4>Number of Servers</h4>
									<input type="text" id="servers" name="servers">
									<span class="servers-error"></span>
									<h4>Number of Pages</h4>
									<input type="text" id="pages" name="pages">
									<span class="pages-error"></span>
									<h4>Card Number</h4>
									<input type="text" id="cardnumber" size="20" data-stripe="number"/> *
									<h4>CVC</h4>
									<input type="text" id="cardcvc" size="4" data-stripe="cvc"/> *
									<h4>Expiration (MM/YYYY)</h4>
									<input type="text" id="cardexp-month" size="2" data-stripe="exp-month"/>
    								<span> / </span><input type="text" id="cardexp-year" size="4" data-stripe="exp-year"/> *
									</br><span class="payment-error"></span></br></br>
									<button type="submit" class="button">Sign Up</button>
								</form>
							</section>
						</div>

						<div class="8u 12u(mobile)">

							<section>
								<h2>Signing Up with Weblytics</h2>
								<img src="images/email-signup.jpg" alt="" class="top blog-post-image" />								
								<p>Enter your information into the form on the left to register with our service. 
									Pricing will vary with the number of pages and servers you wish to test, 
									so you do not have to pay until you utilize the service. For a single domain, 
									the first page or server will start at $5.00 USD. Subsequent servers and pages 
									can be added at a price of $1.00 a piece.</p>
								<p>Note: The domain, server, and page fields on the form are only to give us an idea of 
									the level of service you would require.</p>
								<p>Register for an account today!</p>
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