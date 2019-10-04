<?php
$pageid = 2;
include ("header.php");
echo "<div class='content'>
	<h2>Daemon Info</h2>";
?>
    <table class="table-striped table-bordered table">
    	<thead>
    		<tr>
    			<th>Key</th>
    			<th>Value</th>
    		</tr>
    	</thead>
    	<tbody>
    		 <?php $nwinfo = $nmc->getnetworkinfo(); ?>
    		 <?php $wainfo = $nmc->getwalletinfo(); ?>
    		 <?php $bcinfo = $nmc->getblockchaininfo(); ?>
    		 <?php $miinfo = $nmc->getmininginfo(); ?>
    		 <?php $key = "version";
                   echo "<tr><td>".$key."</td><td>".$nwinfo[$key]."</td></tr>";
                   $key = "protocolversion";
                   echo "<tr><td>".$key."</td><td>".$nwinfo[$key]."</td></tr>";
                   $key = "walletversion";
                   echo "<tr><td>".$key."</td><td>".$wainfo[$key]."</td></tr>";
                   $key = "balance";
                   echo "<tr><td>".$key."</td><td>".$wainfo[$key]."</td></tr>";
                   $key = "paytxfee";
                   echo "<tr><td>".$key."</td><td>".$wainfo[$key]."</td></tr>";
                   $key = "relayfee";
                   echo "<tr><td>".$key."</td><td>".$nwinfo[$key]."</td></tr>";
                   $key = "blocks";
                   echo "<tr><td>".$key."</td><td>".$bcinfo[$key]."</td></tr>";
                   $key = "timeoffset";
                   echo "<tr><td>".$key."</td><td>".$nwinfo[$key]."</td></tr>";
                   $key = "connections";
                   echo "<tr><td>".$key."</td><td>".$nwinfo[$key]."</td></tr>";
                   $key = "difficulty";
                   echo "<tr><td>".$key."</td><td>".$bcinfo[$key]."</td></tr>";
                   $key = "warnings";
                   echo "<tr><td>".$key."</td><td>".$miinfo[$key]."</td></tr>";
             ?>
    	</tbody>
    </table>
<?php 
echo "</div>";
include ("footer.php");
?>
