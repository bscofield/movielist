<?php

require_once('connect.php');

if (isset($_GET['ASIN']) && count($_GET['ASIN']) > 0) {
    foreach ($_GET['ASIN'] as $item) {
	    $request="http://webservices.amazon.com/onca/xml?Service=AWSECommerceService&SubscriptionId=19BAZMZQFZJ6G2QYGCG2&AssociateTag=squidoo-20&Operation=ItemLookup&ItemId=".$item."&ResponseGroup=Request,Medium&Version=2005-02-23";
		$result = amazon_connect($request);
        if (!$result) print "There was an error connecting to the Amazon web service. Please try again.";
        else {
	    	$data = simplexml_load_string($result);
	
		    print '<li>';
		    
		    if (isset($data->Items->Request->Errors->Error->Code)) {
			    print "<li><strong>Amazon Error</strong> (Code " . $data->Items->Request->Errors->Error->Code . ")<br />";
			    print $data->Items->Request->Errors->Error->Message . "</li>";
			    $msg = "Code " . $data->Items->Request->Errors->Error->Code . "\n";
			    $msg .= "Error " . $data->Items->Request->Errors->Error->Message . "\n";
			    $msg .= "ASIN " . $item;
			    mail('gil@squidoo.com', 'amazon error', $msg, 'From: techsupport@squidoo.com');
		    } else {
	            $details = $data->Items->Item[0]->ItemAttributes;
	            if (strlen($details->Title) > 50) {
			        $name = substr($details->Title, 0, 50) . "...";
			        #$name = $details['ProductName'];
		        } else {
			        $name = $details->Title;
		        }
	?>
	<input type="checkbox" name="modules[id<?php echo $_GET['lens_module_id']; ?>][details][products][]" value="<?php echo $item; ?>" checked="checked" />
	<?php
		        if (!empty($data->Items->Item[0]->SmallImage->URL)) {
			        #print $item . '|' . $name . '|' . $details['ImageUrlSmall'] . "\n";
	?>
	<img src="<?php echo $data->Items->Item[0]->SmallImage->URL; ?>" align="middle" style="padding-right: 10px">
	<?php } ?>
	<?php echo $name; ?><br />
	<a href="javascript:void(0)" class="smallLink" onclick="Element.show('<?php echo $_GET['lens_module_id']; ?>desc_<?php echo $item; ?>');Element.hide(this)">Add a description</a>
	<input type="text" name="modules[id<?php echo $_GET['lens_module_id']; ?>][details][desc_<?php echo $item; ?>]" id="<?php echo $_GET['lens_module_id']; ?>desc_<?php echo $item; ?>" style="display: none; margin: 0px; margin-top: 3px; margin-right: 10px; width: 300px">
	<?php
	        }
	        print '</li>';
	    }
    }
} else {
	print "<li>No valid ASINs</li>";
}


?>