<div id="formatForm">
  <h2>What movie would you like to display?</h2>
  <input type="text" class="textfield" 
      value="<?= $module->details['title']; ?>"
      name="modules[id][details][title]" 
      id="modules_id_details_title" />
</div>

<div id="limitForm">
  <h2>How many comments would you like to show?</h2>
  <input type="text" class="textfield" 
      value="<?= $module->details['limit']; ?>"
      name="modules[id][details][limit]" 
      id="modules_id_details_limit" />
</div>
