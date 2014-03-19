<?php

/**
 * @author Chris S - AKA Someguy123
 * @version 0.01 (ALPHA!)
 * @license PUBLIC DOMAIN http://unlicense.org
 * @package +Coin - Bitcoin & forks Web Interface
 */
$pageid = 6;
include("header.php");
$fmaccbal		= explode('|',$_POST['fmaccbal']);
$toaccount		= $_POST['toaccount'];
$move_iso_send	= ( $_POST['toaccount']		!= "---"	) ? 1 						: 0						;
$sendaddr		= ( $_POST['addressbook']	!= "---"	) ? $_POST['addressbook']	: $_POST['address']		;
$sendamt		= ( $_POST['amount']		== ""		) ? 0						: $_POST['amount']		;
$fmaccount		= $fmaccbal[0];
$fmbalance		= $fmaccbal[1];
settype($sendamt, 'double');

echo "
<div class='content'>
";
	if ($move_iso_send == 0) 
	{
		if	(
				( $sendamt > 0 )
				&& 
				( $sendaddr != "" )
				&&
				( $fmbalance >= $sendamt )
			)
		{
			try
			{
				if ($wallet_encrypted)
				{
			 		$nmc->walletpassphrase($_POST['walletpassphrase'], 1);
			 	}
			 	try
			 	{
					$nmc->sendfrom($fmaccount, $sendaddr, $sendamt);
					echo "
	<div class='alert alert-success'>
		Sending <b>{$sendamt}</b> from <b>\"{$fmaccount}\"</b> to <b>{$sendaddr}</b>
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
			catch(Exception $e)
			{
				echo "
	<div class='alert alert-error'>
		<strong>Passphrase Error!</strong> You entered the wrong passphrase while trying to send a payment.
	</div>
				";
			}
		}
		else
		{
			echo "
	<div class='alert alert-error'>
		<strong>Amount and/or address arguments missing, or insufficient funds, for a successfull send.</strong>
	</div>
			";
		}
	}
	else
	{
		if	(
				( $sendamt > 0 )
				&& 
				( $toaccount != "---" )
				&&
				( $move_iso_send == 1 )
				&&
				( $fmbalance >= $sendamt )
			)
		{
			try
			{
				if ($wallet_encrypted)
				{
				 	$nmc->walletpassphrase($_POST['walletpassphrase'], 1);
				}
				try
				{
					$nmc->move($fmaccount, $toaccount, $sendamt);
					echo "
	<div class='alert alert-success'>
		Moving <b>{$sendamt}</b> BTC from <b>\"{$fmaccount}\"</b> to <b>\"{$toaccount}\"</b>
	</div>
					";
				}
				catch(Exception $e)
				{
					echo "
	<div class='alert alert-error'>
		<b>Error:</b> Something went wrong... have you got enough to move that amount of money? Message returned from server: <br> {$e}
	</div>"
					;
				}   
			}
			catch(Exception $e)
			{
				echo "
	<div class='alert alert-error'>
		<strong>Passphrase Error!</strong> You entered the wrong passphrase while trying to move funds.
	</div>
				";
			}
		}
		else
		{
			echo "
	<div class='alert alert-error'>
		<strong>Amount and/or account arguments missing, or insufficient funds, for a successfull move.</strong>
	</div>
			";
		}		
	}
echo "
</div>"
;
include("footer.php");
?>
