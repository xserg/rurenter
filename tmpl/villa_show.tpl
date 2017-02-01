<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<div id="propertyPhotos">
	<div class="prop-desc">	
		<h2>
			{TITLE}   <g:plusone></g:plusone><!-- BEGIN EDIT -->
 / <a href="?op=villa&act=edit&id={EDIT_ID}">Редактировать</a>
<!-- END EDIT -->
			<span>{OPTIONS}</span>
		</h2>
		<!-- <div>{SUMMARY}</div> -->
	</div>
</div>

<!-- BEGIN SUMMARY_BLOCK -->
<div id="summary" class="rounded-clear">
	<div class="rbtitle"><span></span></div>
	<div class="rbcontent">
		<div class="rbinner">
			<h2 id="summary-10041" class="summary">{rent} {PROPTYPE}<br> </h2>
			<div id="listing-wrapper">
			<div class="listing-photo">
						<img src="{M_PHOTO_SRC}" alt="{M_ALT_TEXT}" title="{M_ALT_TEXT}" width="320">
				<div class="photo-count"><a href="#photos-bar"><span>{See_all_photos}</span></a></div>
			</div>
			<div class="listing-details">
				<div class="description">
					<span class="title">{ADDRESS}{Description}</span>
					{prop_num}: <b>{VILLA_ID}</b><br>{type}: {PROPTYPE}<br>
					<span class="detail">{Rooms} {ROOMS_NUM}<br>{Sleeps} {SLEEPS_NUM}</span>
				</div>
		        <div class="amenities">
		        		<span class="title">{Facilities}</span>
			        		{FACILITY} 
						<span class="title">{Owner}</span>
							{USER_INFO}
		        </div><br><br>
			</div>
			<div class="listing-rates">
		<div class="rates-label">
			<span class="title"><a href="#rates-bar">{Rates}</a></span>
					<span class="required-currency">({pay_period})</span>
		</div>
		<div class="rates">
				<b>{MIN_PRICE} - {MAX_PRICE} / {CURRENCY}</b><br>
				<br>{price_in_other}:
				<br><br>
				{EURO_PRICE} / EUR<br>
				{USD_PRICE} / USD<br>

		</div>
			</div>
			<div class="listing-contact">
				<div class="owner-contact">
				{views}: {COUNTER}
				</div>
				<div id="" class="reviews has-count">
					<div class="read-count">
						<div id="reviewcount-trips1004117949" class="review-count"><a href="/pages/rental_index/">{RATING}</a></div>
						<div class="review-links"><a href="/?op=comments&act=show&villa_id={VILLA_ID}">{reviews} ({COMMENTS_CNT})</a></div>
					</div>
					<div class="review-links">
						<a href="{lang}/?op=booking&act=user&villa_id={VILLA_ID}" rel="nofollow">{book}</a>
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
		{SUMMARY}

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

<div id="map-bar" class="anchor-links roundedBox">
{VILLA_NAV}
</div>
				<!-- BEGIN LOCATION_MAP -->
				<div id="property-map-container">
				<div id="property-map" class="map-box"><div id="g-map"></div></div>
				</div>
				<!-- END LOCATION_MAP -->

<div id="photos-bar" class="anchor-links roundedBox">
{VILLA_NAV}
</div>

<!-- BEGIN GALLERY -->
	<div class="prop-photos">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody align="center" valign="top">
				<!-- BEGIN GALLERY_ROW -->
				<tr>
					<!-- BEGIN GALLERY_PHOTO -->
					<td colspan="1" width="50%">
						<div>
							<img src="{PHOTO_SRC}" alt="{ALT_TEXT}" title="{ALT_TEXT}" width="400px">
							<p>{CAPTION}</p>
						</div>
					</td>
					<!-- END GALLERY_PHOTO -->
				</tr>
				<!-- END GALLERY_ROW -->
			</tbody>
		</table>
	</div>
<!-- END GALLERY -->

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
<div id="" class="anchor-links roundedBox">
{VILLA_NAV}
</div>