<?php
/**
 * @author Chris S - AKA Someguy123
 * @version 0.01 (ALPHA!)
 * @license PUBLIC DOMAIN http://unlicense.org
 * @package +Coin - Bitcoin & forks Web Interface
 */
ini_set("display_errors", false);
$pageid = 3;
include ("header.php");
$trans = $nmc->listtransactions('*', 100);
$x = array_reverse($trans);
$bal = $nmc->getbalance("*", 6);	// confirmed balance of wallet 
$bal3 = $nmc->getbalance("*", 0);	// unconfirmed balance of wallet
$bal2 = $bal - $bal3;				// unconfirmed transactions underway
$pbal = number_format($bal,8);
$pbal2 = number_format($bal2,8);
$pbal3 = number_format($bal3,8);
echo "
<div class='content'>
	<div class='span5'>
		<h3>Confirmed Balance: <font color='green'>{$pbal} BTC</font></h3>
		<h4>Unconfirmed Balance: <font color='red'>{$pbal3} BTC</font></h4>
		<h4>Awaiting Confirmation: <font color='red'>{$pbal2} BTC</font></h4>
	</div>
	<div class='span5'>
		<a href='?orphan=1'>
			View Orphans
		</a><br>
		<a href='btc.php'>
			Go back
		</a>
	</div>
	<table class='table-striped table-bordered table'>
		<thead>
		<tr>
			<th>
				Method
			</th>
			<th>
				Address
			</th>
			<th>
				Name
			</th>
			<th>
				Account
			</th>
			<th>
				Amount
			</th>
			<th>
				Confirmations
			</th>
			<th>
				Time
			</th>
		</tr>
		</thead>
";

// Load address book
$addresses_arr = array();
$addressbook = file("addressbook.csv");
foreach ($addressbook as $line)
{
	$values = explode(";", $line);
	$address = $values[0];
	$name = str_replace("\n", "", $values[1]);
	$addresses_arr[$address] = $name;
}
// Load my addresses
$myaddresses = file("myaddresses.csv");
foreach ($myaddresses as $line)
{
	$values = explode(";", $line);
	$address = $values[0];
	$name = str_replace("\n", "", $values[1]);
	$addresses_arr[$address] = $name;
}

foreach ($x as $x)
{
    if($x['amount'] > 0) { $coloramount = "green"; } else { $coloramount = "red"; }
	if (in_array('confirmations', $x))
	{
	    if($x['confirmations'] >= 6) { $colorconfirms = "green"; } else { $colorconfirms = "red"; }
	}
	if (!isset($_POST['orphan']))
	{
		$date = date(DATE_RFC822, $x['time']);
       	echo "
       	<tr>
       	<tr>
       		<td>
       			" . ucfirst($x['category']) . "
       		</td>
       	";
    	if (isset($x['address'])) 
    	{
    		echo "
    		<td>
    			{$x['address']}
    		</td>
    		<td>
			";
			if (in_array($x['address'], $addresses_arr))
			{
				echo "{$addresses_arr[$x['address']]}";
			}
			echo "
    		</td>
			";
			if (isset($x['label'])) 
    		{
				echo "
    			<td>
    				\"{$x['label']}\"
    			</td>
    			";
			}
    	}
    	else
    	{
            echo "
            <td style='text-align: center'>
            	Generated
            </td>
            <td>
            	N/A
            </td>
            <td>
            	N/A
            </td>
            ";
    	}    	
		echo "
			<td>
				<font color='{$coloramount}'>
					{$x['amount']}
				</font>
			</td>
			<td>
				<font color='{$colorconfirms}'>
		";
		if (isset($x['address']))
		{
			echo "{$x['confirmations']}";
		}
		echo "
				</font>
			</td>
			<td>
				{$date}
			</td>
		</tr>";
	}
	else
	{
		$date = date(DATE_RFC822, $x['time']);
		if ($x['category'] == "orphan")
		{
			echo "
		<tr>
			<td>
			";
			if (isset($x['label']))
			{
				echo "
				{$x['label']}
				";
			}
			echo "			
			</td>
			<td>
				{$x['amount']}
			</td>
			<td>
				{$x['confirmations']}
			</td>
			<td>
				{$x['category']}
			</td>
			<td>
				{$date}
			</td>
		</tr>
			";
		}
	}
}
echo "
	</table>
";
//print_r($x);   
include("footer.php");
?>
