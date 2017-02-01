<form id=pay name=pay method="POST" action=""> 
<p><h1>Платеж банковским переводом</h1><br><br></p> 
<p>Платеж на сумму {ammount} {currency}<br><br></p> 
Распечатайте бланк платежа для банковского перевода и оплатите его.<br>
После оплаты обязательно сообщите реквизиты платежа на info@rurenter.ru!<br><br>

<input type="hidden" name="pay_sum" value="{ammount}">
<input type="hidden" name="pay_currency" value="{currency}">
<input type="hidden" name="payment_in_id" value="{payment_in_id}">
<input type="hidden" name="pay_booking_id" value="{booking_id}">
<input type="hidden" name="email" value="{email}"> </p> 
<p> <input type="submit" name='invoice' value="Показать бланк банковского перевода"> </p>
</form> 