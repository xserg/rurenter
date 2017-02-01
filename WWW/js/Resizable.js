
/**
 * Applies drag handles to an element to make it resizable. The drag 
 * handles are inserted into the element and positioned absolutely. Some 
 * elements such as a textarea or imge, don't support this. You can wrap
 *  the element in a div and set config option "resizeChild" to true in 
 * the constructor config argument.
 * 
 * Valid resize handles are: "n", "s", "e", "w", "nw", "sw", "se", "ne". 
 * Handles are assigned the css className "resizable-handle". In addition 
 * they are each assigned a className of their position (eg "n" or "sw", etc)
 * 
 * So your css rules look like:
 * .resizable-handle {
 *   // styles for all handles
 * }
 * .resizable-handle.e {
 *   // addition styles for the eastern-most handle
 *   cursor:e-resize;
 *   right:0;
 *   top:50%;
 * }
 * .resizable-handle.sw {
 *   // styles for the south-west handle
 *   cursor:sw-resize;
 *   left:0;
 *   bottom:0;
 * }
 *
 * Here's an example showing the creation of a typical Resizable:
 * 
 * <code>
 * var resizer = new Resizable("element-id", {
 * 	handles: 'all',
 * 	minWidth: 200,
 * 	minHeight: 100,
 * 	maxWidth: 500,
 * 	maxHeight: 400,
 * 	showHandles: true
 * });
 * CustomEvent.addListener(resizer, "resize", myHandler);
 * </code>
 * 
 * Configuration options (these are checked at instantiation time)
 * 
 * - resizeChild {Boolean} True to resize the first child [false]
 * - target {Element} The element to resize (for example if resizeChild 
 *   is True this becomes the firstChild) [null]
 * - minWidth {Number} The minimum width for the element [5]
 * - minHeight {Number} The minimum height for the element [5]
 * - maxWidth {Number} The maximum width for the element [Number.MAX_VALUE]
 * - maxHeight {Number} The maximum height for the element [Number.MAX_VALUE]
 * - disabled {Boolean} True to disable the resizer on construction. 
 *   You can enable/disable via the enable() and disable() methods [false]
 * - handles {String} String consisting of the resize handles to display. 
 *   The special values "all" and "corner" are shortcuts to show all handles
 *   or just the corner handles, respectively ["all"]
 * - showHandles {Constant} Resizable.HANDLE_SHOW_ALWAYS/True to ensure that the resize handles are always visible, 
 *                        Resizable.HANDLE_SHOW_NEVER/false to ensure they are always invisible (but still functional), 
 *                        Resizable.HANDLE_SHOW_HOVER to show them when the user mouses over the resizable borders,
 *                        Resizable.HANDLE_SHOW_HOVER_ELEMENT to show them when the user mouses over the resizable element
 *                        [Resizable.HANDLE_SHOW_HOVER_ELEMENT]
 * 
 * Create a new resizable component
 * @param {Mixed} el The id or element to resize
 * @param {Object} config configuration options
 */

// {{{ Constructor

Resizable = function(el, config) {
	this.el = $(el);

	this.config = {
		handles: 'all',
		resizeChild: false,
		target: null,
		minWidth:5,
		minHeight:5,
		maxWidth: Number.MAX_VALUE,
		maxHeight: Number.MAX_VALUE,
		showHandles: Resizable.HANDLE_SHOW_HOVER_ELEMENT,
		//wrap: false,
		widthIncrement: 0,
		heightIncrement: 0,
		preserveRatio: false,
		disabled: false
	}

	if(config) Object.extend(this.config, config);

	// ensure we have values that won't make IE crap
	this.config.minWidth  = Math.max(this.config.minWidth, 0);
	this.config.minHeight = Math.max(this.config.minHeight, 0);
	this.config.maxWidth  = Math.max(this.config.maxWidth, 0);
	this.config.maxHeight = Math.max(this.config.maxHeight, 0);

	/*
	if(this.config.wrap) {
		config.target = this.el;
		this.el = this.el.wrap(typeof config.wrap == "object" ? config.wrap : {cls:"xresizable-wrap"});
		this.el.id = this.el.dom.id = config.target.id + "-rzwrap";
		this.el.setStyle("overflow", "hidden");
		this.el.setPositioning(config.target.getPositioning());
		config.target.clearPositioning();
		if(!config.width || !config.height) {
			var csize = config.target.getSize();
			this.el.setSize(csize.width, csize.height);
		}
		if(config.pinned && !config.adjustments) {
			config.adjustments = "auto";
		}
	}
	*/

	var position = Element.getStyle(this.el, 'position');
	if(position != 'absolute' && position != 'fixed') {
		this.el.style.position = 'relative'
	}

	this.el.style.zoom = 1;

	if(this.config.handles == 'all') {
		this.config.handles = ['n', 's', 'e', 'w', 'ne', 'nw', 'se', 'sw'];
	} else if(this.config.handles == 'corners') {
		this.config.handles = ['ne', 'nw', 'se', 'sw'];
	} else if(typeof this.config.handles == 'string') {
		this.config.handles = this.config.handles.split(/\s+/)
	}

	for(var i=0, len=this.config.handles.length; i < len; i++) {
		var pos = this.config.handles[i];
		this.handles[pos] = this.createHandle(pos, this.config.showHandles);
	}

	if(this.config.target) {
		this.target = this.config.target;
	} else if(this.config.resizeChild) {
		this.target = Element.firstChild(this.el);
	}

	/*
	if(this.config.adjustments == 'auto') {
		var rc = this.target;
		var hw = this.west, he = this.east, hn = this.north, hs = this.south;
		if(rc && (hw || hn)) {
			rc.position('relative');
			rc.setLeft(hw ? hw.el.getWidth() : 0);
			rc.setTop(hn ? hn.el.getHeight() : 0);
		}
		this.config.adjustments = [
			(he ? -he.el.getWidth() : 0) + (hw ? -hw.el.getWidth() : 0),
			(hn ? -hn.el.getHeight() : 0) + (hs ? -hs.el.getHeight() : 0) -1 
		];
	}
	*/

	this.recalc();

	if(this.el.setCapture) {
		this.src = this.el;
	} else {
		this.src = window;
		if(Browser.isMoz()) {
			Event.addListener(window, 'mouseout', function(evt) {
				if(this.dragging && !evt.relatedTarget) {
					this.onMouseUp(evt)
				}
			},this)
		}
	}

	this.onMouseDownHandler = this.onMouseDown.bindToEvent(this);
	this.onMouseMoveHandler = this.onMouseMove.bindToEvent(this);
	this.onMouseUpHandler = this.onMouseUp.bindToEvent(this);

	if(!this.config.disabled) {
		this.enable()
	}
};

// }}} Constructor

// {{{ Class variables

Object.extend(Resizable, {
	HANDLE_SHOW_NEVER: 0,
	HANDLE_SHOW_ALWAYS: 1,
	HANDLE_SHOW_HOVER: 3,
	HANDLE_SHOW_HOVER_ELEMENT: 2
});

// }}} Class variables

// {{{ Class body

Resizable.prototype = {

	currentHandle: null,
	oldCursor: null, // non-ie browsers
	target: null,
	dragging: false,
	disabled: true,
	moved: false,
	handles: {},

	enable: function() {
		forEach(this.handles, function(handle) {
			Event.addListener(handle, 'mousedown', this.onMouseDownHandler);
		}, this)
		this.disabled = false;
	},

	disable: function() {
		if(this.dragging) {
			this.cancelDrag()
		}
		forEach(this.handles, function(handle) {
			Event.removeListener(handle, 'mousedown', this.onMouseDownHandler);
		}, this)
		this.disabled = true
	},

	enabled: function() {
		return !this.disabled
	},

	createHandle: function(pos, visibility) {
		var handle = this.el.appendChild(Element.create('div', {
			'resizehandle': pos, // custom property for easy access
			'class': 'resizable-handle ' + pos,
			'style': 'position:absolute; overflow:hidden;',
			'unselectable': 'on'
		}));

		// IE rounding errors
		if(Browser.isIE() && !Browser.isIE7()) {
			if(pos == 'e') {
				handle.style.marginRight = '-1px';
			}
			if(pos == 's') {
				handle.style.marginBottom = '-1px';
			}
		}

		switch(visibility) {
			// transparent
			case false:
			case Resizable.HANDLE_SHOW_NEVER:
				Element.setOpacity(handle, 0);
				break;

			// always showing
			case true:
			case Resizable.HANDLE_SHOW_ALWAYS:
				Element.show(handle); // in case display:none in stylesheet
				break;

			// show when user mouses over borders
			case Resizable.HANDLE_SHOW_HOVER:
				Element.setOpacity(handle, 0);
				Event.addListener(handle, 'mouseover', function() {
					if(!this.dragging) {
						forEach(this.handles, function(handle) {
							Element.setOpacity(handle, 1);
						})
					}
				}, this);
				Event.addListener(handle, 'mouseout',  function() {
					if(!this.dragging) {
						forEach(this.handles, function(handle) {
							Element.setOpacity(handle, 0);
						})
					}
				}, this);
				break;

			// show when user mouses over the resizable element
			case Resizable.HANDLE_SHOW_HOVER_ELEMENT:
				Element.hide(handle);
				if(!arguments.callee.done) { // add mouseover listener once
					Event.addListener(this.el, 'mouseover', function() {
						if(!this.dragging) {
							forEach(this.handles, Element.show)
						}
					}, this);
					Event.addListener(this.el, 'mouseout',  function() {
						if(!this.dragging) {
							forEach(this.handles, Element.hide)
						}
					}, this);
					arguments.callee.done = true;
				}
				break;
		}

		return handle;
	},

	onMouseDown: function(e) {
		CustomEvent.trigger(this, 'mousedown', e);

		var handle = Event.target(e).resizehandle;

		if(!handle || !Event.isLeftClick(e) || this.disabled || e.cancelDrag) {
			if(this.dragging) {
				this.cancelDrag();
			}
			return
		}

		this.currentHandle = handle;

		this.startBox = Element.getBox(this.el);
		this.startPoint = Event.coord(e);

		Event.addListener(this.src, 'mousemove', this.onMouseMoveHandler);
		Event.addListener(this.src, 'mouseup', this.onMouseUpHandler);
		if(this.src.setCapture) {
			this.src.setCapture();
		} else {
			var newCursor = Element.getStyle(this.handles[this.currentHandle], 'cursor');
			this.oldCursor = Element.setCursor(document.body, newCursor);
		}

		this.dragging = true;
		this.moved = false;

		Event.cancel(e);

		this.onResizeStart(e);
	},

	onMouseUp: function(e) {
		CustomEvent.trigger(this, 'mouseup', e);
		this.cancelDrag();
		this.onResizeEnd(e)
	},

	onResizeStart: function(e) {
		CustomEvent.trigger(this, 'resizestart')
	},

	onResizeEnd: function(e) {
		if(this.proxy) {
			// dragging would have been resizing the proxy, 
			// here is where we resize the real element
			var box = Element.getBox(this.proxy);
			this.resize(box.x, box.y, box.width, box.height);
		}
		CustomEvent.trigger(this, 'resizeend', Element.getBox(this.el))
	},

	cancelDrag: function() {
		Event.removeListener(this.src, 'mousemove', this.onMouseMoveHandler);
		Event.removeListener(this.src, 'mouseup', this.onMouseUpHandler);
		if(this.src.releaseCapture) {
			this.src.releaseCapture();
		} else {
			Element.setCursor(document.body, this.oldCursor);
		}
		this.dragging = false
	},

	resize: function(x, y, w, h) {
		var boxW = Math.max(0, w - this.boxSize.width);
		var boxH = Math.max(0, h - this.boxSize.height);
	
		if(this.target) {
			Element.setBox(this.el, x, y, w, h);
			this.target.style.width = boxW + 'px';
			this.target.style.height = boxH + 'px';
		} else {
			Element.setBox(this.el, x, y, boxW, boxH);
		}

		if(CustomEvent.hasListeners(this, 'resize')) {
			CustomEvent.trigger(this, 'resize', x, y, w, h)
		}
	},

	/*
	snap: function(value, inc, min) {
		if(!inc || !value) return value;
		var newValue = value;
		var m = value % inc;
		if(m > 0) {
			if(m > (inc/2)) {
				newValue = value + (inc-m);
			} else {
				newValue = value - m;
			}
		}
		return Math.max(min, newValue);
	},
	*/

	constrain: function(diff, v, min, max) {
		if(diff < 0) {
			return v - Math.min(v - diff, max);
		} else if(diff > 0) {
			return v - Math.max(v - diff, min);
		}
		return diff;
	},

	onMouseMove: function(e) {
		var
			x = this.startBox.x, y = this.startBox.y, 
			w = this.startBox.width, h = this.startBox.height,
			start  = this.startPoint,
			end    = Event.coord(e),
			deltaX = end.x - start.x,
			deltaY = end.y - start.y,
			handle = this.currentHandle;

		if(handle.indexOf('n') >= 0) {
			deltaY = this.constrain(deltaY, h, this.config.minHeight, this.config.maxHeight);
			y += deltaY;
			h -= deltaY;
		}

		if(handle.indexOf('w') >= 0) {
			deltaX = this.constrain(deltaX, w, this.config.minWidth, this.config.maxWidth);
			x += deltaX;
			w -= deltaX;
		}

		if(handle.indexOf('s') >= 0) {
			h += deltaY;
		}

		if(handle.indexOf('e') >= 0) {
			w += deltaX;
		}

		w = Math.min(Math.max(this.config.minWidth, w), this.config.maxWidth);
		h = Math.min(Math.max(this.config.minHeight, h), this.config.maxHeight);

		if(this.proxy) {
			Element.setBox(this.proxy, x, y, w, h);
		} else {
			this.resize(x, y, w, h);
		}
	},

	recalc: function() {
		var c = (this.target || this.el).cloneNode(true);
		Element.setStyle(c, { width:'1px', height:'1px' });
		this.boxSize = Element.getSize(c);
		this.boxSize.width--;
		this.boxSize.height--;
	},

	/**
	 * Returns the target element.
	 */
	getTarget: function() {
		return this.target || this.el;
	},

	destroy: function() {
		forEach(this.handles, Memory.removeElement);
		Memory.removeElement(this.el);
		this.el = null;
	}
};

// }}} Class body

// UTILITYs needed
Element.wrap = function(element, container) {
	if(typeof container == 'string') {
		// got tag
		container = Element.create(container, arguments[2])
	}
	element.parentNode.insertBefore(element, container);
	var newEl = Ext.DomHelper.insertBefore(this.dom, config, !returnDom);
	newEl.dom ? newEl.dom.appendChild(this.dom) : newEl.appendChild(this.dom);
	return newEl;
}

Element.getBox = function(element) {
	var offset = Position.cumulativeOffset(element);
	var size = Element.getSize(element);
	return { x:offset.left, y:offset.top, width:size.width, height:size.height };
}

Element.setBox = function(element, x, y, w, h) {
	var pos = Element.translatePoints(element, x, y);
	element.style.left = pos.left + 'px';
	element.style.top = pos.top + 'px';
	element.style.width = w + 'px';
	element.style.height = h + 'px';
}

Element.translatePoints = function(element, x, y) {
	var p = Element.getStyle(element, 'position');
	var o = Position.cumulativeOffset(element);

	var l = parseInt(Element.getStyle(element, 'left'), 10);
	var t = parseInt(Element.getStyle(element, 'top'), 10);

	if(isNaN(l)){
		l = (p == "relative") ? 0 : element.offsetLeft;
	}
	if(isNaN(t)){
		t = (p == "relative") ? 0 : element.offsetTop;
	}

	return {left: (x - o.left + l), top: (y - o.top + t)};
}

