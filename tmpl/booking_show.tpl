<div id="featuredListings" class="roundedBox">
	<h2 class="rbtitle">
		<span></span>
	</h2>
	<div class="rbcontent">
<div class="rbinner"> 
<div class="content-block ">
<b>{Property}:</b> <a href="?op=villa&act=show&id={villa_id}" target=_blank>{villa_id}, {villa_title}</a><br>
<br>
<b>{Period}:</b> {start_date} - {end_date}, {people} {people_t}
<br><br>
<b>{Duration}:</b> {days} {nights}<br><br>
{note}<br>
<!-- BEGIN USER -->
<b>{renter}:</b> 
{user_name}<br><br>
<!-- END USER -->

<b>{status}:</b> {book_status}
<!-- BEGIN CANCEL -->
<form method="post" action="">
<br><br><input value="Отменить заявку" name='cancel' type="submit">
</form>
<!-- END CANCEL -->
<hr>
<br><br>
<!-- BEGIN PRICE -->
<h2>{Payment_Details}:</h2><br><br>
 <b>{Booking_cost}:</b> {price} {currency}<br><br><br>
<!-- END PRICE -->

<!-- BEGIN PREPAY -->
<b>{Payments}:</b><br><br>
<b>{Deposit}:</b> {prepay} {currency}<br><br>
<!-- END PREPAY -->

<!-- BEGIN BREAKAGE -->
 <b>{Breakage_title}:</b> {breakage} {currency}<br><br><br>
<!-- END BREAKAGE -->




<!-- BEGIN PAY -->
<script type="text/javascript">
<!--
	
	function checkSend(agree)
	{
	//agree.value=0;
		//agree = document.getElementById('agree');
		//alert(agree);
		if (agree)
		{
			document.getElementById('pay_total').disabled=1;
			document.getElementById('pay_prepay').disabled=0;
		}
		else
		{
			document.getElementById('pay_total').disabled=1;
			document.getElementById('pay_prepay').disabled=1;
		}

	}
//-->
</script>
<form method="post" action="{PAY_ACTION}">
<input type="hidden" name="pay_booking_id" value={PAY_BOOKING_ID}>
<!-- BEGIN RUR_PRICE -->
<b>К оплате в рублях: {price_rur} RUR</b><br><br>
<!-- END RUR_PRICE -->
	<!-- BEGIN PAY_SERVICE -->
	{Select_payment_method}:<br>
	<select name="pay_service_id">
		<!-- BEGIN P_OPTION -->
		<option value="{P_VALUE}"{P_SELECTED}>{P_NAME}
		<!-- END P_OPTION -->
	</select>
	<!-- END PAY_SERVICE -->
<br><br>
<a href="/{lang}/office/?op=booking&act=agree&id={PAY_BOOKING_ID}" target="_blank">{terms_and_conditions}</a><br class="clear"><br>
<input type="checkbox" name="agree" id="agree" onclick="checkSend(this.checked);" value=1>{I_have_read_and_accept}<br>
	<!-- BEGIN PREPAY_SUBMIT -->
	<br><input type="submit" name="pay_prepay" id="pay_prepay" value="{pay_deposit} {prepay} {currency}" disabled=1><br>
	<!-- END PREPAY_SUBMIT -->
	<br><input type="submit" name="pay_total" id="pay_total" value="{pay_total} {price} {currency}" disabled=1>
</form>
<hr>
<!-- END PAY -->

<form method="post" action="">
<!-- BEGIN INVOICE -->
<script type="text/javascript">
<!--
	function checkSend(agree)
	{
	//agree.value=0;
		//agree = document.getElementById('agree');
		//alert(agree);
		if (agree)
		{
			document.getElementById('prepay_invoice').disabled=0;
		}
		else
		{
			document.getElementById('prepay_invoice').disabled=1;
		}
	}
//-->
</script>
<table cellspacing=5 cellpadding=10>
<tr>
	<td><span class="required">* </span><b>{Booking_cost} ({currency}):</b></td>
	<td><input type="text" name="price" value="{price}"></td>
</tr>
<tr>
	<td><span class="required">* </span><b>{Deposit} ({currency}):</b></td>
	<td><input type="text" name="prepay" value="{prepay}"></td>
</tr>
</table>
&nbsp;<!--input type="submit" name="invoice" value="Выставить счет"--><br>
<input type="checkbox" name="commission" id="commission" onclick="checkSend(this.checked);" value=1> <b>Я согласен с <a href="/office/?op=users&act=agree" target=_blank>условиями сервиса</a> и выставляю счет с учетом выплаты 10% комиссии для rurenter.ru</b>	<br><br>

<input type="submit" name="prepay_invoice" id="prepay_invoice" value="Выставить счет на предоплату" disabled=1><br>
<!-- END INVOICE -->

<!-- BEGIN FEEDBACK -->
<br><br><h2>{Contact_owner} {feedback_title}:</h2>
<br>{Warning}<br><br>

<textarea name="body" rows="10" cols="50"></textarea><br><input type="submit" name="__submit__" value="Отправить">
<!-- END FEEDBACK -->

<!-- BEGIN STATUS_FORM -->
<br><br>
	{status}:<select name="status_id">
		<!-- BEGIN S_OPTION -->
		<option value="{S_VALUE}"{S_SELECTED}>{S_NAME}
		<!-- END S_OPTION -->
	</select><input type="submit" value="Изменить" name='change_status'>

<!-- END STATUS_FORM -->

</form>
<br><br>
<div id="propertyReviews" class="reviews-read">	
	<h2 id="propertySubHead"><span>{Messages_for_the_booking}</span></h2>
<div class="review-content">
<!-- BEGIN QUERY -->
<br><br>
<div class="date">
						{post_date}
					</div>
<div class="author"><b>{user_id}, {author_name}:</b></div>
<div class="review-response">{body}</div>
<!-- END QUERY -->
</div></div>

</div>
</div>
</div>
</div>