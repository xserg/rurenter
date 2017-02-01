<div id="featuredListings" class="roundedBox">
	<h2 class="rbtitle">
		<span></span>
	</h2>
	<div class="rbcontent">
<div class="rbinner"> 
<div class="content-block ">

№ {id}, Добавлена {post_date} с адреса {post_ip}<br>
<br>
<b>Объект:</b> <a href="/villa/{villa_id}.html" target=_blank>{villa_id}, {villa_title}</a><br>
<br>
<b>Владелец:</b> <a href="?op=users&act=card&id={owner}">#{owner}</a><br>
<!--  <b>Статус:</b> {book_status}<br>-->
<!-- BEGIN STATUS_FORM -->
<br>
<form method="post" action="">
	Статус:<select name="status_id">
		<!-- BEGIN S_OPTION -->
		<option value="{S_VALUE}"{S_SELECTED}>{S_NAME}
		<!-- END S_OPTION -->
	</select><input type="submit" value="Изменить" name='change_status'>
</form>
<!-- END STATUS_FORM -->
<br>

<!-- BEGIN INVOICE -->
<form method="post" action="">
<table cellspacing=5 cellpadding=10>
<tr>
	<td><span class="required">* </span><b>{Booking_cost} ({currency}):</b></td>
	<td><input type="text" name="price" value="{price}"></td>
</tr>
<tr>
	<td><span class="required">* </span><b>{Deposit} (25%) ({currency}):</b></td>
	<td><input type="text" name="prepay" value="{prepay}"></td>
</tr>
</table>

<input type="submit" name="prepay_invoice" id="prepay_invoice" value="Изменить цену"><br>
</form>
<br>
<!-- END INVOICE -->

<b>Период:</b> {start_date} - {end_date}, {people} чел.<br>{days} ночей,<br>{note}<br>
<form method="post" action="">
	<input type="text" name="start_date" value="{start_date}" size=12 id="from">&nbsp;<input type="text" name="end_date" value="{end_date}" size=12 id="to"><input type="submit" name="change_dates" value="Изменить">
</form>
<!-- BEGIN PRICE -->
<h2>{Payment_Details}:</h2>
 <b>{Booking_cost}:</b> {price} {currency}<br><br><br>
<!-- END PRICE -->

<!-- BEGIN BREAKAGE -->
 <b>{Breakage_title}:</b> {breakage} {currency}<br><br><br>
<!-- END BREAKAGE -->

<!-- BEGIN PREPAY -->
<b>{Deposit}:</b> {prepay} {currency}<br><br>
<!-- END PREPAY -->




<!-- BEGIN PAY -->
<form method="post" action="{PAY_ACTION}">
<input type="hidden" name="pay_sum" value={PAY_SUM}>
<input type="hidden" name="pay_currency" value={PAY_CURRENCY}>
<input type="hidden" name="pay_booking_id" value={PAY_BOOKING_ID}>
<!-- BEGIN RUR_PRICE -->
<b>К оплате в рублях: {price_rur} RUR</b><br><br>
<!-- END RUR_PRICE -->
	<!-- BEGIN PAY_SERVICE -->
	Выберите способ оплаты:<br>
	<select name="pay_service_id">
		<!-- BEGIN P_OPTION -->
		<option value="{P_VALUE}"{P_SELECTED}>{P_NAME}
		<!-- END P_OPTION -->
	</select>
	<!-- END PAY_SERVICE -->
<br><br>
<a href="/office/?op=booking&act=agree&id={PAY_BOOKING_ID}" target="_blank">Договор кратковременной аренды (УСЛОВИЯ БРОНИРОВАНИЯ)</a><br class="clear"><br>
<input type="checkbox" name="agree" id="agree" onclick="checkSend(this.checked);" value=1> Я прочитал и согласен с условиями<br>
	<br><input type="submit" name=pay value="Перейти к оплате">
</form>
<!-- END PAY -->

<form method="post" action="">
<!-- BEGIN USER -->
<h2>Информация о арендаторе:</h2> <br>
<a href="?op=users&act=card&id={renter_id}">#{renter_id}</a>, {user_name} {user_lastname}<br>{user_email}, {user_home_phone}<br>зарегистрирован {user_reg_date}, {user_last_ip}
<!-- END USER -->

<!-- BEGIN CONFIRMATION -->
<br><br><a href="/office/?op=booking&act=admincard&id={id}&confirm=1">Отправить подтверждение</a>
<!-- END CONFIRMATION -->

<!-- BEGIN FEEDBACK -->
<br><br>Послать сообщение {feedback_title}:<br>
<textarea name="body" rows="10" cols="50"></textarea><br><input type="submit" name="__submit__" value="Отправить">
<!-- END FEEDBACK -->

</form>
<br><br>
<div id="propertyReviews" class="reviews-read">	
	<h2 id="propertySubHead"><span>Сообщения:</span></h2>
<div class="review-content">
<!-- BEGIN QUERY -->
<div class="date">
						{post_date}
					</div>
<div class="author"><b><a href="?op=users&act=card&id={user_id}">{user_id}, {author_name}</a>:</b></div>
<div class="review-response">{body} : {status_id}</div>
<br>
<!-- END QUERY -->
</div></div>

</div>
</div>
</div>
</div>