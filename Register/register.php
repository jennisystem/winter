<?php
	$table = "winter_users";
	$dbhost = "localhost";
	$dbname = "winterdb";
	$dbuser = "root";
	$dbpass = "";

	$emailReg = "^[^@]{1,64}@[^@]{1,255}$";
	$emailDblReg = "^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$";
	$emailTplReg"^\[?[0-9\.]+\]?$";
	
	$userReg = "^[a-zA-Z0-9]*$";
	
	$domainReg = "^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|?([A-Za-z0-9]+))$";
	
	$username = "This Name Is Too Long And Will Not Work With The Game So It Is Invalid So Enter A Real Username That Is Valid When Signing Up";
	$email = "thisisafakeinvalidemailanddoesnotworksoenteryouremailwhensigningup";
	$colour = 1;

	function check_email_address($email)
	{
		if (!ereg($emailReg, $email)) {
			return false;
		}
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if ( !ereg($emailDblReg, $local_array[$i])) {
				return false;
			}
		}
		if (!ereg($userReg, $_POST["username"])) {
			error('Your username may only contain letters, numbers, and valid characters.');
		}

		if (!ereg($emailTplReg, $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false;
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if(!ereg($domainReg,$domain_array[$i]))
				{
					return false;
				}
			}
		}
		return true;
	}
	function error($error)
	{
		$fullerror = "<h1> An Error Occurred</h1><p>".$error."</p>";
		die($fullerror);
	}

	$conn = mysqli_connect($dbhost, $dbuser, $dbpass) or error("Could not connect: ".$conn->error());
	$conn->select_db($dbname) or error($conn->error());

	if (isset($_POST['submit'])) {

		if (!$_POST['username'] | !$_POST['pass'] | !$_POST['pass2']) {
			error('You did not complete all of the required fields');
		}
		if ($_POST['colour'] >= 16) {
			error('Incorrect Colour');
		}

		if (!get_magic_quotes_gpc()) {
			$_POST['username'] = addslashes($_POST['username']);
		}
		if (ereg("[^A-Za-z0-9_ #$%&'*+/=?^_`{|}~-<>]", $_POST['username'])) {
			error("Your name is invalid. Please try using letters numbers, and a few special characters");
		}
		if (substr($_POST['username'], 0, 1) == " " || substr(strrev($_POST['username']), 0, 1) == " ") {
			error('Error in Username');
		}
		$_POST['username'] = $conn->real_escape_string($_POST['username']);
		$_POST['pass'] = $conn->real_escape_string($_POST['pass']);
		$_POST['colour'] = $conn->real_escape_string($_POST['colour']);
		$_POST['email'] = $conn->real_escape_string($_POST['email']);
		if (!get_magic_quotes_gpc()) {
			$_POST['pass'] = addslashes($_POST['pass']);
			$_POST['email'] = addslashes($_POST['email']);
			$_POST['colour'] = addslashes($_POST['colour']);
			$_POST['username'] = addslashes($_POST['username']);
		}
		$usercheck = $_POST['username'];
		$check = $conn->query("SELECT username FROM $table WHERE username = '$usercheck'")
			or error($conn->error());
		/*
		$ipcheck = $_SERVER['REMOTE_ADDR'];
		$check5= $conn->query("SELECT ip FROM ip_bans WHERE ip = '$ipcheck'") 
		or error($conn->error());
		$check6 = mysql_num_rows($check5);
		*/
		if (check_email_address($_POST['email']) == false) {
			error("Invalid Email!");
		}

		if ($check->num_rows() != 0) {
			error('Sorry, the username '.$_POST['username'].' is already in use.');
		}
		// if ($check6 != 0) {
		//		error('Sorry, it seems that you are IP banned. If you believe this was a mistake, please contact a staff member on the chat.');
		//				}

		if ($_POST['pass'] != $_POST['pass2']) {
			error('Your passwords did not match. ');
		}
		if (strlen($_POST['pass']) <= 3) {
			error('Your password is too short! ');
		}

		$_POST['pass'] = md5($_POST['pass']);
		$ip = $_SERVER['REMOTE_ADDR'];
		if ($ip == "78.144.144.168") {
			error("Sorry bro. You quit.");
		}

		$insert = "INSERT INTO $table (`id`, `username`, `nickname`, `email`, `password`, `active`, `ubdate`, `items`, `curhead`, `curface`, `curneck`, `curbody`, `curhands`, `curfeet`, `curphoto`, `curflag`, `colour`, `buddies`, `ignore`, `joindate`, `lkey`, `coins`, `ismoderator`, `rank`, `ips`) VALUES (NULL, '".$_POST['username']."', '".$_POST['username']."', '".$_POST['email']."', '".$_POST['pass']."', '1', '0', '', '0', '0', '0', '0', '0', '0', '0', '0', '".$_POST['colour']."', '', '', CURRENT_TIMESTAMP, '', '1000', '0', '1', '".$ip."')";
		$log = "Username: ".$_POST['username']." Pass:".$_POST['pass']." Colour:".$_POST['colour']." Email:".$_POST['email']." IP:".$ip." \n";
		$add_member = $conn->query($insert);
	}
?>
<!DOCTYPE html>
<html>
	<head>    
		<title>Penguin Elite :: Registration</title>
	</head>
	<body>
		<form action="register.php" method="post">
			<table border="0">
				<tr>
					<td>Username:</td>
					<td>
						<input type="text" name="username" maxlength="60">
					</td>
				</tr>
				<tr>
					<td>Email Address:</td>
					<td>
						<input type="text" name="email" maxlength="60">
					</td>
				</tr>
				<tr>
					<td>Password:</td>
					<td>
						<input type="password" name="pass" maxlength="10">
					</td>
				</tr>
				<tr>
					<td>Confirm Password:</td>
					<td>
						<input type="password" name="pass2" maxlength="10">
					</td>
				</tr>
				<tr>
					<td>Colour:</td>
					<td>
						<select name="colour" id="colour">
							<option value="1" selected="true">Blue</option>
							<option value="2">Green</option>
							<option value="3">Pink</option>
							<option value="4">Black</option>
							<option value="5">Red</option>
							<option value="6">Orange</option>
							<option value="7">Yellow</option>
							<option value="8">Dark Purple</option>
							<option value="9">Brown</option>
							<option value="10">Peach</option>
							<option value="11">Dark Green</option>
							<option value="12">Light Blue</option>
							<option value="13">Light Green</option>
							<option value="14">Gray</option>
							<option value="15">Aqua</option>
						</select>
					</td>
				</tr>
				<tr>
					<th colspan=2><input type="submit" name="submit" value="Register"></th>
				</tr>
			</table>
		</form>
	</body>
</html>