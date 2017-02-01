<div class="findByDateLabel" id="date-refinement">{keyword}:</div>
<form id="refineSearchform" name="refineSearchForm" method="get" action="">
	{searchForm_keyword_html}

		<div class="clear"></div>
	<div class="findByPriceLabel" id="price-refinement">{price_per_week}<br>(EUR):</div>
		<div id="price-range">
			<div class="container">
				<div class="content show">
					<div id="price-range-fields">
					<div class="form-row">
						<label for="priceFrom">{min}:</label>
						{searchForm_minprice_html}
					</div>
					<div class="form-row">
						<label for="priceTo">{max}:</label>
						{searchForm_maxprice_html}
					</div>

					<div class="form-row">
						<label for="priceTo">{sleeps}:</label>
						{searchForm_sleeps_html}
					</div>
					<div class="form-row">
						<label for="priceTo">{type}:</label>
						{searchForm_proptype_html}
					</div>
				</div>
					<div class="clear"></div>
				<div class="form-row hidden" id="price-range-vaidation">
					<p>{price_rule}</p>
				</div>
			</div>
			</div>
		</div>


	<div id="findByDateButton" class="findByDateButton">
			<input src="/img/skin/orange-go-button1.png" class="search-submit-button" height="24" width="45" type="image">
		</div>
	</form>
</div>
