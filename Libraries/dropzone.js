var count = 0;

function _(id){
    return document.getElementById(id);	
}

function drag_start(event) {
    _('app_status').innerHTML = "Dragging the "+event.target.getAttribute('id');
    event.dataTransfer.setData("text/plain", event.target.id);
    event.dataTransfer.dropEffect = "move";
    event.dataTransfer.setData("text", event.target.getAttribute('id') );
}

function drag_enter(event) {
    event.preventDefault();
    _('app_status').innerHTML = "You are dragging over the "+event.target.getAttribute('id');
}

function drag_leave(event) {
    _('app_status').innerHTML = "You left the "+event.target.getAttribute('id');
}

function drag_drop(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text/plain");
    var elem = $("#" + data);
    var elemId = elem.attr("id");
    var dropZone = $("#drop_zone");
    
    // Check if the element is already in the drop zone
    var existingElem = dropZone.find("#" + elemId);
    
    if (existingElem.length) {
        // If it is, merge the elements
        var overlapElem = getOverlapElement(existingElem, dropZone.children());
        if (overlapElem) {
            var mergedElem = mergeElements(existingElem, overlapElem);
            dropZone.append(mergedElem);
            existingElem.remove();
            overlapElem.remove();
            return;
        }
        
        // If not, update its position
        existingElem.css({ left: event.clientX, top: event.clientY });
    } else {
        // If not, create a new element
        var newElem = elem.clone();
        newElem.attr("id", elemId + "_" + Date.now()); // add unique id
        newElem.css({ position: "absolute", left: event.clientX, top: event.clientY });
        newElem.appendTo(dropZone);
    }
    _('app_status').innerHTML = "Dropped "+elemId+" into the "+event.target.getAttribute('id') + " as " + elem.id;
}

function getOverlapElement(elem, elements) {
    var elemRect = elem.get(0).getBoundingClientRect();
    for (var i = 0; i < elements.length; i++) {
        var overlapElem = $(elements[i]);
        if (overlapElem.attr('id') !== elem.attr('id')) {
            var overlapRect = overlapElem.get(0).getBoundingClientRect();
            if (!(elemRect.right < overlapRect.left || 
                  elemRect.left > overlapRect.right || 
                  elemRect.bottom < overlapRect.top || 
                  elemRect.top > overlapRect.bottom)) {
                return overlapElem;
            }
        }
    }
    return null;
}

function mergeElements(elem1, elem2) {
    var newElem = $('<div></div>');
    newElem.addClass('merged');
    newElem.css({
        position: 'absolute',
        left: elem1.position().left,
        top: elem1.position().top,
        width: elem1.outerWidth(),
        height: elem1.outerHeight() + elem2.outerHeight()
    });
    elem1.children().clone().appendTo(newElem);
    elem2.children().clone().appendTo(newElem);
    newElem.resizable();
    return newElem;
}

function drag_end(event) {
    if(droppedIn == false){
        _('app_status').innerHTML = "You let the "+event.target.getAttribute('id')+" go.";
    }
	droppedIn = false;
}

function readDropZone(){
    for(var i=0; i < _("drop_zone").children.length; i++){
        alert(_("drop_zone").children[i].id+" is in the drop zone with data-attribute " + _("drop_zone").children[i].getAttribute('data-attribute'));
    }
}
