<?php

//First, put here your API User & Password
define('YOUR_API_USER', '');
define('YOUR_API_PASSWORD', '');

require_once implode(DIRECTORY_SEPARATOR, [dirname(dirname(__FILE__)), 'vendor', 'autoload.php']);

$dondominio = new \Dondominio\API\API(array(
	'apiuser' => YOUR_API_USER,
	'apipasswd' => YOUR_API_PASSWORD
));
$page = (array_key_exists('page', $_GET)) ? intval($_GET['page']) : 1;
$pageLength = (array_key_exists('pageLength', $_GET)) ? intval($_GET['pageLength']) : 10;

$domainList = $dondominio->domain_list(array(
	'pageLength' => $pageLength,
	'page' => $page
));

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