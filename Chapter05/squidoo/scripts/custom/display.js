// set the display of the specified element
function setDisplay(elementId, newDisplay) {
  if ($(elementId)) {
    var element = $(elementId);
    element.style.display = newDisplay;
  }
}

// toggle the display of the specified element
// if no current display value is known, assume block
function toggleDisplay(elementIdPrefix) {
  var bodyId = elementIdPrefix + "Body";
  var headerId = elementIdPrefix + "Hdr";
  
  if ($(bodyId)) {
    var element = $(bodyId);
    var currentDisplay = element.style.display;
    
    var collapsed = 'collapse';
    var displayed = 'expanded';
    
    if (currentDisplay == 'none') {
      setDisplay(bodyId, 'block');
      addClass(headerId, displayed);
      desist(elementIdPrefix);
    } else {
      setDisplay(bodyId, 'none');
      addClass(headerId, collapsed);
      persist(elementIdPrefix, collapsed);
    }
  } else if ($(elementIdPrefix)) {
    var element = $(elementIdPrefix);
    var currentDisplay = element.style.display;
    
    var collapsed = 'collapse';
    var displayed = '';
    
    if (currentDisplay == 'none') {
      setDisplay(elementIdPrefix, 'block');
    } else {
      setDisplay(elementIdPrefix, 'none');
    }
  }
}

// add the specified class to the specified element
function addClass(elementId, className) {
  if ($(elementId)) {
    var element = $(elementId);
    var currentClass = element.className;
    
    element.className = className;
  }
}

// persist the chosen display across pages
function persist(elementId, className) {
  var expiration = new Date();
  expiration.setFullYear(expiration.getFullYear() + 10);
 
  document.cookie = elementId + "=" + className + "; path=/; expires=" + expiration.toGMTString();
}

// stop persistence
function desist(elementId) {
  var expiration = new Date();
  expiration.setFullYear(expiration.getFullYear() - 10);
  
  document.cookie = elementId + "=test; path=/; expires=" + expiration.toGMTString();
}

// set timeout to display tooltip
function scheduleShow() {
  if (this.getElementsByTagName) {
    setTipShow(this.getElementsByTagName('div'));
    setTipShow(this.getElementsByTagName('span'));
  }
}

function setTipShow(children) {
  for (var i=0; i<children.length; i++) {
    if (children[i].className.indexOf('toolTip') != -1) {
      children[i].mouseover = true;
      if (children[i].id) {
        window.setTimeout('showTip("' + children[i].id + '")', 250);
      }
    }
  }
}

// set timeout to hide tooltip
function scheduleHide() {
  if (this.getElementsByTagName) {
    setTipHide(this.getElementsByTagName('div'));
    setTipHide(this.getElementsByTagName('span'));
  }
}

function setTipHide(children) {
  for (var i=0; i<children.length; i++) {
    if (children[i].className.indexOf('toolTip') != -1) {
      children[i].mouseover = false;
      if (children[i].id) {
        window.setTimeout('clearTip("' + children[i].id + '")', 1000);
      }
    }
  }
}

// makes the tip visible
function showTip(id) {
  var element = $(id);
  if (element.mouseover) {
    element.style.display = 'block';
  }
}

// hides the tip - or if it's still being mousedover, schedules the next close try
function clearTip(id) {
  var element = $(id);
  if (!element.mouseover) {
    element.style.display = 'none';
  } else {
    window.setTimeout('clearTip("' + id + '")', 1000);
  }
}

// keep tips open when the text is being mousedover
function recordParentMouseover() {
  this.parentNode.mouseover = true;
}

// allow tips to be closed when the text is mousedout
function recordParentMouseout() {
  this.parentNode.mouseover = false;
}

// set up tooltip mouseovers
function setTooltips(elementType) {
  if (!elementType) {
    elementType = 'span';
  }
  
  if (document.getElementsByTagName) {
    var spans = document.getElementsByTagName(elementType);
    
    for (var i=0; i<spans.length; i++) {
      var span = spans[i];
      
      if (span.className.indexOf('moreInfo') != -1) {
        span.onmouseover = scheduleShow;
        span.onmouseout = scheduleHide;
        
        setEvents(span, 'div');
        setEvents(span, 'span');
      }
    }
  }
}

function setEvents(object, elementType) {
  var divs = object.getElementsByTagName(elementType);
  
  for (var j=0; j<divs.length; j++) {
    if (divs[j].className.indexOf('toolTip') != -1) {
      divs[j].onmouseover = recordParentMouseover;
      divs[j].onmouseout = recordParentMouseout;
    }
  }
}