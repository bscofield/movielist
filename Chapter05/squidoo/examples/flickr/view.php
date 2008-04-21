<?php

require_once("lib/phpFlickr/phpFlickr.php");

$f = new phpFlickr("", NULL, false);

if ($this->attributes['details']['show'] == "automatic") {
	if (empty($this->attributes['details']['query']) && empty($this->attributes['details']['text'])) {
        print("No search terms were entered.");
	} else {
		
		if ($this->attributes['details']['searchtype'] == 'tags') {
	        $photos = $f->photos_search(array("tags"=>$this->attributes['details']['query'], "tag_mode"=>$this->attributes['details']['boolean'], "extras"=>"owner_name", "per_page"=>$this->attributes['details']['max_photos']));
		} else if ($this->attributes['details']['searchtype'] == 'text') {
			$tags = trim($this->attributes['details']['tags']);
	        $photos = $f->photos_search(array("text"=>$tags, "extras"=>"owner_name", "per_page"=>$this->attributes['details']['max_photos']));
		}
	    $isError = $f->getErrorMsg();
	    
	    if (!$isError && isset($photos['photo']) && count($photos['photo']) > 0) {
	        echo "<table cellpadding='3' style='border: none; width: 100%'>\n<tr>";
	        foreach ($photos['photo'] as $key => $photo) {
		?>
            <td style="vertical-align: top; text-align: center; width: 33%">
				<a href="http://www.flickr.com/photos/<?php echo $photo['owner']; ?>/<?php echo $photo['id']; ?>">
                    <img style="padding: 1px; border: 1px solid black;" alt="<?php echo $photo['title'] . " by " . $photo['ownername']; ?>" src="<?php echo $f->buildPhotoUrl($photo, "Square"); ?>" align="middle">
				</a>
				<p style="overflow: hidden"><?php echo $photo['title']; ?></p>
			</td>
		<?php
		        if (!(($key+1) % 3)) {
                    echo "</tr>\n<tr>";
		        }
	        }
	        echo "</tr></table>";
	    } else {
	    	echo "No matching pictures were found.<!-- refresh me -->";
	    }
	}
} elseif ($this->attributes['details']['show'] == "advanced") {
    if (isset($this->attributes['details']['photos']) && count($this->attributes['details']['photos']) > 0) {
        echo "<table cellpadding='3' style='border: none; width: 100%'>\n<tr>";
	    foreach (split(",", $this->attributes['details']['photos']) as $key => $photoID) {
		    $photo = $f->photos_getInfo($photoID);
		    $isError = $f->getErrorMsg();
		
		    if (!$isError) {
		    	if (!isset($photo['urls']['url']) || count($photo['urls']['url']) < 1) {
                    //$photopage = "http://www.flickr.com/photos/" . $photo['owner']['nsid'] . "/" . $photo['id'] . "/";
                    $photopage = "http://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'];
                } else {
			        foreach ($photo['urls']['url'] as $photopage) {
                        if ($photopage['type'] == "photopage") {
                            $photopage = $photopage['_value'];
                        }
		            }
                }
                
		?>
            <td style="vertical-align: top; text-align: center; width: 33%">
				<a href="<?php echo $photopage; ?>">
                    <img style="padding: 1px; border: 1px solid black;" alt="<?php echo $photo['title'] . " by " . $photo['owner']['username']; ?>" src="<?php echo $f->buildPhotoUrl($photo, "Square"); ?>" align="middle">
				</a>
				<p style="margin-top: 5px; overflow: hidden;"><?php echo strip_tags($this->attributes['details']['desc_'.$photo['id']]); ?></p>
			</td>
		<?php
		        if (!(($key+1) % 3)) {
                    echo "</tr>\n<tr>";
		        }
	        } else {
	    	    echo "<td>No matching pictures were found.</td>";
	        }
	    }
	    echo "</tr></table>";
    } else {
    	print "No matching pictures were found.";
    }
} elseif ($this->attributes['details']['show'] == "photosets") {
	if (isset($this->attributes['details']['photoset'])) {
	    echo "<table cellpadding='3' style='border: none; width: 100%'>\n<tr>";
	    $photos = $f->photosets_getPhotos($this->attributes['details']['photoset'], "owner_name,owner");
	    $isError = $f->getErrorMsg();

	    if (!$isError) {
	        foreach ($photos['photo'] as $key => $photo) {
                if ($key >= $this->attributes['details']['max_photoset']) {
                    break;
                }
                $myPhoto = $f->photos_getInfo($photo['id']);
		?>
            <td style="vertical-align: top; text-align: center; width: 33%">
				<a href="http://www.flickr.com/photos/<?php echo $myPhoto['owner']['nsid']; ?>/<?php echo $photo['id']; ?>">
                    <img style="padding: 1px; border: 1px solid black;" alt="<?php echo $photo['title'] . " by " . $photo['ownername']; ?>" src="<?php echo $f->buildPhotoUrl($photo, "Square"); ?>" align="middle">
				</a>
				<p style="margin: 5px 0px 5px 0px; overflow: hidden;"><?php echo $photo['title']; ?></p>
			</td>
		<?php
		        if (!(($key+1) % 3)) {
                echo "</tr>\n<tr>";
		        }
	        }
	    } else {
	    	echo "No matching pictures were found.";
	    }
	    echo "</tr>\n</table>";
	} else {
		echo "No matching pictures were found. Please edit the module and pick a photoset before saving.";
	}
} 
?>
<p align="right"><a href="http://www.flickr.com">Powered by Flickr</a></p>