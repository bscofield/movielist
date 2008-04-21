<?php

/**
 * Module class
 *
 * Represents a module for a lens
 *
 */
class Module  {
  /**
   * Module constructor
   *
   * if given a map of data, load the object with that data; else initialize everything to ''
   *
   * @param mixed data map
   */
  function Module($map = array()) {
    $this->module_type_id = array_key_exists('module_type_id', $map) ? $map['module_type_id'] : '';
    $this->module_type_name = array_key_exists('module_type_name', $map) ? $map['module_type_name'] : '';
    $this->module_type_basename = array_key_exists('module_type_basename', $map) ? $map['module_type_basename'] : '';
    $this->module_type_container = array_key_exists('module_type_container', $map) ? $map['module_type_container'] : '';
    
    $this->module_id = array_key_exists('module_id', $map) ? $map['module_id'] : null;
    $this->module_title = array_key_exists('module_title', $map) ? $map['module_title'] : '';
    $this->module_abstract = array_key_exists('module_abstract', $map) ? $map['module_abstract'] : '';
    $this->date_created = array_key_exists('date_created', $map) ? $map['date_created'] : '';
    
    $this->lens_module_id = array_key_exists('module_id', $map) ? $map['lens_module_id'] : null;
    $this->lens_module_title = array_key_exists('lens_module_title', $map) ? $map['lens_module_title'] : '';
    $this->lens_module_subtitle = array_key_exists('lens_module_subtitle', $map) ? $map['lens_module_subtitle'] : '';
    $this->lens_module_abstract = array_key_exists('lens_module_abstract', $map) ? $map['lens_module_abstract'] : '';
    $this->display = array_key_exists('display', $map) ? $map['display'] : null;
    $this->date_added = array_key_exists('date_added', $map) ? $map['date_added'] : null;
    $this->date_last_updated = array_key_exists('date_last_updated', $map) ? $map['date_last_updated'] : null;
    
    $this->links = false;
    $this->items = false;
    $this->lenses = false;
    $this->details = array();
    
    $this->file_name = 'data/module.txt';
    $this->file_error = null;
  }
  
  function save() {
    $data = serialize($this);

    if (!$handle = fopen($this->file_name, 'w+')) {
      $this->file_error = 'Cannot open file (' . $this->file_name . ')';
      return false;
    }
    if (fwrite($handle, $data) === FALSE) {
      $this->file_error = 'Cannot write to file (' . $this->file_name . ')';
      return false;
    }
    fclose($handle);
    return true;
  }
  
  function load() {
	  $file_name = 'data/module.txt';
	  if (file_exists($file_name)) {
      $handle = fopen($file_name, "r");
      $data = '';
    
      while (!feof($handle)) {
        $data .= fread($handle, 8192);
      }
      fclose($handle);
    
      return unserialize($data);
    } else {
	    $file_error = 'Unable to open file (' . $file_name . ')';
	    return false;
    }
  }
  
  function show() {
	  $vars = get_object_vars($this);
	  foreach ($vars as $key => $val) {
		  $this->attributes[$key] = $val;
	  }
	  ob_start();
	  include('view.php');
	  $contents = ob_get_contents();
	  ob_end_clean();
	  
	  return $contents;
  }
  
  function rest_connect($link, $try=1) {
    $result = @file_get_contents($link);
    
    if ($result) {
        return $result;
    } else {
    	if ($try <= 3) {
    		sleep(3);
    		return $this->rest_connect($link, $try+1);
    	} else {
    	    return false;
    	}
    }
  }
  
}