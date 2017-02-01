/*
* Javascript for aoi selection
*/

//Index in the array indicating the string displayed for area units
var iUnitIndex=0;

//Index in the array indicating the currency
var iCurrencyIndex=0;

var aAreaUnits=new Array('km&sup2;','mile&sup2;');

var aAreaConversion=new Array(1.609344,0.621371192);

var aCurrency=new Array('USD','CAD');

var aCurrencySymbol=new Array('$','$');

var map;

var SHOW_LAT_LNG=false;

var CHANGE_AOI=false;

var AOI=[{"id":"4","name":null,"area":1000,"price":195,"cost_per_extra_km":null,"extra_km_increment":null,"has_free_trial":"1","max_area":"1000","area_imp":"201.168","extra_mile_increment":null,"cost_per_km_increment":null}];

//var AOI_ARRAY=null;

var EDIT_AOI=true;

function loadMapAOI() {
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("aoi-select-map"));
		map.setCenter(new GLatLng(55.737162319794564, 37.544617652893066), 10);
		$('btnNext').disabled = true;

		if (EDIT_AOI)
		{
		map.enableGoogleBar();
		map.enableContinuousZoom();
		map.enableScrollWheelZoom();
		map.enableDragging();
		map.enableDoubleClickZoom();
		map.addControl(new GSmallMapControl());
		} else {
			map.disableDragging();
		}
		//Prevent mouse wheel from scrolling page when cursor is over map
		var c = map.getContainer(); // reference to map DIV
		if(Browser.isMoz()) {
			var mozTarget = Browser.os == 0 ? window : c;
			Event.ignore(mozTarget, 'DOMMouseScroll');
		} else {
			map.enableScrollWheelZoom();
			Event.ignore(c, 'mousewheel');
		}

		if(window.AOI_ARRAY) {
			loadDefinedAOI();
		}
	}
}

function isUserValid()
{
	return $('first_name').value.length && $('last_name').value.length &&	$('email').value.length;
}

function isFormValid()
{
	//return (!$('first_name') || isUserValid()) && $('coordinates').value.length;
	//alert($('coordinates').value);
	return $('coordinates').value.length;

}

/** Port of distance calcution in Cleer Impact coded in PHP */
function distanceLngLat(point1, point2) {
	var deg = 0.0174532925199;
	
	return 12746004.5 * Math.asin(Math.sqrt(
				Math.pow(Math.sin((point1.lat - point2.lat)*deg/2),2) +
					Math.cos(point1.lat*deg)* Math.cos(point2.lat*deg)*
						Math.pow(Math.sin((point1.lng - point2.lng)* deg/2),2)));
}

/** Use google maps to determine the area using 2 points from a square */
/* http://www.jstott.me.uk/jscoord/#download */
function getAreaInKilometers(sw,ne)
{
	var _sw= new LatLng(sw.lat(),sw.lng());
	var _ne= new LatLng(ne.lat(),ne.lng());

	var _se =new LatLng(sw.lat(),ne.lng());
	var _nw =new LatLng(ne.lat(),sw.lng());

	var ne_se =distanceLngLat(_ne, _se) / 1000.0;
	var se_sw =distanceLngLat(_se, _sw) / 1000.0;
	var nw_sw =distanceLngLat(_nw, _sw) / 1000.0;

	return (nw_sw * se_sw) ;
}

/** Use google maps to determine the area using 2 points from a square */
function getGoogleAreaInKilometers(sw,ne)
{
	var se = new GLatLng(sw.lat(),ne.lng());
	var nw = new GLatLng(ne.lat(),se.lng());
	
	var ne_se = ne.distanceFrom(se) / 1000.0;
	var se_sw = se.distanceFrom(sw) / 1000.0;
	
	var nw_sw = nw.distanceFrom(sw) / 1000.0;

	return (nw_sw * se_sw) ;
}


function resetOptionTable()
{
	for(i=0;i < AOI.length;++i)
	{
		$('aoi-option-'+i).className='rowOption free-trial-' + AOI[i]['has_free_trial'];
		$('aoi-amount-'+i).innerHTML='-';
		$('aoi-calc-'+i).innerHTML='-';
		$('aoi-total').innerHTML='-';
	}

	$('aoi-extra-amount-' + (AOI.length  )).innerHTML='-';
	$('aoi-extra-calc-' + (AOI.length )).innerHTML= '-';				
	$('aoi-total').innerHTML= '$ 0';
	$('aoi-extra').className='rowOption free-trial-0';
	$('aoi-too-big').className='rowOption free-trial-0';
}

/** Javascript version of code Chris Provided me to estimate a distance between 2 lat lngs
/** @param Distance in lng */
/** @param Distance in lat */
/** @param From lat */
/** @param From ln */
function getDistanceInMeters(dx,dy,lat,lng)
{
	dx*=Math.PI/180;                // difference between points in x (lng) direction
	dy*=Math.PI/180;                // difference between points in y (lat) direction
	lat*=Math.PI/180;            // the Lat
	lng*=Math.PI/180;            // the lng
	
	// determine the distance in meters
	return 6371211.0 * Math.acos(Math.sin(dy) *
	Math.sin(lat) + Math.cos(dy) * Math.cos(lat)* Math.cos(lng-dx)); 
}


function coordFromDistance(coord,distance,bearing)
{
	var lat1=coord.latRadians();
	var lon1=coord.lngRadians();	
	var d=distance;
	var R=6371; //Earth radius
	var brng=bearing * ( Math.PI / 180.0);	

	var lat2 = Math.asin( Math.sin(lat1)*Math.cos(d/R) + 
	Math.cos(lat1)*Math.sin(d/R)*Math.cos(brng) );

	var lon2 = lon1 + Math.atan2(Math.sin(brng)*Math.sin(d/R)*Math.cos(lat1), 
	Math.cos(d/R)-Math.sin(lat1)*Math.sin(lat2));
	
	lon2 = (lon2+Math.PI)%(2*Math.PI) - Math.PI;  // normalise to -180...+180 

	return new GLatLng(lat2 * (180.0 / Math.PI),lon2 * (180.0 / Math.PI) );
}

		

/** The trial page applies the AOI using the center of the map as
	the center of the smallest AOI option */
/** note We aim for a slightly smaller area then the smallest AOI Option 
to compensate for mathematical imprecision */
function applyTrialArea(bounds)
{
	map.clearOverlays();
				
	var selectedArea = getAreaInKilometers(bounds.getSouthWest(),bounds.getNorthEast());

	var center = bounds.getCenter();

	//To compensate for imprecision we lower our target area by the fudge amount
	var fudge= 2.0;

	var width = Math.sqrt(AOI[0]['area'] - fudge);

	//let c = width
	//a ^ 2 + a ^ 2 = c^2
	//a = sqrt( c^2 / 2.0 )
	var a =Math.sqrt(((width *width) / 2.0) );

	var ne = coordFromDistance(center,a,45.0);
	var sw = coordFromDistance(center,a,225.0);

	var trial_bounds = new GLatLngBounds(sw,ne);

	map.addOverlay(new Rectangle(trial_bounds));

	var trial_selectedArea = getAreaInKilometers(trial_bounds.getSouthWest(),trial_bounds.getNorthEast())


	$('coordinates').value='{"sw_lat" : '+trial_bounds.getSouthWest().lat()+','+
									'"sw_lng" : '+trial_bounds.getSouthWest().lng()+','+
									'"ne_lat" : '+trial_bounds.getNorthEast().lat()+','+
									' "ne_lng" : '+trial_bounds.getNorthEast().lng()+''+
									'}';	;
	
	$('btnNext').disabled = !isFormValid();
	
	//$('txtArea').innerHTML=trial_selectedArea.toFixed(2) + " " + aAreaUnits[iUnitIndex];
	
	if(SHOW_LAT_LNG)
	{
		$('SW-lat').innerHTML= trial_bounds.getSouthWest().lat().toFixed(2); 
		$('SW-lng').innerHTML= trial_bounds.getSouthWest().lng().toFixed(2);
		
		$('NE-lat').innerHTML= trial_bounds.getNorthEast().lat().toFixed(2); 
		$('NE-lng').innerHTML= trial_bounds.getNorthEast().lng().toFixed(2);
	}

	var center = trial_bounds.getCenter();

	var iZoomLevel =map.getBoundsZoomLevel(trial_bounds);

	map.setCenter(center, iZoomLevel);	
}

/** Does the calculation of the area and displays it on the screen */
function applyArea(bounds)
{

	var selectedArea = getAreaInKilometers(bounds.getSouthWest(),bounds.getNorthEast());
	
	var i;
	
	//resetOptionTable();
	if (selectedArea < 0.05) {
		$('btnNext').disabled = true;
		alert('Выбранная область слишком мала!');
		$('divPrice').innerHTML='';
		$('coordinates').value='';
		$('txtArea').innerHTML='Too large';
		$('txtPrice').value='';
		$('divMessage').innerHTML='Your selected area is too large';
		return;
	}

	//We reached the last Area Of Interest but our business logic allows for 
	//adding additional increments
	if( (selectedArea > AOI[AOI.length - 1].area) && (AOI[AOI.length - 1].extra_km_increment > 0) && (AOI[AOI.length - 1].max_area > selectedArea))
	{
		
		var areaOverflow = (selectedArea - AOI[AOI.length - 1].area);
		
		var iNumAreaIncrements = Math.ceil(areaOverflow / AOI[AOI.length - 1].extra_km_increment );
		
		var fExtraCost = iNumAreaIncrements * (AOI[AOI.length - 1].cost_per_extra_km * AOI[AOI.length - 1].extra_km_increment);
		
		var fTotal = AOI[AOI.length - 1].price + fExtraCost;
		
		//if(CHANGE_AOI)
    	//{
    	//	if(AOI[i].area < AOI_ARRAY[0].selected_area)
		//	{
		//		alert('You cannot downgrade your AOI until your yearly renewal');
		//		return;
		//	}
		//}
									
		newAOI(AOI.length-1 ,bounds,selectedArea);
			
		if(CHANGE_AOI)
    	{
    		
    		var fSubTotal = AOI[AOI.length - 1].price + fExtraCost;
		
			$('aoi-subtotal').innerHTML= '$' + fSubTotal ;
		
    		var fPriceDiff = fSubTotal- getPrice(AOI_ARRAY[0].fk_extent_option_id);
		
			var fProRate = (fPriceDiff / 365.0) * (365.0 - DAYS_ELAPSED);
			
			var fProRateDiff = fPriceDiff - fProRate;

			fTotal=fProRate;
    		
    		if(fTotal < 0.0)
    			fTotal=0.0;
    		
    		$('aoi-price-prorated-diff').innerHTML= '$'+fProRateDiff.toFixed(2);	
		}
			
		$('aoi-extra-amount-' + (AOI.length  )).innerHTML=iNumAreaIncrements;

		$('aoi-extra-calc-' + (AOI.length )).innerHTML= '$'+fExtraCost.toFixed(2);				

		$('aoi-total').innerHTML= '$'+fTotal.toFixed(2);
		$('aoi-extra').className='rowOption selected';

		
					
		return ;
	}		
	else if( (selectedArea > AOI[AOI.length - 1].area) || (map.getZoom() == 0) )
	{
		$('btnNext').disabled = true;
		selectionTooLarge();
		
		return;
	}
	
	//Determine the AOI option 
	for(i=0;i < AOI.length;++i)
	{
		if(selectedArea <= AOI[i].area)
			break;
	}			

	newAOI(i,bounds,selectedArea);

}


function getPrice(id)
{
	return MAX_AOI_PRICE;
	//for(i=0;i < AOI.length;++i)
	//{
	//	if( id == AOI[i].id )
	//		return AOI[i].price;
	//}
}

/** Executed when user chooses an area that exceeds the maximum subscription package area */		
function selectionTooLarge()
{
	alert('Выбранная область слишком велика!');
	
	$('divPrice').innerHTML='';
	$('coordinates').value='';
	$('txtArea').innerHTML='Too large';
	$('txtPrice').value='';
	$('divMessage').innerHTML='Your selected area is too large';
	/*
	if(SHOW_LAT_LNG)
	{
		$('SW-lat').innerHTML= ''; 
		$('SW-lng').innerHTML= '';
		
		$('NE-lat').innerHTML= ''; 
		$('NE-lng').innerHTML= '';
	}
	
	$('aoi-total').innerHTML= '-';
	$('aoi-total').className='';

	for(i=0;i < AOI.length;++i)
	{
		$('aoi-option-'+i).className='rowOption free-trial-' + AOI[i]['has_free_trial'];

		$('aoi-amount-'+i).innerHTML='-';
		$('aoi-calc-'+i).innerHTML='-';
	}

	$('aoi-too-big').className='selected';
	*/
	
}
	
/** Executed when user chooses a new AOI using the map tool
Updates the rate table with fancy arrows and highlighting */	
function newAOI(i,bounds,selectedArea)
{
	
	//$('divPrice').innerHTML= "$"+AOI[i].price+" "+aCurrency[iCurrencyIndex];
	//$('txtPrice').innerHTML= "$"+AOI[i].price+" "+aCurrency[iCurrencyIndex];
				
	$('coordinates').value= '{"sw_lat" : '+bounds.getSouthWest().lat()+','+
									'"sw_lng" : '+bounds.getSouthWest().lng()+','+
									'"ne_lat" : '+bounds.getNorthEast().lat()+','+
									' "ne_lng" : '+bounds.getNorthEast().lng()+''+
									'}';	

	$('btnNext').disabled = !isFormValid();
	
	if(SHOW_LAT_LNG)
	{
		$('SW-lat').innerHTML= bounds.getSouthWest().lat().toFixed(2); 
		$('SW-lng').innerHTML= bounds.getSouthWest().lng().toFixed(2);
		
		$('NE-lat').innerHTML= bounds.getNorthEast().lat().toFixed(2); 
		$('NE-lng').innerHTML= bounds.getNorthEast().lng().toFixed(2);
	}

	//$('txtArea').innerHTML= (Math.round(selectedArea * 100.0) / 100.0) + " " + aAreaUnits[iUnitIndex];
	//$('txtArea').value= (Math.round(selectedArea * 100.0) / 100.0) ;
	//$('txtPrice').value= "$"+AOI[i].price;
	//$('divMessage').innerHTML="You have selected a " + AOI[i].name + " subscription "+ " ("+ AOI[i].area +" " + aAreaUnits[iUnitIndex] + ")";
	
	//$('aoi-option-'+i).className='selected';


    if(CHANGE_AOI)
    {
    	
    	//if(AOI[i].area < AOI_ARRAY[0].selected_area)
    	//{
    	//	alert('While you can reduce the size of your AOI, you can not downgrade your subscription until it is time to renew your subscription.');
    		//return;
    	//}
    	
    	//if(AOI[i].price < MAX_AOI_PRICE)
    	//{
    		//alert('While you can reduce the size of your AOI, you can not downgrade your subscription until it is time to renew your subscription.');
    		//return;
    	//}
    	
    	//Limited to 1 for now
		$('aoi-amount-'+i).innerHTML=1;

		$('aoi-calc-'+i).innerHTML="$"+AOI[i].price.toFixed(2);

		//'$'+AOI[i].price.toFixed(2)

		$('aoi-subtotal').innerHTML= '$' + AOI[i].price ;	

		var fPriceDiff = AOI[i].price - getPrice(AOI_ARRAY[0].fk_extent_option_id);
		
		var fProRate =0.0;
		var fProRateDiff=0.0;
		
		if(fPriceDiff >= 0)
		{
			fProRate= (fPriceDiff / 365.0) * (365.0 - DAYS_ELAPSED);
			fProRateDiff=fPriceDiff - fProRate;
		}

		var fTotal=fProRate;

		if(fTotal < 0.0)
    		fTotal=0.0;

		$('aoi-total').innerHTML= '$' + fTotal.toFixed(2);	

		$('aoi-price-prorated-diff').innerHTML= '$'+fProRateDiff.toFixed(2);

		$('aoi-total').className='selected';
    }
	else
	{
		//Limited to 1 for now
		/*
		$('aoi-amount-'+i).innerHTML=1;

		$('aoi-calc-'+i).innerHTML="$"+AOI[i].price.toFixed(2);

		$('aoi-total').innerHTML= '$'+AOI[i].price.toFixed(2);	

		$('aoi-total').className='selected';
		*/
	}
	
}
		
/** Used to swap all the kms for miles and do the appropriate conversion math */	
function changeMeasurement(refSelect)
{
	
	if(iUnitIndex == refSelect.selectedIndex)
		return;
	
	iUnitIndex=refSelect.selectedIndex;
	
	var valueNodeList = document.getElementsByClassName('area-value');
	
	for(i=0;i < valueNodeList.length;++i)
		valueNodeList[i].innerHTML= Math.round(aAreaConversion[iUnitIndex] * parseFloat(valueNodeList[i].innerHTML));
	
	var unitNodeList = document.getElementsByClassName('area-unit');
	
	for(i=0;i < unitNodeList.length;++i)
		unitNodeList[i].innerHTML= aAreaUnits[iUnitIndex];
	
}

/** Using global AOI_ARRAY add rectangle and zoom to AOI */
function loadDefinedAOI()
{
	for(i=0;i < AOI_ARRAY.length;++i)
	{
		var bounds = new GLatLngBounds(
			new GLatLng(AOI_ARRAY[i]['sw_lat'],AOI_ARRAY[i]['sw_lng']),
			new GLatLng(AOI_ARRAY[i]['ne_lat'],AOI_ARRAY[i]['ne_lng'])
		);

		map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));

		try { // subscription sign-up?
			toggle_map_drawing(map);
			gExtentDrawer.stamp(bounds);
			applyArea(bounds);
		} catch(e) { // trial sign-up
			applyTrialArea(bounds)
		}
	}		
}



var gExtentDrawer;
function toggle_map_drawing(refMap)
{
	if(!gExtentDrawer) {

		var options = {
			fill: { color:'red', opacity:0.15 },
			disableDraggingOnDrawEnd: true
		}

		if(!Browser.isIE()) {
			options.Rectangle = DraggableResizeableRectangle;
			options.ctrlDrag = true;
			if(window.GDraggableObject) {
				// Sync up the draggable rectangle cursors to that of the map for
				// consitency. We use the GDraggableObject new in v2.93
				options.draggingCursor = GDraggableObject.getDraggingCursor();
				options.draggableCursor = GDraggableObject.getDraggableCursor();
			}
		}

		gExtentDrawer = new GExtentDrawer(refMap, options);

		CustomEvent.addListener(gExtentDrawer, 'extentchange', applyArea );
		CustomEvent.addListener(gExtentDrawer, 'enable', function(isEnabled){ $('btnDraw').disabled=isEnabled ; } );    
		
		gExtentDrawer.enable();
	}
	
	else {
		if(gExtentDrawer.enabled()) {
		gExtentDrawer.disable();
		} else {
		gExtentDrawer.enable();
		}
	}
}

Event.observe(window, 'load', loadMapAOI);
Event.observe(window, 'unload', GUnload);
