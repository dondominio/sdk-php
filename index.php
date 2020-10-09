<?php

require_once("src/DonDominioAPI.php");

if( isset($_POST['url']) && isset( $_POST['params'] )){
	$client = new DonDominioAPI( array(
		'endpoint' => 'https://simple-api.dondominio.net',
		'port' => 443,
		'apiuser' => 'YOUR_API_USER',
		'apipasswd' => 'YOUR_API_PASSWORD',
		'timeout' => 15,
		'debug' => false,
		'debugOutput' => null,
		'largeResponse' => false,
		'verifySSL' => true,
		'outputFilter' => 'Array'
	));

	$parameters = explode( "\r\n", $_POST['params'] );

	try{
		$response = $client->call( $_POST['url'], $_POST['params'] );
	}catch( \DonDominioAPI_Error $e ){
		$error = $e->getMessage();
	}
}

?>

<!doctype>
<html>
<head>
	<title>DonDominio SDK Demo</title>
</head>
<body>
	<?php
	if(!empty($error)){
	?>

	<fieldset>
		<legend>Error</legend>
		
		<p><?php echo $error; ?></p>
	</fieldset>

	<?php
	}
	?>

	<?php
	if(!empty($response)){
	?>

	<fieldset>
		<legend>Response from Server:</legend>
		
		<code><?php echo $response; ?></code>
	</fieldset>

	<?php
	}
	?>

	<form action="index.php" method="post">
		<fieldset>
			<legend>New Request</legend>

			<label for="url">URL:</label>
			<input type="text" name="url" id="url" value="tool/hello" />

			<label for="params">Parameters (one per line, format key=value):</label>
			<textarea name="params" id="params" rows="10"></textarea>

			<button type="submit">Send</button>
		</fieldset>
	</form>
</body>
</html>
