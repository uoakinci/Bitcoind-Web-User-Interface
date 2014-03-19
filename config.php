<?php

/**
 * @author Chris S - AKA Someguy123
 * @version 0.01 (ALPHA!)
 * @license PUBLIC DOMAIN http://unlicense.org
 * @package +Coin - Bitcoin & forks Web Interface
 * This is the config file for +Coin
 * You MUST add your daemon host information to this
 * file, or it won't work.
 * wwortel 3/2014: DO READ SECURITYandCHANGES.txt
 * Communications via this WebUI need to be secured !!!!!!!
 */

session_start();
$wallets = array();

$wallets['wallet 1'] = array(
		"user"		=> "bitcoin",  
            	"pass" 		=> "<same password as 'rpcpassword' in bitcoin.conf>",      
		"host" 		=> "localhost",     
		"port" 		=> <same port as 'rpcport' in bitcoin.conf>,
		"protocol"	=> "http");            

if (isset($_POST['currentWallet']))
	$_SESSION['currentWallet'] = $_POST['currentWallet'];

if (isset($_SESSION['currentWallet']))
	$currentWallet = $_SESSION['currentWallet'];
else
{
	$keys = array_keys($wallets);
	$currentWallet = $keys[0];
	$_SESSION['currentWallet'] = $currentWallet;
}

$nmcu = $wallets[$currentWallet];

?>
