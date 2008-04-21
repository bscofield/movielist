<?php
if (is_array($this->attributes) && array_key_exists('details', $this->attributes) &&
  is_array($this->attributes['details']) &&
array_key_exists('time', $this->attributes['details'])) {    
  $release_format = $this->attributes['details']['release_format'];
  $time = $this->attributes['details']['time'];
  $valid_formats = ['dvd', 'theater', 'tv'];
  $valid_times   = ['1 week', '1 month', '3 months'];

  if (in_array($release_format, $valid_formats) && in_array($time, $valid_times)) {
    $url  = 'http://localhost:3000/releases.xml?release_format=';
    $url .= urlencode($release_format) . '&time=' . urlencode($time);

    $result = $this->rest_connect($url);

    if ($result) {
      $data = @simplexml_load_string($result);

      if ($data->release) {
        print "<dl>";
        foreach ($data->release as $release) {
          print "<dt>";
          print "<a href='http://localhost:3000/movies/". $release->movie_id ."'>";
          print  $release->movie->title . "</a></dt>";
          print "<dd>" . $release->released_on  . "</dd>";
        }
        print "</dl>";
      } else {
        print "Sorry, no upcoming releases were found.<!-- refresh me -->";
      }
    } else {
      print "Sorry, we couldn't connect to MovieList. Please try again later.";
    }
  } else {
    $message = 'Sorry, but there was a problem with your request:';
    if (!in_array($release_format, $valid_releases)) {
      $message .= '<br />';
      $message .= 'Please check that you are specifying a valid release format';
    }
    if (!in_array($time, $valid_times)) {
      $message .= '<br />Please check that you are specifying a valid time';
    }
  }
} else {
  print "Sorry, there was a problem loading releases from MovieList. ";
  print "Please try again later.";
}
?>
