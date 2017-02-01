<div id="featuredListings" class="roundedBox">
	<h2 class="rbtitle">
		<span>{description}</span>
	</h2>
<div class="rbcontent">
<div class="rbinner"> 
<div class="content-block ">
<br><br><p><br><br></p> 
<p>{ammount} {currency}<br><br></p>
{description}

<form id=pay name=pay method="POST" action="{action}"> 
<input type="hidden" name="token" value="{token}">
<input type="hidden" name="payerID" value="{payerID}">
<input type="hidden" name="paymentType" value="Sale">
<input type="hidden" name="amt" value="{ammount}">
<input type="hidden" name="currencyCode" value="{currency}">
<input type="hidden" name="name" value="{description}">
<input type="hidden" name="payment_in_id" value="{payment_in_id}">
<p> <input type="submit" name="DoPayment" value="{submit_text}"> </p>
</form> 

</div>
</div>
</div>
</div>