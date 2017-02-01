<pre>{RENT_AGREE}</pre>
<script type="text/javascript">
<!--
	
	function checkSend(agree)
	{
	//agree.value=0;
		//agree = document.getElementById('agree');
		//alert(agree);
		if (agree)
		{
			document.getElementById('pay').disabled=0;
		}
		else
		{
			document.getElementById('pay').disabled=1;
		}

	}
//-->
</script>
<!-- BEGIN PAY -->
<form method="post" action="{PAY_ACTION}">
<input type="hidden" name="pay_sum" value={PAY_SUM}>
<input type="hidden" name="pay_currency" value={PAY_CURRENCY}>
<input type="hidden" name="pay_booking_id" value={PAY_BOOKING_ID}>
<input type="hidden" name="pay_service_id" value={PAY_SERVICE_ID}>
<!-- BEGIN RUR_PRICE -->
<b>К оплате в рублях: {price_rur} RUR</b><br><br>
<!-- END RUR_PRICE -->
<input type="checkbox" name="agree" id="agree" onclick="checkSend(this.checked);" value=1> Я прочитал и согласен с условиями<br>
<br><input type="submit" name=pay id=pay value="Оплатить счет" disabled=1>
</form>
<!-- END PAY -->