<?php
require_once("../../lib/phpFlickr/phpFlickr.php");

$f = new phpFlickr("", NULL, false);

if ($_POST['method'] == "addPhotos") {
	foreach ($_POST['photos'] as $photoID) {
		$photo = $f->photos_getInfo($photoID);
		if ($f->getErrorCode()) {
			continue;
		} else {
			if (!is_array($photo['description'])) {
				$photo['description'] = strip_tags($photo['description']);

				if (strlen($photo['description']) > 80) {
				    $description = substr($photo['description'],0,80) . "...";
				} else {
					$description = $photo['description'];
				}
			} else {
				$description = '';
			}
			?>
				<li><input type="checkbox" name="modules[id][details][photos][]" value="<?php echo $photo['id']; ?>" checked="checked" />
					<img src="http://static.flickr.com/<?php echo $photo['server'] . "/" . $photo['id'] . "_" . $photo['secret']; ?>_s.jpg" align="middle" style="border:1px solid black; padding:1px; margin-right: 10px">
					<?php echo $photo['title']; ?> by <?php echo $photo['owner']['username']; ?><br />
					<input type="text" name="modules[id][details][desc_<?php echo $photo['id']; ?>]" id="desc_<?php echo $photo['id']; ?>" style="margin: 0px; margin-top: 3px; margin-right: 10px; width: 300px" value="<?php echo $description; ?>" />
				</li>
			<?php
		}			
	}
} elseif ($_POST['method'] == "getPhotosets") {
	$nsid = $f->people_findByUsername($_POST['username']);
	if ($f->getErrorCode()) {
		echo "Username \"" . $_POST['username'] . "\" not found on Flickr.";
	} else {
		$photosets = $f->photosets_getList($nsid);
		if (!isset($_POST['photoset_id'])) $_POST['photoset_id'] = 0;
		if (count($photosets['photoset'])) {
			echo '<h2>Photosets of ' . $_POST['username'] . "</h2>";
			echo '<input type="hidden" id="photoset_count" value="' . count($photosets['photoset']) . '">';
			echo '<input type="hidden" id="photoset_owner" value="' . $nsid . '">';
			echo "<table cellpadding='3' style='border: none; width: 100%'>\n<tr>";
			foreach ($photosets['photoset'] as $key => $photoset) {
				?>
				<td style="vertical-align: top; text-align: center; width: 33%">
					<a target="_blank" href="http://flickr.com/photos/<?php echo $nsid; ?>/sets/<?php echo $photoset['id']; ?>/">
					<img class="flickrThumb" style="margin-bottom: 5px;" alt="<?php echo $photoset['title']; ?>" src="http://static.flickr.com/<?php echo $photoset['server'] . "/" . $photoset['primary'] . "_" . $photoset['secret']; ?>_s.jpg" align="middle" style="padding-right: 10px">
					</a>
					<br /><input type="radio" name="modules[id][details][photoset]" id="photoset<?php echo $key; ?>_" value="<?php echo $photoset['id']; ?>" <?php if ($_POST['photoset_id'] == $photoset['id']) echo 'checked="checked"'; ?> /><label style="font-weight: bold;" for="photoset<?php echo $key; ?>_">Select</label>
					<br /><?php echo $photoset['title']; ?>
				</td>
				<?php
				if (!(($key+1) % 3)) {
					echo "</tr>\n<tr>";
				}
			}
			echo "</table>";
		} else {
            echo "User \"" . $_POST['username'] . "\" has created no photosets on Flickr.";
		}
	}
} elseif ($_POST['method'] == "saveAutomatic") {
	echo "Title: " . $_POST['title'] . "<br />";
	echo "Subtitle: " . $_POST['subtitle'] . "<br />";
	echo "Description: " . $_POST['description'] . "<br />";
	if (!is_array($_POST['tags'])) {
        die("No tags were selected.");
	}
	$photos = $f->photos_search(array("tags"=>implode(",", $_POST['tags']), "tag_mode"=>"all", "extras"=>"owner_name", "per_page"=>$_POST['maximum_photos']));
	echo "<table cellpadding='3' style='border: none; width: 100%'>\n<tr>";
	foreach ($photos['photo'] as $key => $photo) {
		?>
            <td style="vertical-align: top; text-align: center; width: 33%">
				<a target="_blank" href="http://www.flickr.com/photos/<?php echo $photo['owner']; ?>/<?php echo $photo['id']; ?>">
                    <img style="padding: 1px; border: 1px solid black;" alt="<?php echo $photo['title'] . " by " . $photo['ownername']; ?>" src="<?php echo $f->buildPhotoUrl($photo, "Square"); ?>" align="middle">
				</a>
				<p><?php echo $photo['title']; ?> by <?php echo $photo['ownername']; ?></p>
			</td>
		<?php
		if (!(($key+1) % 3)) {
            echo "</tr>\n<tr>";
		}
	}
	echo "</table>";
} elseif ($_POST['method'] == "saveBulk") {
	echo "Title: " . $_POST['title'] . "<br />";
	echo "Subtitle: " . $_POST['subtitle'] . "<br />";
	echo "Description: " . $_POST['description'] . "<br />";
	/*
	foreach ($_POST['photos'] as $key => $photoID) {
		$photo = $f->photos_getInfo($photoID);
		?>
			<div style="height:75px; clear:all; display: block; margin-bottom:20px">
				<img style="float: left" alt="<?php echo $photo['title'] . " by " . $photo['owner']['username']; ?>" src="http://static.flickr.com/<?php echo $photo['server'] . "/" . $photo['id'] . "_" . $photo['secret']; ?>_s.jpg" align="middle" style="padding-right: 10px">
				<p><?php echo $photo['title']; ?> by <?php echo $photo['owner']['username']; ?>
			</div>
		<?php
	}
	*/
    echo "<table cellpadding='3' style='border: none; width: 100%'>\n<tr>";
	foreach ($_POST['photos'] as $key => $photoID) {
		$photo = $f->photos_getInfo($photoID);
		foreach ($photo['urls']['url'] as $photopage) {
            if ($photopage['type'] == "photopage") {
                $photopage = $photopage['_value'];
            }
		}
        if (empty($photopage)) {
            $photopage = "http://www.flickr.com/photos/" . $photo['owner']['nsid'] . "/" . $photo['id'] . "/";
        }
		?>
            <td style="vertical-align: top; text-align: center; width: 33%">
				<a target="_blank" href="<?php echo $photopage; ?>">
                    <img style="padding: 1px; border: 1px solid black;" alt="<?php echo $photo['title'] . " by " . $photo['owner']['username']; ?>" src="<?php echo $f->buildPhotoUrl($photo, "Square"); ?>" align="middle">
				</a>
				<p><?php echo $photo['title']; ?> by <?php echo $photo['owner']['username']; ?><br /><? echo $_POST['photosDescr'][$key]; ?></p>
			</td>
		<?php
		if (!(($key+1) % 3)) {
            echo "</tr>\n<tr>";
		}
	}
	echo "</table>";
} elseif ($_POST['method'] == "savePhotosets") {
	echo "Title: " . $_POST['title'] . "<br />";
	echo "Subtitle: " . $_POST['subtitle'] . "<br />";
	echo "Description: " . $_POST['description'] . "<br />";
	echo "<table cellpadding='3' style='border: none; width: 100%'>\n<tr>";
	$photos = $f->photosets_getPhotos($_POST['photoset'], "owner_name");
	foreach ($photos['photo'] as $key => $photo) {
        if ($key >= $_POST['maximum_photos']) {
            break;
        }
		?>
            <td style="vertical-align: top; text-align: center; width: 33%">
				<a target="_blank" href="http://www.flickr.com/photos/<?php echo $_POST['owner']; ?>/<?php echo $photo['id']; ?>">
                    <img style="padding: 1px; border: 1px solid black;" alt="<?php echo $photo['title'] . " by " . $photo['ownername']; ?>" src="<?php echo $f->buildPhotoUrl($photo, "Square"); ?>" align="middle">
				</a>
				<p><?php echo $photo['title']; ?> by <?php echo $photo['ownername']; ?></p>
			</td>
		<?php
		if (!(($key+1) % 3)) {
            echo "</tr>\n<tr>";
		}
	}
	echo "</tr>\n</table>";
} 
?>