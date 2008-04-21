<?php
/**
 * View for Indeed module
 *
 * For use on the lens
 *
 * @package squidoo.views.layouts
 * @author ghildebrand
 */

?>

<?php

if (is_array($this->attributes) && array_key_exists('details', $this->attributes) &&
    is_array($this->attributes['details']) &&
    array_key_exists('query', $this->attributes['details'])) {    
  
    $result = $this->rest_connect('http://developer.squidoo.com/api/?service=Indeed&q=' . urlencode($this->attributes['details']['query']) . '&l=' . urlencode($this->attributes['details']['location']) . '&limit=' . $this->attributes['details']['numresults']);

    if (!$result) {
			print "Sorry, we couldn't connect to the Indeed API. Please try again later.";
		} else {
			$data = @simplexml_load_string($result);
	
			if (!isset($data) || $data->totalresults == 0) {
				print "Sorry, there were no matching results.<!-- refresh me -->";
			} else {
				print "<dl>";
				foreach ($data->results->result as $result) {
?>
<dt>
  <a href="<?php echo $result->url; ?>"><?php echo strip_tags($result->jobtitle); ?></a>
</dt>
<dd>
  <span style="color: #666"><?php echo $result->company; ?> - <?php echo $result->city . ', ' . $result->state; ?></span><br />
<?php echo strip_tags($result->snippet); ?>...
</dd>
<?php
				}
				print "</dl>";
?>
<a href="http://www.indeed.com/jobs?q=<?php echo urlencode($this->attributes['details']['query']); ?>&l=<?php echo $this->attributes['details']['location']; ?>">Find more jobs like these</a>
<?php
			}
		}
}

?>
<p align="right"><a href="http://www.indeed.com">Powered by Indeed</a></p>