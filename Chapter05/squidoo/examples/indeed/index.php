<?php

#################################################################################
#
#		Squidoo Module Development Kit (MDK)
#		Indeed.com Example Module
#     
#		Version: 1.0
#		Last Modified: January 25, 2006
#
#		Programmed by: Gil Hildebrand, Jr.
#		Contact:       gil@squidoo.com
#
#################################################################################
#
# Copyright ©2006 Squidoo, LLC. All Rights Reserved.
#
# Selling or distributing the code for this program (or any portion of its code)
# without prior written consent is forbidden. In all cases copyright, headers,
# and footers must remain intact. Code must stay in it's unaltered form unless
# express written consent is given by the programmer(s). The only exception to any
# of the above applies to areas where these permissions are given directly in the 
# directions of the original program's documentation.
#
# Any violations to the above statements can and will be prosecuted as seen fit.
#
#################################################################################

ini_set('display_errors', true);
ini_set('include_path', '.');

require('lib/module.php');
if (!($module = Module::load())) {
  $module = new Module();
  $isNew = true;
}
      
if (isset($_REQUEST['action'])) {
  switch ($_REQUEST['action']) {
    case 'reload_partial':
?>
        <form name="module1Form" method="post" id="module1Form" onsubmit="return ajaxEdit(this, 'lensModule', 'module1');" action="#">
          <div class="rowSubmitLeft">
            <input name="subbutton" type="image" src="http://www.squidoo.com/images/btn-save.gif" alt="save" class="button" />
            <input name="cancelbutton" type="image" src="http://www.squidoo.com/images/btn-cancel.gif" alt="cancel" class="button" onclick="closeEdit('module1', false); return false;"/>
          </div>
          
          <fieldset>
          <div class="module_container">
          
          <div id="titleForm">
            <h2>Give your module a title</h2>
            <p>Titles can only be one line, so keep it short<br />
            <input type="text" name="modules[id][lens_module_title]" id="modules_idlens_module_title" class="textfield" value="<?php echo $module->lens_module_title; ?>" /></p>
          </div>

          <?php if ($module->lens_module_subtitle=='') { ?><div id="subText"><a href="javascript:void(0)" onclick="Element.hide('subText');Element.show('subForm');" class="smallLink">Add a subtitle</a></div><?php } ?>

          <div id="subForm" <?php if ($module->lens_module_subtitle=='') echo 'style="display: none"'; ?>>
            <h2>Give your module a subtitle <span class="smallLink" style="font-weight: normal">(optional)</span></h2>
            <p>When titles just aren't enough<br />
            <input type="text" name="modules[id][lens_module_subtitle]" class="textfield" id="modules_id_lens_module_subtitle" value="<?php echo $module->lens_module_subtitle; ?>" /></p>
          </div>

          <?php if ($module->lens_module_abstract=='') { ?><div id="descText"><a href="javascript:void(0)" onclick="Element.hide('descText');Element.show('descForm');" class="smallLink">Add a description</a></div><?php } ?>

          <div id="descForm" <?php if ($module->lens_module_abstract=='') { ?>style="display: none"<?php } ?>>
            <h2>Give your module a description <span class="smallLink" style="font-weight: normal">(optional)</span></h2>
            <p><textarea name="modules[id][lens_module_abstract]" cols="40" rows="5"><?php echo $module->lens_module_abstract; ?></textarea></p>
          </div>
          
          <?php require('form.php'); ?>
          
          </div>
          </fieldset>

          <div class="rowSubmitLeft">
            <input name="subbuttom" type="image" src="http://www.squidoo.com/images/btn-save.gif" alt="save" class="button" />
            <input name="cancelbutton" type="image" src="http://www.squidoo.com/images/btn-cancel.gif" alt="cancel" class="button" onclick="closeEdit('module1', false); return false;"/>
          </div>		
        </form>
<?php
	    exit;
      break;
    case 'save':
      //print "<pre>";print_r($_POST);print "</pre>";
      
      foreach ($_POST['modules']['id'] as $key => $val) {
	      $module->$key = $val;
      }
      if (!$module->save()) {
        print $module->file_error;
      }
      print $module->show();
      exit;
      break;
  }
} else {

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Build a Module: Squidoo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="scripts/custom/builder.js"></script>
<script type="text/javascript" src="scripts/custom/display.js"></script>
<script type="text/javascript" src="scripts/custom/validation.js"></script>
<script type="text/javascript" src="scripts/scriptaculous/prototype.js"></script>
<script type="text/javascript" src="scripts/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="scripts/tiny_mce/tiny_mce.js"></script>
<link href="styles/styles.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body>
<div id="container">
  <div id="header">
    <div id="nav">
      <ul>
        <li><a href="../../">MDK Home</a></li>
      </ul>
    </div>
    <div id="logo">
      <a href="index.php"><img src="http://www.squidoo.com/images/logo-squidoo21.gif" alt="Squidoo" /></a>
    </div>    
  </div>
  <div id="buttons">
    <a href="javascript:void(0)" onclick="openEdit('module1');return false;" id="editLink_module1"><img src="http://www.squidoo.com/images/btn-edit.gif" class="button "alt="Edit" border="0" /></a>
  </div>
  <div id="module1" class="bigBox">
    <div class="viewing" id="module_id1_results">
      <div style="font-family: verdana; font-weight: bold; font-size: 60px; color: #eee; margin-top: 220px;">squid_mdk</div>
      <div class="break"></div>
    </div>
    <div class="editing">
      <div id="editform_1">
        <form name="module1Form" method="post" id="module1Form" onsubmit="return ajaxEdit(this, 'lensModule', 'module1');" action="#">
          <div class="rowSubmitLeft">
            <input name="subbutton" type="image" src="http://www.squidoo.com/images/btn-save.gif" alt="save" class="button" />
            <input name="cancelbutton" type="image" src="http://www.squidoo.com/images/btn-cancel.gif" alt="cancel" class="button" onclick="closeEdit('module1', false); return false;"/>
          </div>
          
          <?php require('form.php'); ?>
          
          <div class="rowSubmitLeft">
            <input name="subbuttom" type="image" src="http://www.squidoo.com/images/btn-save.gif" alt="save" class="button" />
            <input name="cancelbutton" type="image" src="http://www.squidoo.com/images/btn-cancel.gif" alt="cancel" class="button" onclick="closeEdit('module1', false); return false;"/>
          </div>		
        </form>
      </div>
      <div class="break"></div>
    </div>
    <div id="errors" style="display:none"></div>
  </div>
</div>

<?php if (!isset($isNew)) { ?>
<script type="text/javascript">
$('module_id1_results').innerHTML = "<?php echo str_replace("\"", "\\\"", preg_replace('/\n/', '', $module->show())); ?>";
</script>
<?php } ?>
</body>
</html>
<?php } ?>