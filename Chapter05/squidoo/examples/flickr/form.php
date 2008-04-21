<?php

#### The following section sets defaults for everything in the form ####
$fields = array("type", "query", "photoset", "photoset_username");
foreach ($fields as $field) {
  if (!array_key_exists($field, $module->details)) {
    $module->details[$field] = '';
  }
}
if (!array_key_exists("max_photos", $module->details)) {
    $module->details['max_photos'] = 9;
}
if (!array_key_exists("max_photoset", $module->details)) {
    $module->details['max_photoset'] = 9;
}
if (!array_key_exists("show", $module->details)) {
    $module->details['show'] = '';
}
if (!array_key_exists("boolean", $module->details)) {
    $module->details['boolean'] = 'any';
}
if (!array_key_exists("searchtype", $module->details)) {
    $module->details['searchtype'] = 'tags';
}
#### Done setting defaults ####

?>
<script type="text/javascript">
function checkUsername(photoset_id) {
	var params = new Array();
	username = $F('username');
	
	if (username.length > 0) {
			params.push('username=' + username);
	}
	if (params.length) {
		params.push('method=getPhotosets');
		params.push('lens_module_id=');
		params.push('photoset_id=' + photoset_id);
	}
	params = params.join('&');
	if (params) {
		var url = 'scripts/ajax/flickr.php';		
		var myAjax = new Ajax.Updater( "photosetsBrowser", url, {method: 'post', parameters: params} );
		$("photosetsBrowser").innerHTML = "<div id='loading'>Loading...</div>";
		Effect.Pulsate('loading', {duration: 30});
	}
}

function checkPhotos() {
	var params;
	photoArray = new Array();
	getLinks = $F('bulkLoader').split('\n');
	Field.clear('bulkLoader');
	
	for (var n=0; n < getLinks.length; n++) {
		var myVal = extractPhotoID(getLinks[n]);
		if (!myVal || myVal.toString().length < 1) alert('not a valid link or photo ID: '+getLinks[n]);
		else {
			photoArray.push('photos[]=' + myVal);
		}
	}
	if (photoArray.length) {
		photoArray.push('method=addPhotos');
		photoArray.push('lens_module_id=');
	}
	params = photoArray.join('&');
	if (params) {
		var url = 'scripts/ajax/flickr.php';		
		var myAjax = new Ajax.Request( url, {method: 'post', parameters: params, onComplete: showResponse} );
	} else {
		alert('No valid photo IDs were found');
	}
}

function flickr_add_tag() {
    if ($F('add_tag') != "") {
        content = '<li><input type="checkbox" style="margin-right: 5px" name="modules[id][details][tags][]" value="'+$F('add_tag')+'" checked="checked" />'+$F('add_tag')+'</li>';
        new Insertion.Bottom('flickr_tags', content);
        Field.clear('add_tag');
        return true;
    }
}

function showResponse(originalRequest) {
	var response = originalRequest.responseText;
	new Insertion.Bottom('bulkList', response);
}

function extractPhotoID(linkURL) {
	var rePhotoURLCatcher = /http:\/\/.*?\/photos\/.*?\/[0-9]+/; 
	var rePhotoIDCatcher = /[0-9]+$/; 
	var photoID = linkURL.match(rePhotoURLCatcher);
	if (!photoID || photoID == "") {
		photoID = linkURL;
	}
	photoID = photoID.toString();
	return photoID.match(rePhotoIDCatcher);
} 

function photoSort() {
	Sortable.create('bulkList', {ghosting:false,constraint:false});
	$('bulkList').style.cursor='move';	
	
	myButton = '<input type="button" value="Save photo order" onclick="photoSortSave<?php echo $module->lens_module_id; ?>()" />';
	$('reorder').innerHTML = myButton;
}

function photoSortSave() {
	Sortable.destroy('bulkList');
	$('bulkList').style.cursor='';	
	
	myButton = '<input type="button" value="Reorder photos" onclick="photoSort<?php echo $module->lens_module_id; ?>()" />';
	$('reorder').innerHTML = myButton;
}

</script>

<div id="panel1">
	<div class="tabs">
		<ul>
			<li><a href="javascript:void(0);" class="current" style="cursor: default">Select your photos</a></li>
		</ul>
	</div>
		
	<div class="tabcontent">
		<div><h2 style="display: inline;"><input type="radio" name="modules[id][details][show]" id="showOption1" value="automatic" onclick="Element.show('automatic');Element.hide('advanced');Element.hide('photosets')" <?php if ($module->details['show'] == "automatic") echo 'checked="checked"'; ?> /><label for="showOption1"> Let Flickr Pick</label>:</h2> A dynamic display of photos that match your tags.</div>
		<div><h2 style="display: inline;"><input type="radio" name="modules[id][details][show]" id="showOption2" value="advanced" onclick="Element.show('advanced');Element.hide('automatic');Element.hide('photosets')" <?php if ($module->details['show'] == "advanced") echo 'checked="checked"'; ?> /><label for="showOption2"> Let Me Pick</label>:</h2> Select specific photos you want to show on your lens.</div>
		<div><h2 style="display: inline;"><input type="radio" name="modules[id][details][show]" id="showOption3" value="photosets" onclick="Element.show('photosets');Element.hide('automatic');Element.hide('advanced')" <?php if ($module->details['show'] == "photosets") echo 'checked="checked"'; ?> /><label for="showOption3"> Pick By Photoset</label>:</h2> Share your Flickr Photosets.</div>
		
		<div id="automatic" style="<?php echo ($module->details['show'] == "automatic") ? '': 'display: none;'; ?> margin-top: 10px; border-top: 1px solid #000;">
			
			<h2>Enter your search terms: <input type="text" name="modules[id][details][query]" value="<?php echo $module->details['query']; ?>" /></h2>	

			<p style="margin-top: 15px">
			<input type="radio" name="modules[id][details][boolean]" id="boolean_any" value="any" <?php echo ($module->details['boolean'] == 'any') ? 'checked="checked"':''; ?> /><label for="boolean_any"> Find photos with <strong>any</strong> of the checked terms</label> <br />
			<input type="radio" name="modules[id][details][boolean]" id="boolean_all" value="all" <?php echo ($module->details['boolean'] == 'all') ? 'checked="checked"':''; ?> /><label for="boolean_all"> Find photos with <strong>all</strong> of the checked terms</label>
			</p>
			
			<p style="margin-top: 8px">
			<input type="radio" name="modules[id][details][searchtype]" id="searchtype_tags" value="tags" <?php echo ($module->details['searchtype'] == 'tags') ? 'checked="checked"':''; ?> /><label for="searchtype_tags"> Search tags only</label> <br />
			<input type="radio" name="modules[id][details][searchtype]" id="searchtype_text" value="text" <?php echo ($module->details['searchtype'] == 'text') ? 'checked="checked"':''; ?> /><label for="searchtype_text"> Search titles, tags, and descriptions (may be less relevant)</label>
			</p>
			
			<h2>Maximum number of photos to display: 
			<input style="width: 20px" name="modules[id][details][max_photos]" id="max_photos" value="<?php echo $module->details['max_photos']; ?>"></h2>
			
		</div>
		
		<div id="advanced" style="<?php echo ($module->details['show'] == "advanced") ? '': 'display: none;'; ?> margin-top: 10px; border-top: 1px solid #000;">
			<div style="margin-bottom: 10px; padding: 5px;">
				<h2>Enter links to photo pages on Flickr (one per line)</h2>
				Example: http://www.flickr.com/photos/bees/61752182/
				<textarea name="bulkLoader" id="bulkLoader" rows="5" cols="50"></textarea>
				<input type="button" value="Go" onclick="return checkPhotos()" />
			</div>
			
			<ul id="bulkList">
<?php

if (isset($module->details['photos'])) {
	require_once("lib/phpFlickr/phpFlickr.php");
	$f = new phpFlickr("91463f56021a88799ecbf0eb105a08fd ", NULL, false);
	
    foreach (split(",", $module->details['photos']) as $photoID) {
	    $photo = $f->photos_getInfo($photoID);
	    if ($f->getErrorCode()) {
		    continue;
	    } else {
				?>
					<li><input type="checkbox" name="modules[id<?php echo $module->lens_module_id; ?>][details][photos][]" value="<?php echo $photo['id']; ?>" checked="checked" />
						<img src="http://static.flickr.com/<?php echo $photo['server'] . "/" . $photo['id'] . "_" . $photo['secret']; ?>_s.jpg" align="middle" style="border:1px solid black; padding:1px; margin-right: 10px">
						<?php echo $photo['title']; ?> by <?php echo $photo['owner']['username']; ?><br />
						<input type="text" name="modules[id<?php echo $module->lens_module_id; ?>][details][desc_<?php echo $photo['id']; ?>]" id="<?php echo $module->lens_module_id; ?>desc_<?php echo $photo['id']; ?>" style="margin: 0px; margin-top: 3px; margin-right: 10px; width: 300px" value="<?php if (isset($module->details['desc_'.$photo['id']])) echo $module->details['desc_'.$photo['id']]; ?>" />
					</li>
				<?php
	    }	
    }		
}
?>
			</ul>
			
			<p id="reorder<?php echo $module->lens_module_id; ?>"><input type="button" value="Reorder photos" onclick="photoSort<?php echo $module->lens_module_id; ?>()" /></p>
		</div>
			
		<div id="photosets" style="<?php echo ($module->details['show'] == "photosets") ? '': 'display: none;'; ?> margin-top: 10px; border-top: 1px solid #000; padding-top: 10px;">
			Within Flickr,  make sure you know the title of your photoset, and that you've made the set public. Tip: Make a Squidoo photoset in Flickr, just for your lens!
			<h2>Browse a user's photosets</h2>	
			Username: 
			<input id="username" name="modules[id][details][photoset_username]" value="<?php echo $module->details['photoset_username']; ?>" />
			<input type="button" value="Go" onclick="return checkUsername()" />
            <h2>Maximum number of photos to display: 
			<input style="width: 20px" name="modules[id][details][max_photoset]" id="max_photos" value="<?php echo $module->details['max_photoset']; ?>" /></h2>

			<div id="photosetsBrowser" style="margin-top: 10px"></div>
		</div>
	</div>
<?php if ($module->details['show'] == 'photosets') { ?>
<script type="text/javascript">
function load() {
    setTimeout("checkUsername('<?php echo $module->details['photoset']; ?>')", 4000);
}
</script>
<?php } ?>
