<form id=pay name=pay method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp"> 
<p>Платеж через сервис WebMoney<br><br></p> 
<p>{ammount} {currency}<br><br></p> 
<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="{ammount}">
<input type="hidden" name="LMI_PAYMENT_DESC" value="{description}">
<input type="hidden" name="LMI_PAYMENT_NO" value="{payment_in_id}">
<input type="hidden" name="LMI_PAYEE_PURSE" value="{purse_num}">
<input type="hidden" name="LMI_SIM_MODE" value="{sim_mode}"> </p> 
<p> <input type="submit" value="Оплатить"> </p>
</form> 