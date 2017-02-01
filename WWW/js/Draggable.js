/************************
 * BEGIN Draggable 
 *
 * The following Class will wrap around a DOM element
 * to make it draggable. This is accomplished by registering
 * the element to call an instance method on the browser's 
 * "mousedown" event, and then at the start of that method 
 * we register two more methods for the "mousemove" and the 
 * "mouseup" browser events, essentially simulating dragging 
 * behaviour. At "mouseup" we unregister these last two
 * methods to remove dragging behaviour.
 *
 * During this process it also registers the following custom 
 * events to fire. Any similarities in event 
 * names to built-in browser events are irrelevant:
 *
 *    Method   |   Event name   
 * -----------------------------
 * moveTo      | "move"
 * onMouseDown | "mousedown" (on method enter)
 * onMouseDown | "dragstart" (on method leave)
 * onMouseMove | "mousemove"
 * onMouseMove | "drag"
 * onMouseUp   | "mouseup" (on method enter)
 * onMouseUp   | "dragend" (at end of leave)
 *
 * These custom events are "fired" via the CustomEvent.trigger 
 * method. Registering for these events manually via
 * Event.addCustomListener enables you to hook into the 
 * methods above.
 */

function Draggable(elem, options) {
	this.element = elem;

	this.options = Object.extend({
		extent: null,
		draggableCursor: "default",
		draggingCursor: "move"
	}, options || {});

	this.dragPoint = { x:0, y:0 };
	this.dragging = false;
	this.clickStartPos = { x:0, y:0 };
	this.element.style.position = "absolute";
	this.offsetX = 0;
	this.offsetY = 0;
	this.disabled = false;
	this.moveTo(this.element.offsetLeft, this.element.offsetTop);

	// save for later
	this.mouseMoveHandler = this.onMouseMove.bindTo(this);
	this.mouseUpHandler = this.onMouseUp.bindTo(this);

	if(Browser.instance().isMoz()) {
		Event.addListener(window, "mouseout", this.onWindowMouseOut, this)
	}

	// Mozilla does not support IE's mouse capture methods
	// (setCapture and releaseCapture) that set mouse capture to bind 
	// to the object in the current document actually recieving mouse 
	// events, so we use 'document' instead as the event target object.
	this.src = this.element.setCapture ? this.element : window;
	Event.addListener(this.element, "mousedown", this.onMouseDown, this);
	Event.addListener(this.element,"mouseup", this.dispatchMouseUp, this);
	Event.addListener(this.element,"click", this.dispatchClick, this);
	Event.addListener(this.element,"dblclick", this.dispatchDoubleClick, this);

}

Draggable.prototype = {
	dispatchDoubleClick: function(evt) {
		CustomEvent.trigger(this, "dblclick", evt)
	},

	dispatchClick: function(evt) {
		if(this.disabled) {
			CustomEvent.trigger(this, "click", evt)
		}
	},

	dispatchMouseUp: function(evt) {
		if(this.disabled) {
			CustomEvent.trigger(this, "mouseup", evt);
		}
	},

	moveTo: function(left,top) {

		this.offsetX = 0;
		this.offsetY = 0;

		if(this.options.extent) {
			var e = this.options.extent;
			var tmpLeft = Math.max(e.min.x, Math.min(left, e.max.x - this.element.offsetWidth));
			this.offsetX = tmpLeft - left;
			left = tmpLeft;
			var tmpTop = Math.max(e.min.y, Math.min(top, e.max.y - this.element.offsetHeight));
			this.offsetY = tmpTop - top;
			top = tmpTop;
		}

		if(this.left != left || this.top != top) {
			this.left = left;
			this.top = top;
			this.element.style.left = left + "px";
			this.element.style.top = top + "px";
			CustomEvent.trigger(this,"move")
		}
	},

	setDraggableCursor: function(cursor) {
		this.options.draggableCursor = cursor;
		return Element.setCursor(this.element, this.options.draggableCursor);
	},

	getDraggableCursor: function() {
		return this.options.draggableCursor
	},

	setDraggingCursor: function(cursor) {
		var oldCursor = this.options.draggingCursor;
		this.options.draggingCursor = cursor;
		return oldCursor
	},

	getDraggingCursor: function() {
		return this.options.draggingCursor
	},

	setExtent: function(extent) {
		this.options.extent = extent;
	},

	getExtent: function() {
		return this.options.extent;
	},

	onMouseDown: function(evt) {
		CustomEvent.trigger(this,"mousedown",evt);

		if(evt.cancelDrag) return;

		if(this.disabled || !Event.isLeftClick(evt))
			return;

		this.dragging = true;

		this.dragPoint = Event.coord(evt);

		Event.addListener(this.src, "mousemove", this.mouseMoveHandler);
		Event.addListener(this.src, "mouseup", this.mouseUpHandler);

		if(this.element.setCapture) {
			this.element.setCapture()
		}

		this.dragStartTime = (new Date()).getTime();
		this.clickStartPos = Event.coord(evt);

		CustomEvent.trigger(this,"dragstart");

		if(this.options.draggingCursor) {
			Element.setCursor(this.element, this.options.draggingCursor);
		}
		Event.cancel(evt);
	},

	onMouseMove: function(evt) {
		var coord = Event.coord(evt);

		// new left = current left + difference in distance in the x-axis
		var newLeft = this.element.offsetLeft + (coord.x - this.dragPoint.x);

		// new top = current top + difference in distance in the y-axis
		var newTop = this.element.offsetTop + (coord.y - this.dragPoint.y);

		this.moveTo(newLeft, newTop);

		this.dragPoint.x = coord.x + this.offsetX;
		this.dragPoint.y = coord.y + this.offsetY;

		CustomEvent.trigger(this,"drag");
	},

	onMouseUp: function(evt) {
		CustomEvent.trigger(this, "mouseup", evt);

		var coord = Event.coord(evt);

		// Stops listening for dragging behaviour
		Event.detach(this.src, "mousemove", this.mouseMoveHandler);
		Event.detach(this.src, "mouseup", this.mouseUpHandler);

		this.dragging = false;

		if(this.options.draggableCursor) {
			Element.setCursor(this.element, this.options.draggableCursor);
		}

		if(document.releaseCapture) {
			document.releaseCapture();
		}

		CustomEvent.trigger(this,"dragend");

		var dragEndTime = (new Date()).getTime();
		if(dragEndTime - this.dragStartTime <= 500 && Math.abs(this.clickStartPos.x - coord.x) <= 2 && Math.abs(this.clickStartPos.y - coord.y) <= 2) {
			CustomEvent.trigger(this,"click",evt)
		}
	},

	onWindowMouseOut: function(evt) {
		if(this.dragging && !evt.relatedTarget) {
			this.onMouseUp(evt)
		}
	}
}

