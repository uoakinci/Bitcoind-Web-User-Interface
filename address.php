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

<!-- Java Script -->
<script type='text/javascript'>

$(document).on("click", ".open-EditAddrDialog", function () {
     var myAddrId = $(this).data('id');
     $("#myAddress").html(myAddrId);
     $(".modal-body #myAddress").val( myAddrId );
     
     var myAddrName = $(this).data('name');
     $(".modal-body #AddrName").val( myAddrName );
     
    $('#EditAddrDialog').modal('show');
});

</script>

<?php
if (isset($_POST['addaddr']))
{
  $nmc->getnewaddress($_POST['account']);
}

$myaddresses = file("myaddresses.csv");
$myaddress_arr = array();
foreach ($myaddresses as $line)
{
    $values = explode(";", $line);
    $address = $values[0];
    $name = str_replace("\n", "", $values[1]);
    $myaddress_arr[$address] = $name;
}

if (isset($_POST['AddrName']) && isset($_POST['myAddress']))
{
	$myaddress_arr[$_POST['myAddress']] = $_POST['AddrName'];
		
	$f = fopen("myaddresses.csv", "w");
	
	foreach ($myaddress_arr as $address => $name)
	{
	    $line = $address.";".$name."\n";		
	    fputs($f, $line);
	}
	fclose($f);
}


$groupings = $nmc->listaddressgroupings();
echo "
		<table >
		<thead>
			<tr>
				<th>Address</th>
				<th>Unspent</th>
				<th>Label</th>
				<th>Purpose</th>
				<th>Edit</th>
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
					<td>
		";
		$addr_info	= $nmc->getaddressinfo($group[0]);
		$labels_ar	= $addr_info["labels"];  /* json label array; name purpose */
		$this_addr	= $addr_info["address"]; /* validated address */
		if ( count($labels_ar) > 0 ) { /* check that labels are given */
			$mem0		= $labels_ar[0];
			$label_name	= $mem0["name"];
			$purp		= $mem0["purpose"];
		}
		else
		{
			$label_name	= "-";
			$purp		= "-";
		}
		if ( count($group) > 2 )
		{

			echo "
						" . $group[2] . "
					</td>
					<td>
						" . $purp . "
			";
		}
		else
		{
			echo "
						" . "" . "
					</td>
					<td>
						" . "" . "
			";
		}
		echo "
					</td>
					<td>
						<a data-id='".$this_addr."' data-name='".$label_name."' data-toggle='modal' href='#EditAddrDialog' class='open-EditAddrDialog btn btn-mini'>
							Edit
						</a>
					</td>
				</tr>
		";
	}
}
echo "
		</tbody>
		</table>
"; 
?>

		<form action='address.php' method='POST'>
<!-- Modal --->
			<div id="EditAddrDialog" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h3 id="myModalLabel">
						Change Address Name
					</h3>
				</div>
				<div class="modal-body">
					<table>
						<tr>
							<td>
								<div id="myAddress">
									Address to change
								</div>
							</td>
							<td>
								&nbsp; &nbsp;
								<input type="text" name="AddrName" id="AddrName" value="Name"/>
							</td>
						</tr>
					</table>
					<input type="hidden" name="myAddress" id="myAddress" value="Nothing"/>	
					<input type="hidden" name="label" id="label" value="<?php echo $label_name ?>"/>	
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal">
						Close
					</button>
					<button class="btn btn-primary">
						Save Changes
					</button>
				</div>
			</div>
		</form>
<?php    
include ("footer.php");
?>



