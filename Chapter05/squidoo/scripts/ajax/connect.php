<?php

function amazon_connect($link, $try=1) {
    $result = @file_get_contents($link);
    
    if ($result) {
        return $result;
    } else {
    	if ($try <= 3) {
    		sleep(3);
    		return amazon_connect($link, $try+1);
    	} else {
    	    return false;
    	}
    }
}

?>