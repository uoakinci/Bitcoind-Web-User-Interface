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
    		 <?php $info = $nmc->getinfo(); ?>
    		 <?php foreach ($info as $key => $val)
                   {
                       if ($val != "")
                           echo "<tr><td>".$key."</td><td>".$val."</td></tr>";
                   }
             ?>
    	</tbody>
    </table>
<?php 
echo "</div>";
include ("footer.php");
?>
