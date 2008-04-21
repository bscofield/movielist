// define these here for reuse since we do text searches
var ajaxSpanStart = '<span id="ajaxErrorText">';
var ajaxSpanEnd   = '</span>';

function validate(myform, errorElement) {
  var result = true;
  
  if (errorElement == null) {
    errorElement = "errors";
  }

  var errors = $(errorElement);
  errors.style.display = 'none';
  errors.innerHTML = '';

  inputresult = validateFields(myform, 'input', errors);
  selectresult = validateFields(myform, 'select', errors);
  textarearesult = validateFields(myform, 'textarea', errors);
  ajaxresult = true;
  if ($('ajaxErrors') && $('ajaxErrors').innerHTML.length) {
    ajaxresult = false;
  }

  if (!ajaxresult) {
    errors.innerHTML += ajaxSpanStart + '- ' + $('ajaxErrors').innerHTML + ajaxSpanEnd;
  }
  
  result = inputresult && selectresult && textarearesult && ajaxresult;

  if ($('notSubmittable')) {
    // this should only be used when changing the lens name in the LM workshop
    result = false;
    errors.innerHTML += ' - Your submission could not be processed. Please check the form for any remaining errors<br />';
  }

  if (!result) {
    errors.style.display = 'block';
    // make sure errors become visible by scrolling to the error element
    //    delete if we move the errors closer to the problematic field(s)
    scrollToElement('errors');
  } else {
    errors.style.display = 'none';
  }

  return result;
}

function scrollToElement(id) {
  var x = 0;
  var y = 0;
  var element = $(id);
  while (element) {
    x += element.offsetLeft;
    y += element.offsetTop;
    element = element.offsetParent;
  }
  window.scrollTo(x,y);
}

// try to submit the form once AJAX validation finishes
function waitForAJAX(formName) {
  if (ajaxRunning) {
    // try again in 1/2 second
    var ajaxTimer = setTimeout("waitForAJAX('"+formName+"')", 500);
  } else {
    // AJAX validation is done
    if (!$('errors').innerHTML.length) {
      $('errors').style.display = 'none';
      // onSubmit is not called here
      $(formName).submit();
    } else {
      $('errors').style.display = 'block';
    }
  }
}

function validateFields(myform, tagname, errors) {
  var fields = myform.getElementsByTagName(tagname);
  var pass = true;
  var message = '';
  
  var errorsFound = new Array();
  
  for (var i=0; i<fields.length; i++) {
    var fieldpass = true;
    var fieldvalue = fields[i].value;
    var fieldname = fields[i].name;
    var message_fieldname = fieldname.replace(/_/g, ' ');
    var themessage = '';
    
    if (fields[i].getAttribute('maxlength')) {
      var max = fields[i].getAttribute('maxlength');
      var value = fields[i].value;
      if (value.length > max) {
        value = value.substr(0, max);
        fields[i].value = value;
      }
    }
  
    var errormessage = '';
    if (fields[i].getAttribute('errormessage')) {
      errormessage = ' - ' + fields[i].getAttribute('errormessage') + '<br />';
    }
    
    // look for required attribute
    // error is recorded if there is no value
    if ((!fields[i].getAttribute('ignore') || fields[i].getAttribute('ignore') == null) && fields[i].getAttribute('required')) {
      if (tagname != 'select' && fieldvalue == '') {
        fieldpass = false;
        if (errormessage == '') {
          themessage = ' - Please enter your ' + message_fieldname + '<br />';
        } else {
          themessage = errormessage;
        }
      }
      
      if (fields[i].options) {
        if (fields[i].selectedIndex < 1) {
          fieldpass = false;
          if (errormessage == '') {
            themessage = ' - Please select a ' + message_fieldname + '<br />';
          } else {
            themessage = errormessage;
          }
        }
      }
      
      if (fields[i].className.indexOf('radio') >= 0) {
        var buttons = document.getElementsByName(fieldname);
        fieldpass = false;
        for (var j=0; j<buttons.length; j++) {
          if (buttons[j].checked) {
            fieldpass = true;
          }
        }
        if (!fieldpass) {
          if (errormessage == '') {
            themessage = ' - Please select a ' + message_fieldname + ' value<br />';
          } else {
            themessage = errormessage;
          }
        }
      }
    // look for pattern attribute
    // error is recorded if value doesn't match the pattern
    } else if (!fields[i].getAttribute('ignore') && fields[i].getAttribute('pattern') && fields[i].getAttribute('pattern').length) {
      var pattern = fields[i].getAttribute('pattern');
      
      if (pattern == 'email') {
        pattern = "[\\_\\d\\w\\.'\\-]+@([\\d\\w'\\-]\\.?)+";
      } else if (pattern == 'url') {
        fieldvalue = fieldvalue.replace('http://http', 'http');
        fields[i].value = fieldvalue;
        pattern = "^https?://([\\_\\d\\w\\-]+\\.)+[\\_\\d\\w\\-]+(:\\d+)?(/[^\\s]*)*$";
      } else if (pattern == 'feed') {
        pattern = "^((https?://)|(feed:(//)?))([\\_\\d\\w\\-]+\\.)+[\\_\\d\\w\\-]+(:\\d+)?(/[^\\s]*)*$";
      } else if (pattern == 'password') {
        pattern = "[\\S]{4,8}";
      } else if (pattern == 'tag') {
        pattern = "^[\\_\\ \\d\\w\\.'\\-]+$";
      }
      
      var re = new RegExp(pattern);
      if (!re.test(fieldvalue)) {
        fieldpass = false;
        if (errormessage == '') {
          themessage = ' - Please make sure that you are entering your ' + message_fieldname + ' in the correct format<br />';
        } else {
          themessage = errormessage;
        }
      }
    }
    
    // check for confirms attribute
    // error is recorded if the specified field ID has different contents
    if (!fields[i].getAttribute('ignore') && fields[i].getAttribute('confirms')) {
      var confirmed_field = fields[i].getAttribute('confirms');
      if ($(confirmed_field).value != fieldvalue) {
        fieldpass = false;
        if (errormessage == '') {
          themessage = ' - Please make sure that your ' + confirmed_field.replace('_', ' ') + ' is entered correctly in both fields<br />';
        } else {
          themessage = errormessage;
        }
      }
    }
    var mydiv = findParentDiv(fields[i]);
    var mydivClass = mydiv.className;
    if (!mydiv.getAttribute('classPristine')) {
      // store a copy of the field's style
      mydiv.setAttribute('classPristine', mydivClass);
    }
    if (!fieldpass) {
      pass = false;
      mydiv.className = mydivClass + ' error';
      fields[i].setAttribute('error', 'true');
      
      var added = false;
      for (var j=0; j<errorsFound.length; j++) {
        if (errorsFound[j] == themessage) {
          added = true;
        }
      }
      
      if (!added) {
        errorsFound.push(themessage);
      }
    } else {
      if (fields[i].getAttribute('error')) {
        mydiv.className = mydivClass.replace(' error', '');
      }
    }
  }
  
  if (!pass && errorsFound.length > 0) {
    errors.innerHTML += errorsFound.join("\n");
  }
  return pass;
}

function findParentDiv(element) {
  var parent = element.parentNode;
  while (parent.tagName != 'DIV') {
    parent = parent.parentNode;
  }
  
  return parent;
}

function saveFields(collection) {
  var postString = '';
    
  for (var i=0; i<collection.length; i++) {
    var field = collection[i];
    var fieldId = field.id;
    var fieldName = field.name;
    
    var tempString = '';
    
    if (field.type != 'checkbox' && field.type != 'submit' && field.type != 'image' &&
        field.type != 'select-one' && field.type != 'radio') {
      var value = field.value;
      
      var resultField = fieldId + '_value';
      
      if (fieldName.indexOf('[') > -1) {
        resultField = fieldName.substr(0, fieldName.indexOf('[')) + '_value';
      }
      
      if ($(resultField) && value != '') {
        if (fieldName.indexOf('[') > -1 && i > 0) {
          var current = $(resultField).innerHTML;
          if (current != '') {
            current = current + ', ' + value;
          } else {
            current = value;
          }
          $(resultField).innerHTML = escapeForDisplay(current, true);
        } else {
          $(resultField).innerHTML = escapeForDisplay(value, true);
        }
      } else if ($(resultField) && value == '' && i == 0) {
        $(resultField).innerHTML = '';
      }
      
      postString += fieldName + '=' + escapeForURL(value) + '&';
    } else if (field.type == 'checkbox' && field.checked && fieldName.indexOf('[]') < 0) {
      postString += fieldName + '=1&';
    } else if (field.type == 'checkbox' && field.checked && fieldName.indexOf('[]') > 0) {
      var newFieldName = fieldName.replace('[]', '');
      var value = field.value;

      if (postString.indexOf(newFieldName+'=') >= 0) {
        var nameAt = postString.indexOf(newFieldName+'=');
        var ampersandAt = postString.indexOf('&', nameAt);
        var postSlice = postString.slice(nameAt, ampersandAt);
        var newSlice = postSlice + ',' + escapeForURL(value);
        postString = postString.substr(0, nameAt) + newSlice + postString.substr(ampersandAt);
      } else {
        postString += newFieldName + '=' + escapeForURL(value) + '&';
      }
    } else if (field.type == 'select-one') {
      var value = field.options[field.selectedIndex].value;
      postString += fieldName + '=' + escapeForURL(value) + '&';
    } else if (field.type == 'radio') {
      if (field.checked == "1") {
        postString += fieldName + '=' + escapeForURL(field.value) + '&';
      }
    }
  }
  return postString;
}

// clean user entries before displaying on the page
function escapeForDisplay(s, allowHTML) {
  if (s != null) {
    var t = new String(s);
    t = t.replace(/&/g, '&amp;');
    if (!allowHTML) {
      t = t.replace(/</g, '&lt;');
      t = t.replace(/>/g, '&gt;');
    }
    t = t.replace(/\"/g, '&quot;');
    t = t.replace(/\r/g, '');
    t = t.replace(/\n/g, '<br />\n');
  
    t = t.replace(/&amp;nbsp;/g, '&nbsp;');
    return t;
  } else {
    return '';
  }
}

// clean user entries before stuffing in a form field
function escapeForFormField(s) {
  return escapeForDisplay(s);
}

// escape text for use in a query string
function escapeForURL(s) {
  if (s != null) {
    var t = new String(escape(s));
    // JS escape() doesn't touch plus signs
    t = t.replace(/\+/g, '%2B');
  
    return t;
  } else {
    return '';
  }
}

// strip HTML tags from a string
function stripTags(s){
  var regExp = /<\/?[^>]+>/gi;
  s = s.replace(regExp,'');

  return s;
}

function checkTinyMceContent(myform) {
  var textareas = myform.getElementsByTagName('textarea');
  for (var i=0; i<textareas.length; i++) {
    var editorId = '';
    var id = textareas[i].id;
    for (var j=0; j<elements.length; j++) {
      if (textareas[i].id + ":" == elements[j].substr(0, textareas[i].id.length+1)){
        editorId = elements[j].replace(textareas[i].id + ':', '');
      }
    }
    
    var instance = null;
    if (instance = tinyMCE.getInstanceById(editorId)) {
      var markup = textareas[i].value;
      var tiny = instance.getBody().innerHTML;
      
      if (markup != tiny) {
        textareas[i].value = tiny;
      }
    }
  }
}