// GENERAL
var intervals = new Array();

function openEdit(elementId) {
  if (document.getElementById) {
    var linkId = 'editLink_' + elementId;
    if (!$(linkId) || $(linkId).innerHTML != '<a href="javascript:void(0)" onclick="closeEdit(\'module1\')"><img src="http://www.squidoo.com/images/btn-cancel-small.gif" border="0" /></a>') {
      var moduleId = elementId.replace('module', '');
      var element = $(elementId);
      
      var children = element.getElementsByTagName('div');
      if ($('editform_' + moduleId)) {
        $('editform_' + moduleId).innerHTML = '<p style="margin:0;padding:1em;">Loading form...</p>';
      }
      
      for (var i=0; i<children.length; i++) {
        if (children[i].className.indexOf('viewing') > -1) {
          children[i].style.display = 'none';
        } else if (children[i].className.indexOf('editing') > -1) {
          children[i].style.display = 'block';
        }
      }
      
      if ($(linkId)) {
        var link = $(linkId);
        link.innerHTML = '<a href="javascript:void(0)" onclick="closeEdit(\'module1\')"><img src="http://www.squidoo.com/images/btn-cancel-small.gif" border="0" /></a>';
      }
      
      if ($('editform_' + moduleId)) {
      // load module partial
        new Ajax.Updater('editform_' + moduleId, 'index.php?action=reload_partial', {
          asynchronous: true,
          method: 'post'
          });
      }

      // fire any onload JS for the form
      var formFunction = eval('window.load');
      if (formFunction) {
        setTimeout("eval('load();')", 1500);
      }
    }
  }
}


function closeEdit(elementId, showHighlight) {
  if (document.getElementById) {
    var element = $(elementId);
    var moduleId = elementId.replace('module', '');
    
    if (intervals['module' + moduleId]) {
      clearInterval(intervals['module' + moduleId]);
    }
    
    eval("if (window.closeHelper"+moduleId+") { closeHelper"+moduleId+"(); }");
    
    var children = element.getElementsByTagName('div');
    for (var i=0; i<children.length; i++) {
      if (children[i].className.indexOf('viewing') > -1) {
        children[i].style.display = 'block';
        
        if (showHighlight) {
          new Effect.Highlight(children[i]);
        } 
      } else if (children[i].className.indexOf('editing') > -1) {
        children[i].style.display = 'none';
      }
    }
    
    linkId = 'editLink_' + elementId;
    if ($(linkId)) {
      var link = $(linkId);
      link.innerHTML = '<img src="http://www.squidoo.com/images/btn-edit.gif" alt="edit this" class="button" alt="edit" border="0" />';
    }
  }
}

function ajaxEdit(myform, updateAction, elementId, synchronous, closeAfterSave) {
  var pass = null;
  
  /*checkTinyMceContent(myform);*/
  
  if ($(elementId + 'Errors')) {
    pass = validate(myform, elementId + 'Errors');
  } else {
    pass = validate(myform);
  }

  var async = true;
  if (!synchronous) {
    async = true;
  } else {
    async = false;
  }
  
  if (closeAfterSave == null) {
    closeAfterSave = true;
  }
  
  if (pass) {
    var inputs = myform.getElementsByTagName('input');
    var textareas = myform.getElementsByTagName('textarea');
    var selects = myform.getElementsByTagName('select');
    
    postString =  saveFields(inputs);
    postString += saveFields(textareas);
    postString += saveFields(selects);

      var resultDiv = elementId.replace('module', 'module_id') + '_results';
      var titleDiv = elementId.replace('module', 'module_id') + '_title';
      var subtitleDiv = elementId.replace('module', 'module_id') + '_subtitle';
      var titleInput = elementId.replace('module', 'modules_id') + '_lens_module_title';
      var subtitleInput = elementId.replace('module', 'modules_id') + '_lens_module_abstract';
      
      $(resultDiv).innerHTML = 'Loading...';
      
      new Ajax.Request('index.php?action=save',
          {asynchronous: true, method: 'post', postBody: postString,
          onSuccess:function(response) {
            var moduleId = elementId.replace('module', '');
            $(resultDiv).innerHTML = response.responseText;
            
            if ($(titleInput)) {
              var newTitle = escapeForDisplay($(titleInput).value);
              
              if ($(subtitleInput) && $(subtitleInput).value != '') {
                $(subtitleDiv).innerHTML = '<span class="moduleSubtitle">' + escapeForDisplay($(subtitleInput).value) + '</span>';
              }
              $(titleDiv).innerHTML = newTitle;
            }
            
            var initFunction = eval('window.init' + moduleId);
            
            if (initFunction) {
              eval('init' + moduleId + '();');
            }
          }});
      
      if (closeAfterSave) {
        closeEdit(elementId, true);
      }
  } else {
  // validation errors in this form
  }
  
  return false;
}