<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

<div id="container">
				<div class="clear"></div>
<h2>{LIST_TITLE}  <g:plusone></g:plusone></h2><br>
<div class="clear"></div>
<b>{LOCATION_NAV}</b>
<div id="search-results">
	<div class="results-header clearfix">
		<div class="outer">
		 <div class="search-bar">
			<div class="sort-basic-listings-option"><!-- BEGIN TYPE_NAV --><a href="?proptype={TYPE_LINK}" alt="{TYPE_ALT}" title="{TYPE_ALT}">{TYPE_NAME}</a>{TYPE_SEPARATOR}<!-- END TYPE_NAV --></div>
			<div class="sort-order">{MAP_LINK}</a></div>
		</div>
		</div>
		<div class="clear"></div>
	</div>
<div id="sidebarContent">

    <div class="refineYourSearch" id="search-refinements">
        {Search}
    </div>
    <div class="quickfind clearfix">
	{SEARCH_FORM}
	{REGIONS}
<div class="clear"></div><br><br>
    <div class="refineYourSearch" id="search-refinements">
	{TICKETS}
	</div>
	
	<br>
</div>
<div class="column-right">
<br class="clear">

<div class="pager">
	<div class="numberLinks">
		<div class="paginationLinks">
			<span class="label">{CNT}</span>
			{PAGES}
			<div class="clear"></div>
		</div>
	</div>
</div>

				<!-- BEGIN LOCATION_MAP -->
				<div id="property-map-container">
				<div id="list-map" class="map-box"><div id="g-map"></div></div>
				</div>
				<!-- END LOCATION_MAP -->


<!-- BEGIN VILLA -->
<div class="listing-card roundedBox">
	<div class="rbtitle">
		<span>
		</span>
	</div>
	<div class="rbcontent">
		<div class="rbinner">
			<div class="listing-content">
					<div class="clear"></div>
						<div class="listing-headline">
							<h3 class="listing-title">
								<a href="{LINK}">{TITLE}</a>
							</h3>
							<div class="listing-breadcrumb">
	<div class="breadcrumb">
		<ol>
			            <li><a href="" rel="nofollow">{LOCATION}</a></li>
		</ol>
	</div>	
								<span class="listing-propertyid">{PROPTYPE} {VILLA_ID}</span>
							</div>
						</div>
						<div class="clear"></div>
								<div class="listing-details">
									<div class="listing-photo-section">
										<div class="listing-photo">
											<a href="{LINK}" rel="nofollow"><img src="{M_PHOTO_SRC}" title="{M_ALT_TEXT}" alt="{M_ALT_TEXT}"></a>
										</div>
									</div>
									<div class="listing-info">
										<div class="listing-details-upper max-height">
											<div class="listing-description">
												<div><br>{SLEEPS_NUM} {Sleeps}, <br>{SUMMARY} </div>
											</div>
											<div class="listing-rates-lowest">
												
												<div class="rates_title">
													<span>{Rates}
														<span class="rate-type"> 
																({CURRENCY})
														</span>
													</span>
												</div>
												<div class="rates">
													 {MIN_PRICE} {MAX_PRICE} {pay_period}
													<b> </b>
												</div>
											</div>
											<div class="clear"></div>
										</div>
										<div class="listing-details-lower">
											<div class="cal-update">
												<span><a href="{LINK}">{details}</a></span>
											</div>
											<div class="contact">
												<a class="contact-owner" href="/?op=booking&act=user&villa_id={VILLA_ID}" rel="nofollow"><span>{booking}</span></a>
											</div>
											<div class="clear"></div>
											<div class="reviews-container">
    	<div class="reviews-noop">&nbsp;</div>{index} {Rating}
											</div>
											<!--div class="button">
												<a href="" class="primary-button" rel="nofollow"><span>{booking}</span></a>
											</div-->
											<div class="clear"></div>
										</div>
									</div>
									<div class="clear"></div>
								</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<!-- END VILLA -->

<div class="pager">
	<div class="numberLinks">
		<div class="paginationLinks">
			<span class="label">{CNT}</span>
			{PAGES}
			<div class="clear"></div>
		</div>
	</div>
</div>
{HA_LINK}
</div>