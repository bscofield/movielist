<?php
if (is_array($this->attributes) && array_key_exists('details', $this->attributes) &&
    is_array($this->attributes['details']) &&
    array_key_exists('title', $this->attributes['details'])) {    
  $title = $this->attributes['details']['title'];
  $limit = $this->attributes['details']['limit'];

  $url  = 'http://localhost:3000/comments.xml?title=';
  $url .=  urlencode($title) . '&limit=' . urlencode($limit);
  $result = $this->rest_connect($url);

  if ($result) {
    $data = @simplexml_load_string($result);

    print '<strong>Comments about ' . $data->title . '</strong>';
    
    if ($data->comments) {
      print "<ul>";
      foreach ($data->comments->comment as $comment) {
        print "<li>" . $comment->text . "</li>";
      }
      print "</ul>";
    } else {
      print "Sorry, no comments were found.<!-- refresh me -->";
    }
    
    $submit_url = 'http://localhost:3000/movies/' . $data->id . '/comments.xml';
    print "<p>Add a Comment</p>";
    print "<form method='post' action='" . $submit_url . "' ";
    print ">";
    print "<textarea name='comment[text]' id='comment_text'></textarea><br />";
    print "<input type='submit' value='Save Comment' />";
    print "</form>";
    
  } else {
    print "Sorry, we couldn't connect to MovieList. Please try again later.";
  }
} else {
  print "Sorry, there was a problem loading comments from MovieList. ";
  print "Please try again later.";
}
?>
