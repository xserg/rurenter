/**
 * Lightwieght class that wraps building, sizing and positioning a filled 
 * rectangle (div) without resorting to vml or svg. 
 */
function Rectangle(options) {
	this.options = {
		className: '',
		border: {
			width: '1px',
			style: 'solid',
			color: '#888'
		},
		fill: {
			color: 'blue',
			opacity: 0.25
		}
	}

	if(options) Object.merge(options, Object.merge(this.options, options));
}

Rectangle.prototype = {
	minX: null,
	minY: null,
	maxX: null,
	maxY: null,

	draw: function(into, x, y, w, h) {
		if(!this.outer) {
			this.outer = (into || document.body).appendChild(Element.create('div', {
				'style': {
					'overflow': 'hidden',
					'position': 'absolute' // um, this shouldn't be hardcoded
				}
			}));

			this.outer.appendChild(Element.create('div', {
				'class': this.options.className,
				'style': {
					'overflow': 'hidden',
					'border': [this.options.border.width, this.options.border.style, this.options.border.color].join(' ')
				}
			}));

			if(this.options.fill.color) {
				this.outer.firstChild.appendChild(Element.create('div', {
					'style': {
						'background-color': this.options.fill.color,
						'width': '100%',
						'height': '100%',
						'opacity': this.options.fill.opacity
					}
				}))
			}

			this.calc();
		}

		// already created, just [re-]append
		else {
			(into || document.body).appendChild(this.outer);
		}

		this.setBox(x, y, w, h)
	},

	/**
	 * Redraw at px coordinates set in pos.x, pos.y, with size set in size.width, size.height
	 */
	setBox: function(x, y, w, h) {
		var changed = false;

		if(x != null) {
			this.outer.style.left = x + 'px';
			this.minX = x;
			changed = true;
		}

		if(y != null) {
			this.outer.style.top = y + 'px';
			this.minY = y;
			changed = true;
		}

		if(w != null) {
			this.outer.style.width = w + 'px';
			this.outer.firstChild.style.width = Math.max(0, w - this.boxSize.width) + 'px';
			this.maxX = this.minX + this.outer.offsetWidth;
			changed = true;
		}

		if(h != null) {
			this.outer.style.height = h + 'px';
			this.outer.firstChild.style.height = Math.max(0, h - this.boxSize.height) + 'px';
			this.maxY = this.minY + this.outer.offsetHeight;
			changed = true;
		}

		if(changed) {
			CustomEvent.trigger(this, 'extentchange');
		}
	},

	getContainer: function() {
		return this.outer
	},

	getExtent: function() {
		return { minX:this.minX, minY:this.minY, maxX:this.maxX, maxY:this.maxY }
	},

	getBorderSize: function() {
		return { width:this.boxSize.width, height:this.boxSize.height };
	},

	show: function() {
		this.outer.style.display = 'block'
	},

	hide: function() {
		this.outer.style.display = 'none'
	},

	destroy: function() {
		this.outer.parentNode.removeChild(this.outer);
		this.outer = null
	},

	calc: function() {
		this.minX = this.outer.offsetLeft;
		this.minY = this.outer.offsetTop;
		this.maxX = this.minX + this.outer.offsetWidth;
		this.maxY = this.minY + this.outer.offsetHeight;

		Element.setStyle(this.outer.firstChild, { width:'1px', height:'1px' });
		this.boxSize = Element.getSize(this.outer.firstChild);
		this.boxSize.width--;
		this.boxSize.height--
	}
}

/**
 * Rectangle Decorator. Adds resizability functionality to a Rectangle.
 * Conforms to the same interface as Rectangle so can be swapped out.
 * I know Resizeable is spelt wrong.
 */
function ResizeableRectangle(options) {

	/**
	 * Resizable options
	 */
	this.options = {
		resizeChild:true, // Rectangle implementation is a perfect candidate for this
		minWidth:20,
		minHeight:20,
		showHandles:2,
		handles:'all'
	}

	if(options) Object.extend(this.options, options);

	this.rect = new Rectangle(this.options);
	CustomEvent.passThrough(this.rect, 'extentchange', this);
}

// decorate Rectangle
ResizeableRectangle.prototype = {
	rect: null,
	resizer: null,

	draw: function(into, pos, size) {
		this.rect.draw(into, pos, size);

		if(!this.resizer) {
			this.resizer = new Resizable(this.rect.getContainer(), this.options);
			// we only support a subset Resizable's event api (for now)
			CustomEvent.passThrough(this.resizer, 'resizeend', this, 'extentchange');
		}
	},

	setBox: function(x, y, w, h) {
		this.rect.setBox(x, y, w, h)
	},

	getContainer: function() {
		return this.rect.getContainer()
	},

	getExtent: function() {
		return this.rect.getExtent()
	},

	getBorderSize: function() {
		return this.rect.getBorderSize()
	},

	show: function() {
		this.rect.show()
	},

	hide: function() {
		this.rect.hide()
	},

	destroy: function() {
		this.rect.destroy();
		this.rect = null
	}
}

/**
 * ResizeableRectangle Decorator. Adds draggability to a ResizeableRectangle.
 * Conforms to the same interface as ResizeableRectangle so can be swapped out.
 * Currently supports one additional option to ResizeableRectangle:
 * - ctrlDrag (bool) true if you want draggability of the rectangle only when the
 * Ctrl button is pressed, false if the rectangle is always draggable.
 * 
 * @requires Draggable.js
 */
function DraggableResizeableRectangle(options) {

	this.options = {
		ctrlDrag: false // always draggable
	}

	if(options) Object.extend(this.options, options);

	// add resizability and passthrough [a subset of] ResizeableRectangle's event api
	this.rect = new ResizeableRectangle(options);
	CustomEvent.passThrough(this.rect, 'extentchange', this);
	CustomEvent.passThrough(this.rect, 'resizeend', this);
}

// decorate ResizeableRectangle
DraggableResizeableRectangle.prototype = {
	rect: null,
	drag: null,

	draw: function(into, pos, size) {
		this.rect.draw(into, pos, size);

		if(!this.drag) {
			// add draggability and passthrough [a subset of] Draggable's event api
			this.drag = new Draggable(this.rect.getContainer(), this.options);
			CustomEvent.passThrough(this.drag, 'dragend', this, 'extentchange');

			// allow draggability to be toggled with the Ctrl key (optional)
			if(this.options.ctrlDrag) {
				CustomEvent.addListener(this.drag, 'mousedown', function(evt) { this.disabled = !evt.ctrlKey });
			}
		}
	},

	setBox: function(x, y, w, h) {
		this.rect.setBox(x, y, w, h)
	},

	getContainer: function() {
		return this.rect.getContainer()
	},

	getExtent: function() {
		return this.rect.getExtent()
	},

	getBorderSize: function() {
		return this.rect.getBorderSize()
	},

	show: function() {
		this.rect.show()
	},

	hide: function() {
		this.rect.hide()
	},

	destroy: function() {
		this.rect.destroy();
		this.rect = null
	}
}


/**
 * Rectangle drag box selector
 */
function RectangleSelector(container, options) {
	this.container = container;

	this.options = {
		hideOnDragEnd: true
	}

	if(options) Object.extend(this.options, options);

	var position = Element.getStyle(this.container, 'position');
	if(position != 'absolute' && position != 'fixed') {
		this.container.style.position = 'relative'
	}

	Element.setUnselectable(this.container);

	this.rect = new Rectangle(options);
	this.rect.draw(this.container);
	this.rect.hide();

	if(this.container.setCapture) {
		this.src = this.container;
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

	//this.blurHandler = Event.addListener(window,'blur',this.cancelDrag,this);

	this.onMouseDownHandler = this.onMouseDown.bindToEvent(this);
	this.onMouseMoveHandler = this.onMouseMove.bindToEvent(this);
	this.onMouseUpHandler = this.onMouseUp.bindToEvent(this);

	// Event.coordOffset() doesn't account for border widths (yet), calculate manually once
	var borderTop = Browser.isOpera() ? 0 : (parseInt(Element.getStyle(this.container,'border-top-width')) || 0);
	var borderLeft = Browser.isOpera() ? 0 : (parseInt(Element.getStyle(this.container,'border-left-width')) || 0);
	this.container.borderTopWidth = borderTop;
	this.container.borderLeftWidth = borderLeft;
}

RectangleSelector.prototype = {
	disabled: true,
	dragging: false,
	moved: false,

	sPoint: { x:0, y:0 },

	/**
	 * Show drag box (while drawing for example)
	 */
	show: function() {
		this.rect.show()
	},

	/**
	 * Hide drag box (after drawing for example)
	 */
	hide: function() {
		this.rect.hide()
	},

	enable: function() {
		if(this.disabled) {
			Event.addListener(this.container, 'mousedown', this.onMouseDownHandler);
			this.disabled = false;
		}
	},

	disable: function() {
		if(!this.disabled) {
			if(this.dragging) {
				this.cancelDrag()
			}
			Event.removeListener(this.container, 'mousedown', this.onMouseDownHandler);
			this.disabled = true;
		}
	},

	enabled: function() {
		return !this.disabled
	},

	onMouseDown: function(e) {
		CustomEvent.trigger(this, 'mousedown', e);

		if(this.disabled || !Event.isLeftClick(e) || e.cancelDrag) {
			if(this.dragging) {
				this.cancelDrag();
			}
			return
		}

		var p = this.sPoint = Event.coordOffset(e, this.container);

		//if(this.options.extent && !this.options.extent.contains(p)) return;

		this.rect.setBox(p.x, p.y, 0, 0);

		Event.addListener(this.src,'mousemove', this.onMouseMoveHandler);
		Event.addListener(this.src,'mouseup', this.onMouseUpHandler);
		if(this.src.setCapture) this.src.setCapture();

		this.dragging = true;
		this.moved = false;
		this.show();

		Event.cancel(e);

		this.onDragStart()
	},

	onDragStart: function() {
		CustomEvent.trigger(this,'dragstart')
	},

	onMouseMove: function(e) {
		var p1 = Event.coordOffset(e, this.container), 
		    p0 = this.sPoint;

		p1.x -= this.container.borderLeftWidth;
		p1.y -= this.container.borderTopWidth;

		var x = Math.min(p0.x, p1.x),
		    y = Math.min(p0.y, p1.y),
		    w = Math.abs(p1.x - p0.x),
		    h = Math.abs(p1.y - p0.y);

		this.rect.setBox(x, y, w, h);

		this.moved = true;
		this.onDrag()
	},

	onDrag: function() {
		if(CustomEvent.hasListeners(this, 'drag'))
			CustomEvent.trigger(this, 'drag', this.getExtent())
	},

	onMouseUp: function(e) {
		CustomEvent.trigger(this, 'mouseup', e);
		this.cancelDrag();
		this.onDragEnd()
	},

	onDragEnd: function() {
		CustomEvent.trigger(this, 'dragend', this.getExtent())
	},

	cancelDrag: function() {
		if(!this.moved || this.options.hideOnDragEnd) this.hide();
		Event.removeListener(this.src, 'mousemove', this.onMouseMoveHandler);
		Event.removeListener(this.src, 'mouseup', this.onMouseUpHandler);
		if(this.src.releaseCapture) this.src.releaseCapture();
		this.dragging = false
	},

	destroy: function() {
		if(this.dragging) {
			this.cancelDrag()
		}
		this.rect.destroy();
		//Event.removeListener(window, 'blur', this.blurHandler);
		Event.removeListener(this.container, 'mousedown', this.onMouseDownHandler);
		this.container = this.rect = this.src = null
	},

	getExtent: function() {
		return this.rect.getExtent()
	}
}


/**
 * Overlay that sits over map to capture mousedown events. This is needed because
 * the map api simply stops mousedown events from bubbling up to the map container
 * (from the draggable container) thereby making it impossible to add mousedown
 * listeners to the map without hijacking the map container manually like this.
 * 
 * External assets required:
 *   - images/1x1.gif
 */
function GMapCover() {}

GMapCover.extend(GControl, {
	emptyImgSrc: (window.BASE_HREF || '') + 'images/1x1.gif',

	initialize: function(map) {
		var size = map.getSize();

		var coverStyle = {
			'position':   'absolute',
			'opacity':    1,
			'display':    'none',
			'overflow':   'hidden',
			'width':      size.width + 'px',
			'height':     size.height + 'px',
			'background': 'transparent url(' + this.emptyImgSrc + ')'
		};

		this.container = Element.create('div',{'class':'map-cover', 'style': coverStyle});

		/**
		 * Insert cover after DraggableObject container (first child). This is 
		 * semantically equivalent to map.getContainer().firstChild.nextSibling
		 * but is standardized for cross-browser differences wrt whitespace nodes.
		 */
		var slot = Element.nextSibling(Element.firstChild(map.getContainer()));
		map.getContainer().insertBefore(this.container, slot);

		map.getMapCover = K(this);

		return this.container;
	},

	getDefaultPosition: function() {
		return new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(0, 0));
	},

	getContainer: function() {
		return this.container
	},

	show: function() {
		this.container.style.display = ''
	},

	hide: function() {
		this.container.style.display = 'none'
	},

	setCursor: function(cursor) {
		this.savedCursor = Element.setCursor(this.container, cursor);
		return this.savedCursor;
	},

	resetCursor: function() {
		if(this.savedCursor) {
			Element.setCursor(this.container, this.savedCursor)
		}
	}
});


/**
 * Extends RectangleSelector to add behaviour for drawing a rectangle onto a
 * GMap2 instance instead of a just generic html element (as RectangleSelector 
 * does). It overrides the getExtent() method to proved "geo aware" extents 
 * instead of just pixel extents. This means the "drag" and "dragend" events
 * fired would pass the GLatLngBounds of the drawn rectangle instead of just
 * the pixel bounds.
 * 
 * You can use this class to implement a "zoom to box" feature or otherwise 
 * anytime you need to draw a rectangle on a google map and grab it's bounds.
 */
function GRectangleSelector(map, options) {
	this.map = map;
	if(!this.map.getMapCover) {
		this.map.addControl(new GMapCover());
	}
	RectangleSelector.prototype.constructor.call(this, this.map.getMapCover().getContainer(), options);
}

GRectangleSelector.extend(RectangleSelector, {

	/**
	 * Override the base behaviour of RectangleSelector of reporting the simple
	 * northwest and southeast pixel extent of the drawn rectangle to reporting
	 * a GLatLngBounds instead.
	 */
	getExtent: function() {
		var pxExtent = RectangleSelector.prototype.getExtent.call(this);
		// pxExtent is oriented to nw and se pixels, need to reorient to sw and ne
		var swGeo = this.map.fromContainerPixelToLatLng(new GPoint(pxExtent.minX, pxExtent.maxY));
		var neGeo = this.map.fromContainerPixelToLatLng(new GPoint(pxExtent.maxX, pxExtent.minY));
		return new GLatLngBounds(swGeo, neGeo);
	},

	/**
	 * Enable the drawing behaviour
	 */
	enable: function() {
		if(this.disabled) {
			RectangleSelector.prototype.enable.call(this);
			this.map.getMapCover().show();
		}
	},

	/**
	 * Remove the drawing behaviour
	 */
	disable: function() {
		if(!this.disabled) {
			RectangleSelector.prototype.disable.call(this);
			this.map.getMapCover().hide();
		}
	},

	/**
	 * Call when unloading the google maps api (if possible)
	 */
	destroy: function() {
		RectangleSelector.prototype.destroy.call(this);
		this.map = null; // break references
	}
});


/**
 * Adds behaviour to the map for zooming into the center of a GRectangleSelector.
 * 
 * External assets:
 *   - (IE): images/cursors/zoom-in.cur
 * 
 * @see http://googlemapsapi.blogspot.com/2007/06/dragzoomcontrol-v10-easier-zooming_06.html
 */
function GZoomBoxHandler(map, options) {
	this.selector = new GRectangleSelector(map, options);

	this.listeners = new EventListenerList(
		new CustomEventListener(this.selector, 'mousedown', function(e) {
			this.startPoint = { x:e.clientX, y:e.clientY }
		}, this),

		new CustomEventListener(this.selector, 'mouseup', function(e) {
			this.endPoint = { x:e.clientX, y:e.clientY };
			this.ctrlZoom = !(e.metaKey || e.ctrlKey)
		}, this),

		new CustomEventListener(this.selector, 'dragend', this.zoom, this)
	);

	this.map = map;
}

GZoomBoxHandler.prototype = {
	/**
	 * We interpret clicks as really small rectangles, in which case we just
	 * zoom in one level. Capturing the ctrl key on mouseup allows us to toggle 
	 * between two methods of zooming in:
	 * - ctrl key not pressed: zoom in on the point placing it in the center
	 *   of the viewport
	 * - ctrl key pressed: zoom in, leaving the click point in the same spot
	 *   in the viewport.
	 * 
	 * This feature is implemented relying on the (currently undocumented)
	 * 2nd and 3rd parameters to GMap2.zoomIn()
	 */
	ctrlZoom: false,

	/**
	 * The default cursors to use. Specify moz then ie then others (in that order).
	 */
	cursor: ['-moz-zoom-in', 'url(' + (window.BASE_HREF || '') + 'images/cursors/zoom-in.cur)', 'crosshair'],

	/**
	 * On drag end we want to check for really small rectangles and just zoom
	 * in one level (i.e. treat as click). To do this we listen for 'mousedown'
	 * and 'mouseup' and capture the x,y values of the event there.
	 */
	startPoint: null,
	endPoint: null,

	enable: function() {
		if(!this.selector.enabled()) {
			this.selector.enable();
			this.map.getMapCover().setCursor(this.cursor);
			CustomEvent.trigger(this, 'enable', true)
		}
	},

	disable: function() {
		if(this.selector.enabled()) {
			this.selector.disable();
			this.map.getMapCover().resetCursor();
			CustomEvent.trigger(this, 'enable', false)
		}
	},

	enabled: function() {
		return this.selector.enabled()
	},

	/**
	 * Call when unloading the api
	 */
	unload: function() {
		if(this.selector) {
			this.selector.destroy();
			this.listeners.clear();
			this.selector = this.listeners = this.map = null;
		}
	},

	zoom: function(geoExtent) {
		// for really small rectangles (less than 5 pixels in width and height) 
		// just zoom in one level (i.e. treat as click)
		var deltaX = Math.abs(this.endPoint.x - this.startPoint.x);
		var deltaY = Math.abs(this.endPoint.y - this.startPoint.y);
		if(deltaX < 5 && deltaY < 5) {
			this.map.zoomIn(geoExtent.getCenter(), this.ctrlZoom, !this.ctrlZoom);
			return
		}

		// zoom to the best fit of the extent drawn
		var z = this.map.getBoundsZoomLevel(geoExtent);

		// special case: best zoom level is the current zoom level
		if(z == this.map.getZoom()) {
			// Zoom the user in one level. Now although the best fit
			// mathematically is the current zoom level, a user might
			// mentally recover better from a zoomin that chops off their 
			// rectangle a bit than having the map not zoom at all.
			if(this.map.getBounds().containsBounds(geoExtent)) {
				this.map.zoomIn(geoExtent.getCenter(), true, false);
				return;
			}
		}

		this.map.setCenter(geoExtent.getCenter(), z);
	}
}


/**
 * Control for toggling a GZoomBoxHandler on and off. Just a crappy 
 * javascript hyperlink that sits over the map right now.
 * 
 * @param options are the same as for GZoomBoxHandler
 */
function GZoomControl(options) {
	this.options = options
}

GZoomControl.extend(GControl, {
	initialize: function(map) {
		this.handler = new GZoomBoxHandler(map, this.options);

		CustomEvent.addListener(this.handler, 'enable', this.onEnable, this);

		this.container = Element.create('a',{
			'href': '#',
			'class': 'zoom-box-ctrl',
			'style': 'position:absolute',
			'innerHTML': 'zoom...',
			'onclick': this.toggle.bindTo(this)
		});
	
		map.getContainer().appendChild(this.container);

		return this.container
	},

	getDefaultPosition: function() {
		return new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(100,25));
	},

	toggle: function() {
		if(this.handler.enabled()) {
			this.handler.disable()
		} else {
			this.handler.enable()
		}
	},

	onEnable: function(enabled) {
		if(enabled) {
			Element.addClass(this.container, 'on');
		} else {
			Element.removeClass(this.container, 'on');
		}
	}
});


/**
 * GMap2 aware Rectangle. Suitable for adding as an overlay onto a google map.
 * @param GLatLngBounds extent the rectangle represents
 * @param config object supporting the Rectangle interface
 * @see http://code.google.com/apis/maps/documentation/overlays.html#Custom_Overlays
 */
function GRectangleOverlay(extent, options) {
	this.extent = extent;

	this.options = {
		// @see http://code.google.com/apis/maps/documentation/reference.html#GMapPane
		mapPane: G_MAP_MAP_PANE,
		Rectangle: null
	}

	if(options) Object.extend(this.options, options);

	// allow dependency injection of a different Rectangle class to use
	if(this.options.Rectangle) {
		this.Rectangle = this.options.Rectangle
	}
}

GRectangleOverlay.extend(GOverlay, {

	Rectangle: Rectangle,

	// {{{ interface GOverlay

	initialize: function(map) {
		this.map = map;
		this.rect = new this.Rectangle(this.options);

		// re-adjust our GLatLngBounds extent when the underlying rectangle changes
		CustomEvent.addListener(this.rect, 'extentchange', this.recalc, this);

		// append rectangle to map
		this.rect.draw(this.map.getPane(this.options.mapPane));
	},

	redraw: function() {
		if(Browser.isIE()) { // whats fucking new

			var offsets;

			// If the filled rectangle gets too big (eg when zooming in) MSIE
			// shits itself. We work around this by drawing the smallest possible
			// rectangle in the viewport that simulates the larger rectangle.
			return function(force /* unused, we always check intersection */) {
				var mapExtent = this.map.getBounds();

				if(!this.extent.intersects(mapExtent))
					return;

				var ourSw = this.map.fromLatLngToDivPixel(this.extent.getSouthWest()),
				    ourNe = this.map.fromLatLngToDivPixel(this.extent.getNorthEast()),
				    mapSw = this.map.fromLatLngToDivPixel(mapExtent.getSouthWest()),
				    mapNe = this.map.fromLatLngToDivPixel(mapExtent.getNorthEast());

				if(!offsets) {
					var boxSize = this.rect.getBorderSize();
					offsets = {}
					offsets.x = Math.round(boxSize.width / 2);
					offsets.y = Math.round(boxSize.height / 2);
				}

				var minX = Math.max(ourSw.x, mapSw.x - offsets.x),
						minY = Math.max(ourNe.y, mapNe.y - offsets.y),
						maxX = Math.min(ourNe.x, mapNe.x + offsets.x),
						maxY = Math.min(ourSw.y, mapSw.y + offsets.y);

				if(maxX < minX || maxY < minY) {
					this.rect.hide();
					return;
				}

				this.rect.show();

				this.drawing = true;
				this.rect.setBox(minX, minY, maxX - minX, maxY - minY);
				this.drawing = false;
			} // redraw() for MSIE

		}

		else { // not MSIE

			return function(force) {
				if(!force) return;

				var sw = this.map.fromLatLngToDivPixel(this.extent.getSouthWest()),
				    ne = this.map.fromLatLngToDivPixel(this.extent.getNorthEast());

				var x = sw.x,
				    y = ne.y,
				    w = ne.x - sw.x,
				    h = sw.y - ne.y;

				if(w < 0 || h < 0) {
					this.rect.hide();
					return;
				}

				this.rect.show();

				this.drawing = true;
				this.rect.setBox(x, y, w, h);
				this.drawing = false;
			} // redraw() for !MSIE

		}
	}(),

	copy: function() {
		return new GRectangleOverlay(this.extent, this.options);
	},

	remove: function() {
		this.rect.destroy();
		this.rect = null;
		this.map = null;
	},

	// }}} interface GOverlay

	/**
	 * Synch up the rectangle div to our GLatLngBounds instance
	 */
	recalc: function() {
		if(this.drawing) return; // avoid one uneccesary calculation

		var c = this.rect.getContainer(),
		    minX = c.offsetLeft,
		    minY = c.offsetTop,
		    maxX = c.offsetLeft + c.offsetWidth,
		    maxY = c.offsetTop + c.offsetHeight,
		    swGeo = this.map.fromDivPixelToLatLng(new GPoint(minX, maxY)),
		    neGeo = this.map.fromDivPixelToLatLng(new GPoint(maxX, minY));

		this.setExtent(new GLatLngBounds(swGeo, neGeo));
	},

	/**
	 * @param GLatLngBounds
	 */
	setExtent: function(extent) {
		this.extent = extent;
		CustomEvent.trigger(this, 'extentchange', this.extent);
	}
})


/**
 * Draw an extent on a google map. Similar to a GRectangleSelector but stamps
 * the results of a draw onto the map as a GRectangleOverlay.
 * 
 * The same options as both GRectangleSelector and GRectangleOverlay are
 * applicable, including one additional:
 * - boolean disableDraggingOnDrawEnd true if you want drawing to be disabled after a rectangle 
 *   is drawn.
 * 
 * External assets:
 *   - images/cursors/crosshair.cur (falls back on built-in browser crosshair
 *     cursor if not available)
 * 
 * @param GMap2 map instance
 * @param config object literal (same as that for the GRectangleSelector and 
 *        GRectangleOverlay classes combined) 
 */
 function GExtentDrawer(map, options) {
	this.map = map;

	this.options = {
		disableDraggingOnDrawEnd: false
	};

	if(options) Object.extend(this.options, options);

	// add drawing functionality (provided by GRectangleSelector) onto the map
	this.selector = new GRectangleSelector(this.map, this.options);

	// when drawing is done we stamp the results onto the map as a Rectangle overlay
	this.onDragEnd = CustomEvent.addListener(this.selector, 'dragend', this.stamp, this);
}

GExtentDrawer.prototype = {
	cursor: ['url(' + (window.BASE_HREF || '') + 'images/cursors/crosshair.cur)', 'crosshair'],

	gRectOverlay: null,

	enable: function() {
		if(!this.selector.enabled()) {
			this.selector.enable();
			this.map.getMapCover().setCursor(this.cursor);
			CustomEvent.trigger(this, 'enable', true)
		}
	},

	disable: function() {
		if(this.selector.enabled()) {
			this.selector.disable()
			this.map.getMapCover().resetCursor();
			CustomEvent.trigger(this, 'enable', false)
		}
	},

	enabled: function() {
		return this.selector.enabled()
	},

	/**
	 * Callback for the "dragend" event of the GRectangleSelector. Recieves the
	 * GLatLngBounds of the drawn rectangle. We create a GRectangleOverlay 
	 * suitable for addOverlay()ing onto a google map.
	 */
	stamp: function(geoExtent) {
		if(this.options.disableDraggingOnDrawEnd) {
			this.disable()
		}

		if(!this.gRectOverlay) {
			this.gRectOverlay = new GRectangleOverlay(geoExtent, this.options);
			this.map.addOverlay(this.gRectOverlay);

			// reset our state if map.clearOverlays() is called
			var that = this;
			GEvent.addListener(this.map, 'clearoverlays', function(){ that.gRectOverlay = null });

			// when the GLatLngBounds for the rectangle overlay changes for  
			// whatever reason, we want to notify *our* listeners
			CustomEvent.passThrough(this.gRectOverlay, 'extentchange', this);

			// notify listeners of the initial extent
			CustomEvent.trigger(this, 'extentchange', geoExtent);
		}

		else {
			this.gRectOverlay.setExtent(geoExtent);
			this.gRectOverlay.redraw(true);
		}
	},

	/**
	 * Call when unloading the google maps api if possible
	 */
	unload: function() {
		if(this.selector) {
			this.selector.destroy();
			CustomEvent.removeListener(this.selector, this.onDragEnd);
			this.selector = this.onDragEnd = null;
		}
		if(this.map && this.gRectOverlay) {
			this.map.removeOverlay(this.gRectOverlay);
		}
		this.map = this.gRectOverlay = null;
	}
}

// handler = new GRectangleSelector(map, { fill:{ color:'#8cc63f', opacity:0.15 } });
// handler.enable()
