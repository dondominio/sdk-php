# Examples

### Check domain availablity

```php
<?php

require_once('src/DonDominio.php');

$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('example.com');

$domains = array();

try{
	$domains = $domainCheck->get('domains');
}catch(DonDominio_Error $e){
	die('Error found: ' . $e->getMessage());
}

if(count($domains) > 0){
	foreach($domains as $key=>$domain){
		$name = $domain['name'];
		$available = ($domain['available'] == true) ? 'Yes' : 'No';
		$premium = ($domain['premium'] == true) ? 'Yes' : 'No';
		$tld = $domain['tld'];
		$price = $domain['price'] . ' ' . $domain['currency'];
		
		echo $name . "\r\n";
		echo "   Available:\t" . $available . "\r\n";
		echo "   Premium:  \t" . $premium . "\r\n";
		echo "   TLD:      \t" . $tld . "\r\n";
		echo "   Price:    \t" . $price . "\r\n";
		echo "\r\n";
	}
}else{
	echo "No domains found.\r\n";
}

?>
```

### Register a domain

```php
<?php

require_once('src/DonDominio.php');

$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('pepe12345.com');

$domains = array();

try{
	$domains = $domainCheck->get('domains');
}catch(DonDominio_Error $e){
	die('Error found: ' . $e->getMessage());
}

if(count($domains > 0) && $domains[0]['available'] == true){
	//The domain is available to register
	$data = array(
		'period' => 1,
		'premium' => false,
		'nameservers' => 'ns1.dns.com,ns2.dns.com',
		'ownerContactType' => 'individual',
		'ownerContactFirstName' => 'John',
		'ownerContactLastName' => 'Doe',
		'ownerContactIdentNumber' => 'XX00000',
		'ownerContactPhone' => '+00.00000000',
		'ownerContactEmail' => 'john.doe@example.com',
		'ownerContactAddress' => 'Example Address 123',
		'ownerContactCity' => 'Example City',
		'ownerContactPostalCode' => '00000',
		'ownerContactState' => 'Example State',
		'ownerContactCountry' => 'US'
	);
	
	try{
		$domainCreate = $dondominio->domain_create(
			'pepe12345.com',
			$data
		);
		
		$billing = $domainCreate->get('billing');
		$domains = $domainCreate->get('domains');
		
		echo "Register successful\r\n";
		echo "   Amount:    \t" . $billing['total'] . " " . $billing['currency'] . "\r\n";
		echo "\r\n";
		
		foreach($domains as $key=>$domain){
			echo $domain['name'] . "\r\n";
			echo "   Status:    \t" . $domain['status'] . "\r\n";
			echo "   TLD:       \t" . $domain['tld'] . "\r\n";
			echo "   Expiration:\t" . $domain['tsExpir'] . "\r\n";
			echo "   Domain ID: \t" . $domain['domainID'] . "\r\n";
			echo "   Period:    \t" . $domain['period'] . "\r\n";
		}
	}catch(DonDominio_Error $e){
		die('Error found: ' . $e->getMessage());
	}
}else{
	echo "Register failed.\r\n";
}

?>
```

### List domains

```php
<?php

require_once('src/DonDominio.php');

$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXX'
));

$page = (array_key_exists('page', $_GET)) ? intval($_GET['page']) : 1;
$pageLength = (array_key_exists('pageLength', $_GET)) ? intval($_GET['pageLength']) : 10;

$domainList = array();

try{
	$domainList = $dondominio->domain_list(array(
		'pageLength' => $pageLength,
		'page' => $page
	));
}catch(DonDominio_Error $e){
	die('Error found: ' . $e->getMessage());
}

$pageInfo = $domainList->get('queryInfo');
$domains = $domainList->get('domains');

?>

<!doctype>
<html>
	<head>
		<title>DonDominio API Client for PHP Demo</title>
	</head>
	
	<form action="#" method="get" id="options">
		Results per page: <select name="pageLength" onChange="document.getElementById('options').submit();">
			<option value="10" <?php if($pageInfo['pageLength'] == 10){ ?>selected="selected"<?php } ?>>10</option>
			<option value="20" <?php if($pageInfo['pageLength'] == 20){ ?>selected="selected"<?php } ?>>20</option>
			<option value="50" <?php if($pageInfo['pageLength'] == 50){ ?>selected="selected"<?php } ?>>50</option>
			<option value="100" <?php if($pageInfo['pageLength'] == 100){ ?>selected="selected"<?php } ?>>100</option>
			<option value="500" <?php if($pageInfo['pageLength'] == 500){ ?>selected="selected"<?php } ?>>500</option>
			<option value="1000" <?php if($pageInfo['pageLength'] == 1000){ ?>selected="selected"<?php } ?>>1000</option>
		</select>
	</form>

	<body>
		<table border="1" cellspacing="0" cellpadding="5" width="100%">
			<thead>
				<tr>
					<th>Domain name</th>
					<th>TLD</th>
					<th>Expiration</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(is_array($domains)){
					foreach($domains as $key=>$domain){
				?>
					<tr>
						<td><?php echo $domain['name']; ?></td>
						<td><?php echo $domain['tld']; ?></td>
						<td><?php echo $domain['tsExpir']; ?></td>
						<td><?php echo $domain['status']; ?></td>
					</tr>
				<?php
					}
				}else{
				?>
					<tr>
						<td colspan="4">
							<center>No domains found</center>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		
		<p>
		Page <?php echo $pageInfo['page']; ?> of <?php echo ceil($pageInfo['total'] / $pageInfo['pageLength']); ?> - Total domains: <?php echo $pageInfo['total']; ?> <br />
		<?php if($pageInfo['page'] > 1){ ?> <a href="?pageLength=<?php echo $pageInfo['pageLength']; ?>&page=<?php echo ($pageInfo['page'] - 1); ?>">Previous</a>&nbsp; <?php } ?>
		<?php if($pageInfo['page'] < ceil($pageInfo['total'] / $pageInfo['pageLength'])){ ?> <a href="?pageLength=<?php echo $pageInfo['pageLength']; ?>&page=<?php echo ($pageInfo['page'] + 1); ?>">Next</a>&nbsp; <?php } ?>
		</p>
	</body>
</html>
```