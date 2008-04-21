<h2>Enter your search terms: <input type="text" name="modules[id][details][query]" value="<?php echo $module->details['query']; ?>" /></h2>

<h2>Location of job (city, state or zip): 
    <input class="med"
        id="location"
        name="modules[id][details][location]"
        value="<?php echo $module->details['location']; ?>" />
</h2>

<h2>Number of jobs to display: 
    <input class="med"
        id="url" size="4"
        name="modules[id][details][numresults]"
        value="<?php echo !is_null($module->details['numresults']) && $module->details['numresults'] != '' ? $module->details['numresults'] : '3' ?>" />
</h2>