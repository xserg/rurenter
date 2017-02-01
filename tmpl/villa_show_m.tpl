<div id="propertyPhotos">
	<div class="prop-desc">	
		<h2>
			{TITLE}
			<span>{OPTIONS}</span>
		</h2>{PLUSO}
		<div></div>
	</div>
</div>

<!-- BEGIN SUMMARY_BLOCK -->
<div id="summary" class="rounded-clear">
	<div class="rbtitle"><span></span></div>
	<div class="rbcontent">
		<div class="rbinner">
			<h2 id="summary-10041" class="summary">Аренда {PROPTYPE}<br> {SUMMARY}</h2>
			<div id="listing-wrapper">
			<div class="listing-photo">
						<img src="{M_PHOTO_SRC}" alt="{M_ALT_TEXT}" title="{M_ALT_TEXT}">
				<div class="photo-count"><a href="#photos-bar"><span>{See_all_photos}</span></a></div>
			</div>
			<div class="listing-details">
				<div class="description">
					<span class="title">{Description}</span>
					Номер объекта:<b>{VILLA_ID}</b><br>Тип: {PROPTYPE} <br>{Sleeps} {SLEEPS_NUM}, <span class="detail">{Bathrooms} {BATH}</span>
				</div>
		        <div class="amenities">
		        		<span class="title">{Facilities}</span>
			        		{FACILITY} 
						<span class="title">{Owner}</span>
		        </div><br><br>
			</div>
			<div class="listing-rates">
		<div class="rates-label">
			<span class="title"><a href="#rates-bar">{Rates}</a></span>
					<span class="required-currency">
	{CURRENCY}</span>
		</div>
		<div class="rates">
				{MIN_PRICE} - {MAX_PRICE}/{week}&nbsp;<br><b>({EURO_PRICE}EUR)</b><br>

		</div>
			</div>
			<div class="listing-contact">
				<div class="owner-contact">
				</div>
				<a href="/pages/rental_index/"><b>Villarenters индекс</b></a><br><br>
				<div id="" class="reviews has-count">
					<div class="read-count">
						<div id="reviewcount-trips1004117949" class="review-count"><a href="/pages/rental_index/">{RATING}</a></div>
						<div class="review-links"><a href="/?op=comments&act=show&villa_id={VILLA_ID}">Отзывы ({COMMENTS_CNT})</a></div>
					</div>
					<div class="review-links">
						<a href="?op=villa&act=book&id={VILLA_ID}" rel="nofollow">Забронировать</a>
        			</div>
        			<div class="clear"></div>
        		</div>
			</div>
			<div class="clear"></div>
		</div>
					
		</div>
	</div>
	</div>
</div>
<!-- END SUMMARY_BLOCK -->

<div id="location-bar" class="anchor-links roundedBox">
{VILLA_NAV}
</div>

<!-- BEGIN DESRIPTIONS -->
<div id="propertyDetails">
    <h2 class="propertySubHead"><span>{Further_details}</span></h2>   
	<div id="propertyDetailsContent">
	    <!-- BEGIN DESRIPTION -->
		<p>
		<strong>{DESCRIPTION_TITLE}:</strong>
		{DESCRIPTION_BODY}
		 </p>
		<!-- END DESRIPTION -->
	</div>
</div>
<!-- END DESRIPTIONS -->

<!-- BEGIN LOCATION -->
<div id="propertyLocation">
	<div id="propertyLocationContent">
		<div id="propertyLocationText">
		<h2 class="propertySubHead"><span>{LOCATION_TITLE}</span></h2>
		        <!-- BEGIN USER_LOCATION -->
				<div>
		        	<strong>

		        		{LOCATION_NAME}:</strong> 
						{DISTANCE}
		        </div>
				<!-- END USER_LOCATION -->
            <div>
			{LOCATION_DESCRIPTION}
			</div>

		</div>

		<div class="clear"></div>
	</div>
</div>
<!-- END LOCATION -->

<div id="facility-bar" class="anchor-links roundedBox">
{VILLA_NAV}
</div>
<div id="unitAmenities">
<!-- BEGIN OPTION_GROUP -->
	<div class="row">
		<span class="firstColumn">{GROUP_NAME}:</span>
		<!-- BEGIN OPTION_COL -->
		<span class="column">
			<ul>
				<!-- BEGIN OPTION -->
			    	<li>
			    		{OPTION}
					</li>				
				<!-- END OPTION -->
			</ul>
		</span>
		<!-- END OPTION_COL -->
		<hr class="cleaner">
	</div>
<!-- END OPTION_GROUP -->
</div>
<!-- BEGIN LOCATION_MAP -->
<div id="map-bar" class="anchor-links roundedBox">
{VILLA_NAV}
</div>
				<div id="property-map-container">
				<table cellspacing=10 cellpadding=50>
				<tr>
					<td><div id="property-map" class="map-box"><div id="g-map"></div></div></td>
					<td valign=top>&nbsp;</td>

					<td valign=top><div>{PLACES_FORM}</div></td>
				</tr>
				</table>
				
				</div>
				<!-- END LOCATION_MAP -->

<div id="photos-bar" class="anchor-links roundedBox">
{VILLA_NAV}
</div>

<!-- BEGIN GALLERY -->
<div>
				<!-- BEGIN GALLERY_ROW -->
					<!-- BEGIN GALLERY_PHOTO -->
						<div>
							<img src="{PHOTO_SRC}" alt="{ALT_TEXT}" title="{ALT_TEXT}">
							<p>{CAPTION}</p>
						</div>
					<!-- END GALLERY_PHOTO -->
				<!-- END GALLERY_ROW -->
</div>
<!-- END GALLERY -->


<div id="" class="anchor-links roundedBox">
{VILLA_NAV}
</div>