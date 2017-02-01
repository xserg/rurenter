<div id="adv-search-container" class="rbgrade">
<div class="roundedBox">
	<h2 class="rbtitle"><span class="adv-heading">Расширенный поиск</span></h2>
	<div class="clear"></div>
	<div class="rbcontent">
		<div class="rbinner">
			<form id="adv-search-form" name="searchForm" action="/search/" method="get">
			<fieldset>
			<div class="hr">&nbsp;</div>
				<div>
					<h3 class="title">Где бы вы хотели отдохнуть?</h3> 	
					<div class="label">
						<label for="searchKeywords">Место расположения, ключевые слова</label>
					</div>
					<div class="element">
						<!--input autocomplete="off" id="searchKeywords" name="keyword" tabindex="1" class="text input-keyword default ac_input" autocompletion="off"-->
						{searchForm_keyword_html}
					</div>
				</div>
				<div class="hr">&nbsp;</div>
				<!--div class="columns">
					<h3 class="title">When would you like to go?</h3>
					<div class="column">
						<div class="label"><label for="startDateInput">Arrival</label></div>
						<div class="element"><input id="startDateInput" name="startDateInput" value="dd/mm/yyyy" class="datepicker text" maxlength="10" onkeydown="return false;" type="text"></div>
						<div class="clear"></div>
					</div>
					<div class="column">
						<div class="label"><label for="endDateInput">Departure</label></div>
						<div class="element"><input id="endDateInput" name="endDateInput" value="dd/mm/yyyy" class="datepicker text" maxlength="10" onkeydown="return false;" type="text"></div>		
						<div class="clear"></div>
					</div>
				</div>
<div class="hr">&nbsp;</div-->
<div class="columns" id="price-range-fields">
	<h3 class="title">Цена в неделю (Евро)</h3>
	<div class="column" id="price-from-column">
		<div class="label"><label for="priceFrom">Минимальная</label></div>
		<div class="element">
			{searchForm_minprice_html}
		</div>
		<div class="clear"></div>
	</div>
	<div class="column" id="price-to-column">
		<div class="label"><label for="priceTo">Максимальная</label></div>
		<div class="element">
			{searchForm_maxprice_html}
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<div class="form-row hidden" id="price-range-vaidation">
		<p>Price from cannot exceed price to</p>
	</div>
</div>
				<div class="hr">&nbsp;</div>
				<div class="columns">
					<h3 class="title">Какой тип недвижимости вам подойдет?</h3>
					<div class="column">
						<div class="label"><label for="sleeps">Спальных мест:</label></div>
						<div class="element">
							{searchForm_sleeps_html}
						</div>
						<!--div class="label"><label for="bedrooms">Bedrooms</label></div>
						<div class="element">
							<select name="refinements" id="bedrooms">
								<option selected="selected" value="">All</option>
									<option value="Bedrooms:Studio">Studio</option>								
									<option value="Bedrooms:1">1 bedroom</option>								
									<option value="Bedrooms:2">2 bedrooms</option>								
									<option value="Bedrooms:3">3 bedrooms</option>								
									<option value="Bedrooms:4">4 bedrooms</option>								
									<option value="Bedrooms:5*">5+ bedrooms</option>								
							</select>
						</div-->

						<!--div class="label"><label for="themes">Theme</label></div>
						<div class="element">
							<select name="refinements" id="themes">
								<option selected="selected" value="">All</option>
									<option value="Theme:budget">budget</option>								
									<option value="Theme:romantic">romantic</option>								
									<option value="Theme:tourist attractions">tourist attractions</option>								
									<option value="Theme:away from it all">away from it all</option>								
									<option value="Theme:family">family</option>								
									<option value="Theme:luxury">luxury</option>								
									<option value="Theme:activity">activity</option>								
									<option value="Theme:spa">spa</option>								
									<option value="Theme:historic">historic</option>								
									<option value="Theme:farm holidays">farm holidays</option>								
									<option value="Theme:holiday complex">holiday complex</option>								
							</select>
						</div-->						
					</div>
					<div class="column">
						<div class="label"><label for="propertyType">Тип</label></div>	
						<div class="element">
							{searchForm_proptype_html}
						</div>		
						<!-- 
						<div class="label"><label for="bathrooms">Bathrooms</label></div>	
						<div class="element">
							<select name="refinements" id="bathrooms">
								<option selected="selected" value="">All</option>
									<option value="Bathrooms:1">1 bathroom</option>
									<option value="Bathrooms:2">2 bathrooms</option>
									<option value="Bathrooms:3">3 bathrooms</option>
									<option value="Bathrooms:4*">4+ bathrooms</option>
							</select>
						</div> -->
					</div>
					<div class="clear"></div>
				</div>
			</fieldset>
			<!--div class="hr">&nbsp;</div>
			<fieldset>
				<div class="columns">
					<h3 class="title">Do you have any special requirements?</h3>
					<div class="column">
							<div class="prop-box prop-box-lft">
								<input class="checkbox" id="suitability0" name="refinements" value="Suitability:children welcome" type="checkbox">
								<label for="suitability0">children welcome</label>
							</div>									
							<div class="prop-box prop-box-lft">
								<input class="checkbox" id="suitability1" name="refinements" value="Suitability:wheelchair accessible" type="checkbox">
								<label for="suitability1">wheelchair accessible</label>
							</div>									
							<div class="prop-box prop-box-lft">
								<input class="checkbox" id="suitability2" name="refinements" value="Suitability:suitable for elderly or infirm" type="checkbox">
								<label for="suitability2">suitable for the elderly or infirm</label>
							</div>									
					</div>
					<div class="column">
							<div class="prop-box prop-box-rt">
								<input class="checkbox" id="suitability3" name="refinements" value="Suitability:pets considered" type="checkbox">
								<label for="suitability3">pets considered</label>
							</div>											
							<div class="prop-box prop-box-rt">
								<input class="checkbox" id="suitability4" name="refinements" value="Suitability:non smoking only" type="checkbox">
								<label for="suitability4">non smoking only</label>
							</div>											
							<div class="prop-box prop-box-rt">
								<input class="checkbox" id="suitability5" name="refinements" value="Suitability:long term rentals welcome" type="checkbox">
								<label for="suitability5">long term rentals welcome</label>
							</div>											
					</div>
					<div class="clear"></div>
				</div>
			</fieldset-->
			<input src="img/search.png" class="search-submit-button" height="23" type="image" width="65">
			
			<!--h3 class="criteria">Additional Search Criteria</h3>
			<div id="search-refinements-1" class="rc-std-inner expand">
				<div class="container">
					<h4 class="action"><span>Facilities&nbsp;</span></h4>
					<div class="content hidden">
						<div class="refinementsContent">
							<div class="columns">
								<div class="column">
										<div class="cb-box">
											<input class="checkbox" id="amenities0" name="refinements" value="Facilities:air conditioning" type="checkbox">
											<label for="amenities0">air conditioning</label>
										</div>											
										<div class="cb-box">
											<input class="checkbox" id="amenities1" name="refinements" value="Facilities:satellite or cable tv" type="checkbox">
											<label for="amenities1">satellite or cable tv</label>
										</div>											
										<div class="cb-box">
											<input class="checkbox" id="amenities2" name="refinements" value="Facilities:pool" type="checkbox">
											<label for="amenities2">pool</label>
										</div>											
										<div class="cb-box">
											<input class="checkbox" id="amenities3" name="refinements" value="Facilities:internet access" type="checkbox">
											<label for="amenities3">internet access</label>
										</div>											
								</div>
								<div class="column">
										<div class="cb-box">
											<input class="checkbox" id="amenities4" name="refinements" value="Facilities:dishwasher" type="checkbox">
											<label for="amenities4">dishwasher</label>
										</div>										
										<div class="cb-box">
											<input class="checkbox" id="amenities5" name="refinements" value="Facilities:washing machine" type="checkbox">
											<label for="amenities5">washing machine</label>
										</div>										
										<div class="cb-box">
											<input class="checkbox" id="amenities6" name="refinements" value="Facilities:garden" type="checkbox">
											<label for="amenities6">garden</label>
										</div>										
										<div class="cb-box">
											<input class="checkbox" id="amenities7" name="refinements" value="Facilities:barbecue" type="checkbox">
											<label for="amenities7">bbq</label>
										</div>										
										<div class="cb-box">
											<input class="checkbox" id="amenities8" name="refinements" value="Facilities:parking" type="checkbox">
											<label for="amenities8">parking</label>
										</div>										
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="search-refinements-2" class="rc-std-inner expand">
				<div class="container">
					<h4 class="action"><span>Location Type&nbsp;</span></h4>
					<div class="content hidden">
						<div class="refinementsContent">
							<div class="columns">
								<div class="column">
										<div class="cb-box">
											<input class="checkbox" id="location0" name="refinements" value="Location Type:beach" type="checkbox">
											<label for="location0">near the beach</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location1" name="refinements" value="Location Type:lake" type="checkbox">
											<label for="location1">lake</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location2" name="refinements" value="Location Type:waterfront" type="checkbox">
											<label for="location2">waterfront</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location3" name="refinements" value="Location Type:city centre" type="checkbox">
											<label for="location3">city centre</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location4" name="refinements" value="Location Type:mountain" type="checkbox">
											<label for="location4">near mountains</label>
										</div>
								</div>
								<div class="column">
										<div class="cb-box">
											<input class="checkbox" id="location5" name="refinements" value="Location Type:near the sea" type="checkbox">
											<label for="location5">near the sea</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location6" name="refinements" value="Location Type:resort" type="checkbox">
											<label for="location6">on a resort</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location7" name="refinements" value="Location Type:rural" type="checkbox">
											<label for="location7">rural location</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location8" name="refinements" value="Location Type:town" type="checkbox">
											<label for="location8">town</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location9" name="refinements" value="Location Type:village" type="checkbox">
											<label for="location9">village</label>
										</div>
										<div class="cb-box">
											<input class="checkbox" id="location10" name="refinements" value="Location Type:river" type="checkbox">
											<label for="location10">river</label>
										</div>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="search-refinements-3" class="rc-std-inner expand">
				<div class="container">
					<h4 class="action"><span>Leisure Activities&nbsp;</span></h4>
					<div class="content hidden">
						<div class="refinementsContent">
							<div class="columns">
								<div class="column">
										<div class="cb-box">
											<input class="checkbox" id="leisure0" name="refinements" value="Leisure Activities:fishing" type="checkbox">
											<label for="leisure0">fishing</label>
										</div>											
								</div>
								<div class="column">
										<div class="cb-box">
											<input class="checkbox" id="leisure1" name="refinements" value="Leisure Activities:golf" type="checkbox">
											<label for="leisure1">golf</label>
										</div>										
										<div class="cb-box">
											<input class="checkbox" id="leisure2" name="refinements" value="Leisure Activities:ski" type="checkbox">
											<label for="leisure2">ski</label>
										</div>										
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input src="searchForm_files/search.png" class="search-submit-button" height="23" type="image" width="65"-->
		</form></div>
	</div>
</div>
</div>

    </div>

</div>