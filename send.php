<?php

/**
 * @author Chris S - AKA Someguy123
 * @version 0.01 (ALPHA!)
 * @license PUBLIC DOMAIN http://unlicense.org
 * @package +Coin - Bitcoin & forks Web Interface
 */
$pageid = 6;
include("header.php");
$toaddr			= ( $_POST['addressbook']	!= "---"	) ? $_POST['addressbook']	: $_POST['address']		;
$sendamt		= ( $_POST['amount']		== ""		) ? 0						: $_POST['amount']		;
$fmbalance		= $_POST['fmbalance'];

settype($sendamt, 'double');

echo "
<div class='content'>
";
	if	(
			( $sendamt > 0 )
			&&
			( $toaddr != 0 )
			&& 
			( $fmbalance >= $sendamt )
		)
	{	/* data given and funds available */
		/* try regular transfer to given wallet address; address field cannot be empty here */
		if ($validator->validate($toaddr) == TRUE) 
		{
			echo "
	<div class='alert alert-success'>
		Validated destination address!<br>
	</div>
		";
		if ($wallet_encrypted)
		{
			try
			{
				$nmc->walletpassphrase($_POST['walletpassphrase'], 1);
				}
				catch(Exception $e)
				{
					echo "
	<div class='alert alert-error'>
		<strong>Passphrase Error!</strong> You entered the wrong passphrase while trying to send a payment.
	</div>
				";
				}
			}
			try
			{
				$nmc->sendtoaddress($toaddr, $sendamt);
				echo "
	<div class='alert alert-success'>
		Sending <b>{$sendamt}</b> to <b>{$toaddr}</b>
	</div>
			";
			}
			catch(Exception $e)
			{
				echo "
	<div class='alert alert-error'>
		<b>Error:</b> Something went wrong... have you got enough to send that amount of money? Message returned from server: <br> {$e}
	</div>
			";
			}
		}
		else
		{
			echo "
	<div class='alert alert-error'>
		<b>Error:</b> Send address invalid! <br>
	</div>
			";
		} 
	}
	else /* not all data given or sufficient funds available */
	{
		echo "
	<div class='alert alert-error'>
		<strong>Amount and/or address arguments missing, or insufficient funds, for a successfull send.</strong>
	</div>
		";
	}
echo "
</div>"
;
include("footer.php");
?>
