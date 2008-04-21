<div id="formatForm">
  <h2>What format would you like to show?</h2>
  <select 
    name="modules[id][details][release_format]" 
    id="modules_id_details_release_format" />
  <?php 
  $formats = array('dvd', 'theater', 'tv');
  foreach ($formats as $format) {
    echo '<option value="' . $format . '"';
    if ($format == $module->details['release_format']) {
      echo ' selected="selected"';
    }
    echo '>' . $format . '</option>'; 
  }
  ?>
  </select>
</div>

<div id="timeForm">
  <h2>How far in the future would you like to show releases?</h2>
    <select name="modules[id][details][time]" id="modules_id_details_time" />
  <?php 
    $times = array('1 week', '1 month', '3 months');
    foreach ($times as $time) {
      echo '<option value="' . $time . '"';
      if ($time == $module->details['time']) {
        echo ' selected="selected"';
      }
      echo '>' . $time . '</option>'; 
    }
  ?>
    </select>
</div>
