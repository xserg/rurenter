<div class="profile"><div class="p-in"><div class="p-in1">
	<img src="{AVAT_URL}{user_avatar}" alt="" class="avat" />
	<p class="name"><a href="/user/{user_id}.html">{user_name}</a></p>
	<p>Добавлен<br /><strong>{date}</strong></p>
	<p class="descr">{vcomment}</p>
	<p class="tags"><strong>Таги:</strong><!-- BEGIN TAG_LINK --><a href="/tags/{hword}">{tword}</a>{tsep}<!-- END TAG_LINK --></p>

	<p>Рейтинг: {rating}</p>
	<!-- BEGIN SMSTOPCODE -->
	<p>
	<b>Любимый клип на всех страницах сайта!</b><br/><br/>
	Чтоб поднять клип в Sms-Top, пришли Sms сообщение <nobr>'<b>RLK {video_id}</b>'</nobr> (без кавычек) на номер <b>4446</b>.<br/><br/>
	Стоимость запроса на номер 4446 составляет $0,99.<br/><br/>
	</p>
	<!-- END SMSTOPCODE -->

	<ul class="codes">
		<li><label for="jj">Код для ЖЖ</label> <input id="jj" class="inp" type="text" value="{ljcode}" onClick="javascript:this.focus();this.select();" readonly="true" /></li>
		<li><label for="live">Код для liveinternet</label> <input id="live" class="inp" type="text" value="{licode}" onClick="javascript:this.focus();this.select();" readonly="true" /></li>
		<li class="mob"><label for="mob">Скачать на мобильник</label> <input type="text" id="mob" class="inp" value="{wapcode}" onClick="javascript:this.focus();this.select();" readonly="true" /></li>
	</ul>
	<!--p><a href="#">Подписаться</a> на Константин. С</p-->
	<p class="mess"><a href="/?op=admininfo&act=add">Сообщение</a><br /> для администрации сайта</p>

</div></div></div>