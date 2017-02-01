<div id="container">
				<h1></h1>
				<div class="clear"></div>
<div id="search-results">
	<div class="results-header clearfix">
		<div class="outer">
		</div>
		<div class="clear"></div>
	</div>
<div id="sidebarContent">

    <div class="refineYourSearch" id="search-refinements">
        Поиск
    </div>
    <div class="quickfind clearfix">
    	<div class="findByDateLabel" id="date-refinement">Дата:</div>
    	<form id="refineSearchform" name="refineSearchForm" method="get" action="" accept-charset="UTF-8">
    		<input value="" name="keywords" type="hidden">
    		<div id="findByDateForm" class="findByDateForm">
    			<div class="form-row">
    				<label for="startDateInput">От:</label>
    				<input id="date1" name="startDateInput" class="input datepicker" onkeydown="return false;" type="text"  value={startDateInput}>
    			</div>
    			<div class="form-row">
    				<label for="endDateInput">До:</label>
    				<input id="endDateInput" name="endDateInput" class="input datepicker" onkeydown="return false;" type="text" value={endDateInput}>
    			</div>
    		</div>
			<div class="clear"></div>
	    	<div class="findByPriceLabel" id="price-refinement">Цена (в неделю)<br>(EUR):</div>
			<div id="price-range">
				<div class="container">
					<div class="content show">
						<div id="price-range-fields">
			    			<div class="form-row">
			    				<label for="priceFrom">Мин.:</label>
			    				<input class="input price" id="priceFrom" name="minprice" onkeypress="return ha.site.searchform.isDigit(event);" type="text" value={minprice}>
			    			</div>
			    			<div class="form-row">
			    				<label for="priceTo">Макс.:</label>
			    				<input class="input price" id="priceTo" name="maxprice" onkeypress="return ha.site.searchform.isDigit(event);" type="text" value={maxprice}>
			    			</div>
			    		</div>
						<div class="clear"></div>
		    			<div class="form-row hidden" id="price-range-vaidation">
		    				<p>Цена мин. должна быть меньше макс.</p>
		    			</div>
		    		</div>
				</div>
			</div>
    		<div id="findByDateButton" class="findByDateButton">
				<input src="/resources/9734/images/skin/orange-go-button1.png" class="search-submit-button" height="24" width="45" type="image">
			</div>
		</form>
	</div>
	<br>
</div>
<div class="column-right">
<br class="clear">

<div class="pager">
	<div class="numberLinks">
		<div class="paginationLinks">
			<span class="label">Всего: {CNT}</span>
			{PAGES}
			<div class="clear"></div>
		</div>
	</div>
</div>

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
												<div><br>{SLEEPS_NUM} {Sleeps}, <br>{SUMMARY} <!--  5 Bathrooms, 5 Separate Toilets--></div>
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
													 {MIN_PRICE} - {MAX_PRICE} / {week}
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
												<a class="contact-owner" href="/?op=villa&act=book&id={VILLA_ID}" rel="nofollow"><span>{booking}</span></a>
											</div>
											<div class="clear"></div>
											<div class="reviews-container">
    	<div class="reviews-noop">&nbsp;</div>Индекс - {Rating}
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
			<span class="label">Всего: {CNT}</span>
			{PAGES}
			<div class="clear"></div>
		</div>
	</div>
</div>

</div>