<div id="featuredListings" class="roundedBox">
	<h2 class="rbtitle">
		<span>{description}</span>
	</h2>
<div class="rbcontent">
<div class="rbinner"> 
<div class="content-block ">
<form id=pay name=pay method="POST" action="{action}"> 
<br><br>
<h1>{ammount} {currency}</h1>
<br><b>{help_paypal}</b>
<input type="hidden" name="paymentType" value="Sale">
<input type="hidden" name="itemName" value="{description_en}">
<input type="hidden" name="amt" value="{ammount}">
<input type="hidden" name="currencyCode" value="{currency}">

<input type="hidden" name="LMI_PAYMENT_NO" value="{payment_in_id}">
<p><br><br> <input type="submit" value="{submit_text}" name='setExpess'> </p>
</form> 

</div>
</div>
</div>
</div>