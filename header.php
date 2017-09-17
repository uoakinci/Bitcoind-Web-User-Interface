<?php
include("config.php");
include("jsonRPCClient.php");
/**
* @author Chris S - AKA Someguy123
* @version 0.01 (ALPHA!)
* @license PUBLIC DOMAIN http://unlicense.org
* @package +Coin - Bitcoin & forks Web Interface
*/
/**
* This header verifies that the daemon is responding
* It also creates the template header for bootstrap
* which includes the nice navigation bar
* and all the CSS.
* 
*/
$nmc = new jsonRPCClient("{$nmcu['protocol']}://{$nmcu['user']}:{$nmcu['pass']}@{$nmcu['host']}:{$nmcu['port']}", true);
try {
	$nmcinfo = $nmc->getnetworkinfo();
}
catch(exception $e) {
	echo "Failed to retrieve data from the daemon, please check your configuration, and ensure that your coin daemon is running:<br>	{$e}";
}

$wallet_encrypted = true;
try {
 	$nmc->walletlock();
}
catch(Exception $e) { // Wallet is not encrypted
	$wallet_encrypted = false;
}
 
// Begin bootstrap code
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>Bitcoin Web UI</title>
	<meta name='description' content=''>
	<meta name='author' content=''>

	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
		<script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script>
	<![endif]-->

	<!-- Le styles -->
	<link href='css/bootstrap.min.css' rel='stylesheet'>
	<style type='text/css'>
		body {
			padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f0f0f0
		}
		.container > footer p {
			text-align: center; /* center align it with the container */
		}
		 .page-header {
			background-color: #f7f7f7;
			padding: 20px 20px 10px;
			margin: 0px 0px 20px;
		}
		.content {
			margin: 0px 0px 20px;
			padding: 20px 20px 10px;
			minimum-height: 600px;
			background-color: #fff;
			-webkit-border-radius: 0 0 10px 10px;
			-moz-border-radius: 0 0 10px 10px;
			border-radius: 0 0 10px 10px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
			box-shadow: 0 1px 2px rgba(0,0,0,.15);
		}
		.bitcoinsymbol{
		width: 32px;
		height: 50px;
		background-repeat: no-repeat;
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAyCAYAAAA9ZNlkAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3gMPECU1WUUKzQAAABl0RVh0Q29tbWVudABDcmVhdGVkIHdpdGggR0lNUFeBDhcAAAJ7SURBVFjD7ZhPiM1RFMc/b96Y8We8N9IkfzILiUm9KH82o2ShLNhYTWxslIUofzY2o8jKStlrQliwsUFRJkQWbIjSkH8h0fAYxmNzXt1+zrm/+3u/nzubd+rUe+fec37f372/c873XvBLN9AAfomuccbOOGN/aFE6A+aUnHklx15OjLUkHUyxtAFMOYBO0R5PFrgyG+iV39OKAjEoaVSEfgWuA3uBuYmsMaUL6AdWAJuAawUCeg2cBapZV+WmEfCkMncGUAN2A1eAuuG7JwuAbUaQdYH+Fw3/kVAAy4wAazO8xE4jxpEQ54UFAAAYVWJ8ByppdWCyoAzbrtimuy/yvwvRC+CVYh+KWQmfKLZaTAB1o/ZEA9DnW5UYALTMuRALwD5hTsktuZHmOK+AOlAFvikxDoY45wWwGfip+J8IRW8BWC/NJ6kzgfnScD4lfBrA5wx9xAtgAvih6KQy9z1wCBjwkZeyYe8BDij230IyypLLTUalfcyzgEXCqsaNiph5BVYqcyvCqg4Dj6TZaL5jwNK8AEI+wgqwAfio+DeA0zGyoCmXjTinYgEAuGvE2hULwKCHsEbpBaOGfQGwKtbJ6KphH4oF4LlhXxILQJdhn4gFYMCwP4iRBXiObtUOz7VMUbLVsN8HvlhO/QWuwJgRa0saj8tzNmzePTxt9Xg2bDgeD3hwDTgqrTu1D5SUK5s+4DEwx3jAQ+A88EF4QS+wGFidskV1YAdw6Z9iIJeOt4CXBpfLo3eAjULd1H1qAO+kOdxWGNN+5/8I8Nb5upc7Y8dkRceBN8Az4J5sRcvS7cmCc4mx6Nd0hdSK9kVlG0AogCalTtrIkwEAfwG44EfGuvD3EwAAAABJRU5ErkJggg==);
		}
		.eurosymbol{
		width: 50px;
		height: 50px;
		background-repeat: no-repeat;
		background-image: url(data:image/png;base64,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);
		}
		.usdollarsymbol{
		width: 26px;
		height: 50px;
		background-repeat: no-repeat;
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAyCAYAAABCtcuVAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAEM3AABDNwFkIt34AAAAB3RJTUUH3gMPEDYRBKmvjgAAAiJJREFUWMPtmLtLXEEUxn9ufOCCUZAQH4VNCClSKYjgFsEuYichhSCmCNiltBESSJEmlnaWKhY2Lin2D5CoIPgsEjHiupKgoIJuIAmspjnKuMzsnZmdKxZ7YIp77pn55jzmm3MvuMkBcCUj4TIxwR1JBej+A1Vb2NQBr4Fe4JGi3waOgF1gDVgENl03kAQWZKErx3EErALjQEMpkDmPxU3jsy50jcB34HHA1JwWF0MdcBgBsg78UZ6nJLybQMEw56JYMQtcalzPAx8Uu2wJCkoBGeBcsXmjGjwxxPdXGVz3DjgDXqnKjxqQv4FItUHNUZ/G4FOgYriVo2ONR21xXBO6Q9USBw2daDxaDO0RwLKh6npChy5jeLcEjIYMXUsEX10Ab4F2IKfovWTMgzCH5LAnXcG+lMHSK8BzoMYW7H2Aq2HEFqwR2CoT7B/QZAvYLOy74wl2aTgiVqJW3YTsPArwmQ/Qgaa8O4F0RA9RFlAxMwyUABsMCQTQbbip50MDmUg6F0enmtboWuMAeqjRFeIIXV4Tuh8Jn0RFSL1G9xVB/G3JUVEe5Qzl3X8dv5vqEGJ1BaqRTlYHsgtQJT1crWbRb8CGcN2xPE8rbXNKWOGlDJN0yAat+EpHmDZ2XddoD2TSC/HOVqJsd4CnwJ7u5WSJZNqOLDDsUqL9wAzwU75x8pLL4v78XGwmDHn2lqxvF+RKQVW+cys/NCpA3kD7wnvO8h8iXmb0fbcW5AAAAABJRU5ErkJggg==);
		}
	</style>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<link href='css/bootstrap-responsive.min.css' rel='stylesheet'>
</head>
<body>
	<script src='js/jquery.min.js'></script>
	<script src='js/bootstrap.min.js'></script>
	<div class='navbar navbar-fixed-top'>
		<div class='navbar-inner'>
			<div class='container'>
				<a class='brand' href='#'>+Coin WebUI</a>
				<div class='nav-collapse'>
					<ul class='nav'>
						<li <?php if ($pageid == 1)	{ echo "class=active"; } ?>> <a href='index.php'>Home</a></li>
						<li <?php if ($pageid == 2)	{ echo "class=active"; } ?>> <a href='daemon.php'>Daemon Info</a></li>
						<li <?php if ($pageid == 3)	{ echo "class=active"; } ?>> <a href='btc.php'>Transactions</a></li>
						<li <?php if ($pageid == 4)	{ echo "class=active"; } ?>> <a href='address.php'>My Addresses</a></li>
						<li <?php if ($pageid == 5)	{ echo "class=active"; } ?>> <a href='addressbook.php'>Addressbook</a></li>
						<li <?php if ($pageid == 6)	{ echo "class=active"; } ?>> <a href='index.php'>Move or Send</a></li>
					</ul>
				</div><!--/.nav-collapse -->
				<span style='color: #E4E4E4;'>Select wallet server: &nbsp;</span>
				<select id='currentWallet' onchange='window.location.href=\"index.php?currentWallet=\"+document.getElementById(\"currentWallet\").value;' style='margin-top: 5px;'>
<?php
	foreach ($wallets as $walletName => $walletData)
		echo "
					<option id=\"".$walletName."\" ".($currentWallet == $walletName ? "selected" : "").">".$walletName."</option>
		";
?>
				</select>
			</div><!--/.container -->
		</div><!--/.navbar-inner -->
	</div><!--/.navbar navbar-fixed-top -->
