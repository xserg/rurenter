Browser = function(uaString) {
	var
		type = -1, os = null, revision = 0,	version = -1,
		browsers = ["opera","msie","safari","firefox","mozilla"],
		oss = ["x11;","macintosh","windows"],
		userAgent = uaString.toLowerCase();

	for(var i = 0; i < browsers.length; ++i) {
		var c = browsers[i];
		if(userAgent.indexOf(c) != -1) {
			type = i;
			var d = new RegExp(c+"[ /]?([0-9]+(.[0-9]+)?)");
			if(d.exec(userAgent) != null) {
				version = parseFloat(RegExp.$1)
			}
			break
		}
	}

	for(var i = 0; i < oss.length; ++i) {
		var c = oss[i];
		if(userAgent.indexOf(c) != -1) {
			os = i;
			break
		}
	}

	if(type == 4 || type == 3) {
		if(/\brv:\s*(\d+\.\d+)/.exec(userAgent)) {
			revision = parseFloat(RegExp.$1)
		}
	}

	return {
		os: os,
		type: type,
		version: version,
		revision: revision,

		isIE: function() {return type == 1},
		isIE7: function() {return type == 1 && version >= 7 && version < 8},
		isSafari: function() {return type == 2},
		isMoz: function() {return type == 3 || type == 4},
		isOpera: function() {return type == 0},
		isMozCore: function() {return type == 4 && revision<1.7},
		isSafari: function() {return type == 2}
	}
}(navigator.userAgent);

/**
 * Utility functions
 */

/**
 * Standard "no operation" function for javascript
 */
function nop() {return false}

/**
 * Identity combinator
 */
function I(x) {return x}

/**
 * K combinator
 */
function K(x) {
	return function(y) {
		return x
	}
}

/*
	forEach, version 1.0
	Copyright 2006, Dean Edwards
	License: http://www.opensource.org/licenses/mit-license.php
*/

// array-like enumeration
if (!Array.forEach) { // mozilla already supports this
	Array.forEach = function(array, callback, scope) {
		for (var i = 0, len = array.length; i < len; ++i) {
			callback.call(scope, array[i], i, array);
		}
	};
}

// generic enumeration
Function.prototype.forEach = function(object, callback, scope) {
	for (var key in object) {
		if (typeof this.prototype[key] == "undefined") {
			callback.call(scope, object[key], key, object);
		}
	}
};

// character enumeration
String.forEach = function(string, callback, scope) {
	Array.forEach(string.split(""), function(chr, index) {
		callback.call(scope, chr, index, string);
	});
};

// globally resolve forEach enumeration
var forEach = function(object, callback, scope) {
	if (object) {
		var resolve = Object; // default
		if (object instanceof Function) {
			// functions have a "length" property
			resolve = Function;
		} else if (object.forEach instanceof Function) {
			// the object implements a custom forEach method so use that
			object.forEach(callback, scope);
			return;
		} else if (typeof object == "string") {
			// the object is a string
			resolve = String;
		} else if (typeof object.length == "number") {
			// the object is array-like
			resolve = Array;
		}
		resolve.forEach(object, callback, scope);
	}
};


/**
 * eg
 * var a = [1,2,3,4];
 * a.push(5,6,7,8);
 * a -> [1,2,3,4,5,6,7,8]
 * a.push([9,10,11,12]);
 * a -> [1,2,3,4,5,6,7,8,[9,10,11,12]]
 */
if(!Array.prototype.push) {
	Array.prototype.push = function() {
		for(var i = 0; i < arguments.length; ++i) {
			this[this.length] = arguments[i]
		}
	}
}

/**
 * eg
 * [1,2,3,3,3,3,3,4].remove(3) -> 5 (items deleted)
 * a = [1,2,3,3,3,3,3,4];
 * a.remove(3);
 * a -> [1,2,4]
 */
if(!Array.prototype.remove) {
	Array.prototype.remove = function(item) {
		var c = 0;
		for(var i = 0; i < this.length; ++i) {
			if(this[i] == item) {
				this.splice(i--,1);
				c++
			}
		}
		return c
	}
}

/**
 * eg
 * [1,2,3,4].indexOf(3) -> 2
 * [1,2,3,4].indexOf(5) -> -1
 */
if(!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(item) {
		for(var i = 0; i < this.length; ++i) {
			if(this[i] == item) {
				return i;
			}
		}
		return -1
	}
}

/**
 * Non-intelligent string capitalization method
 * eg
 * "start".capitalize() -> "Start"
 * "DisplayColor".capitalize() -> "Displaycolor"
 * "DisplayColor".capitalize() -> "Displaycolor"
 * "The Big Brown Bear".capitalize() -> "The big brown bear"
 */
if(!String.prototype.capitalize) {
	String.prototype.capitalize = function() {
		return this.charAt(0).toUpperCase() + this.substr(1).toLowerCase()
	}
}

/**
 * eg
 * "background-color".camelize() -> "backgroundColor"
 * "display".camelize() -> "display"
 * "DisplayColor".camelize() -> "DisplayColor"
 */
if(!String.prototype.camelize) {
	String.prototype.camelize = function() {
		return this.replace(/-(\w)/g, function(a,b) {
			return ("" + b).toUpperCase()
		})
	}
}

/**
 * eg
 * "     hello     ".trim() -> "hello"
 */
if(!String.prototype.trim) {
	String.prototype.trim = function() {
		return this.replace(/^\s+/, '').replace(/\s+$/, '')
	}
}

/**
 * eg
 * "     hello     ".ltrim() -> "hello     "
 */
if(!String.prototype.ltrim) {
	String.prototype.ltrim = function() {
		return this.replace(/^\s+/, '')
	}
}

/**
 * eg
 * "     hello     ".rtrim() -> "     hello"
 */
if(!String.prototype.rtrim) {
	String.prototype.rtrim = function() {
		return this.replace(/\s+$/, '')
	}
}


/**
 * Bind a function to run as a method on an object
 */
Function.prototype.bindTo = function() {
	var callback = this, args = arguments;
	return function() {
		var slice = Array.prototype.slice;
		return callback.apply(args[0],slice.call(args,1).concat(slice.call(arguments)))
	};
};

Function.prototype.bindToEvent = function(object) {
	var callback = this;
	return function(evt) {
		callback.call(object, evt || window.event)
	}
}


/**
 * Allow easier inheritance for javascript
 */
Function.prototype.extend = function(superclass, overrides) {
	var f = function() {};
	f.prototype = superclass.prototype;
	this.prototype = new f();
	this.prototype.constructor = this;
	this.superclass = superclass.prototype;
	if(superclass.prototype.constructor == Object.prototype.constructor) {
		superclass.prototype.constructor = superclass;
	}

	if(overrides) {
		Object.extend(this.prototype, overrides)
	}

	return this;
};

/**
 * Returns a function that runs the passed function before this function.
 * The resulting function returns the results of the original function.
 * The passed function is called with the parameters of the original function.
 * @param {Function} fn The function to call before the original
 * @param {Object} scope (optional) The scope of the passed function 
 * (Defaults to scope of original function or window)
 * @return {Function} The new function
 */
Function.prototype.intercept = function(fn, scope) {
	if(typeof fn != "function") { return this }

	var method = this;

	return function() {
		if(fn.apply(scope || this, arguments) === false) {
			return
		}
		return method.apply(this, arguments)
	}
};

/**
 * eg
 * (1786342).toHexStr() -> "001b41e6"
 */
Number.prototype.toHexStr = function() {
	var a = "";
	var b;
	for(var c = 7; c >= 0; --c) {
		b = this >>> c * 4 & 15;
		a += b.toString(16)
	}
	return a
};

Number.prototype.approxEquals = function(num) {
	return Math.abs(this-num) <= 1.0E-9
};

/**
 * Ensure a number falls within a lower and upper bound
 * eg
 * (3).limitBy(0,5) -> 3
 * (3).limitBy(5,10) -> 5
 * (3).limitBy(-99,2) -> 2
 * (3).limitBy(10,-10) -> 3 (order doesn't matter)
 */
Number.prototype.constrain = function(lo, hi) {
	lo = lo || 0;
	hi = hi || 0;
	if(lo > hi) {
		var tmp = lo;
		lo = hi;
		hi = tmp;
	}
	return Math.min(Math.max(this,lo),hi);
}

/**
 * returns int with st/nd/rd/th suffix attached
 * eg
 * (3).toOrdinal() -> "3rd"
 */ 
Number.prototype.toOrdinal = function(){
	var m = this % 10;
	return (this + ["th","st","nd","rd"][(m<=3 && Math.floor(this%100/10)!=1)*m]);
}

/**
 * Escaping regular expression characters in JavaScript
 * http://simonwillison.net/2006/Jan/20/escape/
 */
RegExp.escape = (function() {
  var specials = [
    '/', '.', '*', '+', '?', '|',
    '(', ')', '[', ']', '{', '}', '\\'
  ];

  var sRE = new RegExp('(\\' + specials.join('|\\') + ')', 'g');

  return function(text) {
    return text.replace(sRE, '\\$1');
  }
})();

if(!window.$) {
	function $(element) {
		return typeof element == 'string' ? document.getElementById(element) : element
	}
}

/**
 * Copy properties from one or more objects into destination object
 * eg
 * var obj = {a:1,b:2,c:3};
 * Object.extend(obj,{b:69},{c:42});
 * obj.a -> 1
 * obj.b -> 69
 * obj.c -> 42
 */
Object.extend = function(destination) {
	for(var i = 1; i < arguments.length; ++i) {
		var source = arguments[i];
		for(property in source) {
			destination[property] = source[property];
		}
	}
	return destination;
}

Object.merge = function(destination) {
	for(var i = 1; i < arguments.length; ++i) {
		var source = arguments[i];
		for(var k in source) {
			if(source[k] && typeof source[k] == 'object' && source[k].constructor == Object) {
				if(typeof destination[k] == 'undefined') {
					destination[k] = {};
				}
				Object.merge(destination[k], source[k])
			} else if(typeof source[k] != 'undefined') {
				destination[k] = source[k]
			}
		}
	}

	return destination
}

/**
 * Turns simple object literal (key => value pairs) into key=value&key2=value2
 * Nesting not allowed.
 */
Object.toQueryString = function(obj) {
	var parts = [];

	for(var key in obj) {
		var value = obj[key], type = (value === null) ? 'undefined' : typeof value;

		switch(type) {
			case 'function':
			case 'undefined':
			case 'object': break;

			default:
				parts.push(encodeURIComponent(key) + '=' + encodeURIComponent(value))
				break;
		}
	}

	return parts.join('&');
}

/**
 * Parses a string in the form key=value&key2=value2 and returns an equivalent 
 * object literal: { key: 'value', key2: 'value2'}
 */
Object.fromQueryString = function(str) {
	var pairs = str.match(/^\??(.*)$/)[1].split('&'), obj = {};
	for(var i = 0, len = pairs.length; i < len; ++i) {
		var pair = pairs[i].split('=');
		obj[pair[0]] = pair[1];
	}
	return obj;
}

/**
 * window opening methods
 */
window.openCentered = function(url, name, width, height, options) {
	if(!width) {
		if(typeof window.screenX == 'number' && typeof window.innerWidth == 'number') {
			width = window.innerWidth * .68
		} else if(typeof window.screenTop == 'number' && typeof document.documentElement.offsetHeight == 'number') {
			width = document.documentElement.offsetWidth * .68
		} else {
			width = 500
		}
	}

	if(!height) {
		if(typeof window.screenX == 'number' && typeof window.innerHeight == 'number') {
			height = window.innerHeight * .68
		} else if(typeof window.screenTop == "number" && typeof document.documentElement.offsetHeight == "number") {
			height = document.documentElement.offsetHeight * .68
		} else {
			height = 250
		}
	}

	var arrOptions = [];
	arrOptions.push('width='+width);
	arrOptions.push('innerWidth='+width);
	arrOptions.push('height='+height);
	arrOptions.push('innerHeight='+height);

	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;

		var xc = (aw - width) / 2;
		var yc = (ah - height) / 2;

		arrOptions.push('left='+xc);
		arrOptions.push('screenX='+xc);
		arrOptions.push('top='+yc);
		arrOptions.push('screenY='+yc);
	}

	if(options) {
		if(typeof options == 'string') {
			arrOptions.push(options)
		} else {
			arrOptions = arrOptions.concat(options)
		}
	}

	return window.open(url, name, arrOptions.join(','));
}

/**
 * Partial argument list: url, name, options
 * Full argument list: url, name, width, height, options
 */
window.openFocused = function(url, name) {
	var width, height, options; // additional parameters

	if(!arguments.callee.opened) {
		arguments.callee.opened = {}
	}

	if(arguments.length <= 3) {
		options = arguments[2]
	} else {
		// assume full argument list
		width = arguments[2];
		height = arguments[3];
		options = arguments[4]
	}

	var win;
	if(name == undefined || name == '_blank') {
		// do it fresh everytime
		win = null;
	} else {
		var oWin = arguments.callee.opened[name];
		if(!oWin) {
			oWin = {
				url: url,
				win: null
			};
			arguments.callee.opened[name] = oWin
		}
		win = oWin.win;
	}

	if(!win || win.closed) {
		if(width && height) {
			win = window.openCentered(url, name, width, height, options)
		} else {
			if(options instanceof Array) {
				options = options.join(',')
			}
			if(options && options.trim().length) {
				win = window.open(url, name, options)
			} else {
				win = window.open(url, name)
			}
		}
	} else {
		win.location.href = url;
		win.focus()
	}

	if(oWin) oWin.win = win;

	return win;
}

window.openFrom = function(link, width, height, options) {
	var href = link.getAttribute('href');

	if(!href) {
		throw "Missing href attribute on target element"
	}

	return window.openFocused.apply(window,[href, link.getAttribute('target')].concat(Array.prototype.slice.call(arguments,1)))
}

/**
 * Cookie read/write class.
 *
 * Modified from "JavaScript: The Definitive Guide"
 * Original author: David Flanagan
 *
 * Modified to encorporate auto typecasting of cookie vars from
 * Jack Slocum's Ext (1.0) set of state classes (/state/State.js).
 *
 * Sample Usage:
 * <code>
 * // Create the cookie we'll use to save state for this web page.
 * // Since we're using the default path, this cookie will be accessible
 * // to all web pages in the same directory as this file or "below" it.
 * // Therefore, it should have a name that is unique among those pages.
 * // Not that we set the expiration to 10 days in the future.
 * var visitordata = new Cookie({expireIn: 240});
 *
 * // First, try to read data stored in the cookie.  If the cookie is not
 * // defined, or if it doesn't contain the data we need, then query the
 * // user for that data.
 * if (!visitordata.load('name','color')) {
 * 	visitordata.name = prompt("What is your name:", "");
 * 	visitordata.color = prompt("What is your favorite color:", "");
 * }
 * 
 * // Keep track of how many times this user has visited the page:
 * if (visitordata.visits == null) visitordata.visits = 0;
 * visitordata.visits++;
 * 
 * // Store the cookie values, even if they were already stored, so that the 
 * // expiration date will be reset to 10 days from this most recent visit.
 * // Also, store them again to save the updated visits state variable.
 * visitordata.save();
 * </code>
 *
 * Constructor.  Creates a cookie object for the specified document, with the 
 * specified attributes.
 *
 * Parameter: options hash (object literal) with the following key/values (all are
 *            optional):
 *   expireIn: an optional number that specifies the number of hours from now
 *              that the cookie should expire.
 *   path: an optional string that specifies the cookie path attribute.
 *   domain: an optional string that specifies the cookie domain attribute.
 *   secure: an optional boolean value that, if true, requests a secure cookie.
 *   document: the Document object that the cookie is stored for.
 */
function Cookie(options) {
	options = options || {};

	this.$cfg = {
		expires:  options.expires || null,
		expireIn:	options.expireIn || options.expirein || 0,
		path:     options.path || null,
		domain:   options.domain || null,
		secure:   !!options.secure,
		document: options.document || document
	}
}

// UTILITY

Object.extend(Cookie, {

	/**
	 * This is the utility method of the class that handles reading
	 * from the magic document.cookie property searching for a single
	 * name/value pair in the cookie string matching parameter 1.
	 * Note that the value found is not unescaped or decoded after
	 * reading; be sure to handle decoding the return value (if
	 * neccessary).
	 * Returns string on success, false on failure.
	 */
	read: function() {
		var searchRE = {};

		return function(name, ops) {
			ops = ops || {};

			// check cache for search RE based on name
			if(!searchRE[name]) {
				// create and cache
				searchRE[name] = new RegExp("\\b" + RegExp.escape(name) + "=(.*?);")
			}

			// Find the first cookie matching parameter 1
			var cookie = (ops.document || document).cookie + ";",
					search = searchRE[name],
					matches = search.exec(cookie);

			return matches && matches[1] ? matches[1] : false
		}
	}(),

	/**
	 * This is the utility method of the class that handles writing
	 * to the magic document.cookie property with single name/value pair. 
	 * Note that name/value pairs are not escaped or encoded before writing; 
	 * they are written "raw", so be sure to handle encoding beforehand
	 * (if neccessary):
	 *
	 * e.g. this doesn't work:
	 * Cookie.write('mycookie', "John's name has a semi;colon in it");
	 * alert(Cookie.read('mycookie')); // -> "John's name has a semi"
	 *
	 * Fix:
	 * Cookie.write('mycookie', escape("John's name has a semi;colon in it"));
	 */
	write: function(name, value, ops) {
		ops = ops || {};

		if(!(ops.expires instanceof Date) && ops.expireIn) {
			// assumes ops.expireIn specified in hours
			ops.expires = new Date(new Date().getTime() + ops.expireIn * 3600000);
		}

		(ops.document || document).cookie = name + "=" + value +
			 (ops.expires ? ("; expires=" + ops.expires.toGMTString()) : "") +
			 (ops.path ? ("; path=" + ops.path) : "") +
			 (ops.domain ? ("; domain=" + ops.domain) : "") +
			 (ops.secure ? "; secure" : "");
	},

	/**
	 * This is the utility method of the class that handles clearing
	 * a cookie named by the first parameter.
	 */
	erase: function(name, ops) {
		ops = ops || {};

		(ops.document || document).cookie = name + "=; expires=Fri, 02-Jan-1970 00:00:00 GMT" +
			 (ops.path ? ("; path=" + ops.path) : "") +
			 (ops.domain ? ("; domain=" + ops.domain) : "") +
			 (ops.secure ? "; secure" : "");
	}
});

// CLASS DEF

Cookie.prototype = function() {

	var namePrefix = "ys-",
	    loadRE = /^\s?ys-(.*?)=(.*?)$/;

	return {
		/**
		 * Read the document.cookie property and set properties on this
		 * instance
		 */
		load: function(/* *names */) {

			var found = false;

			if(arguments.length) {
				for(var i = 0, len = arguments.length; i < len; ++i) {
					var name = arguments[i], value = this.read(namePrefix + name);
					if(value !== false) {
						this[name] = this.decodeValue(value);
						found = true
					}
				}
			} else {
				// Find all the cookies this class could have written
				// (they'll all have the prefix namePrefix)
				var cookie = this.$cfg.document.cookie,
						parts = cookie.split(";"),
						found = false,
						matches;

				for(var i = 0, len = parts.length; i < len; ++i) {
					if(matches = loadRE.exec(parts[i])) {
						this[matches[1]] = this.decodeValue(matches[2]);
						found = true;
					}
				}
			}

			return found;
		},

		/**
		 * Write out instance property/values to separate cookies.
		 */
		save: function() {
			// Create one cookie per key/value pair
			this.forEach(function(value, name) {
				if(value == null) {
					this.erase(namePrefix + name);
				} else {
					this.write(namePrefix + name, this.encodeValue(value)) 
				}
			});
		},

		/**
		 * Erase all these cookies.
		 * @param boolean persist. If true, instance variables will not be cleared.
		 *                         Default is false.
		 */
		clear: function(persist) {
			this.forEach(function(value, name) {
				this.erase(namePrefix + name);
				if(!persist) {
					delete this[name]
				}
			});
		},

		/**
		 * Shortcut for checking undefined cookie vars. 
		 * Note that cookie variables set to null will return true (i.e. they are 
		 * "defined" wrt this class).
		 */
		defined: function(name) {
			return typeof this[name] != 'undefined'
		},

		/**
		 * iterate over each cookie name/value pair for this instance, calling the
		 * callback given in parameter one.
		 */
		forEach: function(callback, scope) {
			for(var name in this) {
				// ignore properties with names that begin with '$' and also methods
				if(name.charAt(0) == '$' || typeof this[name] == 'function') {
					continue;
				}

				callback.call(scope || this, this[name], name, this);
			}
		},

		// UTILITY

		read: function(name) {
			return Cookie.read(name, this.$cfg)
		},

		write: function(name, value) {
			return Cookie.write(name, value, this.$cfg)
		},

		erase: function(name) {
			return Cookie.erase(name, this.$cfg)
		},

		// PROTECTED

		/**
		 * Encode a cookie value with a reference to the var type and
		 * then escape()ing it.
		 */
		encodeValue: function(value) {
			var encoded = null;

			switch(typeof value) {
				case "function":
				case "undefined":
					break;

				case "number":
					encoded = "n|" + value;
					break;

				case "boolean":
					encoded = "b|" + (value ? "1" : "0");
					break;

				case "object":
					if(value === null) break;

					var flat = [];

					if(value instanceof Array) {
						for(var i = 0, len = value.length; i < len; ++i) {
							flat.push(this.encodeValue(value[i]));
						}

						encoded = "a|" + flat.join(",");
					}

					else if(value instanceof Date) {
						encoded = "d|" + escape(value.toGMTString());
					}

					else {
						for(var key in value){
							if(typeof value[key] != "function") {
								// TODO: avoid double escape here
								flat.push(escape(key + "=" + this.encodeValue(value[key])));
							}
						}

						encoded = "o|" + flat.join(",");
					}

					break;

				case "string":
				default:
					encoded = "s|" + escape(value);
					break;
			}

			return encoded;
		},

		$decodeRE: /^(n|b|a|d|o|s)\|(.*)$/,

		/**
		 * Decode and typecast an encoded cookie value
		 */
		decodeValue: function(encodedValue) {

			var matches = this.$decodeRE.exec(encodedValue);

			if(!matches || !matches[1]) return; // non state cookie

			var type = matches[1];
			var value = matches[2];

			switch(type) {
				case "n":
					return Number(value);

				case "b":
					return (value == "1");

				case "d":
					return new Date(Date.parse(unescape(value)));

				case "a":
					var all = [];
					var values = value.split(",");
					for(var i = 0, len = values.length; i < len; ++i) {
						all.push(this.decodeValue(values[i]));
					}
					return all;

				case "o":
					var all = {};
					var values = value.split(",");
					for(var i = 0, len = values.length; i < len; i++) {
						var kv = unescape(values[i]).split("=");
						if(kv.length != 2) continue;
						all[kv[0]] = this.decodeValue(kv[1]);
					}
					return all;

				default:
					return unescape(value);
			}
		} 

	} // end of prototype declaration 
}();

/**
 * Cookie class extension that allows state to be saved in one
 * cookie, rather than one cookie per state variable.
 *
 * Modified from "JavaScript: The Definitive Guide"
 * Original author: David Flanagan
 */
function NamespacedCookie(namespace, options) {
	this.$namespace = namespace;
	Cookie.call(this, options);
}

NamespacedCookie.extend(Cookie, {
	load: function(names) {
		if(names && NamespacedCookie.superclass.load.call(this, names)) {
			NamespacedCookie.superclass.clear.call(this, true)
		}

		// Find the cookie named this.$namespace
		var values = this.read(this.$namespace);

		if(!values) { return false }

		var parts = values.split('&');

		for(var i = 0, len = parts.length; i < len; ++i) {
			var kv = parts[i].split(':');
			if(kv.length != 2) continue;
			this[kv[0]] = this.decodeValue(kv[1]);
		}

		return true;
	},

	save: function() {
		// Create one cookie named this.$namespace and stuff all the
		// key/value pairs found in there.
		var pairs = [];

		this.forEach(function(value, name) {
			if(value != null) {
				pairs.push(name + ':' + this.encodeValue(value))
			}
		});

		if(pairs.length) {
			this.write(this.$namespace, pairs.join('&'));
			return true
		} else {
			return false
		}
	},

	clear: function(persist) {
		this.erase(this.$namespace);
		if(!persist) {
			this.forEach(function(value, name) { delete this[name] });
		}
	}
});

/**
 * DOM element manipulation + introspection
 */
if (!window.Element) {
  var Element = {}
}

Object.extend(Element, 
{
	/* internal use only */
	forEach: function(element, callback, args) {
		if(element instanceof Array) {
			args = Array.prototype.slice.call(args,1); // args.shift()
			for(var i=0, len=element.length; i < len; ++i) {
				var el = element[i];
				callback.apply(Element, [el].concat(args))
			}
		} else {
			callback.apply(Element, args)
		}
	},

	getOwner: function(element) {
		return element && element.ownerDocument ? element.ownerDocument : document
	},

	hide: function(element) {
		try { $(element).style.display = 'none' } catch(e){}
	},

	show: function(element) {
		try { $(element).style.display = '' } catch(e){}
	},

	remove: function(element) {
		return element.parentNode.removeChild(element)
	},

	/**
	 * Normalize innerHTML = '...' to account for browser differences in 
	 * embedded <script> tag handling.
	 *
	 * @param element     DOM element to update innerHTML of
	 * @param html        string of html to insert
	 * @param evalScripts boolean indicating whether or not to evaluate any
	 *                    <script></script> tags in the html. Defaults to true
	 */
	update: function() {
		var scriptFragment = '(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)';
		var matchAll = new RegExp(scriptFragment, 'img');
		var matchOne = new RegExp(scriptFragment, 'im');

		var globalEval = 
			window.execScript || 
			(
				Browser.isSafari() ?
					function(s){ window.setTimeout(s,0) } : // asynch in safari
					function(s){ eval.call(window,s) }      // opera needs eval wrapped
			);

		function stripScripts(htmlString) {
			return htmlString.replace(matchAll, '');
		}

		function extractScripts(htmlString) {
			var scripts = [];
			var scriptTags = htmlString.match(matchAll);

			if(scriptTags) {
				for(var i = 0; i < scriptTags.length; ++i) {
					var matches = scriptTags[i].match(matchOne);
					if(matches) {
						scripts[scripts.length] = matches[1]
					}
				}
			}

			return scripts;
		}

		return function(element, html, evalScripts) {
			evalScripts = (evalScripts == undefined) ? true : !!evalScripts;

			element.innerHTML = stripScripts(html);

			if(evalScripts) {
				var scripts = extractScripts(html);
				if(scripts.length) {
					setTimeout(function() {
						for(var i = 0; i < scripts.length; ++i)
							globalEval(scripts[i])
					}, 0);
				}
			}
		}
	}(),

	/**
	 * Clean whitespace of an element. If the second parameter deep is true,
	 * the elements children are cleaned as well (recursively).
	 */
	clean: function(element, deep) {
		var node = element.firstChild,
				reNopace = /\S/;

		while(node) {
			var nextNode = node.nextSibling;
			if(node.nodeType == 1 && deep) {
				Element.clean(node, true)
			} else if(node.nodeType == 3 && !reNopace.test(node.nodeValue)) {
				element.removeChild(node)
			}
			node = nextNode;
		}
	},

	/**
	 * Gets the fist child, skipping text nodes
	 * @return {HTMLElement} The first child or null
   */
	firstChild: function(element) {
		var n = element.firstChild;
		while(n && n.nodeType != 1) {
			n = n.nextSibling
		}
		return n
	},

	/**
	 * Gets the next sibling, skipping text nodes
	 * @return {HTMLElement} The next sibling or null
   */
	nextSibling : function(element) {
		var n = element.nextSibling;
		while(n && n.nodeType != 1) {
			n = n.nextSibling
		}
		return n
	},

	/**
	 * Gets the previous sibling, skipping text nodes
	 * @return {HTMLElement} The previous sibling or null
   */
	prevSibling : function(element) {
		var n = element.previousSibling;
		while(n && n.nodeType != 1) {
			n = n.previousSibling
		}
		return n;
	},

	/**
	 * Add a CSS class to the element.
	 * @param {String/Array} className The CSS class to add or an array of classes
	 */
	addClass : function(element, className, force /* internal use only */) {
		if(className instanceof Array) {
			for(var i = 0, len = className.length; i < len; ++i)
				Element.addClass(element, className[i], force)
		} else {
			if(force || !Element.hasClass(element, className))
				element.className += (element.className ? " " : "") + className
		}
	},

	/**
	 * Adds a CSS class to this element and removes the class from all siblings
	 * @param {String} className The className to add or array of classes
	 */
	addSingletonClass: function(element, className) {
		var siblings = element.parentNode.childNodes;
		for(var i = 0, len = siblings.length; i < len; ++i) {
			var s = siblings[i];
			if(s.nodeType == 1)
				Element.removeClass(s, className)
		}

		Element.addClass(element, className, /* force= */true);
	},

	/**
	 * Removes a CSS class from the element.
	 * @param {String/Array} className The CSS class to remove or an array of classes
	 */
	removeClass : function(element, className) {
		if(!className)
			element.className = ""
		else
			Element.replaceClass(element, className, "")
	},

	/**
	 * Toggles (adds or removes) the passed class.
	 * @param {String} className the CSS class to toggle
	 */
	toggleClass: function(element, className) {
		if(Element.hasClass(element, className)) {
			Element.removeClass(element, className)
		} else {
			Element.addClass(element, className)
		}
	},

	/**
	 * Checks if a CSS class is in use by the element.
	 * @param {String} className The CSS class to check
	 * @return {Boolean} true or false
	 */
	hasClass: function(element, className) {
		// don't test with regex if className property empty
		return element.className.length && new RegExp("(^|\\s)" + className + "(\\s|$)").test(element.className)
	},

	/**
	 * Replaces a CSS class on the element with another.
	 * @param {String} className The CSS class to replace or array of class names.
	 *                 If string, newClassName must also be a string otherwise behaviour
	 *                 is undefined.
	 * @param {String} newClassName The replacement CSS class or array of class names.
	 *                 Must be string if className is a string.
	 */
	replaceClass: function() {

		// Utility function: replace "search" with "replace" in element.className. 
		// "search" has to fully match one of the space separated strings in className.
		function replaceClass(element, search, replace) {
			if(element.className) {

				if(replace && new RegExp("(^|\\s)" + replace + "(\\s|$)").test(element.className)) {
					replace = ''
				}

				var subject = element.className,
				    searchRE = new RegExp("(^|\\s+)" + search + "(\\s+|$)", "g"),
				    m = searchRE.exec(subject);

				if(m) {
					// swallow unneeded/extra spaces
					if(replace)
						replace = (m[1] ? " " : "") + replace + (m[2] ? " " : "")
					else
						replace = m[1] && m[2] ? " " : ""

					element.className = subject.replace(searchRE, replace)
				}
			}
		}

		return function(element, className, newClassName) {
			if(className instanceof Array) {
				if(newClassName instanceof Array) {
					// replace value for value until one of the arrays is cleared
					while(className.length && newClassName.length)
						replaceClass(element, className.shift(), newClassName.shift())
				}

				else {
					// replace all classNames with newClassName
					while(className.length) {
						replaceClass(element, className.shift(), newClassName)
					}
				}
			}

			else {
				// straight replace className with newClassName
				replaceClass(element, className, newClassName)
			}
		}
	}(),

	/**
	 * Set the cursor for an element. Takes an element whose cursor you want to set, 
	 * and a second argument of one of:
	 * - null/''/0/false	reverts the cursor to the browser's default for that element
	 *                  	(same as if supplying 'auto')
	 * - string         	tries to set the cursor to the string given
	 * - array          	Loops through, trying to set the cursor
	 *                  	to each value, stopping on success
	 * @returns The previous cursor of the element
	 */
	setCursor: function() {
		function $cursor(el, c) {
			try {
				el.style.cursor = c
			} catch(e) {
				if(c == 'pointer') {
					c = 'hand';
					el.style.cursor = c
				}
			}
			return el.style.cursor == c
		}

		return function(element) {
			var oldCursor = Element.getStyle(element, 'cursor');

			if(arguments[1]) {
				if(arguments[1] instanceof Array) {
					var list = arguments[1];
					for(var i = 0; i < list.length; ++i) {
						var c = list[i];
						if(c && /^url\([^)]+\)/i.test(c) && 
							 $cursor(element, list.slice(i).join(', ')) || 
							 $cursor(element, c))
						{
							break
						}
					}
				} else {
					$cursor(element, arguments[1])
				}
			} else {
				element.style.cursor = 'auto'
			}

			return oldCursor
		}
	}(),

	create: function(type, attributes, parentElement) {
		type = type.toLowerCase();
		var element = Element.getOwner(parentElement).createElement(type);

		if(attributes) {
			for(var attr in attributes) {
				var value = attributes[attr];
				switch(attr) {
					case 'class':
					case 'className':
						element.className += (element.className ? ' ' : '') + value
						break;
					case 'for':
					case 'htmlFor':
						element.htmlFor = value;
						break;
					case 'style':
						Element.setStyle(element, value);
						break;
					case 'unselectable':
						if(value && value != 'off') {
							Element.setUnselectable(element)
						}
						break;
					default:
						element[attr] = value;
						break;
				}
			}
		}

		if(parentElement) parentElement.appendChild(element);

		return element
	},

	/**
	 * Make an element "unselectable" by the mouse. Useful for elements w/
	 * dragging behaviour. There is no cross-browser way to make an element 
	 * selectable again after this method has been called on it. It seems 
	 * only IE gets it right so that functionality has been ommitted.
	 */
	setUnselectable: function(element) {
		element.unselectable = 'on';
		element.onselectstart = nop;
		element.style.MozUserSelect = 'none';
		element.style.userSelect = 'none' ; // CSS3 (not supported yet)
		element.style.KhtmlUserSelect = 'none';
		Element.addClass(element, 'unselectable');
	},

	contains: function(haystack, needle) {
		if(haystack.contains && !Browser.isSafari()) { // contains on safari broken
			return haystack.contains(needle)
		}

		while(needle && needle != haystack) {
			if(needle.parentNode == haystack) 
				return true;
			needle = needle.parentNode;
		}

		return needle ? needle == haystack : false
	},

	getSize: function(element) {
		var size;

		if(Element.getStyle(element, 'display') != 'none' && element.parentNode && element.parentNode.nodeType == 1) {
			size = {width: element.offsetWidth, height: element.offsetHeight};
			if(size.width > 0 || size.height > 0) {
				return size
			}
		}

		var offscreen = Element.create("div",{
			style: { position: 'absolute', left: -screen.width+'px', top: -screen.height+'px' }
		});

		document.body.appendChild(offscreen);

		if(Element.getStyle(element, 'position') == 'absolute' && (!Browser.isIE() || Browser.version > 6)) {
			offscreen.style.minWidth = screen.width + 'px';
			offscreen.style.minHeight = screen.width + 'px';
		}

		var e = element.cloneNode(true);
		e.style.display = '';

		offscreen.appendChild(e);

		size = {width:e.offsetWidth, height:e.offsetHeight};

		Element.remove(offscreen);
		offscreen = null;

		return size
	},

	getStyle: function() {
		var floatCssName;

		if(Browser.isIE()) {
			floatCssName = 'styleFloat';

			var read = function(element, style) {
				style = style.camelize();
				return element.style[style] || (element.currentStyle ? element.currentStyle[style] : null)
			}

			var readOpacity = function(element) {
				var tmp = read(element,'filter').match(/alpha\(opacity=(.*)\)/i);
				return (tmp && tmp.length > 1)  ? parseFloat(tmp[1])/100 : 1;
			}
		} else {
			floatCssName = 'cssFloat';

			var read = function(element, style) {
				var camelStyle = style.camelize();
				if(element.style[camelStyle]) {
					return element.style[camelStyle]
				}

				var od = element.ownerDocument || document;
				if(od.defaultView && od.defaultView.getComputedStyle) {
					var css = od.defaultView.getComputedStyle(element, null);
					if(css)
						return css.getPropertyValue(style);
				}

				return null
			}

			var readOpacity = function(element) {
				var value = read(element,'opacity');
				return value ? parseFloat(value) : 1
			}
		}

		if(Browser.isOpera()) {
			var _read = read;
			// fix for bug in opera that returns bogus values for left/top/right/bottom
			// when element has no such values (i.e. when positioned statically)
			read = function(element, style) {
				switch(style) {
					case 'left':
					case 'top':
					case 'right':
					case 'bottom':
						if(_read(element, 'position') == 'static')
							return null;
				}

				return _read(element, style)
			}
		}

		return function(element, style) {
			var value;

			switch(style) {
				case 'float':
				case 'cssFloat':
				case 'styleFloat':
					value = read(element, floatCssName) || '';
					break;

				case 'opacity':
					value = readOpacity(element);
					break;

				default:
					value = read(element, style) || '';
					break;
			}

			return value == 'auto' ? '' : value
		}
	}(),

	setStyle: function() {
		var floatCssName = Browser.isIE() ? 'styleFloat' : 'cssFloat';

		function $style(el, styleName, styleValue) {
			switch(styleName) {
				case 'opacity':
					Element.setOpacity(el,styleValue);
					break;

				case 'cursor':
					Element.setCursor(el,styleValue);
					break;

				case 'float':
				case 'cssFloat':
				case 'styleFloat':
					styleName = floatCssName;
					/* fallthrough */

				default:
					el.style[styleName.camelize()] = styleValue == undefined ? 'auto' : styleValue;
					break;
			}
		}

		return function(element, styles, value) {
			switch(arguments.length) {
				case 2:
					var argtype = typeof styles;
					if(argtype == 'string') {
						Array.forEach(styles.split(';'), function(part) {
							var kv = part.split(':'),
							    key = kv[0].trim(),
							    val = kv[1] ? kv[1].trim() : false;

							if(key && val) {
								$style(element, key, val)
							}
						})
					} else if(argtype == 'object') {
						for(var style in styles) {
							$style(element, style, styles[style])
						}
					}
					break;

				case 3:
					$style(element, styles, value);
					break;
			} // switch
		}
	}(),

	setOpacity: function() {
		if(Browser.isIE()) {
			return function(element, opacity) {
				element.style.filter = Element.getStyle(element,'filter').replace(/alpha\([^\)]*\)/gi,'') + 
				                       (opacity === '' || opacity == undefined || opacity == 1 ? 
				                        "" : "alpha(opacity=" + Math.round(opacity*100) + ")");
			}
		} else {
			return  function(element, opacity) {
				element.style.opacity = opacity
			}
		}
	}(),

	getOpacity: function(element) {
		// getStyle already normalized for cross-browser opacity reads
		return Element.getStyle(element, 'opacity')
	}
});

/* Allow Element methods to operate on an element or 
 * an array of elements passed as a parameter. */
(function() {

	Array.forEach(arguments, function(method) {
		var callback = Element[method]; if(typeof callback != 'function') return; // continue

		/* Redefine method to accept an array of elements as the first parameter */
		Element[method] = function(element) {
			Element.forEach(element, callback, arguments);
		}
	})

})('addClass','removeClass','replaceClass','clean','hide','show','setOpacity','setStyle','setCursor')

if(!window.Position) {
	var Position = {};
}

Object.extend(Position, {
	cumulativeOffset: function(element) {
		if(element.getBoundingClientRect) { // IE
			var box = element.getBoundingClientRect();
			var scroll = Position.getScrollOffset();
			// IE has an off-by-two quirk with getBoundingClientRect
			return {
				left: box.left + scroll.left - 2,
				top: box.top + scroll.top - 2
			}
		}

		var amnt = {
			left:	0,
			top:	0
		};

		// safari bug: doubles offsets in some cases
		if(Browser.isSafari() && Element.getStyle(element, 'position') == 'absolute' ) {
			amnt.left -= document.body.offsetLeft;
			amnt.top -= document.body.offsetTop
		}

		while(element) {
			amnt.left += element.offsetLeft;
			amnt.top += element.offsetTop;
			element = element.offsetParent
		}

		return amnt
	},

	cumulativeOffsetTo: function(element, to) {
		var amnt = {
			left:	0,
			top:	0
		};
		while(element && element != to) {
			amnt.left += element.offsetLeft;
			amnt.top += element.offsetTop;
			element = element.offsetParent
		}
		return amnt;
	},

	getWindowHeight: function() {
		return Position.getWindowSize().height;
	},

	getWindowWidth: function() {
		return Position.getWindowSize().width;
	},

	getWindowSize: function() {
		var size = {
			width: 	0,
			height:	0
		};

		if(window.self && self.innerWidth) {
			size.width = self.innerWidth;
			size.height = self.innerHeight;
		} else if(document.documentElement && document.documentElement.clientWidth) {
			size.width = document.documentElement.clientWidth;
			size.height = document.documentElement.clientHeight;
		}
		return size;
	},

	getScrollOffset: function() {
		var offset = {
			left: 0,
			top: 0
		};
		offset.left =	window.pageXOffset
					|| document.documentElement.scrollLeft
					|| document.body.scrollLeft
					|| 0;
		offset.top =	window.pageYOffset
					|| document.documentElement.scrollTop
					|| document.body.scrollTop
					|| 0;

		return offset;
	},

	getHighestZIndex: function(element) {
		var max = 0;

		if(element.offsetParent) {
			while(element) {
				max = Math.max(max, parseInt(Element.getStyle(element, 'z-index')) || 0)
				element = element.offsetParent
			}
		} else {
			var all = document.getElementsByTagName("*");
			for(var i = 0; i < all.length; ++i) {
				max = Math.max(max, parseInt(Element.getStyle(parent.childNodes[i], 'z-index')) || 0)
			}
		}

		return max
	}
});


/**
 * Custom Event listening class
 */
var CustomEvent = function() {
	var cacheKey = '____customEventCache';

	function addListener(obj, type, callback, scope) {
		var handler = scope ? callback.bindTo(scope) : callback;
		if(!obj[cacheKey]) {
			obj[cacheKey] = {}
		}
		if(!hasListeners(obj,type)) {
			obj[cacheKey][type] = []
		}

		if(!hasListener(obj,type,handler)) {
			obj[cacheKey][type].push(handler)
		}
		return handler
	}
	function removeListener(obj, type, handler) {
		if(hasListeners(obj,type)) {
			var list = getListeners(obj, type);
			var i = list.indexOf(handler);
			if(i >= 0) {
				list.splice(i,1);
				return true
			}
		}
		return false
	}
	function trigger(obj, type) {
		if(hasListeners(obj,type)) {
			var handlers = getListeners(obj, type);
			var args = Array.prototype.slice.call(arguments,2);
			for(var i = 0; i < handlers.length; ++i) {
				handlers[i].apply(obj,args)
			}
		}
	}
	function clearListeners(obj, type) {
		if(!obj[cacheKey]) return;
		if(type) {
			if(hasListeners(obj,type)) {
				_destroy(obj,type);
				obj[cacheKey][type] = null;
				delete obj[cacheKey][type]
			}
		} else {
			for(type in obj[cacheKey]) {
				_destroy(obj,type);
				obj[cacheKey][type] = null;
				delete obj[cacheKey][type]
			}
			obj[cacheKey] = null
			//delete obj[cacheKey]
		}
	}
	function _destroy(obj,type) {
		var listeners = obj[cacheKey][type];
		for(var i = 0; i < listeners.length; ++i) {
			listeners[i] = null;
			delete listeners[i]
		}
	}
	function passThrough(observed, name, observer, newname) {
		addListener(observed, name, function() {
			trigger.apply(null, [observer, newname || name].concat(Array.prototype.slice.call(arguments)));
		})
	}
	function hasListener(obj, type, handle) {
		return getListeners(obj, type).indexOf(handle) >= 0
	}
	function hasListeners(obj, type) {
		return !!getListeners(obj, type)
	}
	function getListeners(obj, type) {
		return obj[cacheKey] ? (obj[cacheKey][type] || false) : false
	}

	return {
		addListener:   	addListener,
		removeListener:	removeListener,
		trigger:       	trigger,
		clearListeners:	clearListeners,
		passThrough:   	passThrough,
		hasListener:   	hasListener,
		hasListeners:  	hasListeners,
		getListeners:  	getListeners,
		addListeners:  	function(obj, types, callback, scope) {
			for(var i = 0; i < types.length; ++i) {
				addListener(obj, types[i], callback, scope)
			}
		}
	}
}();

/**
 * cross browser event listening class
 */
var Event = function() {
	var cacheKey = '____eventCache',
	    keys = {
	    	BACKSPACE: 8,
	    	TAB: 9,
	    	ENTER: 13,
	    	SHIFT: 16,
	    	CTRL: 17,
	    	ALT: 18,
	    	PAUSE_BREAK: 19,
	    	CAPS_LOCK: 20,
	    	ESCAPE: 27,
	    	ESC: 27,
	    	SPACEBAR: 32,
	    	PAGE_UP: 33,
	    	PAGE_DOWN: 34,
	    	END: 35,
	    	HOME: 36,
	    	LEFT: 37,
	    	UP: 38,
	    	RIGHT: 39,
	    	DOWN: 40,
	    	INSERT: 45,
	    	DELETE: 46,
	    	MULTIPLY: 106,
	    	ADD: 107,
	    	SUBTRACT: 109,
	    	DECIMAL: 110,
	    	DIVIDE: 111,
	    	F1: 112,
	    	F2: 113,
	    	F3: 114,
	    	F4: 115,
	    	F5: 116,
	    	F6: 117,
	    	F7: 118,
	    	F8: 119,
	    	F9: 120,
	    	F10: 121,
	    	F11: 122,
	    	F12: 123,
	    	NUM_LOCK: 144,
	    	SCROLL_LOCK: 145,
	    	COLON: 186,
	    	EQUALS: 187,
	    	COMMA: 188,
	    	DASH: 189,
	    	PERIOD: 190,
	    	BACKTICK: 192,
	    	OPEN_BRACKET: 219,
	    	PIPE: 220,
	    	CLOSE_BRACKET: 221,
	    	QUOTE: 222
	    };

	if(!window.addEventListener) {
		function handleEvent() {
			var e = window.event;
			var handlers = this[cacheKey][e.type] || [];
			for(var i=0; i < handlers.length; ++i) {
				handlers[i].call(this, e);
			}
		}
		var addListener = function(element, type, callback, scope) {
			var doSearch = !(scope);
			var handler = scope ? callback.bindToEvent(scope) : callback;

			if(!element[cacheKey]) {
				doSearch = false;
				element[cacheKey] = {}
			}
			var handlers = element[cacheKey][type];
			if(!handlers) {
				handlers = element[cacheKey][type] = [];
				if(element['on'+type]) {
					handlers.push(element['on'+type])
				}
				doSearch = false
			}
			if(!doSearch || handlers.indexOf(handler) < 0) {
				handlers.push(handler);
				element['on'+type] = handleEvent
			}
			element = null;
			return handler
		};
		var removeListener = function(element, type, handler) {
			if(element[cacheKey] && element[cacheKey][type]) {
				var i = element[cacheKey][type].indexOf(handler);
				if(i > -1) {
					element[cacheKey][type].splice(i,1);
					return true
				}
			}
			return false
		};
		var clearListeners = function(element, type) {
			if(!element[cacheKey]) return;
			if(type) {
				var handlers = element[cacheKey][type] || [];
				handlers.length = 0
			} else {
				var cache = element[cacheKey];
				for(type in cache) {
					var handlers = cache[type];
					handlers.length = 0
				}
			}
		};
		var target = function(evt) {
			return (evt || window.event).srcElement
		};
		var preventDefault = function(evt) {
			evt = evt || window.event;
			evt.returnValue = false
		};
		var stopPropagation = function(evt) {
			evt = evt || window.event;
			evt.cancelBubble = true
		};
		var cancel = function(evt) {
			evt = evt || window.event;
			evt.returnValue = false;
			evt.cancelBubble = true
		};
		var coord = function(evt) {
			return {
				x: evt.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft),
				y: evt.clientY + (document.documentElement.scrollTop || document.body.scrollTop)
			}
		};

		/**
		 * Normalize character (i.e. ascii) code reporting in IE and across printable 
		 * vs non-printable keystrokes.
		 *
		 * Quirks re keypress events:
		 * - the character code is reported in the keyCode property, while the key code
		 *   is simple unavailable. 
		 *
		 * For other events (ie keydown/up), the true charCode (i.e. the ascii code) is 
		 * simple not available so return 0. (hint: don't use the "onkeydown" event to capture 
		 * character codes, only keypress is capable of doing it correctly cross-browser.
		 * Finally, only keydown/up are capable of capturing the true key code of the key
		 * pressed cross-browser.)
		 */
		var charCode = function(evt) {
			return evt.type == 'keypress' ? evt.keyCode : 0
		};
	} else {
		var target = function(evt) {
			return evt.target
		};
		var addListener = function(element, type, callback, scope) {
			var handler = scope ? callback.bindTo(scope) : callback;
			element.addEventListener(type, handler, false);
			return handler
		};
		var removeListener = function(element, type, handler) {
			element.removeEventListener(type, handler, false)
		};
		var preventDefault = function(evt) {
			evt.preventDefault()
		};
		var stopPropagation = function(evt) {
			evt.stopPropagation()
		};
		var cancel = function(evt) {
			evt.preventDefault();
			evt.stopPropagation()
		};
		
		var coord = function(evt) {
			return {
				x: evt.pageX,
				y: evt.pageY
			}
		};
		var clearListeners = function() {};

		/**
		 * Normalize character (i.e. ascii) code reporting across non-microsoft browsers and 
		 * printable vs non-printable keystrokes.
		 *
		 * Quirks re keypress events:
		 * - Gecko has the charCode property that reports the true ascii code we're looking for. 
		 * - Opera does not have charCode, it uses both the "keyCode" and "which" properties 
		 *   instead. However, we can't fallback on keyCode for opera because Gecko reports the 
		 *   true keyCode there (not the ascii code we're looking for), so we use "which" for Opera. 
		 *
		 * We do this because for non-printable characters the values of both charCode and "which" 
		 * is 0 (in both browsers) but the value of keyCode will be non-zero in Gecko. For 
		 * printable characters, either charCode or which will have the code we want depending on
		 * the browser.
		 *
		 * For other events (ie keydown/up), the true charCode (i.e. the ascii code) is 
		 * simple not available so return 0. 
		 *
		 * Lesson: use keydown if you need to capture true key codes, and use keypress to capture 
		 * true character codes. Those are the only relationships that can gaurantee correct results
		 * cross-browser
		 */
		var charCode = function(evt) {
			return evt.type == 'keypress' ? evt.charCode || evt.which : 0
		};
	}

	return {
		coord:          	coord,
		cancel:         	cancel,
		target:         	target,
		keys:           	keys,
		charCode:       	charCode,
		addListener:    	addListener,
		removeListener: 	removeListener,
		clearListeners: 	clearListeners,
		preventDefault: 	preventDefault,
		stopPropagation:	stopPropagation,

		ignore: function(element, events) {
			if(typeof events == 'string') {
				events = [events]
			}
			for(var i = 0; i < events.length; ++i) {
				var type = events[i];
				addListener(element, type, cancel)
			}
		},

		isLeftClick: function(evt) {
			evt = evt || window.event;
			return evt && (evt.type == 'click' || evt.type == 'dblclick' || evt.which == 1 || evt.button == 1)
		},

		addListeners: function(element, types, callback, scope) {
			for(var i = 0; i < types.length; ++i) {
				addListener(element, types[i], callback, scope)
			}
		},

		coordOffset: function(evt, relativeTo) {
			// relativeTo = relativeTo || document.documentElement || document.body;
			relativeTo = relativeTo || document.body;
			var offset = Position.cumulativeOffset(relativeTo);
			var ec = coord(evt);
			return {
				x: ec.x - offset.left + (relativeTo.scrollLeft || 0),
				y: ec.y - offset.top + (relativeTo.scrollTop || 0)
			}
		},

		onReady: function() {

			var loaded = false, callbacks = [], timer;

			function domReady() {
				if(loaded) { return }

				loaded = true;

				if(timer) {
					clearInterval(timer);
					timer = null
				}

				for(var i = 0; i < callbacks.length; ++i) {
					callbacks[i]();
					delete callbacks[i];
				}

				callbacks.length = 0;
			}

			return function(callback, scope) {
				if(loaded) {
					return callback.call(scope || window);
				}

				if(!callbacks.length) {
					if (document.addEventListener) {
						document.addEventListener("DOMContentLoaded", domReady, false);
					} else if(/WebKit/i.test(navigator.userAgent)) {
						timer = setInterval(function() {
							if (/loaded|complete/.test(document.readyState)) domReady();
						}, 10);
					}

					/*@cc_on @*/
					/*@if (@_win32)
							var dummy = location.protocol == "https:" ?  "https://javascript:void(0)" : "javascript:void(0)";
							document.write("<script id=__ie_onload defer src='" + dummy + "'><\/script>");
							document.getElementById("__ie_onload").onreadystatechange = function() {
								if (this.readyState == "complete") { domReady() }
							};
					/*@end @*/

					Event.addListener(window, 'load', domReady);
				}

				callbacks.push(scope ? callback.bindTo(scope) : callback);
			}
		}(), // onReady

		/**
		 * Normalizes mouse wheel delta across browsers. Returns the wheel delta
		 * (positive == wheel rolled towards screen, negative == away from screen)
		 */
		getWheelDelta : function(evt) {
			var delta = 0;
			if(typeof evt.detail != 'undefined') { /* Mozilla/Opera>=9 */
				delta = -evt.detail/3
			} else if(evt.wheelDelta) { /* IE/Opera<9 */
				delta = evt.wheelDelta / 120
			}
			return delta
		},

		/**
		 * Normalize keyCode reporting across browsers and keystrokes.
		 *
		 * Noteworthy quirks re keyCode property of the event object:
		 * - keydown/keyup event reports same keyCode consistently across browsers
		 * - keypress event reports different keyCodes across browsers as
		 *   well as for printable vs non-printable keys. Here is that breakdown:
		 *    - Printable characters:
		 *      - IE/Opera: no way to get the true keyCode of the key. The "keyCode"
		 *        reported is actually the charCode (ascii value) for the key.
		 *      - Gecko: keyCode always reported as 0
		 *    - Non-printable characters:
		 *      - IE: keypress handlers are simple never called
		 *      - Gecko/Opera: true keyCode reported
		 * 
		 * See http://www.quirksmode.org/js/keys.html for quirks when detecting
		 * keystrokes.
		 *
		 * Lesson: use keydown if you need to capture true key codes, and use keypress to capture 
		 * true character codes. Those are the only relationships that can gaurantee correct results
		 * cross-browser
		 */
		keyCode: function(evt) {
			return evt.type == 'keypress' ? 0 : evt.keyCode
		}
	}
}(); // Event

/**
 * Event listener management helper classes
 */
function CustomEventListener(obj, type, callback, scope) {
	this.obj = obj;
	this.type = type;
	this.handler = CustomEvent.addListener(obj, type, callback, scope)
}

CustomEventListener.prototype = {
	detach: function() {
		if(this.obj) {
			CustomEvent.removeListener(this.obj, this.type, this.handler);
			this.obj = this.handler = null
		}
	}
}

function EventListenerList() {
	if(arguments.length) {
		this.push.apply(this,arguments)
	}
}

EventListenerList.prototype = {
	length: 0,
	push: function() {
		for(var i = 0; i < arguments.length; ++i) {
			this[this.length++] = arguments[i];
		}
	},
	clear: function() {
		for(var i = 0; i < this.length; ++i) {
			this[i].detach();
			delete this[i];
		}
		this.length = 0
	}
}

function EventListener(element, type, callback, scope) {
	this.element = element;
	this.type = type;
	this.handler = Event.addListener(element, type, callback, scope)
}

EventListener.prototype = {
	detach: function() {
		if(this.element) {
			Event.removeListener(this.element, this.type, this.handler);
			this.element = this.handler = null
		}
	}
}

/**
 * Memory management code that hooks into Event.clearListeners to cleanup
 * a DOM node
 */
if(Browser.isIE()) {
	Memory = {
		usage: 0,
		removeElement: function(element) {
			Event.clearListeners(element);
			Memory.removeElementChildren(element);
			element.parentNode.removeChild(element)
		},
		removeElementChildren: function(element) {
			while(element.hasChildNodes()) {
				Memory.removeElement(element.lastChild)
			}
		},
		clean: function() {
			Event.clearListeners(window);
			Memory.removeElementChildren(document.body);
			CollectGarbage()
		}
	}
} else {
	Memory = {
		usage: 0,
		removeElement: function(element) {Element.remove(element)},
		removeElementChildren: function(element) {element.innerHTML = ''},
		clean: function(){}
	}
}

/**
 * Add outerHTML to mozilla to aid in debugging DOM elements
 */
if(typeof HTMLElement != 'undefined') {
	if(HTMLElement.prototype) {
		if(!HTMLElement.prototype.outerHTML && HTMLElement.prototype.__defineSetter__) {
			HTMLElement.prototype.__defineSetter__("outerHTML", function (html) {
				var range = this.ownerDocument.createRange();
				range.selectNodeContents(this);
				var docFrag = range.createContextualFragment(html);
				this.parentNode.replaceChild(docFrag, this);
			})
			HTMLElement.prototype.__defineGetter__("outerHTML", function () {
				var clonedSelf = this.cloneNode(true);
				var tmpdiv = document.createElement('div');
				tmpdiv.appendChild(clonedSelf);
				var outerHTML = tmpdiv.innerHTML;
				tmpdiv = null;
				return outerHTML;
			})
		} // if not outerHTML

		if(!HTMLElement.prototype.insertAdjacentHTML) {
			HTMLElement.prototype.insertAdjacentHTML = function (sWhere, sHTML) {
				var df;   // : DocumentFragment
				var r = this.ownerDocument.createRange();

				switch(String(sWhere).toLowerCase()) {
					// before opening tag of element
					case "beforebegin":
						r.setStartBefore(this);
						df = r.createContextualFragment(sHTML);
						this.parentNode.insertBefore(df, this);
						break;

					// after opening tag of element
					case "afterbegin":
						if(this.firstChild) {
							r.setStartBefore(this.firstChild)
						} else {
							r.selectNodeContents(this);
							r.collapse(true);
						}
						df = r.createContextualFragment(sHTML);
						this.insertBefore(df, this.firstChild);
						break;

					// before closing tag of element
					case "beforeend":
						if(this.lastChild) {
							r.setStartAfter(this.lastChild)
						} else {
							r.selectNodeContents(this);
							r.collapse(false);
						}
						df = r.createContextualFragment(sHTML);
						this.appendChild(df);
						break;

					// after closing tag of element
					case "afterend":
						r.setStartAfter(this);
						df = r.createContextualFragment(sHTML);
						this.parentNode.insertBefore(df, this.nextSibling);
						break;

					default: return;
				 } // switch
			} // insertAdjacentHTML
		} // if not insertAdjacentHTML

		//if(!HTMLElement.prototype.contains && HTMLElement.prototype.compareDocumentPosition) {
		//	HTMLElement.prototype.contains = function (arg) {
		//		return !!(this.compareDocumentPosition(arg) & 16)
		//	}
		//}
	}
}

// bc
Browser.instance = function() {return Browser}

Event.observe = Event.attach = Event.addListener;
Event.stopObserving = Event.detach = Event.removeListener;
Event.addCustomListener = function() {return CustomEvent.addListener.apply(CustomEvent,arguments)}
Event.addCustomListeners = function() {return CustomEvent.addListeners.apply(CustomEvent,arguments)}
Event.removeCustomListener = function() {return CustomEvent.removeListener.apply(CustomEvent,arguments)}
Event.clearCustomListeners = function() {return CustomEvent.clearListeners.apply(CustomEvent,arguments)}
Event.trigger = function() {return CustomEvent.trigger.apply(CustomEvent,arguments)}
Event.passThrough = function() {return CustomEvent.passThrough.apply(CustomEvent,arguments)}
Event.stopBubble = Event.stopPropagation;

// prototype
Event.stop = Event.cancel;
Event.element = Event.target;
Event.pointerX = function(e) {return Event.coord(e).x}
Event.pointerY = function(e) {return Event.coord(e).y}
Event.findElement = function(event, tagName) {
	var element = Event.element(event);
	while (element.parentNode && (!element.tagName || (element.tagName.toUpperCase() != tagName.toUpperCase())))
		element = element.parentNode;
	return element;
}
// end bc
