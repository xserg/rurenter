{image_header}
<h1>{Header1} <span>({ALL_CNT})</span></h1>
<div id="search-keyword">
<div id="search-form-heading">
		<span>{Search}</span>
		<a href="?op=villa&act=search" class="advanced-search-link" rel="nofollow">{Advanced}</a>
		<div class="clear"></div>
	</div>
	<form name="searchForm" id="keywordSearchForm" action="/search/" method="get">
	<div id="search-form" class="search-form">
		<div class="search-input">
			<input value="" autocomplete="off" name="keyword" id="searchKeywords" rel="" class="input-keyword default ac_input" type="text">
			<input id="side-search-button" src="/img/skin/orange-go-button1.png" class="search-submit-button" height="24" width="44" type="image">
		</div>
	</div>
	</form>
</div>

<div id="container">
<!--  -->
<h2><!-- BEGIN TYPE_ITEM --><!-- BEGIN TYPE_NAV --><a href="{TYPE_LINK}" alt="{TYPE_ALT}" title="{TYPE_ALT}">{TYPE_NAME}</a><!-- END TYPE_NAV --><!-- BEGIN TYPE_SEL -->{TYPE_NAME}<!-- END TYPE_SEL -->{TYPE_SEPARATOR}<!-- END TYPE_ITEM --></h2>
<!--  -->
<strong>{wellcome}</strong>

<div id="featuredListings" class="roundedBox">
	<h2 class="rbtitle">
		<span></span>
	</h2>
	<div class="rbcontent">
		<div class="rbinner"> 
			<div class="content-block ">
			    <div class="photo-flow">
					    <!-- BEGIN TOP_VILLA -->
						<div class="featured-property {last}">
					    <h4>{TOP_VILLA_TITLE} ()</h4>
					    	<a href="{TOP_VILLA_LINK}" alt="" title="" style="background: rgb(238, 238, 238) url({TOP_VILLA_IMAGE}) no-repeat scroll center center; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;" class="featured-photo">
			        			<span>{View_this_property}</span>  
			        		</a>
						</div>
						<!-- END TOP_VILLA -->

				</div> <!-- end photo-flow -->
				<div class="clear"></div>
			</div>	<!-- end content-block -->	
		</div> <!-- end rbinner -->
	</div> <!-- end rbcontent -->
</div>	<!-- end featuredListings -->

<div id="node-results">
	<div class="results-header">
		<span class="inner">
			<strong>
				{World_Holiday_Destinations}
				<span class="showall"><a href="regions/world">{Show_All}</a></span>
			</strong>
		</span>
		
	</div>
	<div class="results-body clearfix">
    	<div id="mainPageBuckets">
	<div class="clear"></div>
<!-- BEGIN REGION -->
<div class="regionheader">
	<h3>
	<a href="{REGION_LINK}" class="headerLink"><span>{Holiday_villas_in}</span> {REGION_NAME}<span> ({REGION_CNT})</span></a>
	</h3>
	<div class="show-all-box"><a href="{REGION_ALL_LINK}" class="showall">{Show_All}</a></div>
</div>
<div class="threeBuckets clearfix">
                	<!-- BEGIN COUNTRIES_COLUMN -->
					<ul><!-- BEGIN COUNTRY_ROW -->
                                <li><a href="{COUNTRY_LINK}">{COUNTRY_NAME} ({COUNTRY_CNT})</a></li>
						<!-- END COUNTRY_ROW -->
                 	</ul>
					<!-- END COUNTRIES_COLUMN -->
</div>
<!-- END REGION -->

    	</div>
		<div id="right-column">
			

<div class="popular-links">
		<h2>{ANNONCE_TITLE}</h2>
		{ANNONCE}
		<ul>
		{news}

		</ul>
</div>
		</div>
		<p style="clear: left;">&nbsp;</p>
	</div>
</div>
	</div>

</div>

</div>



