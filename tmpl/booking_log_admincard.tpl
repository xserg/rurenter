<div id="featuredListings" class="roundedBox">
	<div class="rbcontent">
<div class="rbinner"> 
<div class="content-block ">

� {id}, ��������� {post_date} � ������ {post_ip}<br>
<br>
<b>������:</b> <a href="http://villarenters.ru/villa/{villa_id}.html" target=_blank>{villa_id}, {villa_title}</a><br>
<br>
<b>��������:</b> <a href="?op=users&act=card&id={owner}">#{owner}</a><br><br>
<b>������:</b> {book_status}<br>
<br>
<b>������:</b> {start_date} - {end_date}, {people} �����., {childs} �����, {infants} �����.<br><br>{note}<br>
<br>
<a href="{url}" target=_blank>{url}</a>
<br><br>

<!-- BEGIN PRICE -->
 <b>{days} ����, {price} {currency}</b><br><br>
<!-- END PRICE -->


<!-- BEGIN USER -->
<b>���������� � ����������:</b> <br>
<a href="?op=users&act=card&id={renter_id}">#{renter_id}</a>,<br>
<b>���:</b> {user_name}<br> <b>�������:</b> {user_lastname}<br> <b>�������:</b> {user_age}<br>
�-����: {user_email}, ������: {user_password}<br>

<br>���������������: {user_reg_date}, IP: {user_last_ip}<br>

<b>������:</b> {user_country}
<b>�����:</b> {user_city}<br>
�����: {user_street} ���: 
{user_house_num}
<br>

<br>
������� ��������: {user_home_phone}<p>
������� �������: {user_mobile_phone}

<!-- END USER -->

<!-- BEGIN INVOICE -->
<b>����:</b> {days} <br><br><b>���� ({currency}):</b> <input type="text" name="price" value="{price}">&nbsp;<input type="submit" name="invoice" value="��������� ����"><br>
<!-- END INVOICE -->

<!-- BEGIN GUEST -->
<br><br>�����: {guest_name} {guest_last_name} {guest_age}
<!-- END GUEST -->

<!-- BEGIN LOGIN -->
<form method=post action="{url}">
	<input type="hidden" name="__EVENTTARGET" value="_nothing">
	 <input type="hidden" name="__EVENTARGUMENT" value="">
	<input type="hidden" name="__VIEWSTATE" value="">
	 
	<input type="hidden" name="">
<!---->
	<input type="hidden" name="VillaCalendar$DayDDL" value="{start_day}">
	<input type="hidden" name="VillaCalendar$MonthDDL" value="{start_month}">
	<input type="hidden" name="VillaCalendar$YearDDL" value="{start_year}">
	<input type="hidden" name="VillaCalendar$DurationTB" value="{days}">
	<input type="hidden" name="VillaCalendar$AdultsDDL" value="{people}">
	<input type="hidden" name="VillaCalendar$ChildrenDDL" value="{childs}">
	<input type="hidden" name="VillaCalendar$InfantsDDL" value="{infants}">
	<input type="hidden" name="VillaCalendar$StartDayHF" value="{start_day}">
	<input type="hidden" name="VillaCalendar$StartMonthHF" value="{start_month}">
	<input type="hidden" name="VillaCalendar$StartYearHF" value="{start_year}">
	<input type="hidden" name="VillaCalendar$MaxDateHF" value="01/02/2015">
	<input type="hidden" name="VillaCalendar$CalPosHF" value="8">
	<input type="hidden" name="VillaCalendar$CurrencySymbolHF" value="&pound;">
	<input type="hidden" name="VillaCalendar$GetNumberOfPeopleHF" value="10">
	<input type="hidden" name="VillaCalendar$MarkUpHF" value="0">
	<input type="hidden" name="PartyCollapsiblePanelExtender_ClientState" value="true">
	<input type="hidden" name="ddlTitle" value="Mr.">
	<input type="hidden" name="txtFirstName" value="1">
	<input type="hidden" name="txtLastName" value="6">
	<input type="hidden" name="ddlAge" value="6">
	<input type="hidden" name="ddlSex" value="M">
	<input type="hidden" name="txtAddress1" value="1">
	<input type="hidden" name="txtHomeTel" value="1">
	<input type="hidden" name="txtAddress2" value="2">
	<input type="hidden" name="txtAddress3" value="">
	<input type="hidden" name="txtWorkTel" value="">
	<input type="hidden" name="txtTown" value="moscow">
	<input type="hidden" name="txtCounty" value="Russia">
	<input type="hidden" name="txtPostCode" value="1">
	<input type="hidden" name="ddlCountry" value="">
	<input type="hidden" name="txtMobileTel" value="">
	
	<input type="hidden" name="dgPartyMembers$ctl02$ddlTitle" value="Mr">
	<input type="hidden" name="dgPartyMembers$ctl02$txtFirstName" value="2">
	<input type="hidden" name="dgPartyMembers$ctl02$txtLastName" value="2">
	<input type="hidden" name="dgPartyMembers$ctl02$ddlAge" value="4">
	<input type="hidden" name="dgPartyMembers$ctl02$ddlGender" value="M">
	<input type="hidden" name="SpecialCollapsiblePanelExtender_ClientState" value="false">
	<input type="hidden" name="txtExtra" value="">
	<input type="hidden" name="ibtnSpecialContinue.x" value="40">
	<input type="hidden" name="ibtnSpecialContinue.y" value="12">
	<input type="hidden" name="SummaryCollapsiblePanelExtender_ClientState" value="">
	<input type="hidden" name="ResultCollapsiblePanelExtender_ClientState" value="true">
	<input type="hidden" name="Accordion1_AccordionExtender_ClientState" value="0">

<!--  
	<input type="text" name="VRRenterLogin$EmailTB" value="{user_email}"> <br>
	<input type="text" name="VRRenterLogin$PasswordTB" value="{user_password}">
	<input type="hidden" name="VRRenterLogin$MemberTypeGroup"  value="OldRenterRB">
	<input type="hidden" name="VRRenterLogin$AgreeCB"          value="on">
-->	<input type="submit" name="VRRenterLogin$LoginButton" value="Login">

</form>
<!-- END LOGIN -->
<br><br>

<a href="?op=booking_log&act=send&id={id}" target="_blank">��������</a> | <a href="?op=booking_log&act=status&id={id}">�������� ������</a>
</div>
</div>
</div>
</div>