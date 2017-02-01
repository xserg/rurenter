<div id="container">
				<h1></h1>
				<div class="clear"></div>
<div id="search-results">
	<div class="results-header clearfix">
		<div class="outer">
		    <!--div class="search-bar">
                <div class="sort-basic-listings-option">
			        <input id="fullAdsFirst" checked="checked" type="checkbox">
			        <label for="fullAdsFirst">Show properties with images first</label>
			    </div>
				<div class="sort-order">
					<strong>33</strong> Properties, sort by:&nbsp;
<select id="sortingSelect">
    <option selected="selected" value="clearSortOrder:-">Select Option</option>
    <option value="bedrooms:+">Bedrooms: Least to Most</option>
    <option value="bedrooms:-">Bedrooms: Most to Least</option>
    <option value="prices:-">Price: High to Low</option>
    <option value="prices:+">Price: Low to High</option>
    <option value="reviewCount:-">Number of Reviews</option>
    <option value="specialOfferCount:-">Special Offers First</option>
</select>
				</div>				
			</div-->
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
    				<input id="date1" name="startDateInput" class="input datepicker" onkeydown="return false;" type="text">
    			</div>
    			<div class="form-row">
    				<label for="endDateInput">До:</label>
    				<input id="endDateInput" name="endDateInput" class="input datepicker" onkeydown="return false;" type="text">
    			</div>
    		</div>
			<div class="clear"></div>
	    	<div class="findByPriceLabel" id="price-refinement">Цена (в неделю)<br>(GBP):</div>
			<div id="price-range">
				<div class="container">
					<div class="content show">
						<div id="price-range-fields">
			    			<div class="form-row">
			    				<label for="priceFrom">Мин.:</label>
			    				<input class="input price" id="priceFrom" name="minprice" onkeypress="return ha.site.searchform.isDigit(event);" type="text">
			    			</div>
			    			<div class="form-row">
			    				<label for="priceTo">Макс.:</label>
			    				<input class="input price" id="priceTo" name="maxprice" onkeypress="return ha.site.searchform.isDigit(event);" type="text">
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
	<!--  
	<div class="refinements-box">
    	<div id="selectedRefinementsContainer">
    		<div class="label">Current Criteria:</div>
    		<ul class="currentCriteria">
        			<li>
        				<h2>Mayenne</h2>
        				<a rel="nofollow" href="http://www.holiday-rentals.co.uk/search">remove</a>
        				<div class="clear"></div>
        			</li>
        	</ul>
        	<span><a href="/search" rel="nofollow">(remove all criteria)</a></span>
        </div>
    </div>
	<ul class="criteria">
		<li>
			<div class="criteriaCategory">Bedrooms</div>
			<ul class="criteria bedrooms">
				<li id="bedrooms:bedrooms_1">
					<a href="/search/refined/france/mayenne/region:227/Bedrooms:1" class="refinementLink" rel="nofollow">1 bedroom</a> (5)
				</li>
				<li id="bedrooms:bedrooms_2">
					<a href="/search/refined/france/mayenne/region:227/Bedrooms:2" class="refinementLink" rel="nofollow">2 bedrooms</a> (8)
				</li>
			</ul>
		</li>
	</ul>

	-->
	<br>
</div>