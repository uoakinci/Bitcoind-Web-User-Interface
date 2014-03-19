<?php
/**
* @author Chris S - AKA Someguy123
* @version 0.01 (ALPHA!)
* @license PUBLIC DOMAIN http://unlicense.org
* @package +Coin - Bitcoin & forks Web Interface
*/
ini_set("display_errors", false);
$pageid = 1;
include ("header.php");
// 7 last transactions; any account
$trans = $nmc->listtransactions('*', 7);
$x = array_reverse($trans);
$bal = $nmc->getbalance("*", 6);	// confirmed balance of wallet 
$bal3 = $nmc->getbalance("*", 0);	// unconfirmed balance of wallet
$bal2 = $bal - $bal3;				// unconfirmed transactions underway
$pbal = number_format($bal,8);
$pbal2 = number_format($bal2,8);
$pbal3 = number_format($bal3,8);
// Calculate EUR balance
$arr = json_decode(file_get_contents("https://btc-e.com/api/2/btc_eur/ticker"),true);
$eur = $arr['ticker']['last'];
$peur = number_format($eur,2);
$pbaleur = number_format($bal*$eur,2);
// Calculate USD balance
$arr = json_decode(file_get_contents("https://btc-e.com/api/2/btc_usd/ticker"),true);
$usd = $arr['ticker']['last'];
$pbalusd = number_format($bal*$usd,2);

function data_uri($file, $mime) 
{  
  $contents = file_get_contents($file);
  $base64   = base64_encode($contents); 
  return ('data:' . $mime . ';base64,' . $base64);
}

?>
<!-- Java Script -->
<script type='text/javascript'>

$(document).on("click", ".open-SetPassPhrase", function () {	
	$('#SetPassPhrase').modal('show');
});
$(document).on("click", ".open-ChangePassPhrase", function () {	
	$('#ChangePassPhrase').modal('show');
});
</script>
<?php
// Show the wallet and exchange rates in BTC, EUR, and USD
	echo "
	<div class='content'>
		<div class='row'>
			<div class='span12'>
				<div class='row'>
					<div class='span5'>
						<h3><div class='bitcoinsymbol'></div></h3>
						<h3>Confirmed Balance: <font color='green'>{$pbal} BTC</font></h3>
						<h4>Unconfirmed Balance: <font color='red'>{$pbal3} BTC</font></h4>
						<h4>Awaiting Confirmation: <font color='red'>{$pbal2} BTC</font></h4>
						<br>
						<h3><div class='eurosymbol'></div></h3>
						<h3>Confirmed Balance: <font color='green'>{$pbaleur} EUR</font></h3>
						<h4><a href='http://preev.com/btc/eur'>Trading Price: 1 BTC = </a> <font color='blue'>{$peur} EUR</font></h4>
						<br>
						<h3><div class='usdollarsymbol'></div></h3>
						<h3>Confirmed Balance: <font color='green'>{$pbalusd} USD</font></h3>
						<h4><a href='http://preev.com/btc/usd'>Trading Price: 1 BTC = </a> <font color='blue'>{$pusd} USD</font></h4>
						<br>
						<h3>Send coins:</h3>
						<form action='send.php' method='POST'>
	";
?>
						<table style="width: 100%;">
						<tr>
							<td>From account:</td>
							<td>
<?php $addr = $nmc->listaccounts();
// get account with max balance
	$maxBalance = -1;
	$maxAccount = null;
	foreach ($addr as $fmaccount => $fmbalance)
	{
		if ($balance > $maxBalance)
		{
			$maxBalance = $fmbalance;
			$maxAccount = $fmaccount;
		}
	}
	echo "
								<select name='fmaccbal'>
	";
	foreach ($addr as $fmaccount => $fmbalance)
	{
		$fmaccbal = $fmaccount.'|'.$fmbalance;
		echo "
									<option value='{$fmaccbal}' ".($fmaccount == $maxAccount ? " selected='selected' " : "").">
										\"{$fmaccount}\" ({$fmbalance})
									</option>
		";
	}
	echo "
								</select>
	";
?>
							</td>
						</tr>
						<tr>
							<td>Move to account:</td>
							<td>
<?php
// set to account to None; will determine send or move transaction
	echo "
								<select name='toaccount'>
									<option value='---' selected='selected'>
										External; use Send to Address
									</option>
	";
	foreach ($addr as $toaccount => $tobalance)
	{
		echo "
									<option value='{$toaccount}'>
										\"{$toaccount}\" ({$tobalance})
									</option>
		";
	}
	echo "
								</select>
	";
?>
							</td>
						</tr>
						<tr>
							<td>Send to address:</td>
							<td>
<?php 
// addressbook
	$addressbook = file("addressbook.csv");
	echo "
								<select name='addressbook'>
	";
	echo "
									<option value='---'>
										Use custom to address:
									</option>
	";
	foreach ($addressbook as $line)
	{
		$values = explode(";", $line);
		$address = $values[0];
		$name = str_replace("\n", "", $values[1]);
		echo "
									<option value='{$address}'>
										{$name} ({$address})
									</option>
		";
	}
	echo "
								</select><br>
	";
	echo "
								<input type='text' placeholder='To address' name='address'>
	";
?>
							</td>
						</tr>
						<tr>
							<td>Amount:</td>
							<td>
								<input type='text' placeholder='Amount' name='amount'>			
							</td>
						</tr>
						<tr>
							<td>Passphrase:</td>
							<td>
<?php
	if (isset($_POST['PassPhrase']) && isset($_POST['PassPhrase2']))
	{
//check both passwords are the same
		if ($_POST['PassPhrase'] === $_POST['PassPhrase2'])
		{
			if (isset($_POST['CurrPassPhrase']))
			{
// Change password
				try
				{
					$nmc->walletpassphrasechange($_POST['CurrPassPhrase'], $_POST['PassPhrase']);
					echo "
								<div class='alert alert-success'>
									<button type='button' class='close' data-dismiss='alert'>&times;</button>
									Wallet passphrase successfully changed.
								</div>
					";												
				} 
				catch(Exception $e)
				{
				 	echo "
				 				<div class='alert alert-error'>
						 			<strong>Passphrase error!</strong> Wrong current passphrase entered.
						 		</div>
					";
 				} 
			}
			else 
			{
// Set password
				$nmc->encryptwallet($_POST['PassPhrase']);
				echo "
								<div class='alert alert-success'>
									<button type='button' class='close' data-dismiss='alert'>&times;</button>
									Wallet is now encypted.<br>Keep that passphrase safe!
								</div>
				";												
			}							
		}
		else
		{
			echo "
								<div class='alert alert-error'>
									<button type='button' class='close' data-dismiss='alert'>&times;</button>
									<strong>Warning!</strong> Passphrases do not match!<br>Wallet encryption not set.
								</div>
			";
		}
	}				
	if ($wallet_encrypted)
	{
		echo "
								<div class='input-append'>
									<input type='password' placeholder='Wallet Passphrase' name='walletpassphrase'> &nbsp; &nbsp;
									<a href='#ChangePassPhrase' class='open-ChangePassPhrase btn btn-tiny'>Change</a>
								</div>
		";
	}
	else 
	{
		echo "
								Wallet un-encrypted &nbsp; &nbsp; <a href='#SetPassPhrase' class='open-SetPassPhrase btn btn-tiny'>Set</a>
		";
	}
?>		
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<br><input class='btn btn-primary' type='submit' value='Move or Send'>	
							</td>
						</tr>
						</table>
						</form>
					</div><!--/.span5 -->
					<div class='span6'>
						<table class='table-striped table-bordered table'>
							<thead>
							<tr>
								<th>Method</th>
								<th>Account and Address</th>
								<th>Amount</th>
								<th>Confirms</th>
							</tr>
							</thead>
<?php 
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
	if($x['confirmations'] >= 6) { $colorconfirms = "green"; } else { $colorconfirms = "red"; }
	
//	$date = date(DATE_RFC822, $x['time']);
	echo "
							<tr>
	";
	echo "
								<td>
									 " . ucfirst($x['category']) . " 
								</td>
	";
	if (isset($x['address']))
	{
		if ($addresses_arr[$x['address']])
		{
			$name = $addresses_arr[$x['address']];
		}
		else
		{ 
			$name = $x['address'];
		}
		echo "
								<td>
									{$name} - {$x['account']}
								</td>
		";
	}
	else
	{
	   echo "
	   							<td style='text-align: center'>
	   								Generated
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
										{$x['confirmations']}
									</font>
								</td>
							</tr>
	";
}
?>
						</table>
						<a href='btc.php'>More...</a>
					</div><!--/.span6 -->
				</div><!--/.row -->
			</div><!--/.span12 -->
		</div><!--/.row -->
	<form action='index.php' method='POST'>
<!-- Modal --->
		<div id="SetPassPhrase" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="myModalLabel">Set Wallet Pass Phrase</h3>
			</div>
			<div class="modal-body">
				Choose a long, secure passphrase... Your wallet depends on it:
				<br><input type="password" class="input-xxlarge" name="PassPhrase" id="PassPhrase" value="" />
				<br>Re-type to confirm:
				<br><input type="password" class="input-xxlarge" name="PassPhrase2" id="PassPhrase2" value="" />
				<br>Passphrase can be changed later.
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Close</button>
				<button class="btn btn-primary">Save Changes</button>
			</div>
		</div>
<!-- Modal --->
		<div id="ChangePassPhrase" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="myModalLabel">Change Wallet Pass Phrase</h3>
			</div>
			<div class="modal-body">
				Enter your current passphrase:
				<br><input type="password" class="input-xxlarge" name="CurrPassPhrase" id="CurrPassPhrase" value="" />
				<br>Choose a new long, secure passphrase:
				<br><input type="password" class="input-xxlarge" name="PassPhrase" id="PassPhrase" value="" />
				<br>Re-type to confirm:
				<br><input type="password" class="input-xxlarge" name="PassPhrase2" id="PassPhrase2" value="" />
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal">Close</button>
				<button class="btn btn-primary">Save Changes</button>
			</div>
		</div>
	</form>
<?php 
include("footer.php"); // starts with closing div (.navbar navbar-fixed-top)
?>
