<?php

/**
 * @author Chris S - AKA Someguy123
 * @version 0.01 (ALPHA!)
 * @license PUBLIC DOMAIN http://unlicense.org
 * @package +Coin - Bitcoin & forks Web Interface
 */

//ini_set("display_errors", false);
$pageid = 4;
include ("header.php");
?>



<?php
if (isset($_GET['NewAddr']))
{
  $nmc->getnewaddress($_GET['NewAddr']);
}
echo "
                <table >
                <thead>
                        <tr>
                                <th>Address</th>
                                <th>Label</th>
                        </tr>
                </thead>
                <tbody>
";

$labels = $nmc->listlabels();
foreach ( $labels as $label)
{
        $address_info=$nmc->getaddressesbylabel($label);
        foreach ( $address_info as $address => $purp)
        {
                echo "<tr><td>" . $address ."</td><td>".$label."</td></tr>";
        }
}
echo "</tbody></table>";
$groupings = $nmc->listaddressgroupings();
echo "
		<table >
		<thead>
			<tr>
				<th>Address</th>
				<th>Unspent</th>
				<th>Label</th>
			</tr>
		</thead>
		<tbody>
";
foreach ($groupings as $group0 => $member)
{
	foreach ($member as $group)
	{
		echo "
				<tr>
					<td>
						" . $group[0] . "
					</td>
					<td>
						" . $group[1] . "
					</td>
		";
		if ( count($group) > 2 )
		{

			echo "<td>" . $group[2] . "</td>";
		}
		else
		{
			echo "<td> No label </td>";
		}
		echo "</tr>"
	}
}
echo "
		</tbody>
		</table>
"; 
?>


<?php    
include ("footer.php");
?>



