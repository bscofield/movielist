<?php
if (is_array($this->attributes) && array_key_exists('details', $this->attributes) &&
  is_array($this->attributes['details']) &&
array_key_exists('limit', $this->attributes['details'])) {    
  $limit  = $this->attributes['details']['limit'];
  if (is_numeric($limit) && $limit > 0) {
    $url    = 'http://localhost:3000/releases.xml?limit=' . urlencode($limit);
    $result = $this->rest_connect($url);

    if ($result) {
      $data = @simplexml_load_string($result);

      if ($data->release) {
        print "<dl>";
        foreach ($data->release as $release) {
          print "<dt>";
          print "<a href='http://localhost:3000/movies/". $release->movie_id ."'>";
          print  $release->movie->title . "</a></dt>";
          print "<dd>" . $release->format . ' - ' . $release->released_on . "</dd>";
        }
        print "</dl>";
      } else {
        print "Sorry, no upcoming releases were found.<!-- refresh me -->";
      }
    } else {
      print "Sorry, we couldn't connect to MovieList. Please try again later.";
    }
  } else {
    print "Sorry, but the limit you entered was invalid. ";
    print "Please try a number greater than zero.";
  }
} else {
  print "Sorry, there was a problem loading releases from MovieList. ";
  print "Please try again later.";
}
?>
