<h2 class="no first-child">{title}</h2>
<div class="userb"><div class="u1"><div class="u2">
	<p><img src="{AVAT_URL}{user_avatar}" class="avatar" alt="" /></p>
	<p class="nname"><a href="/user/{user_id}.html"><strong>{username}</strong></a></p>
	<p><!-- BEGIN CITY_LINK --><a href="/users/{geo_type}/{geo_hname}">{geo_name}</a>{tsep}<!-- END CITY_LINK --></p>
	<p><strong>{stat_gender}</strong></p>
	<!-- BEGIN AGE -->
	<p>Возраст: <a href="/users/age/{age}">{age}</a></p>
	<p>Дата рождения: <br><a href="/users/birth/{bdate}">{birthday}</a></p>
	<p>Знак зодиака: <a href="/users/horo/{sign}">{horo}</a></p>
	<!-- END AGE -->
	<!-- BEGIN URL --><p>Домашняя страница: <br><a href="{url}">{url}</a></p><!-- END URL -->

	<p class="tags"><strong>Интересы:</strong><!-- BEGIN TAG_LINK --><a href="/users/interest/{hword}">{tword}</a>{tsep}<!-- END TAG_LINK --></p>
	<p>О себе<br /><strong>{stat_about}</strong></p>

<!-- BEGIN MESSAGE_ADD --><p class=warn>Сообщение отправлено</p><!-- END MESSAGE_ADD -->
<!-- BEGIN MESSAGE_FORM -->
<p><b>Написать сообщение:</b></p>
<form method=post action="/office/?op=messages&act=new">
	<input name="rcpt" type="hidden" value="{rcpt}" />
	<textarea name="body" rows="5" cols="14"></textarea><br>
	<center><input type="submit" name="__submit__" value="Послать"></center>
</form>
<!-- END MESSAGE_FORM -->
<!-- BEGIN ADD_FRIEND -->
<br><p class="nname"><a href="/office/?op=friends&act=add&friend_id={friend_id}"><strong>Добавить в друзья</strong></a></p><br>
<!-- END ADD_FRIEND -->
<!-- BEGIN FRIEND_LENT -->
<p class="friendlist"><br><a href="/user/lenta/{friend_id}.html"><img src="/img/friendlist.png" alt="Френдлента" /></a></p>
<!-- END FRIEND_LENT -->
</div></div></div>
<!-- BEGIN FRIENDS -->
<h2 class="no"> Его друзья <em>({friends_cnt})</em></h2>
	<ul class="friends">
		<!-- BEGIN FRIEND --><li><a href="/user/{fid}.html">{fname}</a>  </li><!-- END FRIEND -->
	</ul>
<!-- END FRIENDS -->